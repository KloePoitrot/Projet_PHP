<?php 
session_start();
$message = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <title>Liste articles</title>
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
                if($_SESSION['niveau'] == "admin" || $_SESSION['niveau'] == "moderateur" ){    
        ?>
        
            <h1 class="header">Liste des articles</h1>
            
            <?php 
                require_once "connect.php";

                // Condition pur supprimer un article
                if(isset($_GET['delete']) && isset($_GET['id'])){
                    if($_SESSION['niveau'] == "admin"){
                        if($_GET['delete'] == 'y'){
                            $idDelete = $_GET['id'];
                            $request = "DELETE FROM articles WHERE id_article = :id";
                            $data = $db->prepare($request);
                            $data->execute(array(
                                'id' => $idDelete,
                            ));
                            $message = "<p class='success'>Article supprimé!</p>";
                        }
                    }

                    if($_SESSION['niveau'] != "admin"){
                        $message = "<p class='warning'>Action non-authorisé.</p>";
                    }
                }

                $data = $db->prepare("SELECT id_article, titre_article, image_article, date_article, categorie_article, statut_article FROM articles ORDER BY id_article DESC");
                $data->execute();
                $results = $data->fetchAll();
                ?>
                <?= $message?>
                    
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Image</th>
                            <th>Date de création</th>
                            <th>Catégorie</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    
                <?php
                foreach($results as $result){
                ?>
                    <tr>
                        <td><?= $result["id_article"]?></td>
                        <td><?= $result["titre_article"]?></td>
                        <td><img class="avatar small" src="../<?= $result["image_article"]?>" alt="image de l'article <?= $result["id_article"]?>"></td>
                        <td><?= $result["date_article"]?></td>
                        <td><?= $result["categorie_article"]?></td>
                        <td><?= $result["statut_article"]?></td>
                        <td><a class="button" href="../detailarticle.php?id=<?= $result["id_article"]?>">Afficher</a></td>
                        <td><a class="button" href="editarticle.php?id=<?= $result["id_article"]?>">Editer</a></td>
                        <?php 
                            if($_SESSION['niveau'] == 'admin'){
                                ?>
                                <td><a class="button btndelete" href="?delete=y&id=<?= $result["id_article"]?>">Supprimer</a></td>
                                <?php
                            }
                        ?>
                    </tr>
                <?php
                }
                ?>
                    </tbody>
                </table>
                
                <?php
            ?>

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