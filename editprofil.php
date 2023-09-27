<?php 
session_start();
require_once "admin/connect.php";
$id = isset($_GET['id']) ? $_GET['id'] : null;

$message = null;
$imgUpload = null;
$isFormOk = true;

// Recupère les données de l'utilisateur selectionné
$reqDisplay = "SELECT id_user, nom_user, prenom_user, avatar_user, mail_user, pseudo_user, pass_user FROM users WHERE id_user = :id";
$reqDisplay = $db->prepare($reqDisplay);
$reqDisplay->execute(array(
    "id" => $_SESSION['id'],
));
$dataDisplay = $reqDisplay->fetch();


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

    // Test de l'image
    if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK){

        // Verification du mime
        $info = getimagesize($_FILES['image']['tmp_name']);
        // Il y a bien un fichier, verifie l'extention de l'image
        if($info && ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg' || $info['mime'] == 'image/png')){
            // Genere un nom de fichier
            $nom_fichier = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['image']['tmp_name'], 'images/pages/'.$nom_fichier);
            $imgUpload = 'images/pages/'.$nom_fichier;
        } else {
            // sinon cest un echec
            $message = "<p>L'image uploadée est invalide.</p>";
        }
    } else {
        $message = '<p>le fichier doit etre au format jpeg ou png.</p>';
    }

    // Si tout est ok
    if($isFormOk){
        // Test si le mail ou le pseudo existe
        $mail = $_POST['mail'];
        $pseudo = $_POST['pseudo'];

        require_once "admin/connect.php";
        $request = "SELECT mail_user, pseudo_user FROM users WHERE id_user != :id and (mail_user = :mail or pseudo_user = :pseudo)";
        $request = $db->prepare($request);
        $request->execute(array(
            "id" => $_POST['id'],
            "mail" => $mail,
            "pseudo" => $pseudo
        ));
        $data = $request->fetch();

        if($data){
            // Le mail ou pseudo sont deja utilisé
            $message = "<p>Une erreur est survenue, veuillez vérifier vos informations.</p>";
        }

        if(!$data){
            // Le mail et pseudo sont libre
            $img = $imgUpload ? $imgUpload : $dataDisplay['avatar_user'];
            $password = !empty($_POST['pssw']) ? password_hash($_POST['pssw'], PASSWORD_DEFAULT) : $dataDisplay['pass_user'];
            $request = "UPDATE users SET nom_user = :nom,  prenom_user = :prenom, mail_user = :mail, pseudo_user = :pseudo, avatar_user = :avatar, pass_user = :pass WHERE id_user = :id";
            $data = $db->prepare($request);

            // Executer la requete avec les données
            $data->execute(array(
                "id" => $_POST['id'],
                "nom" => $_POST['nom'],
                "prenom" => $_POST['prenom'],
                "mail" => $_POST['mail'],
                "pseudo" => $_POST['pseudo'],
                "avatar" => $img,
                "pass" => $password,
            ));

            $message = "<p>Info modifié!</p>";
        }
    }
}
        
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <?php include_once "modules/header.php" ?>
    <main>
        <?php 
            if(isset($_SESSION['id']) && $dataDisplay){
        ?>
        <h1>Profil utilisateur (Modifier)</h1>
        <?= $message?>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="pseudo">Pseudo:</label>
            <input type="text" name="pseudo" value="<?= $dataDisplay['pseudo_user']?>">
            <label for="nom">Nom:</label>
            <input type="text" name="nom" value="<?= $dataDisplay['nom_user']?>">
            <label for="prenom">Prénom:</label>
            <input type="text" name="prenom" value="<?= $dataDisplay['prenom_user']?>">
            <label for="mail">Email:</label>
            <input type="text" name="mail" value="<?= $dataDisplay['mail_user']?>">
            <label for="mail">Mot de passe:</label>
            <input type="password" name="pssw">
            <label for="image">Changer l'image:</label>
            <input type="file" name="image" id="image" value>
            <input type="hidden" name="id" value="<?= $_SESSION['id']?>">
            <input type="submit" name="submit" value="Modifier">
        </form>
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