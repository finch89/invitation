<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Users.php';
require_once '../../classes/Events.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion.php?error=' . urlencode("Veuillez vous connecter"));
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: list.php?error=' . urlencode("Événement non trouvé"));
    exit;
}

$database = new Database();
$pdo = $database->getConnection();

$event = Events::trouverParId($pdo, $_GET['id']);

if (!$event || $event->getCreatedBy() != $_SESSION['user_id']) {
    header('Location: list.php?error=' . urlencode("Événement non trouvé"));
    exit;
}

include '../../includes/header.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1>Modifier l'événement</h1>
            <p><?= htmlspecialchars($event->getTitle()) ?></p>
        </div>
        <a href="view.php?id=<?= $event->getId() ?>" class="btn btn-outline">← Retour</a>
    </div>
    
    <div class="form-container" style="max-width: 800px;">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="../../traitements/events/update_event.php" id="eventForm">
            <input type="hidden" name="event_id" value="<?= $event->getId() ?>">
            
            <div class="form-group">
                <label for="title">Titre de l'événement *</label>
                <input type="text" id="title" name="title" required 
                       value="<?= htmlspecialchars($event->getTitle()) ?>">
                <small class="form-help">Minimum 3 caractères</small>
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($event->getDescription()) ?></textarea>
                <small class="form-help">Minimum 10 caractères</small>
            </div>
            
            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="date_event">Date et heure *</label>
                    <input type="datetime-local" id="date_event" name="date_event" required
                           value="<?= date('Y-m-d\TH:i', strtotime($event->getDateEvent())) ?>">
                </div>
                
                <div class="form-group">
                    <label for="location">Lieu *</label>
                    <input type="text" id="location" name="location" required 
                           value="<?= htmlspecialchars($event->getLocation()) ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <option value="draft" <?= $event->getStatus() == 'draft' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="published" <?= $event->getStatus() == 'published' ? 'selected' : '' ?>>Publié</option>
                    <option value="cancelled" <?= $event->getStatus() == 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                    <option value="completed" <?= $event->getStatus() == 'completed' ? 'selected' : '' ?>>Terminé</option>
                </select>
            </div>
            
            <div class="form-group" style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Mettre à jour</button>
                <a href="view.php?id=<?= $event->getId() ?>" class="btn btn-outline" style="flex: 1;">Annuler</a>
            </div>
        </form>
        
        <div style="margin-top: 3rem; padding-top: 2rem; border-top: 2px solid var(--border-color);">
            <h3 style="color: var(--error-text);">Zone de danger</h3>
            <p style="color: var(--text-secondary); margin-bottom: 1rem;">Une fois supprimé, cette action est irréversible.</p>
            <a href="../../traitements/events/delete_event.php?id=<?= $event->getId() ?>" 
               class="btn btn-error" 
               onclick="return confirm('Êtes-vous ABSOLUMENT sûr de vouloir supprimer cet événement ? Toutes les invitations associées seront également supprimées.')">
                🗑️ Supprimer l'événement
            </a>
        </div>
    </div>
</div>

<script src="../../assets/js/events.js"></script>
<?php include '../../includes/footer.php'; ?>