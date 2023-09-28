<?php 
session_start();
$message = null;
$isFormOk = true;
if(isset($_POST['submit'])){
    // test si le pseudo ou mail est correct
    if(empty($_POST['info']) || strlen($_POST['info']) < 5){
        $message = "<p class='warning'>Pseudo ou email invalide (5 caractères minimum)</p>";
        $isFormOk .= false;
    }

    // Teste si le mot de pass est correct
    if(empty($_POST['passw']) || strlen($_POST['passw']) < 5){
        $message .= "<p class='warning'>Mot de passe invalide (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    if($isFormOk){
        // Test si le mail ou le pseudo existe
        $mailoupseudo = $_POST['info'];

        require_once "admin/connect.php";
        $request = "SELECT id_user, mail_user, pseudo_user, niveau_compte FROM users WHERE mail_user = :mail or pseudo_user = :pseudo";
        $request = $db->prepare($request);
        $request->execute(array(
            "mail" => $mailoupseudo,
            "pseudo" => $mailoupseudo
        ));
        $data = $request->fetch();

        if(!$data){
            // Le mail ou pseudo ne correspondent pas
            $message = "<p class='warning'>Une erreur est survenue, veuillez vérifier vos informations.</p>";
        }

        if($data){
            $token = password_hash('token', PASSWORD_DEFAULT);
            $_SESSION['token'] = $token;
            $_SESSION['id'] = $data['id_user'];
            $_SESSION['niveau'] = $data['niveau_compte'];
            
            header ('location: index.php?login=true');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <title>Connexion</title>
</head>
<body>
    <?php include_once "modules/header.php" ?>
    <main>
        <?= $message?>
        <h1 class="header">Connexion</h1>
        <?php if(empty($_SESSION['token'])){?>
        <form action="" method="post">
            <input type="text" name="info" placeholder="Pseudo ou Email">
            <input type="password" name="passw" placeholder="Mot de passe">
            <input type="submit" name="submit" value="Se connecter">
        </form>
        <?php }?>

        <?php if(isset($_SESSION['token'])){?>
            <p>Vous êtes déjà connecté.</p>
            <a href="index.php?logout=true">Deconnexion</a>
        <?php }?>
    </main>
</body>
</html>