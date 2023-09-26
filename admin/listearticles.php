<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste articles</title>
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
                if($_SESSION['niveau'] == "admin" || $_SESSION['niveau'] == "moderateur" ){    
        ?>
        
            <h1>Liste des articles</h1>
            
            <?php 
                require_once "connect.php";
                $data = $db->prepare("SELECT id_article, titre_article, image_article, date_article, categorie_article, statut_article FROM articles");
                $data->execute();
                $results = $data->fetchAll();
                ?>
                    
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
                        <td><img src="../<?= $result["image_article"]?>" alt="image de l'article <?= $result["id_article"]?>"></td>
                        <td><?= $result["date_article"]?></td>
                        <td><?= $result["categorie_article"]?></td>
                        <td><?= $result["statut_article"]?></td>
                        <td><a href="">Afficher</a></td>
                        <td><a href="">Editer</a></td>
                        <td><a href="">Supprimer</a></td>
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
</body>
</html>