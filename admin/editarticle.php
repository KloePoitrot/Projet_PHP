<?php 
session_start();
require_once "connect.php";
$id = isset($_GET['id']) ? $_GET['id'] : null;


$message = null;
$isFormOk = true;

// Recupère les données de l'article selectionné
$reqDisplay = "SELECT titre_article, contenu_article, categorie_article, statut_article, image_article FROM articles WHERE id_article = :id";
$reqDisplay = $db->prepare($reqDisplay);
$reqDisplay->execute(array(
    "id" => $id,
));
$dataDisplay = $reqDisplay->fetch();

$imgUpload = $dataDisplay['image_article'];
if(isset($_POST['submit'])){
    // Test du titre
    if(empty($_POST['title']) || strlen($_POST['title']) < 5){
        $message .= "<p class='warning'>Votre titre n'est pas assez long. (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test du contenu
    if(empty($_POST['contenu']) || strlen($_POST['contenu']) < 120){
        $message .= "<p class='warning'>Votre contenu n'est pas assez long. (120 caractères minimum)</p>";
        $isFormOk = false;
    }

    // test de la catégorie
    if(empty($_POST['categorie']) || strlen($_POST['categorie']) < 5){
        $message .= "<p class='warning'>La catégorie n'est pas valable. (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test du statut
    if($_POST['statut'] == 'null'){
        $message .= "<p class='warning'>Veuillez selectionner un statut.</p>";
        $isFormOk = false;
    }

    // Test du statut
    if($_POST['categorie'] == 'null'){
        $message .= "<p class='warning'>Veuillez selectionner une catégorie.</p>";
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
        $img = isset($_FILES['image']) ? $imgUpload : $dataDisplay['image_article'];
        $request = "UPDATE articles SET titre_article = :titre, image_article = :img, contenu_article = :contenu,  categorie_article = :categorie, statut_article = :statut WHERE id_article = :id";
        $data = $db->prepare($request);

        // Executer la requete avec les données
        $data->execute(array(
            "id" => $id,
            "img" => $img,
            "titre" => $_POST['title'],
            "contenu" => $_POST['contenu'],
            "categorie" => $_POST['categorie'],
            "statut" => $_POST['statut'],
        ));

        $message = "<p class='success'>Article modifié!</p>";
    
    }
}
        


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <title>Modification d'article</title>
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
                    
                        <h1 class="header">Modification de l'article</h1>
                        <?= $message?>
                        <form class="margin-b" action="" method="post" enctype="multipart/form-data">
                            <label for="pseudo">Titre:</label>
                            <input type="text" name="title" value="<?= $dataDisplay['titre_article']?>">
                            <label for="pseudo">Contenu:</label>
                            <textarea name="contenu"><?= $dataDisplay['contenu_article']?></textarea>
                            <label for="pseudo">Catégorie:</label>
                            <select name="categorie" id="statut">
                                <option value="<?= $dataDisplay['categorie_article']?>"><?= $dataDisplay['categorie_article']?></option>
                                <?php 
                                $datacat = $db->prepare("SELECT id_cat, nom_cat FROM categories ORDER BY id_cat DESC");
                                $datacat->execute();
                                $resultscat = $datacat->fetchAll();
                                foreach($resultscat as $resultcat){
                                ?>
                                <option value="<?= $resultcat['nom_cat']?>"><?= $resultcat['nom_cat']?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <label for="pseudo">Statut:</label>
                            <select name="statut" id="statut">
                                <option value="<?= $dataDisplay['statut_article']?>"><?= $dataDisplay['statut_article']?></option>
                                <option value="brouillon">Brouillon</option>
                                <option value="en attente">En attente</option>
                                <option value="publié">Publier</option>
                            </select>
                            <label for="image">Changer l'image:</label>
                            <input type="file" name="image" id="image">
                            <input type="hidden" name="id" value="<?= $_GET['id']?>">
                            <input type="submit" name="submit" value="Envoyer">
                        </form>
                        <a class="button btndelete" href="listearticles.php?delete=y&id=<?= $_GET['id']?>">Supprimer l'article</a>

        <?php
                    }
                }
                // Sinon refuser l'acces 
                if(!isset($_GET['id']) || filter_var($id, FILTER_VALIDATE_INT) === false || !$dataDisplay){ 
        ?>
            <p>Aucun article sélectionné.</p>
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
