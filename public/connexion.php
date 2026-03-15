<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

include '../includes/header.php';
?>

<div class="container">
    <h1>Connexion</h1>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Inscription réussie ! Vous pouvez maintenant vous connecter.
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="../traitements/traiter_connexion.php">
        <div class="form-group">
            <label>Email :</label>
            <input type="email" name="email" required 
                   value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>">
        </div>
        
        <div class="form-group">
            <label>Mot de passe :</label>
            <input type="password" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
    
    <p>Pas encore inscrit ? <a href="inscription.php">Inscrivez-vous</a></p>
</div>

<?php include '../includes/footer.php'; ?>