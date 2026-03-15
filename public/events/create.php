<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Users.php';
require_once '../../classes/Events.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion.php?error=' . urlencode("Veuillez vous connecter"));
    exit;
}

include '../../includes/header.php';
?>

<div class="container">
    <div class="form-container" style="max-width: 800px;">
        <h1 class="form-title">Créer un nouvel événement</h1>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="../../traitements/events/create_event.php" id="eventForm">
            <div class="form-group">
                <label for="title">Titre de l'événement *</label>
                <input type="text" id="title" name="title" required 
                       placeholder="Ex: Mariage de Jean et Marie"
                       value="<?= isset($_GET['title']) ? htmlspecialchars($_GET['title']) : '' ?>">
                <small class="form-help">Minimum 3 caractères</small>
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" rows="5" required 
                          placeholder="Décrivez votre événement..."><?= isset($_GET['description']) ? htmlspecialchars($_GET['description']) : '' ?></textarea>
                <small class="form-help">Minimum 10 caractères</small>
            </div>
            
            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="date_event">Date et heure *</label>
                    <input type="datetime-local" id="date_event" name="date_event" required
                           value="<?= isset($_GET['date_event']) ? htmlspecialchars($_GET['date_event']) : '' ?>"
                           min="<?= date('Y-m-d\TH:i') ?>">
                </div>
                
                <div class="form-group">
                    <label for="location">Lieu *</label>
                    <input type="text" id="location" name="location" required 
                           placeholder="Ex: Salle des fêtes, Paris"
                           value="<?= isset($_GET['location']) ? htmlspecialchars($_GET['location']) : '' ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <option value="draft">Brouillon</option>
                    <option value="published" selected>Publié immédiatement</option>
                </select>
            </div>
            
            <div class="form-group" style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Créer l'événement</button>
                <a href="list.php" class="btn btn-outline" style="flex: 1;">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script src="../../assets/js/events.js"></script>
<?php include '../../includes/footer.php'; ?>