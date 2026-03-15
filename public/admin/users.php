<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Users.php';
require_once '../../includes/admin_check.php'; // Vérifie si l'utilisateur est admin

$database = new Database();
$pdo = $database->getConnection();

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../includes/header.php';
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Administration - Utilisateurs</h1>
        <p>Gérez tous les utilisateurs de la plateforme</p>
    </div>
    
    <?php if (isset($_SESSION['admin_success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['admin_success'] ?>
            <?php unset($_SESSION['admin_success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['admin_error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['admin_error'] ?>
            <?php unset($_SESSION['admin_error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header">
            <h2>Liste des utilisateurs</h2>
        </div>
        <div class="card-body">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-color);">
                        <th style="text-align: left; padding: 0.75rem;">ID</th>
                        <th style="text-align: left; padding: 0.75rem;">Nom</th>
                        <th style="text-align: left; padding: 0.75rem;">Prénom</th>
                        <th style="text-align: