<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Events.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/events/create.php');
    exit;
}

// Récupération des données
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$date_event = $_POST['date_event'] ?? '';
$location = $_POST['location'] ?? '';
$status = $_POST['status'] ?? 'draft';

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    $event = new Events($pdo);
    $event->setTitle($title)
          ->setDescription($description)
          ->setDateEvent($date_event)
          ->setLocation($location)
          ->setCreatedBy($_SESSION['user_id'])
          ->setStatus($status);
    
    if ($event->sauvegarder()) {
        $_SESSION['success'] = "Événement créé avec succès !";
        header('Location: ../../public/events/view.php?id=' . $event->getId());
        exit;
    } else {
        throw new Exception("Erreur lors de la création");
    }
    
} catch (InvalidArgumentException $e) {
    $params = [
        'error' => $e->getMessage(),
        'title' => urlencode($title),
        'description' => urlencode($description),
        'date_event' => urlencode($date_event),
        'location' => urlencode($location)
    ];
    header('Location: ../../public/events/create.php?' . http_build_query($params));
    exit;
    
} catch (Exception $e) {
    error_log("Erreur création événement: " . $e->getMessage());
    header('Location: ../../public/events/create.php?error=' . urlencode("Erreur technique"));
    exit;
}
?>