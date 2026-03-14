<?php
$host = "localhost";
$db = "bd_invitation";
$user = "root";
$pass = ""; // Ajout de la variable $pass (vide par défaut pour XAMPP/WAMP)
$charset = "utf8mb4";

$dns = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $conn = new PDO($dns, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Correction: ERR MODE
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Correction: FETCH
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    echo "Connexion réussie !"; // Optionnel: message de confirmation
} catch (PDOException $e) { // Correction: PDOException
    die("Connection échouée : " . $e->getMessage());
}
?>