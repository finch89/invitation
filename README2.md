# 🎉 InvitationApp - Application de Gestion d'Inscriptions

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![PHP](https://img.shields.io/badge/PHP-8.2.29+-purple)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange)
![License](https://img.shields.io/badge/license-MIT-green)

## 📋 Table des matières
- [Description](#description)
- [Fonctionnalités](#fonctionnalités)
- [Fonctionalités](#Fonctionnalités-à-venir)
- [Capture d'écran](#capture-décran)
- [Prérequis techniques](#prérequis-techniques)
- [Installation](#installation)
- [Structure du projet](#structure-du-projet)
- [Base de données](#base-de-données)
- [Utilisation](#utilisation)
- [Fonctionnement détaillé](#fonctionnement-détaillé)
- [Sécurité](#sécurité)
- [Personnalisation](#personnalisation)
- [Dépannage](#dépannage)
- [Améliorations futures](#améliorations-futures)
- [Contributeurs](#contributeurs)
- [Licence](#licence)

# description

**InvitationApp** est une application web développée en PHP procédural qui permet aux utilisateurs de créer un compte, de se connecter et d'accéder à un tableau de bord personnalisé. L'application intègre un système de thèmes (clair/sombre) avec une interface moderne et responsive.

Cette application sert de base pour un système plus complet de gestion d'invitations pour des événements (mariages, anniversaires, conférences, etc.).

# fonctionnalités

### Fonctionnalités actuelles
- ✅ **Inscription utilisateur** avec validation des données
- ✅ **Connexion sécurisée** avec hashage des mots de passe
- ✅ **Tableau de bord** personnalisé après connexion
- ✅ **Déconnexion** avec destruction de session
- ✅ **Thème clair/sombre** (blanc/noir pour clair, noir/jaune pour sombre)
- ✅ **Interface responsive** (mobile, tablette, desktop)
- ✅ **Séparation des responsabilités** (MVC simplifié)
- ✅ **Protection contre les injections SQL** (requêtes préparées)
- ✅ **Gestion des erreurs** et validation des formulaires

# Fonctionnalités-à-venir
- 🔜 Modification du profil utilisateur
- 🔜 Changement de mot de passe
- 🔜 Création d'événements
- 🔜 Gestion des invitations
- 🔜 Envoi d'emails
- 🔜 Interface d'administration
- 🔜 Statistiques avancées

???
# capture-décran 

# structure-du-projet
invitation-app/
│
├── index.php                    # Page d'accueil
│
├── config/
│   └── database.php             # Configuration de la base de données
│
├── classes/
│   └── Users.php                # Classe User (modèle)
│
├── public/                       # Pages accessibles
│   ├── inscription.php           # Formulaire d'inscription
│   ├── connexion.php             # Formulaire de connexion
│   ├── dashboard.php             # Tableau de bord (après connexion)
│   └── deconnexion.php           # Déconnexion
│
├── traitements/                   # Logique de traitement (POST)
│   ├── traiter_inscription.php
│   └── traiter_connexion.php
│
├── includes/                      # Fichiers inclus
│   ├── header.php                 # En-tête HTML + navigation
│   └── footer.php                 # Pied de page + scripts
│
└── assets/                         # Ressources statiques
    ├── css/
    │   └── style.css               # Styles CSS (thèmes clair/sombre)
    └── js/
        └── theme.js                 # Gestion du changement de thème

# installation



# base-de-données

Configurer la base de données
Créez une base de données MySQL nommée bd_invitation :
```sql
CREATE DATABASE bd_invitation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
Importer la structure de la table

```sql
USE bd_invitation;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lastname VARCHAR(100) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```
Configurer la connexion à la base de données
Modifiez le fichier config/database.php avec vos informations :
```php
private $host = 'localhost';
private $dbname = 'bd_invitation';
private $username = 'root';     // Votre nom d'utilisateur MySQL
private $password = '';         // Votre mot de passe MySQL
```
# utilisation
Utilisation
1. Page d'accueil (/index.php)
Présentation de l'application

Statistiques (nombre d'utilisateurs)

Appels à l'action (inscription/connexion)

2. Inscription (/public/inscription.php)
Formulaire avec validation

Vérification que l'email n'est pas déjà utilisé

Hashage automatique du mot de passe

3. Connexion (/public/connexion.php)
Authentification par email/mot de passe

Création de session utilisateur

Redirection vers le tableau de bord

4. Tableau de bord (/public/dashboard.php)
Informations personnelles

Actions disponibles selon le rôle

Statistiques (pour les admins)

5. Déconnexion (/public/deconnexion.php)
Destruction de la session

Redirection vers l'accueil

# fonctionnement-détaillé

Flux d'inscription
1. Utilisateur → Remplit formulaire (inscription.php)
2. Formulaire → POST vers (traiter_inscription.php)
3. Validation des données
4. Création objet Users
5. Hashage du mot de passe (password_hash)
6. Insertion en base de données
7. Redirection vers connexion.php

Flux de connexion
1. Utilisateur → Remplit formulaire (connexion.php)
2. Formulaire → POST vers (traiter_connexion.php)
3. Recherche de l'utilisateur par email
4. Vérification du mot de passe (password_verify)
5. Création de session ($_SESSION)
6. Redirection vers dashboard.php

 Système de thèmes
Thème clair : :root (blanc + noir)

Thème sombre : [data-theme="dark"] (noir + jaune)

Sauvegarde du choix dans localStorage

Changement dynamique via JavaScript

Sécurité
Mesures implémentées
- Hashage des mots de passe avec password_hash()

- Requêtes préparées (protection injections SQL)

- Validation des données côté serveur

- Protection XSS avec htmlspecialchars()

- Gestion des sessions sécurisée

- Validation des emails avec filter_var()

- Vérification des doublons (email unique)

Bonnes pratiques
Mots de passe minimum 8 caractères

Destruction de session à la déconnexion

Redirection après POST (évite la double soumission)

Logs des erreurs (pas d'affichage public)

Personnalisation
Changer les couleurs du thème
Dans assets/css/style.css, modifiez les variables:

```css
/* Thème clair */
:root {
    --accent-primary: #000000;  /* Couleur principale */
    --accent-secondary: #333333; /* Couleur secondaire */
}

/* Thème sombre */
[data-theme="dark"] {
    --accent-primary: #ffd700;   /* Jaune */
    --accent-secondary: #ffed4a;  /* Jaune clair */
}
```

# améliorations-futures

Améliorations futures
### Priorité haute
- Modification du profil (nom, prénom, email)

- Changement de mot de passe (avec ancien mot de passe)

- Réinitialisation de mot de passe par email

### Priorité moyenne
- Création d'événements (titre, date, lieu, description)

- Gestion des invitations (ajout de contacts, envoi)

- Page d'administration (gestion des utilisateurs)

### Priorité basse
- API REST pour application mobile

- Export des données (CSV, PDF)

- Intégration de calendrier (Google Calendar, Outlook)