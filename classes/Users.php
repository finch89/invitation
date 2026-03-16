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
    //La porte d'entrée
    /*
    En clair : On crée une fonction publique (accessible depuis 
    n'importe où dans ton code). Elle attend qu'on lui donne un 
    ingrédient de base : la variable $lastname (qui vient 
    généralement de ton formulaire, comme $_POST['nom']). 
    */
    public function setLastname($lastname) {
    //Le contrôle de sécurité (Le vigile)
    /*
    En clair : Le code vérifie si la case est vide. empty() 
    regarde si l'utilisateur a cliqué sur "S'inscrire" en oubliant
     de remplir son nom, ou s'il n'a mis qu'un zéro. 
     */
        if (empty($lastname)) {
    //Le message d'erreur (Le panneau d'affichage)
    /*
    En clair : Si le nom est vide, le code s'arrête net ici. 
    La commande throw déclenche une alarme (une Exception). 
    L'utilisateur ne sera pas enregistré, et cette phrase d'erreur
    pourra être attrapée par ton fichier de traitement pour être 
    affichée en rouge sur ton site : "Le nom ne peut pas être vide". 
      */
            throw new InvalidArgumentException("Le nom ne peut pas être vide");
        }
    //Le grand nettoyage (La magie !) 
    /* 
trim($lastname) : Cette fonction PHP sort ses ciseaux et coupe tous les espaces invisibles 
    tapés par erreur au début et à la fin. Si l'utilisateur a tapé "   dupont  ", 
    trim le transforme en "dupont".

strtoupper(...) : Cette fonction prend le résultat propre et le force tout en MAJUSCULES.
    "dupont" devient "DUPONT".

$this->lastname = ... : Enfin, on prend ce beau résultat tout propre ("DUPONT") et on le range bien 
    à l'abri dans le coffre-fort privé de notre classe ($this->lastname). C'est lui qui ira dans 
    la base de données ! */  
        $this->lastname = strtoupper(trim($lastname));
        //Le petit bonus pratique
/*
En clair : Cette ligne renvoie l'utilisateur en cours.

À quoi ça sert ? C'est une astuce de développeur qui s'appelle le 
"chaînage". Ça te permet d'écrire ton code PHP sur une seule ligne
 de manière très élégante si tu le souhaites, comme ceci :

$nouvelUtilisateur->setFirstname("Jean")->setLastname("Dupont")->setEmail("..."); 
*/
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