// j'ai CSS dans la page pour que ce soit plus joli,mais c'est doit
// etre dans la feuille de style, je le met la pour que tu puisse 
// voir le resultat final, mais tu doit le mettre dans la feuille de style 
// pour que ce soit plus propre et plus facile a maintenir css
<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Users.php';
require_once '../../classes/Events.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: list.php');
    exit;
}

$database = new Database();
$pdo = $database->getConnection();

$event = Events::trouverParId($pdo, $_GET['id']);

if (!$event || $event->getCreatedBy() != $_SESSION['user_id']) {
    header('Location: list.php?error=' . urlencode("Événement non trouvé"));
    exit;
}

$invitations = $event->getInvitations();
$stats = $event->getStats();

include '../../includes/header.php';
?>

<div class="dashboard-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <a href="list.php" class="btn btn-outline btn-small">← Retour à la liste</a>
        <div style="display: flex; gap: 1rem;">
            <a href="edit.php?id=<?= $event->getId() ?>" class="btn btn-outline">Modifier</a>
            <a href="../../traitements/events/send_invitations.php?event_id=<?= $event->getId() ?>" class="btn btn-primary">Inviter des personnes</a>
        </div>
    </div>
    
    <div class="event-header card" style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;"><?= htmlspecialchars($event->getTitle()) ?></h1>
                <?= $event->getStatusBadge() ?>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 1.5rem; font-weight: bold;"><?= $stats['accepted'] ?>/<?= $stats['total'] ?></div>
                <small>confirmés</small>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 2rem; padding: 1rem 0; border-top: 1px solid var(--border-color);">
            <div style="text-align: center;">
                <div style="font-size: 1.5rem;">📅</div>
                <div style="font-weight: bold;"><?= $event->getDateEventFormatted('d/m/Y') ?></div>
                <small>Date</small>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 1.5rem;">⏰</div>
                <div style="font-weight: bold;"><?= $event->getDateEventFormatted('H:i') ?></div>
                <small>Heure</small>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 1.5rem;">📍</div>
                <div style="font-weight: bold;"><?= htmlspecialchars($event->getLocation()) ?></div>
                <small>Lieu</small>
            </div>
        </div>
        
        <div style="margin-top: 2rem;">
            <h3>Description</h3>
            <p style="white-space: pre-line;"><?= nl2br(htmlspecialchars($event->getDescription())) ?></p>
        </div>
    </div>
    
    <div class="stats-grid" style="margin-bottom: 2rem;">
        <div class="stat-card">
            <span class="stat-value"><?= $stats['total'] ?></span>
            <span class="stat-label">Total invités</span>
        </div>
        <div class="stat-card">
            <span class="stat-value" style="color: var(--success-text);"><?= $stats['accepted'] ?></span>
            <span class="stat-label">Accepté</span>
        </div>
        <div class="stat-card">
            <span class="stat-value" style="color: var(--warning-text);"><?= $stats['maybe'] ?></span>
            <span class="stat-label">Peut-être</span>
        </div>
        <div class="stat-card">
            <span class="stat-value" style="color: var(--error-text);"><?= $stats['declined'] ?></span>
            <span class="stat-label">Refusé</span>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Liste des invités</h2>
            <a href="../../traitements/events/send_invitations.php?event_id=<?= $event->getId() ?>" class="btn btn-primary btn-small">+ Ajouter des invités</a>
        </div>
        <div class="card-body">
            <?php if (empty($invitations)): ?>
                <p style="text-align: center; padding: 2rem;">Aucun invité pour le moment. <a href="../../traitements/events/send_invitations.php?event_id=<?= $event->getId() ?>">Ajouter des invités</a></p>
            <?php else: ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-color);">
                            <th style="text-align: left; padding: 0.5rem;">Nom</th>
                            <th style="text-align: left; padding: 0.5rem;">Email</th>
                            <th style="text-align: left; padding: 0.5rem;">Statut</th>
                            <th style="text-align: left; padding: 0.5rem;">Date réponse</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invitations as $inv): ?>
                            <tr style="border-bottom: 1px solid var(--border-light);">
                                <td style="padding: 0.75rem 0.5rem;">
                                    <?= $inv['guest_name'] ? htmlspecialchars($inv['guest_name']) : '-' ?>
                                </td>
                                <td style="padding: 0.75rem 0.5rem;"><?= htmlspecialchars($inv['guest_email']) ?></td>
                                <td style="padding: 0.75rem 0.5rem;">
                                    <?php
                                    $statusClass = match($inv['status']) {
                                        'accepted' => 'badge-success',
                                        'declined' => 'badge-error',
                                        'maybe' => 'badge-warning',
                                        default => 'badge-secondary'
                                    };
                                    $statusText = match($inv['status']) {
                                        'accepted' => 'Accepté',
                                        'declined' => 'Refusé',
                                        'maybe' => 'Peut-être',
                                        default => 'En attente'
                                    };
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td style="padding: 0.75rem 0.5rem;">
                                    <?= $inv['response_date'] ? date('d/m/Y H:i', strtotime($inv['response_date'])) : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
