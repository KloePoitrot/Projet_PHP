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
    <title>Liste Utilisateurs</title>
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
        
            <h1 class="header">Liste des comptes utilisateur</h1>
            <?php 
                require_once "connect.php";

                // Condition pur supprimer un compte
                if(isset($_GET['delete']) && isset($_GET['id'])){
                    if($_GET['delete'] == 'y' && $_SESSION['niveau'] == "admin"){
                        $idDelete = $_GET['id'];
                        $request = "DELETE FROM users WHERE id_user = :id";
                        $data = $db->prepare($request);
                        $data->execute(array(
                            'id' => $idDelete,
                        ));
                        $message = "<p class='success'>Utilisateur supprimé!</p>";
                    }
                    
                    if($_SESSION['niveau'] != "admin"){
                        $message = "<p class='warning'>Action non-authorisé.</p>";
                    }
                }

                // Affichage des utilisateurs
                $data = $db->prepare("SELECT id_user, nom_user, prenom_user, mail_user, pseudo_user, avatar_user, niveau_compte FROM users");
                $data->execute();
                $results = $data->fetchAll();
                ?>
                <?= $message?>
                    
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Pseudo</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Niveau compte</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    
                <?php
                foreach($results as $result){
                ?>
                    <tr>
                        <td><?= $result["id_user"]?></td>
                        <td><img class="avatar small" src="../<?= $result["avatar_user"]?>" alt="avatar de l'utilisateur <?= $result["pseudo_user"]?>"></td>
                        <td><?= $result["pseudo_user"]?></td>
                        <td><?= $result["nom_user"]?></td>
                        <td><?= $result["prenom_user"]?></td>
                        <td><?= $result["mail_user"]?></td>
                        <td><?= $result["niveau_compte"]?></td>
                        <td><a class="button" href="editutilisateur.php?id=<?= $result["id_user"]?>">Modifier</a></td>
                        <?php 
                            if($_SESSION['niveau'] == 'admin'){
                                ?>
                        <td><a class="button btndelete" href="?delete=y&id=<?= $result["id_user"]?>">Supprimer</a></td>
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