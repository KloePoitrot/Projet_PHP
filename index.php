<?php
session_start();

// Connexion et Deconnexion
$success = null;
if(isset($_GET['login']) == 'true'){
    $success = '<p>Vous êtes connecté!</p>';
}
if(isset($_GET['logout']) == 'true'){
    $success = '<p>Vous vous êtes déconnecté!</p>';
    unset($_SESSION['token']);
    unset($_SESSION['id']);
    unset($_SESSION['niveau']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <?php include_once "modules/header.php" ?>
    <main>
        <?= $success?>
        <h1>Bienvenu!</h1>
    </main>
</body>
</html>