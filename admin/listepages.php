<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste Utilisateurs</title>
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
        
            <h1>Liste des comptes utilisateur</h1>
            
            <?php 
                require_once "connect.php";
                $data = $db->prepare("SELECT id_user, nom_user, prenom_user, mail_user, pseudo_user, avatar_user, niveau_compte FROM users");
                $data->execute();
                $results = $data->fetchAll();
                ?>
                    
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
                        <td><img src="../<?= $result["avatar_user"]?>" alt="avatar de l'utilisateur <?= $result["pseudo_user"]?>"></td>
                        <td><?= $result["pseudo_user"]?></td>
                        <td><?= $result["nom_user"]?></td>
                        <td><?= $result["prenom_user"]?></td>
                        <td><?= $result["mail_user"]?></td>
                        <td><?= $result["niveau_compte"]?></td>
                        <td><a href="">Modifier</a></td>
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
                if($_SESSION['niveau'] == "admin" && $_SESSION['niveau'] == "moderateur"){ 
        ?>
            <p>Acces denied</p>
        <?php 
                }
            }

            // Refuser l'acces si personne n'est connecté
            if(empty($_SESSION['niveau'])){
        ?>
            <p>Acces denied</p>
        <?php 
            }
        ?>
</body>
</html>