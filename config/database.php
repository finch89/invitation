<?php
// Configuration de la base de données
class Database {
    private $host = 'localhost';
    private $dbname = 'bd_invitation';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function getConnection() {
        $this->pdo = null;
        
        try {
            $this->pdo = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            error_log("Erreur de connexion : " . $e->getMessage());
            die("Erreur de connexion à la base de données");
        }
        
        return $this->pdo;
    }
}
?>