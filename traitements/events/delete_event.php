<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Events.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/connexion.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ../../public/events/list.php?error=' . urlencode("Événement non trouvé"));
    exit;
}

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    $event = Events::trouverParId($pdo, $_GET['id']);
    
    if (!$event || $event->getCreatedBy() != $_SESSION['user_id']) {
        throw new Exception("Événement non trouvé");
    }
    
    if ($event->supprimer()) {
        $_SESSION['success'] = "Événement supprimé avec succès";
        header('Location: ../../public/events/list.php');
        exit;
    } else {
        throw new Exception("Erreur lors de la suppression");
    }
    
} catch (Exception $e) {
    error_log("Erreur delete événement: " . $e->getMessage());
    header('Location: ../../public/events/view.php?id=' . $_GET['id'] . '&error=' . urlencode("Erreur lors de la suppression"));
    exit;
}
?>
