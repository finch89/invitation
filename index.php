<?php
session_start();
require_once 'config/database.php';

try {
    $database = new Database();
    $pdo = $database->getConnection();
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $totalUsers = $stmt->fetch()['total'];
} catch (Exception $e) {
    $totalUsers = 0;
    error_log("Erreur index: " . $e->getMessage());
}

include 'includes/header.php';
?>

<!-- Barre de statistiques -->
<?php if ($totalUsers > 0): ?>
<div class="stats-bar">
    <p class="feature-description">🌟 <strong><?= $totalUsers ?></strong> personnes nous font déjà confiance !</p>
</div>
<?php endif; ?>

<!-- Section Hero -->
<section class="hero-section">
    <h1 class="hero-title">Bienvenue sur InvitationApp</h1>
    <p class="hero-subtitle">La solution simple et élégante pour gérer vos invitations</p>
    
    <div class="hero-buttons">  <!-- J'ai changé le nom pour être cohérent -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="public/dashboard.php" class="btn btn-primary">Mon tableau de bord</a>
        <?php else: ?>
            <a href="public/inscription.php" class="btn btn-primary">Créer un compte gratuit</a>
            <a href="public/connexion.php" class="btn btn-secondary">Se connecter</a>
        <?php endif; ?>
    </div>
</section>

<!-- Section Fonctionnalités -->
<section class="features-section">
    <h2 class="section-title">Pourquoi choisir InvitationApp ?</h2>  <!-- Ajout de la classe section-title -->
    
    <div class="features-grid">  <!-- CORRECTION: C'était "features-section" au lieu de "features-grid" -->
        
        <div class="feature-card">  <!-- CORRECTION: J'ai mis "feature-card" au lieu de "feature" -->
            <div class="feature-icon">📧</div>  <!-- Ajout d'icônes -->
            <h3 class="feature-title">Invitations intelligentes</h3>
            <p class="feature-description">Créez et envoyez des invitations personnalisées en quelques clics</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">📊</div>  <!-- Ajout d'icônes -->
            <h3 class="feature-title">Suivi en temps réel</h3>
            <p class="feature-description">Visualisez qui a répondu à votre invitation</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">🔔</div>  <!-- Ajout d'icônes -->
            <h3 class="feature-title">Rappels automatiques</h3>
            <p class="feature-description">Envoyez des rappels aux invités</p>
        </div>
        
    </div>
</section>

<?php include 'includes/footer.php'; ?>