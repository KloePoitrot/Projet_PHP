<?php 
session_start();
// Initialisation des variables
$imgUpload = "images/avatar/avatarpardefaut.jpg";
$isFormOk = true;
$message = null;

// Test d'envoi du formulaire
if(isset($_POST['submit'])){
    // Test d'envoi du pseudo
    if(empty($_POST['categorie']) || strlen($_POST['categorie']) < 5){
        $message .= "<p class='warning'>La catégorie est incorrecte (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Si tout est ok
    if($isFormOk){
        // Test si le mail ou le pseudo existe
        $categorie = $_POST['categorie'];

        require_once "connect.php";
        $request = "SELECT nom_cat FROM categories WHERE nom_cat = :categorie";
        $request = $db->prepare($request);
        $request->execute(array(
            "categorie" => $categorie,
        ));
        $data = $request->fetch();

        if($data){
            // Le mail ou pseudo sont deja utilisé
            $message = "<p class='warning'>La catégorie existe déja</p>";
        }

        if(!$data){
            // Le mail et pseudo sont libre
            $request = "INSERT INTO categories(nom_cat) VALUES(:categorie)";
            $data = $db->prepare($request);
            $data->execute(array(
                'categorie' => $_POST['categorie']
            ));


            $message = "<p class='success'>Catégorie créée!</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <title>Inscription</title>
</head>
<body>
    <?php include_once "../modules/headeradmin.php" ?>
    <main>
        <?php 
            // Verifie si on est connecté
            if(!empty($_SESSION['niveau'])){
                // Verifie si le compte est de niveau admin ou moderateur
                if($_SESSION['niveau'] == "admin" || $_SESSION['niveau'] == "moderateur" ){    
        ?>
        
            <h1 class="header">Créer une catégorie</h1>
            <?= $message?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="text" name="categorie" placeholder="Catégorie">
                <input type="submit" name="submit" value="Envoyer">


            </form>

        <?php
                }
                // Sinon refuser l'acces 
                if($_SESSION['niveau'] != "admin" && $_SESSION['niveau'] != "moderateur"){ 
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
    </main>
    </main>
</body>
</html>