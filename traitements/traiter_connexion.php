<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Users.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/connexion.php');
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header('Location: ../public/connexion.php?error=' . urlencode("Veuillez remplir tous les champs"));
    exit;
}

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    $user = Users::login($pdo, $email, $password);
    
    if ($user) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_nom'] = $user->getFirstname() . ' ' . $user->getLastname();
        $_SESSION['user_role'] = $user->getRole();
        
        header('Location: ../public/dashboard.php');
        exit;
    } else {
        header('Location: ../public/connexion.php?error=' . urlencode("Email ou mot de passe incorrect") . '&email=' . urlencode($email));
        exit;
    }
    
} catch (Exception $e) {
    error_log("Erreur connexion: " . $e->getMessage());
    header('Location: ../public/connexion.php?error=' . urlencode("Erreur technique"));
    exit;
}
?>