<?php 
session_start();
require_once "connect.php";
$id = isset($_GET['id']) ? $_GET['id'] : null;


$message = null;
$imgUpload = "";
$isFormOk = true;

        // Recupère les données de la page selectionné
        $reqDisplay = "SELECT titre_page, contenu_page, statut_page, image_page FROM pages WHERE id_page = :id";
        $reqDisplay = $db->prepare($reqDisplay);
        $reqDisplay->execute(array(
            "id" => $id,
        ));
        $dataDisplay = $reqDisplay->fetch();


if(isset($_POST['submit'])){
    // Test du titre
    if(empty($_POST['title']) || strlen($_POST['title']) < 5){
        $message .= "<p>Votre titre n'est pas assez long. (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test du contenu
    if(empty($_POST['contenu']) || strlen($_POST['contenu']) < 120){
        $message .= "<p>Votre contenu n'est pas assez long. (120 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test du statut
    if($_POST['statut'] == 'null'){
        $message .= "<p>Veuillez selectionner un statut.</p>";
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
                move_uploaded_file($_FILES['image']['tmp_name'], '../images/pages/'.$nom_fichier);
                $imgUpload = 'images/pages/'.$nom_fichier;
            } else {
                // sinon cest un echec
                $message = "<p>L'image uploadée est invalide.</p>";
            }
        }

    // Si tout est ok
    if($isFormOk){
        $img = !empty($_FILES['image']) ? $imgUpload : $dataDisplay['image_page'];
        $request = "UPDATE pages SET titre_page = :titre, image_page = :img, contenu_page = :contenu, statut_page = :statut WHERE id_page = :id";
        $data = $db->prepare($request);

        // Executer la requete avec les données
        $data->execute(array(
            "id" => $id,
            "img" => $img,
            "titre" => $_POST['title'],
            "contenu" => $_POST['contenu'],
            "statut" => $_POST['statut'],
        ));

        $message = "<p>Page modifiée!</p>";
    
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de page</title>
    <style>
        img{
            width:50px
        }
    </style>
</head>
<body>
    <main>
    <?php include_once "../modules/headeradmin.php"; ?>
    <?php 
            // Verifie si on est connecté
            if(!empty($_SESSION['niveau'])){
                // Verifie si le compte est de niveau admin ou moderateur
                if($_SESSION['niveau'] == "admin" || $_SESSION['niveau'] == "moderateur"){    
                    if(isset($_GET['id']) && filter_var($id, FILTER_VALIDATE_INT) && $dataDisplay){
                    ?>
                    
                        <h1>Modification de la page</h1>
                        <?= $message?>
                        <form action="" method="post" enctype="multipart/form-data">
                        <label for="pseudo">Titre:</label>
                        <input type="text" name="title" value="<?= $dataDisplay['titre_page']?>">
                        <label for="pseudo">Contenu:</label>
                        <textarea name="contenu"><?= $dataDisplay['contenu_page']?></textarea>
                        <label for="pseudo">Statut:</label>
                        <select name="statut" id="statut">
                            <option value="null">--- Selectionner un statut ---</option>
                            <option value="brouillon">Brouillon</option>
                            <option value="en attente">En attente</option>
                            <option value="publié">Publier</option>
                        </select>
                        <label for="image">Changer l'image:</label>
                        <input type="file" name="image" id="image">
                        <input type="hidden" name="id" value="<?= $_GET['id']?>">
                        <input type="submit" name="submit" value="Envoyer">


                    </form>
                        <a href="listepages.php?delete=y&id=<?= $_GET['id']?>">Supprimer la page</a>

        <?php
                    }
                }
                // Sinon refuser l'acces 
                if(!isset($_GET['id']) || filter_var($id, FILTER_VALIDATE_INT) === false || !$dataDisplay){ 
        ?>
            <p>Aucun page sélectionnée.</p>
        <?php 
                }
                // Sinon refuser l'acces 
                if($_SESSION['niveau'] != "admin" && $_SESSION['niveau'] == "moderateur"){ 
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