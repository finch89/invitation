<?php
// Démarrer la session
session_start();

// Rediriger si déjà connecté
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Inclure l'en-tête
include '../includes/header.php';
?>

<div class="container">
    <h1>Inscription</h1>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Inscription réussie ! <a href="connexion.php">Connectez-vous</a>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="../traitements/traiter_inscription.php">
        <div class="form-group">
            <label>Nom :</label>
            <input type="text" name="lastname" required 
                   value="<?= isset($_GET['lastname']) ? htmlspecialchars($_GET['lastname']) : '' ?>">
        </div>
        
        <div class="form-group">
            <label>Prénom :</label>
            <input type="text" name="firstname" required
                   value="<?= isset($_GET['firstname']) ? htmlspecialchars($_GET['firstname']) : '' ?>">
        </div>
        
        <div class="form-group">
            <label>Email :</label>
            <input type="email" name="email" required
                   value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>">
        </div>
        
        <div class="form-group">
            <label>Mot de passe (min. 8 caractères) :</label>
            <input type="password" name="password" required minlength="8">
        </div>
        
        <div class="form-group">
            <label>Confirmer le mot de passe :</label>
            <input type="password" name="confirm_password" required minlength="8">
        </div>
        
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
    
    <p>Déjà inscrit ? <a href="connexion.php">Connectez-vous</a></p>
</div>

<?php include '../includes/footer.php'; ?>