<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="fr" data-theme="<?= $_COOKIE['theme'] ?? 'light' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvitationApp - Gérez vos invitations simplement</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <!-- Navigation avec les classes CSS existantes -->
    <nav class="navbar">
        <div class="navbar-container">
            <!-- Logo à gauche -->
            <div class="navbar-logo">
                <a href="/index.php">InvitationApp</a>
            </div>
            
            <!-- Menu de navigation -->
            <ul class="navbar-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/public/dashboard.php">Tableau de bord</a></li>
                    <li><a href="/public/events/list.php">Mes événements</a></li>
                    <li><a href="/public/deconnexion.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/public/inscription.php">Inscription</a></li>
                    <li><a href="/public/connexion.php">Connexion</a></li>
                <?php endif; ?>
                
                <!-- Bouton de thème -->
                <li>
                    <button id="theme-switch" class="theme-switch" aria-label="Changer de thème">
                        🌙
                    </button>
                </li>
            </ul>
        </div>
    </nav>
    <main>