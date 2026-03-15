<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Users.php';
require_once '../../classes/Events.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion.php');
    exit;
}

$database = new Database();
$pdo = $database->getConnection();

// Récupérer les événements de l'utilisateur
$events = Events::trouverParUtilisateur($pdo, $_SESSION['user_id']);

include '../../includes/header.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1>Mes événements</h1>
            <p>Gérez tous vos événements</p>
        </div>
        <a href="create.php" class="btn btn-primary">+ Nouvel événement</a>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success'] ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($events)): ?>
        <div class="card text-center p-5">
            <div class="empty-state">
                <div class="empty-state-icon">🎉</div>
                <h2>Aucun événement pour le moment</h2>
                <p>Créez votre premier événement pour commencer à inviter vos proches !</p>
                <a href="create.php" class="btn btn-primary">Créer un événement</a>
            </div>
        </div>
    <?php else: ?>
        <div class="events-grid">
            <?php foreach ($events as $event): 
                $stats = $event->getStats();
                $date = new DateTime($event->getDateEvent());
            ?>
                <div class="event-card" data-status="<?= $event->getStatus() ?>" data-date="<?= $date->format('Y-m-d') ?>">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($event->getTitle()) ?></h3>
                        <?= $event->getStatusBadge() ?>
                    </div>
                    
                    <div class="card-body">
                        <p class="event-description-preview">
                            <?= substr(htmlspecialchars($event->getDescription()), 0, 100) ?>...
                        </p>
                        
                        <div class="event-meta-compact">
                            <div class="event-meta-item">
                                <span class="event-meta-icon">📅</span>
                                <span class="event-meta-text">
                                    <?= $date->format('d/m/Y') ?> à <?= $date->format('H:i') ?>
                                </span>
                            </div>
                            <div class="event-meta-item">
                                <span class="event-meta-icon">📍</span>
                                <span class="event-meta-text"><?= htmlspecialchars($event->getLocation()) ?></span>
                            </div>
                        </div>
                        
                        <div class="stats-mini">
                            <div class="stats-mini-item">
                                <div class="stats-mini-value"><?= $stats['total'] ?></div>
                                <div class="stats-mini-label">Invit茅s</div>
                            </div>
                            <div class="stats-mini-item">
                                <div class="stats-mini-value text-success"><?= $stats['accepted'] ?></div>
                                <div class="stats-mini-label">Accept茅</div>
                            </div>
                            <div class="stats-mini-item">
                                <div class="stats-mini-value text-error"><?= $stats['declined'] ?></div>
                                <div class="stats-mini-label">Refus茅</div>
                            </div>
                        </div>
                        
                        <div class="event-actions">
                            <a href="view.php?id=<?= $event->getId() ?>" class="btn btn-primary btn-small btn-expand">Voir</a>
                            <a href="edit.php?id=<?= $event->getId() ?>" class="btn btn-outline btn-small btn-icon">✏️</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>