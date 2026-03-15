<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Events.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/events/list.php');
    exit;
}

$event_id = $_POST['event_id'] ?? 0;
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$date_event = $_POST['date_event'] ?? '';
$location = $_POST['location'] ?? '';
$status = $_POST['status'] ?? 'draft';

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Vérifier que l'événement appartient bien à l'utilisateur
    $event = Events::trouverParId($pdo, $event_id);
    if (!$event || $event->getCreatedBy() != $_SESSION['user_id']) {
        throw new Exception("Événement non trouvé");
    }
    
    $event->setTitle($title)
          ->setDescription($description)
          ->setDateEvent($date_event)
          ->setLocation($location)
          ->setStatus($status);
    
    if ($event->sauvegarder()) {
        $_SESSION['success'] = "Événement mis à jour avec succès !";
        header('Location: ../../public/events/view.php?id=' . $event_id);
        exit;
    } else {
        throw new Exception("Erreur lors de la mise à jour");
    }
    
} catch (InvalidArgumentException $e) {
    $params = [
        'error' => $e->getMessage(),
        'id' => $event_id
    ];
    header('Location: ../../public/events/edit.php?' . http_build_query($params));
    exit;
    
} catch (Exception $e) {
    error_log("Erreur update événement: " . $e->getMessage());
    header('Location: ../../public/events/edit.php?id=' . $event_id . '&error=' . urlencode("Erreur technique"));
    exit;
}
?>