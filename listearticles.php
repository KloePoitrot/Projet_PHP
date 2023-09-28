<?php 
session_start();
$message = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <title>Liste articles</title>
    <style>
        img{
            width:50px
        }
    </style>
</head>
<body>
    <?php include_once "modules/header.php"; ?>
        <main>
            <h1 class="header">Liste des articles</h1>
            
            <?php 
                require_once "admin/connect.php";

                // Condition pur supprimer un compte
                if(isset($_GET['delete']) && isset($_GET['id'])){
                if($_GET['delete'] == 'y'){
                    $idDelete = $_GET['id'];
                    $request = "DELETE FROM articles WHERE id_article = :id";
                    $data = $db->prepare($request);
                    $data->execute(array(
                        'id' => $idDelete,
                    ));
                    $message = "<p class='success'>Article supprimé!</p>";
                }

                if($_SESSION['niveau'] != "admin"){
                    $message = "<p class='warning'>Action non-authorisé.</p>";
                }
                }

                $data = $db->prepare("SELECT id_article, titre_article, image_article, date_article, categorie_article FROM articles WHERE statut_article = :stat ORDER BY id_article DESC");
                $data->execute(array(
                    "stat" => 'publié',
                ));
                $results = $data->fetchAll();
                ?>
                <?= $message?>
                    
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Image</th>
                            <th>Date de création</th>
                            <th>Catégorie</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    
                <?php
                foreach($results as $result){
                ?>
                    <tr>
                        <td><?= $result["titre_article"]?></td>
                        <td><img class="avatar small" src="<?= $result["image_article"]?>" alt="image de l'article <?= $result["id_article"]?>"></td>
                        <td><?= $result["date_article"]?></td>
                        <td><?= $result["categorie_article"]?></td>
                        <td><a class="button" href="detailarticle.php?id=<?= $result['id_article']?>">Voir l'article</a></td>
                    </tr>
                <?php
                }
                ?>
                    </tbody>
                </table>
    </main>
</body>
</html>