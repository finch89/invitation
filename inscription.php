<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fomulaire</title>
</head>

<body>

    <?php
    // Valider les données
    // Créer l'utilisateur dans la base de données
    // Envoyer un email
    // Rediriger vers une page de succès ou de connexion
?>

 <?php
// 1. D'abord, créez votre connexion PDO
$pdo = new PDO(
    "mysql:host=localhost;dbname=bd_invitation;charset=utf8mb4",
    "root",
    "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// 2. Créez l'objet User
$user = new Users($pdo);

// 3. Utilisez les setters avec les données POST
try {
    $user->setLastname($_POST['lastname'])
         ->setFirstname($_POST['firstname'])
         ->setEmail($_POST['email'])
         ->setPassword($_POST['password'])  // Le mot de passe en clair est automatiquement hashé
         ->setRole('user');  // Optionnel, 'user' par défaut

    // 4. Sauvegardez en base de données
    if ($user->sauvegarder()) {
        echo "Inscription réussie !";
        // Redirection vers page de connexion ou accueil
        header('Location: connexion.php?success=1');
        exit;
    } else {
        echo "Erreur lors de l'inscription";
    }

} catch (InvalidArgumentException $e) {
    // Erreur de validation (champ vide, email invalide, mot de passe trop court...)
    echo "Erreur de validation : " . $e->getMessage();
} catch (Exception $e) {
    // Autres erreurs (base de données, etc.)
    echo "Erreur : " . $e->getMessage();
}
?>

    <h1>S'inscrire</h1>
    <form action="" method="POST">
        <fieldset>
            <label for="lastname">Entrer votre nom</label>
            <input type="text" id="lastname" name="lastname" required>
        </fieldset>
        <fieldset>
            <label for="firstname">Entrer votre prenom</label>
            <input type="text" id="firstname" name="firstname" required>
        </fieldset>
        <fieldset>
            <label for="email">Entrer votre email</label>
            <input type="email" id="email" name="email" required placeholder="votre@mail.com">
        </fieldset>
        <fieldset>
            <label for="password">Entrer votre mot de passe</label>
            <input type="password" id="password" name="password">
        </fieldset>
        <button>Envoyer</button>
    </form>

    <pre>
- Prénom
- Nom
- email
- Mot de passe
(role, created_at)
    </pre>

</body>

</html>