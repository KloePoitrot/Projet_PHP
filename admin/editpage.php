<?php 
session_start();
require_once "connect.php";
$id = isset($_GET['id']) ? $_GET['id'] : null;


$message = null;
$isFormOk = true;
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

    // Si tout est ok
    if($isFormOk){
        $request = "UPDATE pages SET titre_page = :titre, contenu_page = :contenu, statut_page = :statut WHERE id_page = :id";
        $data = $db->prepare($request);

        // Executer la requete avec les données
        $data->execute(array(
            "id" => $id,
            "titre" => $_POST['title'],
            "contenu" => $_POST['contenu'],
            "statut" => $_POST['statut'],
        ));

        $message = "<p>Page modifiée!</p>";
    
    }
}
        // Recupère les données de la page selectionné
        $reqDisplay = "SELECT titre_page, contenu_page, statut_page FROM pages WHERE id_page = :id";
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
    <title>Modification de page</title>
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
                    if(isset($_GET['id']) && filter_var($id, FILTER_VALIDATE_INT)){
                    ?>
                    
                        <h1>Modification de la page</h1>
                        <?= $message?>
                        <form action="" method="post">
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
                        <input type="hidden" name="id" value="<?= $_GET['id']?>">
                        <input type="submit" name="submit" value="Envoyer">


                    </form>
                        <a href="listepages.php?delete=y&id=<?= $_GET['id']?>">Supprimer la page</a>

        <?php
                    }
                }
                // Sinon refuser l'acces 
                if(!isset($_GET['id']) || filter_var($id, FILTER_VALIDATE_INT) === false){ 
        ?>
            <p>Aucun page sélectionnée.</p>
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