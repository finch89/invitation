# 🎉 InvitationApp - Application de Gestion d'Inscriptions

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![PHP](https://img.shields.io/badge/PHP-8.2.29+-purple)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange)
![License](https://img.shields.io/badge/license-MIT-green)

## 📋 Table des matières
- [Aperçu du projet](#aperçu-du-projet)
- [Fonctionnalités](#fonctionnalités)
- [Fonctionnalités actuelles](#fonctionnalités-actuelles)
- [Fonctionnalités à venir](#fonctionnalités-à-venir)
- [Captures d'écran](#captures-décran)
- [Prérequis techniques](#prérequis-techniques)
- [Installation](#installation)
- [Structure du projet](#structure-du-projet)
- [Base de données](#base-de-données)
- [Utilisation détaillée](#utilisation-détaillée)
- [Système de thèmes](#système-de-thèmes)
- [Architecture CSS](#architecture-css)
- [Sécurité](#sécurité)
- [Guide de personnalisation](#guide-de-personnalisation)
- [API et Fonctions](#api-et-fonctions)
- [Dépannage](#dépannage)
- [Contributeurs](#contributeurs)
- [Ce qui a été ajoute](#AJOUTÉ)
- [Licence](#licence)

# aperçu-du-projet

**InvitationApp** est une application web complète développée en PHP orienté objet qui permet aux utilisateurs de :
- Créer un compte et gérer leur profil
- Créer et gérer des événements (mariages, anniversaires, conférences, etc.)
- Envoyer des invitations par email
- Suivre les réponses des invités en temps réel
- Bénéficier d'une interface moderne avec thème clair/sombre

L'application suit une architecture MVC simplifiée avec séparation claire des responsabilités et intègre les meilleures pratiques de sécurité.

---
### ✅ **Fonctionnalités actuelles** (Version 1.0)

#### **Authentification & Utilisateurs**
- ✅ Inscription avec validation des données
- ✅ Connexion sécurisée avec hashage des mots de passe (`password_hash()`)
- ✅ Gestion des sessions utilisateur
- ✅ Profils avec rôles (user / admin)
- ✅ Déconnexion avec destruction de session
- ✅ Protection contre les injections SQL (requêtes préparées)
- ✅ Validation des emails avec `filter_var()`

#### **Gestion des événements**
- ✅ **Création d'événements** (titre, description, date, lieu)
- ✅ **Modification des événements** (édition complète)
- ✅ **Suppression des événements** avec confirmation
- ✅ **Liste des événements** personnels avec grille responsive
- ✅ **Statuts des événements** (brouillon, publié, annulé, terminé)
- ✅ **Filtrage et tri** des événements par date/statut
- ✅ **Vue détaillée** d'un événement avec toutes ses informations
- ✅ **Statistiques par événement** (nombre d'invités, taux de réponse)

#### **Gestion des invitations**
- ✅ **Ajout d'invités** par email (simple ou multiple)
- ✅ **Envoi d'invitations** avec emails personnalisés
- ✅ **Suivi des réponses** (accepté, refusé, peut-être, en attente)
- ✅ **Statistiques en temps réel** avec graphiques
- ✅ **Tableau des invités** avec statuts et dates de réponse
- ✅ **Suppression d'invités** individuelle
- ✅ **Badges de statut** avec codes couleur
- ✅ **Contrainte d'unicité** (email unique par événement)

#### **Dashboard & Statistiques**
- ✅ **Tableau de bord personnalisé** selon le rôle
- ✅ **Vue d'ensemble des activités** récentes
- ✅ **Statistiques globales** pour les admins
- ✅ **Aperçu des derniers événements**
- ✅ **Actions rapides** (créer événement, voir la liste)
- ✅ **Mini statistiques** sur les cartes d'événements
- ✅ **Progression des réponses** (pourcentage accepté/refusé)

#### **Administration**
- ✅ **Dashboard admin** avec métriques globales
- ✅ **Gestion des utilisateurs** (liste, modification, suppression)
- ✅ **Vue globale des événements** de tous les utilisateurs
- ✅ **Vérification admin** avec `admin_check.php`
- ✅ **Statistiques système** (total users, events, invitations)
- ✅ **Actions rapides admin** (gérer utilisateurs, événements)

#### **Interface utilisateur**
- ✅ **Design responsive** (mobile, tablette, desktop)
- ✅ **Système de thèmes** clair/sombre avec persistance (localStorage)
- ✅ **Animations et transitions** fluides (0.2s)
- ✅ **Messages de succès/erreur** avec alertes stylisées
- ✅ **Validation des formulaires** (côté client et serveur)
- ✅ **Navigation sticky** avec effet de flou
- ✅ **Bouton de thème** avec animation de rotation
- ✅ **Indicateur de page active** dans la navigation

#### **Architecture CSS modulaire**
- ✅ **Variables CSS** pour les thèmes (clair/sombre)
- ✅ **Composants séparés** (navigation, boutons, formulaires, etc.)
- ✅ **Pages spécifiques** (accueil, dashboard, événements, admin)
- ✅ **Media queries** pour le responsive
- ✅ **Classes utilitaires** (marges, paddings, flexbox)

### **Fonctionnalités à venir** (Version 3.0)

#### **Priorité haute**
- 🔜 **Envoi d'emails automatiques** via SMTP
- 🔜 **Rappels automatiques** pour les invités n'ayant pas répondu
- 🔜 **Export des listes d'invités** (CSV, PDF)
- 🔜 **Calendrier interactif** avec vue mensuelle

#### **Priorité moyenne**
- 🔜 **API REST** pour application mobile
- 🔜 **Notifications en temps réel** avec WebSockets
- 🔜 **Paiement en ligne** pour événements payants
- 🔜 **Gestion des photos** pour les événements

#### **Priorité basse**
- 🔜 **Intégration Google Calendar**
- 🔜 **Chat en direct** pour les invités
- 🔜 **Sondages et questionnaires** pour les événements

---

## Captures d'écran


### Page d'accueil - Thème clair
[À insérer]

text

### Dashboard utilisateur
[À insérer]

text

### Création d'événement
[À insérer]

text

### Liste des événements
[À insérer]

text

### Détail d'un événement avec invitations
[À insérer]

text

### Administration
[À insérer]

text

### Thème sombre

### Page d'accueil - Thème clair

# installation


# base-de-données

Configurer la base de données
1. Créez une base de données MySQL nommée bd_invitation :
```sql
CREATE DATABASE bd_invitation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
2. Importer la structure de la table

```sql
USE bd_invitation;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lastname VARCHAR(100) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des événements
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date_event DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('draft', 'published', 'cancelled', 'completed') DEFAULT 'draft',
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_created_by (created_by),
    INDEX idx_date_event (date_event),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des invitations (VERSION COMPLÈTE)
CREATE TABLE IF NOT EXISTS invitations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    guest_email VARCHAR(255) NOT NULL,
    guest_name VARCHAR(255),
    status ENUM('pending', 'accepted', 'declined', 'maybe') DEFAULT 'pending',
    response_date DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE KEY unique_invitation (event_id, guest_email),
    INDEX idx_event_id (event_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Créer un admin par défaut (mot de passe: admin123)
INSERT INTO users (lastname, firstname, email, password, role) VALUES 
('Admin', 'System', 'admin@invitationapp.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
```
4. Configurer la connexion à la base de données
Modifiez le fichier config/database.php avec vos informations :
```php
private $host = 'localhost';
private $dbname = 'bd_invitation';
private $username = 'root';     // Votre nom d'utilisateur MySQL
private $password = '';         // Votre mot de passe MySQL
```
5. Lancer l'application
Avec Laragon/WAMP/XAMPP : placez le dossier dans www/ ou htdocs/

Accès : http://localhost/invitation-app/

6. Identifiants par défaut

Admin : admin@invitationapp.com / admin123
User  : (à créer lors de l'inscription)

# structure-du-projet
invitation-app/
│
├── index.php                          # Page d'accueil
│
├── config/
│   └── database.php                   # Configuration BDD
│
├── classes/
│   ├── Users.php                       # Classe Utilisateur (CRUD complet)
│   └── Events.php                       # Classe Événement (avec invitations)
│
├── public/                             # Pages accessibles
│   ├── inscription.php                  # Inscription
│   ├── connexion.php                     # Connexion
│   ├── dashboard.php                     # Tableau de bord
│   ├── deconnexion.php                   # Déconnexion
│   │
│   ├── events/                           # Gestion des événements
│   │   ├── create.php                     # Création d'événement
│   │   ├── list.php                        # Liste des événements
│   │   ├── view.php                        # Détail d'un événement
│   │   └── edit.php                        # Modification d'événement
│   │
│   ├── invitations/                       # Gestion des invitations
│   │   └── send.php                        # Envoi d'invitations
│   │
│   └── admin/                            # Administration
│       ├── index.php                       # Dashboard admin
│       ├── users.php                       # Gestion des utilisateurs
│       └── events.php                       # Gestion des événements (admin)
│
├── traitements/                         # Traitements POST
│   ├── traiter_inscription.php
│   ├── traiter_connexion.php
│   │
│   └── events/                           # Traitements des événements
│       ├── create_event.php                # Création d'événement
│       ├── update_event.php                 # Mise à jour d'événement
│       ├── delete_event.php                 # Suppression d'événement
│       └── send_invitations.php              # Envoi d'invitations
│
├── includes/                            # Fichiers inclus
│   ├── header.php                        # En-tête + navigation (avec classes CSS)
│   ├── footer.php                        # Pied de page
│   └── admin_check.php                    # Vérification admin
│
└── assets/                               # Ressources statiques
    ├── css/
    │   ├── main.css                        # Point d'entrée CSS
    │   ├── 00-variables.css                 # Thèmes et couleurs
    │   ├── 01-base.css                       # Reset et base
    │   ├── 02-components/                    # Composants
    │   │   ├── navigation.css
    │   │   ├── buttons.css
    │   │   ├── forms.css
    │   │   ├── alerts.css
    │   │   ├── cards.css
    │   │   ├── badges.css
    │   │   ├── stats.css
    │   │   └── action-lists.css
    │   ├── 03-pages/                         # Pages spécifiques
    │   │   ├── home.css
    │   │   ├── dashboard.css
    │   │   ├── auth.css
    │   │   ├── events.css
    │   │   └── admin.css
    │   ├── 04-layout/                        # Layout
    │   │   ├── footer.css
    │   │   └── responsive.css
    │   └── 05-utilities/                     # Utilitaires
    │       └── utilities.css
    │
    └── js/
        ├── theme.js                          # Gestion du thème
        └── events.js                          # Scripts événements

Base de données (Structure détaillée)

Table users
Champ	Type	Description
```sql
id	INT AUTO_INCREMENT	Identifiant unique
lastname	VARCHAR(100)	Nom de famille
firstname	VARCHAR(100)	Prénom
email	VARCHAR(255) UNIQUE	Email (unique)
password	VARCHAR(255)	Mot de passe hashé
role	ENUM('user', 'admin')	Rôle utilisateur
created_at	TIMESTAMP	Date d'inscription
```
Table events
Champ	Type	Description
```sql
id	INT AUTO_INCREMENT	Identifiant unique
title	VARCHAR(255)	Titre de l'événement
description	TEXT	Description détaillée
date_event	DATETIME	Date et heure
location	VARCHAR(255)	Lieu
created_by	INT	ID du créateur (FK)
created_at	TIMESTAMP	Date de création
status	ENUM	draft/published/cancelled/completed
```
Table invitations (MISE À JOUR)
Champ	Type	Description
```sql
id	INT AUTO_INCREMENT	Identifiant unique
event_id	INT NOT NULL	ID de l'événement (FK)
guest_email	VARCHAR(255) NOT NULL	Email de l'invité
guest_name	VARCHAR(255)	Nom de l'invité
status	ENUM	pending/accepted/declined/maybe
response_date	DATETIME	Date de réponse
created_at	TIMESTAMP	Date d'envoi
```
# utilisation
Utilisation
1. Page d'accueil (/index.php)
Présentation de l'application

Statistiques en direct

Appels à l'action (inscription/connexion)

Bouton de changement de thème

2. Inscription (/public/inscription.php)
Formulaire avec validation en temps réel

Vérification email unique

Hashage automatique du mot de passe

Redirection vers connexion

3. Connexion (/public/connexion.php)
Authentification par email/mot de passe

Création de session

Redirection vers tableau de bord

4. Tableau de bord (/public/dashboard.php)
Vue d'ensemble des activités

Derniers événements (3 derniers)

Actions rapides (créer événement, voir la liste)

Statistiques personnelles

Aperçu admin (si rôle admin)

5. Gestion des événements
Création (/public/events/create.php)
```php
$event = new Events($pdo);
$event->setTitle($_POST['title'])
      ->setDescription($_POST['description'])
      ->setDateEvent($_POST['date_event'])
      ->setLocation($_POST['location'])
      ->setCreatedBy($_SESSION['user_id'])
      ->setStatus($_POST['status'])
      ->sauvegarder();
```
Liste (/public/events/list.php)
Grille des événements avec statuts

Mini statistiques par événement

Actions (voir, modifier, supprimer)

Filtres par date/statut

Détail (/public/events/view.php)
Informations complètes de l'événement

Statistiques détaillées (total, acceptés, refusés)

Liste des invités avec statuts

Actions d'invitation

Modification (/public/events/edit.php)
Formulaire pré-rempli

Mise à jour des informations

Zone de danger pour la suppression

6. Gestion des invitations (/public/invitations/send.php)
Ajout simple
```php
// Ajout d'un seul invité
$event->ajouterInvitation($email, $name);
```
Ajout multiple
Format accepté :
- email@exemple.com
- Nom:email@exemple.com
- Jean Dupont:jean@email.com
Statuts disponibles
🟡 En attente (pending)

🟢 Accepté (accepted)

🔴 Refusé (declined)

🔵 Peut-être (maybe)

Statistiques en temps réel
```php
$stats = $event->getStats();
// [
//     'total' => 10,
//     'pending' => 4,
//     'accepted' => 3,
//     'declined' => 2,
//     'maybe' => 1
// ]
```
7. Administration (/public/admin/)
Dashboard admin (index.php)
Statistiques globales

Derniers utilisateurs inscrits

Derniers événements créés

Actions rapides

Gestion des utilisateurs (users.php)
Liste complète des utilisateurs

Filtres et recherche

Modification des rôles

Suppression d'utilisateurs

Gestion des événements (events.php)
Tous les événements de la plateforme

Vue par créateur

Statistiques globales

8. Déconnexion (/public/deconnexion.php)
Destruction de session

Redirection vers accueil

## Système de thèmes
Thème clair
```css
:root {
    --bg-primary: #ffffff;
    --bg-secondary: #f5f7fa;
    --text-primary: #1a1e24;
    --text-secondary: #4a5568;
    --accent-primary: #2563eb;
    --border-color: #e2e8f0;
}
```
Thème sombre
```css
[data-theme="dark"] {
    --bg-primary: #0a0c10;
    --bg-secondary: #111318;
    --text-primary: #f0f2f5;
    --text-secondary: #a0a8b8;
    --accent-primary: #60a5fa;
    --border-color: #2d2f36;
}
```
Changement de thème
```js
// Sauvegarde dans localStorage
localStorage.setItem('theme', 'dark');
// Application
document.documentElement.setAttribute('data-theme', 'dark');
```
Architecture CSS
assets/css/
├── 00-variables.css      # Thèmes et couleurs (centralisé)
├── 01-base.css           # Reset, base, typographie
├── 02-components/        # Composants réutilisables
├── 03-pages/             # Styles spécifiques aux pages
├── 04-layout/            # Mise en page globale
└── 05-utilities/         # Classes utilitaires
Navigation (avec classes)
```html
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-logo">InvitationApp</div>
        <ul class="navbar-menu">
            <li><a href="/public/dashboard.php">Tableau de bord</a></li>
            <li><button class="theme-switch">🌙</button></li>
        </ul>
    </div>
</nav>
```
Sécurité
Mesures implémentées
✅ Hashage des mots de passe : password_hash() / password_verify()

✅ Protection injections SQL : requêtes préparées PDO

✅ Protection XSS : htmlspecialchars() sur toutes les sorties

✅ Validation des données : filtres PHP

✅ Sessions sécurisées : régénération d'ID

✅ Email validation : filter_var($email, FILTER_VALIDATE_EMAIL)

✅ Contrainte d'unicité : email unique par événement

✅ Vérification des rôles : admin_check.php

Bonnes pratiques
Mots de passe minimum 8 caractères

Destruction des sessions à la déconnexion

Redirection après POST (évite double soumission)

Logs d'erreurs (pas d'affichage public)

Validation côté client ET serveur

📚 API et Fonctions
Classe Users
```php
$user = new Users($pdo);
$user->setLastname('Dupont')
     ->setFirstname('Jean')
     ->setEmail('jean@email.com')
     ->setPassword('password123')
     ->setRole('user')
     ->sauvegarder();

// Méthodes statiques
$user = Users::trouverParId($pdo, 1);
$user = Users::trouverParEmail($pdo, 'email@exemple.com');
$user = Users::login($pdo, 'email@exemple.com', 'password');
```
Classe Events (MISE À JOUR)
```php
// Création d'événement
$event = new Events($pdo);
$event->setTitle('Mariage')
      ->setDescription('Notre mariage')
      ->setDateEvent('2025-06-15 15:00')
      ->setLocation('Paris')
      ->setCreatedBy($userId)
      ->sauvegarder();

// Gestion des invitations
$event->ajouterInvitation('invite@email.com', 'Nom Invité');
$invitations = $event->getInvitations();

// Statistiques
$stats = $event->getStats(); 
// ['total', 'accepted', 'declined', 'pending', 'maybe']
```
❗ Dépannage
Problèmes courants et solutions
Problème	Solution
Erreur de connexion BDD	Vérifier identifiants dans config/database.php
Page blanche	Activer display_errors dans PHP
Erreur "Class not found"	Vérifier require_once et chemins
Le thème ne change pas	Vérifier inclusion de theme.js
Erreur 404 sur les assets	Vérifier chemins (avec/sans /)
Email déjà utilisé	L'email est unique en BDD
Date dans le passé	Validation dans Events::setDateEvent()
Colonne manquante	Exécuter les scripts SQL de mise à jour
Erreur 500	Vérifier les logs dans error_log

Activer le mode débogage
```php
// En haut de votre fichier PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```
# contributeurs

Contributeurs
[Votre Nom] - Développeur principal - @votre-github

Comment contribuer ?
Fork le projet

Créez votre branche (git checkout -b feature/AmazingFeature)

Committez vos changements (git commit -m 'Add AmazingFeature')

Push (git push origin feature/AmazingFeature)

Ouvrez une Pull Request

# AJOUTÉ

 Ce qui a été AJOUTÉ dans cette version
✅ Nouvelles fonctionnalités
Système complet d'invitations

Ajout d'invités par email

Gestion des statuts (pending/accepted/declined/maybe)

Statistiques en temps réel

Gestion des événements améliorée

CRUD complet (Create, Read, Update, Delete)

Vue détaillée avec liste des invités

Modification et suppression d'événements

Dashboard utilisateur enrichi

Affichage des événements récents

Actions rapides (créer événement, voir liste)

Statistiques personnalisées

Administration complète

Gestion des utilisateurs

Vue globale des événements

Dashboard admin avec métriques

Architecture CSS modulaire

Fichiers CSS séparés par composant

Navigation avec classes dédiées

Thèmes clair/sombre optimisés

Corrections et optimisations

Structure BDD corrigée (colonnes manquantes ajoutées)

Chemins d'accès corrigés dans tous les fichiers

Gestion d'erreurs améliorée

📁 Nouveaux fichiers ajoutés
classes/Events.php (classe événement complète)

public/events/edit.php (modification événement)

public/invitations/send.php (envoi invitations)

traitements/events/update_event.php (traitement mise à jour)

traitements/events/delete_event.php (traitement suppression)

traitements/events/send_invitations.php (traitement invitations)

includes/admin_check.php (vérification admin)

assets/css/03-pages/events.css (styles événements)

assets/css/03-pages/admin.css (styles admin)

assets/js/events.js (scripts événements)

# licence
Licence
Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.
```text
MIT License

Copyright (c) 2025 [Votre Nom]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files...
```
Support
Issues : GitHub Issues

Email : votre-email@example.com

Documentation : Wiki

🌟 Remerciements
PHP.net pour la documentation

MDN Web Docs pour les ressources CSS

Tous les contributeurs et testeurs

📊 Statistiques du projet
Fichiers : 40+ fichiers PHP/CSS/JS

Classes : 2 classes principales (Users, Events)

Tables BDD : 3 tables (users, events, invitations)

Pages : 15+ pages accessibles

Lignes de code : 5000+ lignes

⭐ N'oubliez pas de laisser une étoile si ce projet vous est utile !

