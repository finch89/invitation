<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php?error=' . urlencode("Veuillez vous connecter pour accéder au tableau de bord"));
    exit;
}

require_once '../config/database.php';
require_once '../classes/Users.php';
require_once '../classes/Events.php';  // Ajouté pour les événements

try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    $user = Users::trouverParId($pdo, $_SESSION['user_id']);
    
    if (!$user) {
        session_destroy();
        header('Location: connexion.php?error=' . urlencode("Utilisateur non trouvé"));
        exit;
    }
    
    $_SESSION['user_nom'] = $user->getFirstname() . ' ' . $user->getLastname();
    $_SESSION['user_role'] = $user->getRole();
    
    // Récupérer les derniers événements de l'utilisateur
    $recentEvents = Events::trouverParUtilisateur($pdo, $_SESSION['user_id']);
    $recentEvents = array_slice($recentEvents, 0, 3); // 3 derniers
    
} catch (Exception $e) {
    error_log("Erreur dashboard: " . $e->getMessage());
    $error = "Une erreur est survenue lors du chargement du tableau de bord";
}

include '../includes/header.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Tableau de bord</h1>
        <p>Bienvenue, <strong><?= htmlspecialchars($_SESSION['user_nom'] ?? 'Utilisateur') ?></strong> !</p>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error">
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
        <div class="card">
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
        <div class="card">
            <div class="card-header">
                <h2>Actions rapides</h2>
            </div>
            <div class="card-body">
                <ul class="action-list">
                    <li><a href="edit_profil.php" class="action-link">✏️ Modifier mon profil</a></li>
                    <li><a href="change_password.php" class="action-link">🔑 Changer mon mot de passe</a></li>
                    
                    <!-- LIEN CORRIGÉ ICI -->
                    <li><a href="events/create.php" class="action-link">📅 Créer un événement</a></li>
                    <li><a href="events/list.php" class="action-link">📋 Mes événements</a></li>
                    
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="admin/users.php" class="action-link">👥 Gérer les utilisateurs</a></li>
                        <li><a href="admin/events.php" class="action-link">📊 Tous les événements</a></li>
                    <?php endif; ?>
                    
                    <li><a href="deconnexion.php" class="action-link text-danger">🚪 Se déconnecter</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Carte Événements récents -->
        <div class="card">
            <div class="card-header">
                <h2>Événements récents</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($recentEvents)): ?>
                    <ul class="event-list">
                        <?php foreach ($recentEvents as $event): ?>
                            <li class="event-item">
                                <a href="events/view.php?id=<?= $event->getId() ?>" class="event-link">
                                    <strong><?= htmlspecialchars($event->getTitle()) ?></strong>
                                    <small><?= $event->getDateEventFormatted('d/m/Y') ?></small>
                                    <?= $event->getStatusBadge() ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="events/list.php" class="btn btn-outline btn-small">Voir tous mes événements</a>
                <?php else: ?>
                    <p class="text-center">Vous n'avez pas encore d'événements.</p>
                    <a href="events/create.php" class="btn btn-primary btn-small">Créer mon premier événement</a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Si admin, afficher les statistiques -->
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <div class="card dashboard-card-large">
            <div class="card-header">
                <h2>Aperçu administrateur</h2>
                <a href="admin/users.php" class="btn btn-small">Voir tout</a>
            </div>
            <div class="card-body">
                <?php
                try {
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
                    $totalUsers = $stmt->fetch()['total'];
                    
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM events");
                    $totalEvents = $stmt->fetch()['total'];
                    
                    $stmt = $pdo->query("SELECT COUNT(*) as today FROM users WHERE DATE(created_at) = CURDATE()");
                    $newToday = $stmt->fetch()['today'];
                ?>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-value"><?= $totalUsers ?></span>
                        <span class="stat-label">Utilisateurs</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?= $totalEvents ?></span>
                        <span class="stat-label">Événements</span>
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

<!-- Ajout du CSS pour les événements récents -->
<style>
.event-list {
    list-style: none;
    padding: 0;
    margin: 0 0 1rem 0;
}

.event-item {
    margin-bottom: 0.5rem;
    padding: 0.5rem;
    background-color: var(--bg-secondary);
    border-radius: var(--border-radius-md);
    transition: background-color var(--transition-speed);
}

.event-item:hover {
    background-color: var(--accent-soft);
}

.event-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--text-primary);
    text-decoration: none;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.event-link small {
    color: var(--text-muted);
    font-size: 0.85rem;
}
</style>

<?php include '../includes/footer.php'; ?>