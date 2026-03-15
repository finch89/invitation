<!DOCTYPE html>
<html lang="fr" data-theme="<?= $_COOKIE['theme'] ?? 'light' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvitationApp - Gérez vos invitations simplement</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="/index.php">Accueil</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="/public/dashboard.php">Tableau de bord</a></li>
                <li><a href="/public/deconnexion.php">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="/public/inscription.php">Inscription</a></li>
                <li><a href="/public/connexion.php">Connexion</a></li>
            <?php endif; ?>
            <li>
                <button id="theme-switch" class="theme-switch" aria-label="Changer de thème">
                    🌙
                </button>
            </li>
        </ul>
    </nav>
    <main>