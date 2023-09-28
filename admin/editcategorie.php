<?php 
session_start();
require_once "connect.php";
$id = isset($_GET['id']) ? $_GET['id'] : null;


$message = null;
$isFormOk = true;

        // Recupère les données de la catégorie selectionné
        $reqDisplay = "SELECT nom_cat FROM categories WHERE id_cat = :id";
        $reqDisplay = $db->prepare($reqDisplay);
        $reqDisplay->execute(array(
            "id" => $id,
        ));
        $dataDisplay = $reqDisplay->fetch();

if(isset($_POST['submit'])){
    // Test du titre
    if(empty($_POST['nom']) || strlen($_POST['nom']) < 5){
        $message .= "<p class='warning'>Votre catégorie n'est pas assez longue. (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Si tout est ok
    if($isFormOk){
        // Test si la catégorie existe
        $request = "SELECT nom_cat FROM categories WHERE id_cat != :id and nom_cat = :nom";
        $request = $db->prepare($request);
        $request->execute(array(
            "id" => $_POST['id'],
            "nom" => $_POST['nom']
        ));
        $data = $request->fetch();

        if($data){
            // La catégorie existe déja
            $message = "<p class='warning'>La catégorie ".$_POST['nom']." existe déja.</p>";
        }

        if(!$data){
            // La catégorie n'existe pas

            // selectionne les articles sous cette catégorie
            $cate = $dataDisplay['nom_cat'];
            $request = "UPDATE articles SET categorie_article = :cat WHERE categorie_article = :cate";
            $data = $db->prepare($request);

            // Executer la requete avec les données
            $data->execute(array(
                "cate" => $cate,
                "cat" => $_POST['nom']
            ));


            //change le nom de la catégorie
            $request = "UPDATE categories SET nom_cat = :nom WHERE id_cat = :id";
            $data = $db->prepare($request);

            // Executer la requete avec les données
            $data->execute(array(
                "id" => $_POST['id'],
                "nom" => $_POST['nom']
            ));

            $message = "<p class='success'>Info modifié!</p>";
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
    <title>Modification de catégorie</title>
    <style>
        img{
            width:50px
        }
    </style>
</head>
<body>
    <?php include_once "../modules/headeradmin.php"; ?>
    <main>
    <?php 
            // Verifie si on est connecté
            if(!empty($_SESSION['niveau'])){
                // Verifie si le compte est de niveau admin ou moderateur
                if($_SESSION['niveau'] == "admin" || $_SESSION['niveau'] == "moderateur"){    
                    if(isset($_GET['id']) && filter_var($id, FILTER_VALIDATE_INT) && $dataDisplay){
                    ?>
                    
                        <h1 class="header" class='warning'>Modification de la catégorie</h1>
                        <?= $message?>
                        <form class="margin-b" action="" method="post" enctype="multipart/form-data">
                        <label for="nom">Nom:</label>
                        <input type="text" name="nom" value="<?= $dataDisplay['nom_cat']?>">
                        <input type="hidden" name="id" value="<?= $_GET['id']?>">
                        <input type="submit" name="submit" value="Envoyer">


                    </form>
                    <?php 
                        if($_SESSION['niveau'] == 'admin'){
                            ?>
                        <a class="button btndelete" href="listecategories.php?delete=y&id=<?= $_GET['id']?>">Supprimer la catégorie</a>
                            <?php
                        }
                    ?>

        <?php
                    }
                }
                // Sinon refuser l'acces 
                if(!isset($_GET['id']) || filter_var($id, FILTER_VALIDATE_INT) === false || !$dataDisplay){ 
        ?>
            <p>Aucune catégorie sélectionnée.</p>
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
</body>
</html>