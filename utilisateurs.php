<?php
class Users {
    public $nom;
    public $prenom;
    public $email;

    public function __construct($nom, $prenom, $email) {
        // Correction: utiliser strtoupper() et strtolower() avec ucfirst()
        $this->nom = strtoupper($nom);           // Met tout en majuscules
        $this->prenom = ucfirst(strtolower($prenom)); // Première lettre en majuscule, reste en minuscules
        $this->email = $email;
    }
    
    public function sauvegarder($conn) {
        $stmt = $conn->prepare("INSERT INTO users (nom, prenom, email) VALUES (?, ?, ?)");
        
        // Méthode 1: avec bindValue()
        $stmt->bindValue(1, $this->nom);
        $stmt->bindValue(2, $this->prenom);
        $stmt->bindValue(3, $this->email);
        
        // OU Méthode 2: directement avec execute()
        // $stmt->execute([$this->nom, $this->prenom, $this->email]);
        
        return $stmt->execute();
    }
    
    public function afficher() {
        return "Nom: $this->nom, Prénom: $this->prenom, Email: $this->email";
    }
}

// Exemple d'utilisation :
/*
$conn = new PDO("mysql:host=localhost;dbname=bd_invitation", "root", "");
$user = new Users("dupont", "jean", "jean@email.com");
if ($user->sauvegarder($conn)) {
    echo $user->afficher();
}
*/
?>