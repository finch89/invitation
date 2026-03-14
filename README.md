# Projet invitation

Les évènements sont prédéfinis (comment ?)
L'utilisateur doit s'inscrire au site (créer un compte avec confirmation de compte par email) et peut ensuite voir les évènements et rejoindre un évènement (avec un formulaire). 
On reçoit une confirmation qu'on s'est inscrit à l'évènement.

L'administrateur reçoit une alert d'une personnne qui vient de s'inscrire (demande de participation). L'administrateur peut confirmer ou refuser l'inscription. On envoit un mail dans les 2 cas. 

## Utilisateur

2 rôles

- Administrateur
    - Gère les évènements (Ajouter, Modification, Suppression)
    - Gère les personnes (Accepter ou Refuser)
- Utilisateurs
    - Voir les évènements
    - S'inscrire à un évènements

## Structure la base

Tu t'es déconnecté de l'outil de tchat en fermant l'onglet du navigateur je pense ^^
vu

```php
$pdo = new PDO('mysql:host=localhost;dbname=bg_invitation;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
```

- users, retient les utilisateurs inscrit
    - id
    - firstname
    - lastname
    - email
    - confirmed_at (null | date)
    - password
    - role ('admin' | 'user')
    - created_at
- events, l'ensemble des évènements
    - id
    - name
    - start_at (date)
    - end_at (date)
    - address
    - city
    - postal_code
    - country
    - lat
    - lng
    - image 
    - description
- event_user, table de liaison entre utilisateurs et les évènements
    - event_id
    - user_id
    - state, entier (0: en attente, -1: refusé, 1: accepté)
    - created_at

## Tâches

- [ ] Inscription de l'utilisateur
    - [ ] Création du formulaire HTML
    - [ ] Création de la table (base de données)
    - [ ] Traitement du formulaire (PHP)
    - [ ] Envoie de l'email de confirmation
    - [ ] Action de confirmaion
- [ ] Connexion
- [ ] Rappel du mot de passe


## A retenir

### Date

Dans la base de données les datetimes sont sauvegardé sous ce format là 2005-08-15T15:52:01

```php
$date = new DateTime();
$date->format('Y-m-d'); // 2024-03-01 https://www.php.net/manual/fr/datetime.format.php
(new DateTime())->format(DateTime::ATOM); // 2005-08-15T15:52:01+0000 // Y-m-d\\TH:i:sP
```

### Base de données

#### Se connecter à la base données

```php
$pdo = new PDO('mysql:host=localhost;dbname=bg_invitation;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
```

#### Requêtes préparées

Quand on fait une requête on évite

```php
$sql = "INSERT INTO table (firstname, lastname) VALUES ($_POST[...])
```

Car cela est dangereux d'un point de vu sécurité on utilise les requetes préparées.

```php
$stmt = $pdo->prepare("INSERT INTO table (firstname, lastname) VALUES (:firstname, :lastname)")
$stmt->execute([
    ':firstname' => 'John',
    ':lastname' => 'Doe',
]);

// avec des données postées
$stmt->execute([
    ':firstname' => $_POST['firstname'],
    ':lastname' => $_POST['lastname'],
]);
// Equivalent
$stmt->bindParam(':firstname', $_POST['firstname']);
$stmt->bindParam(':lastname', $_POST['lastname']);
$stmt->execute();
```

## A faire 

- [ ] Créer la table events pour gérer les évènements
- [ ] Créer un formulaire pour enregistrer un évènement
    - La balise **textarea** permet de rentrer du texte multiligne
    - Pour la date de début / fin de l'évènement - https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/input/datetime-local (inspecter dans le réseau pour voir comment les donnés sont envoyées au serveur)
- [ ] Mettre en place le traitement des données et la création de l'évènement dans la base de données
