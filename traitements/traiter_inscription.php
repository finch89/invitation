<?php
// Démarrer la session
session_start();

// Inclure les fichiers nécessaires
require_once '../config/database.php';
require_once '../classes/Users.php';

// Vérifier que le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/inscription.php');
    exit;
}

// Fonction de redirection avec erreur
function redirectWithError($error, $data = []) {
    $params = array_merge(['error' => $error], $data);
    header('Location: ../public/inscription.php?' . http_build_query($params));
    exit;
}

// Récupération et nettoyage basique des données
$lastname = trim($_POST['lastname'] ?? '');
$firstname = trim($_POST['firstname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Données à conserver en cas d'erreur
$formData = [
    'lastname' => $lastname,
    'firstname' => $firstname,
    'email' => $email
];

// Validations basiques
if (empty($lastname)) redirectWithError("Le nom est requis", $formData);
if (empty($firstname)) redirectWithError("Le prénom est requis", $formData);
if (empty($email)) redirectWithError("L'email est requis", $formData);
if (empty($password)) redirectWithError("Le mot de passe est requis", $formData);
if ($password !== $confirm_password) redirectWithError("Les mots de passe ne correspondent pas", $formData);

try {
    // Connexion à la base de données
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Vérifier si l'email existe déjà
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        redirectWithError("Cet email est déjà utilisé", $formData);
    }
    
    // Création et sauvegarde de l'utilisateur
    $user = new Users($pdo);

   

    $user->setLastname($lastname)
         ->setFirstname($firstname)
         ->setEmail($email)
         ->setPassword($password)
         ->setRole('user');
    
    if ($user->sauvegarder()) {
        // Succès ! Rediriger vers la page de connexion
        header('Location: ../public/connexion.php?success=1');
        exit;
    } else {
        redirectWithError("Erreur lors de l'inscription", $formData);
    }
    
} catch (InvalidArgumentException $e) {
    // Erreur de validation de la classe
    redirectWithError($e->getMessage(), $formData);
    
} catch (PDOException $e) {
    // Erreur de base de données
    error_log("Erreur BD inscription: " . $e->getMessage());
    redirectWithError("Erreur technique, veuillez réessayer plus tard", $formData);
    
} catch (Exception $e) {
    // Autres erreurs
    error_log("Erreur inscription: " . $e->getMessage());
    redirectWithError("Une erreur est survenue", $formData);
}
?>