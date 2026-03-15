<?php
class Events {
    private $id;
    private $title;
    private $description;
    private $date_event;
    private $location;
    private $created_by;
    private $created_at;
    private $status;
    private $conn;

    public function __construct($pdo = null) {
        $this->conn = $pdo;
        $this->status = 'draft'; // brouillon par défaut
    }

    // ===== SETTERS =====
    public function setTitle($title) {
        if (empty($title)) {
            throw new InvalidArgumentException("Le titre est requis");
        }
        if (strlen($title) < 3) {
            throw new InvalidArgumentException("Le titre doit contenir au moins 3 caractères");
        }
        if (strlen($title) > 100) {
            throw new InvalidArgumentException("Le titre ne peut pas dépasser 100 caractères");
        }
        $this->title = htmlspecialchars(trim($title), ENT_QUOTES, 'UTF-8');
        return $this;
    }

    public function setDescription($description) {
        if (empty($description)) {
            throw new InvalidArgumentException("La description est requise");
        }
        if (strlen($description) < 10) {
            throw new InvalidArgumentException("La description doit contenir au moins 10 caractères");
        }
        $this->description = htmlspecialchars(trim($description), ENT_QUOTES, 'UTF-8');
        return $this;
    }

    public function setDateEvent($date) {
        if (empty($date)) {
            throw new InvalidArgumentException("La date est requise");
        }
        
        // Vérifier le format de date
        $date_obj = DateTime::createFromFormat('Y-m-d\TH:i', $date);
        if (!$date_obj) {
            $date_obj = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        }
        if (!$date_obj) {
            $date_obj = DateTime::createFromFormat('Y-m-d', $date);
        }
        
        if (!$date_obj) {
            throw new InvalidArgumentException("Format de date invalide");
        }
        
        // Vérifier que la date n'est pas dans le passé (optionnel)
        // $now = new DateTime();
        // if ($date_obj < $now) {
        //     throw new InvalidArgumentException("La date ne peut pas être dans le passé");
        // }
        
        $this->date_event = $date_obj->format('Y-m-d H:i:s');
        return $this;
    }

    public function setLocation($location) {
        if (empty($location)) {
            throw new InvalidArgumentException("Le lieu est requis");
        }
        $this->location = htmlspecialchars(trim($location), ENT_QUOTES, 'UTF-8');
        return $this;
    }

    public function setCreatedBy($userId) {
        if (empty($userId) || !is_numeric($userId)) {
            throw new InvalidArgumentException("ID utilisateur invalide");
        }
        $this->created_by = (int)$userId;
        return $this;
    }

    public function setStatus($status) {
        $validStatus = ['draft', 'published', 'cancelled', 'completed'];
        if (!in_array($status, $validStatus)) {
            throw new InvalidArgumentException("Statut invalide");
        }
        $this->status = $status;
        return $this;
    }

    // ===== GETTERS =====
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getDateEvent() { return $this->date_event; }
    
    public function getDateEventFormatted($format = 'd/m/Y H:i') { 
        if (!$this->date_event) return '';
        return date($format, strtotime($this->date_event)); 
    }
    
    public function getLocation() { return $this->location; }
    public function getCreatedBy() { return $this->created_by; }
    public function getCreatedAt() { return $this->created_at; }
    public function getStatus() { return $this->status; }
    
    public function getStatusBadge() {
        switch($this->status) {
            case 'draft':
                return '<span class="badge badge-secondary">Brouillon</span>';
            case 'published':
                return '<span class="badge badge-success">Publié</span>';
            case 'cancelled':
                return '<span class="badge badge-error">Annulé</span>';
            case 'completed':
                return '<span class="badge badge-primary">Terminé</span>';
            default:
                return '<span class="badge badge-secondary">' . $this->status . '</span>';
        }
    }

    // ===== MÉTHODES CRUD =====
    public function sauvegarder() {
        if (!$this->conn) {
            throw new Exception("Pas de connexion à la base de données");
        }

        try {
            if ($this->id) {
                return $this->mettreAJour();
            }
            return $this->inserer();
        } catch (PDOException $e) {
            error_log("Erreur lors de la sauvegarde de l'événement : " . $e->getMessage());
            return false;
        }
    }

