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
    <title>Liste catégories</title>
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
        
            <h1 class="header">Liste des catégories</h1>
            <a class="button" href="newcategorie.php">Créer une catégorie</a>
            
            <?php 
                require_once "connect.php";

                // Condition pur supprimer une page
                if(isset($_GET['delete']) && isset($_GET['id'])){
                    if($_SESSION['niveau'] == "admin"){
                        if($_GET['delete'] && $_GET['id'] != '0'){
                            $idDelete = $_GET['id'];
                            // Selectionne le nom de la catégorie a supprimer
                            $reqDisplay = "SELECT nom_cat FROM categories WHERE id_cat = :id";
                            $reqDisplay = $db->prepare($reqDisplay);
                            $reqDisplay->execute(array(
                                "id" => $idDelete,
                            ));
                            $dataDisplay = $reqDisplay->fetch();

                            // selectionne les articles sous cette catégorie
                            $request = "UPDATE articles SET categorie_article = :cat WHERE categorie_article = :cate";
                            $data = $db->prepare($request);

                            $data->execute(array(
                                "cate" => $dataDisplay['nom_cat'],
                                "cat" => 'Sans Catégorie'
                            ));
                            
                            // Supprime la catégorie
                            $request = "DELETE FROM categories WHERE id_cat = :id";
                            $data = $db->prepare($request);
                            $data->execute(array(
                                'id' => $idDelete,
                            ));
                            $message = "<p class='success'>Catégorie supprimée!</p>";
                        }
                    }
                    
                    if($_SESSION['niveau'] != "admin"){
                        $message = "<p class='warning'>Action non-authorisé.</p>";
                    }
                }


                $data = $db->prepare("SELECT id_cat, nom_cat FROM categories WHERE NOT id_cat = 0 ORDER BY id_cat DESC");
                $data->execute();
                $results = $data->fetchAll();
                ?>
                <table class="margin-t"> 
                    <tbody>
                <?= $message?>
                <?php
                foreach($results as $result){
                ?>
                    <tr>
                        <td><?= $result["nom_cat"]?></td>
                        <td><a class="button" href="editcategorie.php?id=<?= $result["id_cat"]?>">Editer</a></td>
                        <?php 
                            if($_SESSION['niveau'] == 'admin'){
                                ?>
                        <td><a class="button btndelete" href="?delete=y&id=<?= $result["id_cat"]?>">Supprimer</a></td>
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