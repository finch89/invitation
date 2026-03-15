<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Events.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/connexion.php');
    exit;
}

// Cas de suppression d'invitation
if (isset($_GET['delete']) && isset($_GET['event_id'])) {
    try {
        $database = new Database();
        $pdo = $database->getConnection();
        
        $event = Events::trouverParId($pdo, $_GET['event_id']);
        
        if (!$event || $event->getCreatedBy() != $_SESSION['user_id']) {
            throw new Exception("Événement non trouvé");
        }
        
        if ($event->supprimerInvitation($_GET['delete'])) {
            $_SESSION['success'] = "Invité retiré avec succès";
        } else {
            throw new Exception("Erreur lors du retrait");
        }
        
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    
    header('Location: ../../public/invitations/send.php?event_id=' . $_GET['event_id']);
    exit;
}

// Traitement POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/events/list.php');
    exit;
}

$event_id = $_POST['event_id'] ?? 0;
$action = $_POST['action'] ?? '';

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    $event = Events::trouverParId($pdo, $event_id);
    
    if (!$event || $event->getCreatedBy() != $_SESSION['user_id']) {
        throw new Exception("Événement non trouvé");
    }
    
    $success = 0;
    $errors = 0;
    
    if ($action === 'add') {
        // Ajout d'un seul invité
        $email = filter_var($_POST['guest_email'] ?? '', FILTER_VALIDATE_EMAIL);
        $name = trim($_POST['guest_name'] ?? '');
        
        if (!$email) {
            throw new Exception("Email invalide");
        }
        
        if ($event->ajouterInvitation($email, $name)) {
            $success = 1;
        } else {
            $errors = 1;
        }
        
    } elseif ($action === 'add_multiple') {
        // Ajout multiple
        $lines = explode("\n", trim($_POST['guest_list'] ?? ''));
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Format: "Nom:email" ou juste "email"
            if (strpos($line, ':') !== false) {
                list($name, $email) = explode(':', $line, 2);
                $name = trim($name);
                $email = trim($email);
            } else {
                $name = '';
                $email = trim($line);
            }
            
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if ($email && $event->ajouterInvitation($email, $name)) {
                $success++;
            } else {
                $errors++;
            }
        }
    }
    
    $message = "$success invité(s) ajouté(s) avec succès";
    if ($errors > 0) {
        $message .= ", $errors erreur(s)";
    }
    
    $_SESSION['success'] = $message;
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

header('Location: ../../public/invitations/send.php?event_id=' . $event_id);
exit;
?>