    private function inserer() {
        $sql = "INSERT INTO events (title, description, date_event, location, created_by, status, created_at)
                VALUES (:title, :description, :date_event, :location, :created_by, :status, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        
        $result = $stmt->execute([
            ':title' => $this->title,
            ':description' => $this->description,
            ':date_event' => $this->date_event,
            ':location' => $this->location,
            ':created_by' => $this->created_by,
            ':status' => $this->status
        ]);
        
        if ($result) {
            $this->id = $this->conn->lastInsertId();
            $this->created_at = date('Y-m-d H:i:s');
        }
        
        return $result;
    }

    private function mettreAJour() {
        $sql = "UPDATE events 
                SET title = :title, 
                    description = :description, 
                    date_event = :date_event, 
                    location = :location,
                    status = :status
                WHERE id = :id AND created_by = :created_by";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title' => $this->title,
            ':description' => $this->description,
            ':date_event' => $this->date_event,
            ':location' => $this->location,
            ':status' => $this->status,
            ':id' => $this->id,
            ':created_by' => $this->created_by
        ]);
    }

    public function supprimer() {
        if (!$this->id) {
            return false;
        }

        try {
            // Supprimer d'abord les invitations liées
            $stmt = $this->conn->prepare("DELETE FROM invitations WHERE event_id = ?");
            $stmt->execute([$this->id]);

            // Puis supprimer l'événement
            $stmt = $this->conn->prepare("DELETE FROM events WHERE id = ? AND created_by = ?");
            return $stmt->execute([$this->id, $this->created_by]);
        } catch (PDOException $e) {
            error_log("Erreur suppression événement: " . $e->getMessage());
            return false;
        }
    }

    // ===== MÉTHODES STATIQUES =====
    public static function trouverParId($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $event = new self($pdo);
        $event->id = $data['id'];
        $event->title = $data['title'];
        $event->description = $data['description'];
        $event->date_event = $data['date_event'];
        $event->location = $data['location'];
        $event->created_by = $data['created_by'];
        $event->created_at = $data['created_at'];
        $event->status = $data['status'];
        
        return $event;
    }

    public static function trouverParUtilisateur($pdo, $userId, $status = null) {
        $sql = "SELECT * FROM events WHERE created_by = :user_id";
        $params = [':user_id' => $userId];
        
        if ($status) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }
        
        $sql .= " ORDER BY date_event DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        $events = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $event = new self($pdo);
            $event->id = $data['id'];
            $event->title = $data['title'];
            $event->description = $data['description'];
            $event->date_event = $data['date_event'];
            $event->location = $data['location'];
            $event->created_by = $data['created_by'];
            $event->created_at = $data['created_at'];
            $event->status = $data['status'];
            $events[] = $event;
        }
        
        return $events;
    }

    public static function trouverTous($pdo, $limit = null) {
        $sql = "SELECT e.*, u.firstname, u.lastname 
                FROM events e 
                JOIN users u ON e.created_by = u.id 
                ORDER BY e.date_event DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $pdo->query($sql);
        
        $events = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $event = new self($pdo);
            $event->id = $data['id'];
            $event->title = $data['title'];
            $event->description = $data['description'];
            $event->date_event = $data['date_event'];
            $event->location = $data['location'];
            $event->created_by = $data['created_by'];
            $event->created_at = $data['created_at'];
            $event->status = $data['status'];
            $event->creator_name = $data['firstname'] . ' ' . $data['lastname'];
            $events[] = $event;
        }
        
        return $events;
    }

    public function getInvitations() {
        if (!$this->id || !$this->conn) {
            return [];
        }

        $stmt = $this->conn->prepare("
            SELECT i.*, u.firstname, u.lastname, u.email 
            FROM invitations i 
            LEFT JOIN users u ON i.email = u.email
            WHERE i.id = ?
            ORDER BY i.created_at DESC
        ");
        $stmt->execute([$this->id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStats() {
        if (!$this->id || !$this->conn) {
            return [
                'total' => 0,
                'pending' => 0,
                'accepted' => 0,
                'declined' => 0,
                'maybe' => 0
            ];
        }

        $stmt = $this->conn->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                SUM(CASE WHEN status = 'declined' THEN 1 ELSE 0 END) as declined,
                SUM(CASE WHEN status = 'maybe' THEN 1 ELSE 0 END) as maybe
            FROM invitations 
            WHERE id = ?
        ");
        $stmt->execute([$this->id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ajouterInvitation($email, $name = null) {
        if (!$this->id || !$this->conn) {
            return false;
        }

        // Vérifier si l'invitation existe déjà
        $stmt = $this->conn->prepare("SELECT id FROM invitations WHERE event_id = ? AND guest_email = ?");
        $stmt->execute([$this->id, $email]);
        if ($stmt->fetch()) {
            return false; // Déjà invité
        }

        $stmt = $this->conn->prepare("
            INSERT INTO invitations (event_id, guest_email, guest_name, status, created_at)
            VALUES (?, ?, ?, 'pending', NOW())
        ");
        
        return $stmt->execute([$this->id, $email, $name]);
    }

    public function supprimerInvitation($invitationId) {
        if (!$this->id || !$this->conn) {
            return false;
        }

        $stmt = $this->conn->prepare("DELETE FROM invitations WHERE id = ? AND event_id = ?");
        return $stmt->execute([$invitationId, $this->id]);
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'date_event' => $this->date_event,
            'location' => $this->location,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status
        ];
    }
}
?>