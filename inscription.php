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


    var_dump($_POST);
    
   if(isset($_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['password'])){

$pdo = new PDO(
    'mysql:host=localhost;dbname=bd_invitation;charset=utf8mb4',
    'root',
    '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
"INSERT INTO users (lastname, firstname, email, password, role, created_at)
VALUES (:lastname, :firstname, :email, :password, 'user', NOW())"
);

$stmt->execute([
    ':lastname' => $_POST['lastname'],
    ':firstname' => $_POST['firstname'],
    ':email' => $_POST['email'],
    ':password' => $password_hash
]);

echo "Utilisateur créé";
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