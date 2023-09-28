<?php 
session_start();
// Initialisation des variables
$imgUpload = "images/avatar/avatarpardefaut.jpg";
$isFormOk = true;
$message = null;

// Test d'envoi du formulaire
if(isset($_POST['submit'])){
    // Test d'envoi du pseudo
    if(empty($_POST['username']) || strlen($_POST['username']) < 5){
        $message .= "<p class='warning'>Le pseudo est incorrecte (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test d'envoi du nom
    if(empty($_POST['nom']) || strlen($_POST['nom']) < 2){
        $message .= "<p class='warning'>Le nom est invalide (2 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test d'envoi du prénom
    if(empty($_POST['prenom']) || strlen($_POST['prenom']) < 2){
        $message .= "<p class='warning'>Le prénom est invalide (2 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test d'envoi du mail
    if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $message .= "<p class='warning'>Le mail est invalide</p>";
        $isFormOk = false;
    }

    // Test d'envoi du mot de passe
    if(empty($_POST['passw']) || strlen($_POST['passw']) < 5){
        $message .= "<p class='warning'>Le mot de passe est invalide (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test de l'image
    if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK){
        if($isFormOk){
            // Verification du mime
            $info = getimagesize($_FILES['image']['tmp_name']);
            // Il y a bien un fichier, verifie l'extention de l'image
            if($info && ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg' || $info['mime'] == 'image/png')){
                // Genere un nom de fichier
                $nom_fichier = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                move_uploaded_file($_FILES['image']['tmp_name'], '../images/pages/'.$nom_fichier);
                $imgUpload = 'images/pages/'.$nom_fichier;
            } else {
                // sinon cest un echec
                $message = "<p class='warning'>L'image uploadée est invalide.</p>";
            }
        }
    }

    // Si tout est ok
    if($isFormOk){
        // Test si le mail ou le pseudo existe
        $mail = $_POST['email'];
        $pseudo = $_POST['username'];

        require_once "admin/connect.php";
        $request = "SELECT mail_user, pseudo_user FROM users WHERE mail_user = :mail or pseudo_user = :pseudo";
        $request = $db->prepare($request);
        $request->execute(array(
            "mail" => $mail,
            "pseudo" => $pseudo
        ));
        $data = $request->fetch();

        if($data){
            // Le mail ou pseudo sont deja utilisé
            $message = "<p class='warning'>Une erreur est survenue, veuillez vérifier vos informations.</p>";
        }

        if(!$data){
            // Le mail et pseudo sont libre
            $pass = password_hash($_POST['passw'], PASSWORD_DEFAULT);
            $request = "INSERT INTO users(nom_user, prenom_user, mail_user, pseudo_user, pass_user, avatar_user, niveau_compte) VALUES(:nom, :prenom, :email, :pseudo, :passw, :avatar, :nivcompte)";
            $data = $db->prepare($request);
            $data->execute(array(
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'email' => $_POST['email'],
                'pseudo' => $_POST['username'],
                'passw' => $pass,
                'avatar' => $imgUpload,
                'nivcompte' => "membre",
            ));


            $message = "<p class='success'>Formulaire envoyé</p>";
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
    <title>Inscription</title>
</head>
<body>
    <?php include_once "modules/header.php" ?>
    <main>
        <h1 class="header">Inscription</h1>
        <?= $message?>
        <?php if(!isset($_SESSION['token'])){?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="text" placeholder="Pseudo" name="username">
                <input type="text" placeholder="Nom" name="nom">
                <input type="text" placeholder="Prénom" name="prenom">
                <input type="mail" placeholder="Email" name="email">
                <input type="password" placeholder="Mot de passe" name="passw">
                <input type="file" name="image" id="image">
                <input type="submit" name="submit" value="S'inscrire">
            </form>
        <?php }?>

        <?php if(isset($_SESSION['token'])){?>
            <p class='warning'>Vous êtes déjà connecté.</p>
            <a href="index.php?logout=true">Deconnexion</a>
        <?php }?>
    </main>
</body>
</html>