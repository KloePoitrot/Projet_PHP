<?php 
session_start();
require_once "connect.php";
$id = isset($_GET['id']) ? $_GET['id'] : null;

$message = null;
$isFormOk = true;
if(isset($_POST['submit'])){
    // Test d'envoi du pseudo
    if(empty($_POST['pseudo']) || strlen($_POST['pseudo']) < 5){
        $message .= "<p>Le pseudo est incorrecte (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test d'envoi du nom
    if(empty($_POST['nom']) || strlen($_POST['nom']) < 2){
        $message .= "<p>Le nom est invalide (2 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test d'envoi du prénom
    if(empty($_POST['prenom']) || strlen($_POST['prenom']) < 2){
        $message .= "<p>Le prénom est invalide (2 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test d'envoi du mail
    if(empty($_POST['mail']) || !filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){
        $message .= "<p>Le mail est invalide</p>";
        $isFormOk = false;
    }

    // Si tout est ok
    if($isFormOk){
        // Le mail et pseudo sont libre
        $request = "UPDATE users SET nom_user = :nom,  prenom_user = :prenom, mail_user = :mail, pseudo_user = :pseudo, niveau_compte = :niveau WHERE id_user = :id";
        $data = $db->prepare($request);

        // Executer la requete avec les données
        $data->execute(array(
            "id" => $_POST['id'],
            "nom" => $_POST['nom'],
            "prenom" => $_POST['prenom'],
            "mail" => $_POST['mail'],
            "pseudo" => $_POST['pseudo'],
            "niveau" => $_POST['niveaucompte'],
        ));

        $message = "<p>Utilisateur modifié!</p>";
    
    }
}
        // Recupère les données de l'utilisateur selectionné
        $reqDisplay = "SELECT id_user, nom_user, prenom_user, mail_user, pseudo_user, pass_user, niveau_compte FROM users WHERE id_user = :id";
        $reqDisplay = $db->prepare($reqDisplay);
        $reqDisplay->execute(array(
            "id" => $id,
        ));
        $dataDisplay = $reqDisplay->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste pages</title>
    <style>
        img{
            width:50px
        }
    </style>
</head>
<body>
    <?php include_once "../modules/headeradmin.php"; ?>
    <?php 
            // Verifie si on est connecté
            if(!empty($_SESSION['niveau'])){
                // Verifie si le compte est de niveau admin ou moderateur
                if($_SESSION['niveau'] == "admin"){    
                    if(isset($_GET['id'])){
        ?>
        
            <h1>Modification de l'utilisateur</h1>
            <?= $message?>
            <form action="" method="post">
                <label for="pseudo">Pseudo:</label>
                <input type="text" name="pseudo" value="<?= $dataDisplay['pseudo_user']?>">
                <label for="nom">Nom:</label>
                <input type="text" name="nom" value="<?= $dataDisplay['nom_user']?>">
                <label for="prenom">Prénom:</label>
                <input type="text" name="prenom" value="<?= $dataDisplay['prenom_user']?>">
                <label for="mail">Email:</label>
                <input type="text" name="mail" value="<?= $dataDisplay['mail_user']?>">
                <label for="niveaucompte">Niveau Compte:</label>
                <input name="niveaucompte" value="<?= $dataDisplay['niveau_compte']?>">
                <input type="hidden" name="id" value="<?= $_GET['id']?>">
                <input type="submit" name="submit" value="Modifier">
            </form>

            <a href="listeutilisateurs.php?delete=y&id=<?= $dataDisplay["id_user"]?>">Supprimer l'utilisateur</a>

        <?php
                    }
                }
                // Sinon refuser l'acces 
                if(!isset($_GET['id'])){ 
        ?>
            <p>Aucun utilisateur sélectionné.</p>
        <?php 
                }
                // Sinon refuser l'acces 
                if($_SESSION['niveau'] != "admin"){ 
        ?>
            <p>Access denied</p>
        <?php 
                }
            }

            // Refuser l'acces si personne n'est connecté
            if(empty($_SESSION['niveau'])){
        ?>
            <p>Access denied</p>
        <?php 
            }
        ?>
</body>
</html>