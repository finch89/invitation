<?php
// Vérification si l'utilisateur est admin
// À inclure au début des pages d'administration

if (!isset($_SESSION['user_id'])) {
    header('Location: /public/connexion.php?error=' . urlencode("Veuillez vous connecter"));
    exit;
}

try {
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../classes/Users.php';
    
    $database = new Database();
    $pdo = $database->getConnection();
    
    $user = Users::trouverParId($pdo, $_SESSION['user_id']);
    
    if (!$user || $user->getRole() !== 'admin') {
        // Rediriger vers le tableau de bord avec un message d'erreur
        header('Location: /public/dashboard.php?error=' . urlencode("Accès non autorisé - Administration réservée"));
        exit;
    }
    
    // Stocker les infos admin en session si besoin
    $_SESSION['is_admin'] = true;
    $_SESSION['admin_name'] = $user->getFirstname() . ' ' . $user->getLastname();
    
} catch (Exception $e) {
    error_log("Erreur vérification admin: " . $e->getMessage());
    header('Location: /public/dashboard.php?error=' . urlencode("Erreur de vérification"));
    exit;
}
?>