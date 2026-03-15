<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Users.php';
require_once '../../classes/Events.php';
require_once '../../includes/admin_check.php';

$database = new Database();
$pdo = $database->getConnection();

// Statistiques globales
$stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
$totalUsers = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM events");
$totalEvents = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM invitations");
$totalInvitations = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE DATE(created_at) = CURDATE()");
$newUsersToday = $stmt->fetch()['total'];

// Derniers utilisateurs
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
$recentUsers = $stmt->fetchAll();

// Derniers événements
$stmt = $pdo->query("
    SELECT e.*, u.firstname, u.lastname 
    FROM events e 
    JOIN users u ON e.created_by = u.id 
    ORDER BY e.created_at DESC 
    LIMIT 5
");
$recentEvents = $stmt->fetchAll();

include '../../includes/header.php';
?>

<div class="admin-dashboard">
    <div class="admin-header">
        <h1>Dashboard Administration</h1>
        <div class="admin-header-actions">
            <span class="badge badge-admin">Admin: <?= htmlspecialchars($_SESSION['admin_name']) ?></span>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="admin-stats-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-icon">👥</div>
            <div class="admin-stat-value"><?= $totalUsers ?></div>
            <div class="admin-stat-label">Utilisateurs totaux</div>
            <div class="admin-stat-change positive">+<?= $newUsersToday ?> aujourd'hui</div>
        </div>
        
        <div class="admin-stat-card">
            <div class="admin-stat-icon">📅</div>
            <div class="admin-stat-value"><?= $totalEvents ?></div>
            <div class="admin-stat-label">Événements créés</div>
        </div>
        
        <div class="admin-stat-card">
            <div class="admin-stat-icon">✉️</div>
            <div class="admin-stat-value"><?= $totalInvitations ?></div>
            <div class="admin-stat-label">Invitations envoyées</div>
        </div>
        
        <div class="admin-stat-card">
            <div class="admin-stat-icon">📊</div>
            <div class="admin-stat-value"><?= round(($totalInvitations / max($totalEvents, 1)), 1) ?></div>
            <div class="admin-stat-label">Moy. invitations/évt</div>
        </div>
    </div>
    
    <div class="admin-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <!-- Derniers utilisateurs -->
        <div class="admin-table-container">
            <div class="admin-table-header">
                <h2>Derniers utilisateurs</h2>
                <a href="users.php" class="btn btn-small btn-primary">Voir tout</a>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentUsers as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Derniers événements -->
        <div class="admin-table-container">
            <div class="admin-table-header">
                <h2>Derniers événements</h2>
                <a href="events.php" class="btn btn-small btn-primary">Voir tout</a>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Créateur</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentEvents as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['title']) ?></td>
                            <td><?= htmlspecialchars($event['firstname'] . ' ' . $event['lastname']) ?></td>
                            <td><?= date('d/m/Y', strtotime($event['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Actions rapides -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <h2>Actions rapides</h2>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="users.php" class="btn btn-primary">Gérer les utilisateurs</a>
                <a href="events.php" class="btn btn-outline">Voir tous les événements</a>
                <a href="../events/list.php" class="btn btn-outline">Mes événements</a>
                <a href="../events/create.php" class="btn btn-outline">Créer un événement</a>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>