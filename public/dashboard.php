<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si non connecté
    header('Location: connexion.php?error=' . urlencode("Veuillez vous connecter pour accéder au tableau de bord"));
    exit;
}

// Inclure les fichiers nécessaires
require_once '../config/database.php';
require_once '../classes/Users.php';

try {
    // Connexion à la base de données
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Récupérer les informations complètes de l'utilisateur connecté
    $user = Users::trouverParId($pdo, $_SESSION['user_id']);
    
    // Vérifier si l'utilisateur existe toujours en base
    if (!$user) {
        session_destroy();
        header('Location: connexion.php?error=' . urlencode("Utilisateur non trouvé"));
        exit;
    }
    
    // Mettre à jour les informations de session si nécessaire
    $_SESSION['user_nom'] = $user->getFirstname() . ' ' . $user->getLastname();
    $_SESSION['user_role'] = $user->getRole();
    
} catch (Exception $e) {
    error_log("Erreur dashboard: " . $e->getMessage());
    $error = "Une erreur est survenue lors du chargement du tableau de bord";
}

// Inclure l'en-tête
include '../includes/header.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Tableau de bord</h1>
        <p>Bienvenue, <strong><?= htmlspecialchars($_SESSION['user_nom'] ?? 'Utilisateur') ?></strong> !</p>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>
    
    <div class="dashboard-grid">
        <!-- Carte Profil -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2>Mon Profil</h2>
            </div>
            <div class="card-body">
                <?php if (isset($user)): ?>
                    <p><strong>Nom :</strong> <?= htmlspecialchars($user->getLastname()) ?></p>
                    <p><strong>Prénom :</strong> <?= htmlspecialchars($user->getFirstname()) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($user->getEmail()) ?></p>
                    <p><strong>Rôle :</strong> 
                        <span class="badge badge-<?= $user->getRole() ?>">
                            <?= htmlspecialchars($user->getRole()) ?>
                        </span>
                    </p>
                    <p><strong>Membre depuis :</strong> 
                        <?= date('d/m/Y', strtotime($user->getCreatedAt() ?? 'now')) ?>
                    </p>
                <?php endif; ?>
                <a href="edit_profil.php" class="btn btn-primary">Modifier mon profil</a>
            </div>
        </div>
        
        <!-- Carte Actions rapides -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2>Actions rapides</h2>
            </div>
            <div class="card-body">
                <ul class="action-list">
                    <li><a href="edit_profil.php" class="action-link">✏️ Modifier mon profil</a></li>
                    <li><a href="change_password.php" class="action-link">🔑 Changer mon mot de passe</a></li>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="admin/users.php" class="action-link">👥 Gérer les utilisateurs</a></li>
                        <li><a href="admin/stats.php" class="action-link">📊 Voir les statistiques</a></li>
                    <?php endif; ?>
                    <li><a href="deconnexion.php" class="action-link text-danger">🚪 Se déconnecter</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Carte Activité récente -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2>Activité récente</h2>
            </div>
            <div class="card-body">
                <p>Dernière connexion : <strong><?= date('d/m/Y H:i') ?></strong></p>
                <p>Statut : <span class="badge badge-success">Connecté</span></p>
                <!-- Ajoutez ici d'autres informations d'activité -->
            </div>
        </div>
        
        <!-- Si admin, afficher un aperçu des statistiques -->
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <div class="dashboard-card dashboard-card-large">
            <div class="card-header">
                <h2>Aperçu administrateur</h2>
                <a href="admin/users.php" class="btn btn-small">Voir tout</a>
            </div>
            <div class="card-body">
                <?php
                try {
                    // Statistiques pour l'admin
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
                    $totalUsers = $stmt->fetch()['total'];
                    
                    $stmt = $pdo->query("SELECT COUNT(*) as today FROM users WHERE DATE(created_at) = CURDATE()");
                    $newToday = $stmt->fetch()['today'];
                ?>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-value"><?= $totalUsers ?></span>
                        <span class="stat-label">Utilisateurs totaux</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?= $newToday ?></span>
                        <span class="stat-label">Nouveaux aujourd'hui</span>
                    </div>
                </div>
                <?php
                } catch (Exception $e) {
                    echo "<p>Erreur lors du chargement des statistiques</p>";
                }
                ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.dashboard-header {
    margin-bottom: 30px;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
}

.dashboard-header h1 {
    margin: 0 0 10px 0;
    font-size: 2em;
}

.dashboard-header p {
    margin: 0;
    font-size: 1.2em;
    opacity: 0.9;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.dashboard-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.dashboard-card-large {
    grid-column: 1 / -1;
}

.card-header {
    padding: 15px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.card-header h2 {
    margin: 0;
    font-size: 1.3em;
    color: #333;
}

.card-body {
    padding: 20px;
}

.badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 0.85em;
    font-weight: 600;
}

.badge-user {
    background: #e3f2fd;
    color: #1976d2;
}

.badge-admin {
    background: #f3e5f5;
    color: #7b1fa2;
}

.badge-success {
    background: #e8f5e8;
    color: #2e7d32;
}

.action-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.action-list li {
    margin-bottom: 10px;
}

.action-link {
    display: block;
    padding: 10px;
    background: #f8f9fa;
    color: #333;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s;
}

.action-link:hover {
    background: #e9ecef;
}

.text-danger {
    color: #dc3545;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
}

.btn-small {
    padding: 5px 10px;
    font-size: 12px;
}

.alert {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    text-align: center;
}

.stat-item {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-value {
    display: block;
    font-size: 2em;
    font-weight: bold;
    color: #667eea;
}

.stat-label {
    display: block;
    margin-top: 5px;
    color: #666;
    font-size: 0.9em;
}

@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include '../includes/footer.php'; ?>