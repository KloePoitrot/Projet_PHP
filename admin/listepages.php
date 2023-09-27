<?php 
session_start();
$message = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste pages</title>
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
        
            <h1>Liste des pages</h1>
            
            <?php 
                require_once "connect.php";

                // Condition pur supprimer une page
                if(isset($_GET['delete']) && isset($_GET['id'])){
                    if($_GET['delete']){
                        $idDelete = $_GET['id'];
                        $request = "DELETE FROM pages WHERE id_page = :id";
                        $data = $db->prepare($request);
                        $data->execute(array(
                            'id' => $idDelete,
                        ));
                        $message = "<p>Page supprimée!</p>";
                    }
                    
                    if($_SESSION['niveau'] != "admin"){
                        $message = "<p>Action non-authorisé.</p>";
                    }
                }


                $data = $db->prepare("SELECT id_page, titre_page, image_page, date_page, statut_page FROM pages ORDER BY id_page DESC");
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
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    
                <?php
                foreach($results as $result){
                ?>
                    <tr>
                        <td><?= $result["id_page"]?></td>
                        <td><?= $result["titre_page"]?></td>
                        <td><img src="../<?= $result["image_page"]?>" alt="image de la page <?= $result["id_page"]?>"></td>
                        <td><?= $result["date_page"]?></td>
                        <td><?= $result["statut_page"]?></td>
                        <td><a href="">Afficher</a></td>
                        <td><a href="editpage.php?id=<?= $result["id_page"]?>">Editer</a></td>
                        <td><a href="?delete=y&id=<?= $result["id_page"]?>">Supprimer</a></td>
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