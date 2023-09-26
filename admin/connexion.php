<?php 
session_start();
$message = null;
$isFormOk = true;
if(isset($_POST['submit'])){
    // test si le pseudo ou mail est correct
    if(empty($_POST['info']) || strlen($_POST['info']) < 5){
        $message = "<p>Pseudo ou email invalide (5 caractères minimum)</p>";
        $isFormOk .= false;
    }

    // Teste si le mot de pass est correct
    if(empty($_POST['passw']) || strlen($_POST['passw']) < 5){
        $message .= "<p>Mot de passe invalide (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    if($isFormOk){
        // Test si le mail ou le pseudo existe
        $mailoupseudo = $_POST['info'];

        require_once "connect.php";
        $request = "SELECT id_user, mail_user, pseudo_user, niveau_compte FROM users WHERE mail_user = :mail or pseudo_user = :pseudo";
        $request = $db->prepare($request);
        $request->execute(array(
            "mail" => $mailoupseudo,
            "pseudo" => $mailoupseudo
        ));
        $data = $request->fetch();

        if(!$data){
            // Le mail ou pseudo ne correspondent pas
            $message = "<p>Une erreur est survenue, veuillez vérifier vos informations.</p>";
        }

        if($data){
            if($data['niveau_compte'] === "admin" || $data['niveau_compte'] === "moderateur"){
                $token = password_hash('token', PASSWORD_DEFAULT);
                $_SESSION['token'] = $token;
                $_SESSION['id'] = $data['id_user'];
                $_SESSION['niveau'] = $data['niveau_compte'];
                
                header ('location: dashboard.php');
            }

            if($data['niveau_compte'] != "admin" && $data['niveau_compte'] != "moderateur"){
                $message = "<p>Votre compte n'a pas accès a ces informations. Veuillez vous connecter <a href='../connexion.php'>ici</a></p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion admin</title>
</head>
<body>
    <?php include_once "../modules/headeradmin.php" ?>
    <main>
        <h1>Connexion admin</h1>
        <?= $message?>
        <?php 
        // Si on est pas connecté, affiché le formulaire de connexion
            if(empty($_SESSION['token'])){
        ?>

        
            <form action="" method="post">
                <input type="text" name="info" placeholder="Pseudo ou Email">
                <input type="password" name="passw" placeholder="Mot de passe">
                <input type="submit" name="submit" value="Se connecter">
            </form>


        <?php 
            }
            // Si connecté
            if(isset($_SESSION['niveau'])){
                // Si admin ou modérateur, affiche la deconnexion
                if($_SESSION["niveau"] == "admin" || $_SESSION["niveau"] == "moderateur"){
            ?>

                <p>Vous êtes déjà connecté.</p>
                <a href="../index.php?logout=true">Deconnexion</a>


        <?php 
            }
            // Sinon demander la connexion
            if($_SESSION["niveau"] != "admin" && $_SESSION["niveau"] != "moderateur"){
            ?>


                <p>Vous n'avez pas accès, veuillez vous connecter en tant qu'admin. <a href="../index.php?logout=true">Deconnexion</a></p>


        <?php 
            }
        }
        ?>
    </main>
</body>
</html>