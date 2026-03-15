<?php
class Users {
    private $id;
    private $lastname;
    private $firstname;
    private $email;
    private $password;
    private $role;
    private $created_at;
    private $conn;

    public function __construct($pdo = null) {
        $this->conn = $pdo;
    }

    // Setters
    public function setLastname($lastname) {
        if (empty($lastname)) {
            throw new InvalidArgumentException("Le nom ne peut pas être vide");
        }
        $this->lastname = strtoupper(trim($lastname));
        return $this;
    }

    public function setFirstname($firstname) {
        if (empty($firstname)) {
            throw new InvalidArgumentException("Le prénom ne peut pas être vide");
        }
        $this->firstname = ucfirst(strtolower(trim($firstname)));
        return $this;
    }

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email invalide");
        }
        $this->email = strtolower(trim($email));
        return $this;
    }

    public function setPassword($password) {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException("Le mot de passe doit contenir au moins 8 caractères");
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    public function setRole($role = 'user') {
        $roles_valides = ['user', 'admin'];
        if (!in_array($role, $roles_valides)) {
            throw new InvalidArgumentException("Rôle invalide");
        }
        $this->role = $role;
        return $this;
    }

    // Getters
    public function getId() { 
        return $this->id; 
    }
    
    public function getLastname() { 
        return $this->lastname; 
    }
    
    public function getFirstname() { 
        return $this->firstname; 
    }
    
    public function getEmail() { 
        return $this->email; 
    }
    
    public function getRole() { 
        return $this->role; 
    }
    
    public function getCreatedAt() { 
        return $this->created_at; 
    }

    // Méthode de sauvegarde (insérer ou mettre à jour)
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
            error_log("Erreur lors de la sauvegarde : " . $e->getMessage());
            return false;
        }
    }

    // Insertion d'un nouvel utilisateur
    private function inserer() {
        $sql = "INSERT INTO users (lastname, firstname, email, password, role, created_at)
                VALUES (:lastname, :firstname, :email, :password, :role, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        
        $result = $stmt->execute([
            ':lastname' => $this->lastname,
            ':firstname' => $this->firstname,
            ':email' => $this->email,
            ':password' => $this->password,
            ':role' => $this->role ?? 'user'
        ]);
        
        if ($result) {
            $this->id = $this->conn->lastInsertId();
            $this->created_at = date('Y-m-d H:i:s');
        }
        
        return $result;
    }

    // Mise à jour d'un utilisateur existant
    private function mettreAJour() {
        $sql = "UPDATE users 
                SET lastname = :lastname, 
                    firstname = :firstname, 
                    email = :email,
                    role = :role
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':lastname' => $this->lastname,
            ':firstname' => $this->firstname,
            ':email' => $this->email,
            ':role' => $this->role,
            ':id' => $this->id
        ]);
    }

    // Vérification du mot de passe
    public function verifierPassword($password) {
        return password_verify($password, $this->password);
    }

    // Trouver un utilisateur par email
    public static function trouverParEmail($pdo, $email) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $user = new self($pdo);
        $user->id = $data['id'];
        $user->lastname = $data['lastname'];
        $user->firstname = $data['firstname'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->role = $data['role'];
        $user->created_at = $data['created_at'];
        
        return $user;
    }

    // Trouver un utilisateur par ID
    public static function trouverParId($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $user = new self($pdo);
        $user->id = $data['id'];
        $user->lastname = $data['lastname'];
        $user->firstname = $data['firstname'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->role = $data['role'];
        $user->created_at = $data['created_at'];
        
        return $user;
    }

    // Méthode login
    public static function login($pdo, $email, $password) {
        $user = self::trouverParEmail($pdo, $email);
        
        if ($user && $user->verifierPassword($password)) {
            return $user;
        }
        
        return null;
    }

    // Supprimer un utilisateur
    public function supprimer() {
        if (!$this->id) {
            return false;
        }

        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$this->id]);
    }

    // Afficher les informations (pour débogage)
    public function afficher() {
        return sprintf(
            "ID: %d, Nom: %s, Prénom: %s, Email: %s, Rôle: %s",
            $this->id ?? 0,
            $this->lastname,
            $this->firstname,
            $this->email,
            $this->role ?? 'user'
        );
    }

    // Convertir en tableau
    public function toArray() {
        return [
            'id' => $this->id,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'email' => $this->email,
            'role' => $this->role,
            'created_at' => $this->created_at
        ];
    }
}
?>