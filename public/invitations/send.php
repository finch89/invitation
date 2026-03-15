<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Users.php';
require_once '../../classes/Events.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion.php');
    exit;
}

if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    header('Location: ../events/list.php?error=' . urlencode("Événement non trouvé"));
    exit;
}

$database = new Database();
$pdo = $database->getConnection();

$event = Events::trouverParId($pdo, $_GET['event_id']);

if (!$event || $event->getCreatedBy() != $_SESSION['user_id']) {
    header('Location: ../events/list.php?error=' . urlencode("Événement non trouvé"));
    exit;
}

include '../../includes/header.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1>Inviter des personnes</h1>
            <p>Pour : <?= htmlspecialchars($event->getTitle()) ?></p>
        </div>
        <a href="../events/view.php?id=<?= $event->getId() ?>" class="btn btn-outline">← Retour à l'événement</a>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>
    
    <div class="events-grid" style="grid-template-columns: 1fr 1fr;">
        <!-- Formulaire d'ajout d'invité -->
        <div class="card">
            <div class="card-header">
                <h2>Ajouter un invité</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="../../traitements/events/send_invitations.php">
                    <input type="hidden" name="event_id" value="<?= $event->getId() ?>">
                    
                    <div class="form-group">
                        <label for="guest_name">Nom de l'invité (optionnel)</label>
                        <input type="text" id="guest_name" name="guest_name" 
                               placeholder="Ex: Jean Dupont">
                    </div>
                    
                    <div class="form-group">
                        <label for="guest_email">Email de l'invité *</label>
                        <input type="email" id="guest_email" name="guest_email" required 
                               placeholder="exemple@email.com">
                    </div>
                    
                    <button type="submit" name="action" value="add" class="btn btn-primary btn-block">
                        ➕ Ajouter cet invité
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Ajout multiple -->
        <div class="card">
            <div class="card-header">
                <h2>Ajouter plusieurs invités</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="../../traitements/events/send_invitations.php">
                    <input type="hidden" name="event_id" value="<?= $event->getId() ?>">
                    
                    <div class="form-group">
                        <label for="guest_list">Liste des invités (un par ligne)</label>
                        <textarea id="guest_list" name="guest_list" rows="5" 
                                  placeholder="Nom:email&#10;Jean Dupont:jean@email.com&#10;Marie:marie@email.com"></textarea>
                        <small class="form-help">Format: Nom:email ou simplement email</small>
                    </div>
                    
                    <button type="submit" name="action" value="add_multiple" class="btn btn-primary btn-block">
                        ➕➕ Ajouter tous ces invités
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Liste des invités existants -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <h2>Invités déjà ajoutés (<?= count($event->getInvitations()) ?>)</h2>
        </div>
        <div class="card-body">
            <?php $invitations = $event->getInvitations(); ?>
            <?php if (empty($invitations)): ?>
                <p class="text-center p-3">Aucun invité pour le moment</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="invitations-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invitations as $inv): ?>
                                <tr>
                                    <td class="guest-name"><?= htmlspecialchars($inv['guest_name'] ?? '-') ?></td>
                                    <td class="guest-email"><?= htmlspecialchars($inv['guest_email']) ?></td>
                                    <td>
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
                                    <td>
                                        <a href="../../traitements/events/send_invitations.php?delete=<?= $inv['id'] ?>&event_id=<?= $event->getId() ?>" 
                                           class="btn btn-small btn-outline"
                                           onclick="return confirm('Retirer cet invité ?')">
                                            🗑️
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
