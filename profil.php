<?php 
session_start();
require_once "admin/connect.php";
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

$message = null;
// Recupère les données de l'utilisateur selectionné
$reqDisplay = "SELECT id_user, nom_user, avatar_user, prenom_user, mail_user, pseudo_user, pass_user, niveau_compte FROM users WHERE id_user = :id";
$reqDisplay = $db->prepare($reqDisplay);
$reqDisplay->execute(array(
    "id" => $id,
));
$dataDisplay = $reqDisplay->fetch();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        img{
            width:200px;
        }
        .bold{
            font-weight:900;
        }
    </style>
    <title>Profile</title>
</head>
<body>
    <?php include_once "modules/header.php" ?>
    <main>
        <?php 
            if(isset($_SESSION['id']) && $dataDisplay){
        ?>
        <h1>Profil utilisateur</h1>
        <div>
            <img src="<?= $dataDisplay['avatar_user'] ?>" alt="Avatar de <?= $dataDisplay['pseudo_user'] ?>">
            <ul>
                <li><span class="bold">Pseudo</span> • <?= $dataDisplay['pseudo_user'] ?></li>
                <li><span class="bold">Nom</span> • <?= $dataDisplay['nom_user'] ?></li>
                <li><span class="bold">Prénom</span> • <?= $dataDisplay['prenom_user'] ?></li>
                <li><span class="bold">Email</span> • <?= $dataDisplay['mail_user'] ?></li>
            </ul>
        </div>

        <a href="editprofil.php">Modifier le profil</a>
        <?php
            }
        ?>

        <?php 
            if(!isset($_SESSION['id']) && !$dataDisplay){
        ?>
        <p>Access denied</p>
        <?php
            }
        ?>
    </main>
</body>
</html>