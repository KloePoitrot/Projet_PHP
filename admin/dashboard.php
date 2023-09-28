<?php 
session_start();

$statutclass = '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <title>Dashboard</title>
</head>
<body>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <title>Connexion admin</title>
</head>
<body>
    <?php include_once "../modules/headeradmin.php" ?>
    <main>
        <?php 
            // Verifie si on est connecté
            if(!empty($_SESSION['niveau'])){
                // Verifie si le compte est de niveau admin ou moderateur
                if($_SESSION['niveau'] == "admin" || $_SESSION['niveau'] == "moderateur" ){    
                    require_once "connect.php";
        ?>
        
            <h1 class="header">Dashboard</h1>
            
            <article class="margin-b">
                <h2 class="header1">Gestion</h2>
                <ul>
                    <li><a href="newpage.php">Créer une nouvelle page</a></li>
                    <li><a href="newarticle.php">Créer un nouvel article</a></li>
                    <li><a href="newcategorie.php">Créer une nouvelle catégorie</a></li>
                    <li><a href="listeutilisateurs.php">Gérer les comptes utilisateur</a></li>
                </ul>
            </article>

            <section>
                <h2 class="header1">Activité</h2>
                <h3 class="header2">5 dernières pages</h3>
                <a class="button" href="listepages.php">Tout afficher</a>
                <article class="display margin-b">
                    <?php 
                        // recupère les 5 dernières données
                        $data = $db->prepare("SELECT id_page, titre_page, image_page, date_page, statut_page FROM pages ORDER BY id_page DESC LIMIT 5;");
                        // execute les données
                        $data->execute();
                        $results = $data->fetchAll();

                        // affiche les données
                        foreach($results as $result){
                            ?>
                                <div class="item">
                                    <img src="../<?= $result['image_page']?>" alt="<?= $result['titre_page']?>">
                                    <div>
                                        <h3><?= $result['titre_page']?></h3>
                                        <div>
                                            <p class="<?= $statutclass?>"><?= $result['statut_page']?></p>
                                            <p class="small"><?= $result['date_page']?></p>
                                        </div>
                                    </div>
                                </div>
                        <?php
                        }
                        ?>
                </article>

                <h3 class="header2">5 derniers articles</h3>
                <a class="button" href="listearticles.php">Tout afficher</a>
                <article class="display margin-b">
                    <?php 
                        $data = $db->prepare("SELECT id_article, titre_article, image_article, date_article, categorie_article, statut_article FROM articles ORDER BY id_article DESC LIMIT 5;");
                        $data->execute();
                        $results = $data->fetchAll();

                        foreach($results as $result){
                            ?>
                                <div class="item">
                                    <img src="../<?= $result['image_article']?>" alt="<?= $result['titre_article']?>">
                                    <div>
                                        <h3><?= $result['titre_article']?></h3>
                                        <div>
                                            <p><?= $result['categorie_article']?></p>
                                            <p class="<?= $statutclass?>"><?= $result['statut_article']?></p>
                                            <p class="small"><?= $result['date_article']?></p>
                                        </div>
                                    </div>
                                </div>
                        <?php
                        }
                        ?>
                </article>

                <h3 class="header2">5 derniers utilisateurs</h3>
                <a class="button" href="listeutilisateurs.php">Tout afficher</a>
                <article class="display">
                    <?php 
                        $data = $db->prepare("SELECT id_user, pseudo_user, avatar_user FROM users ORDER BY id_user DESC LIMIT 5;");
                        $data->execute();
                        $results = $data->fetchAll();

                        foreach($results as $result){
                            ?>
                                <div class="item">
                                    <img class="avatar small" src="../<?= $result['avatar_user']?>" alt="<?= $result['pseudo_user']?>">
                                    <p><?= $result['pseudo_user']?></p>
                                </div>
                        <?php
                        }
                        ?>
                </article>
            </section>

        <?php
                }
                // Sinon refuser l'acces 
                if($_SESSION['niveau'] != "admin" && $_SESSION['niveau'] != "moderateur"){ 
        ?>
            <p class='warning'>Access denied</p>
        <?php 
                }
            }

            // Refuser l'acces si personne n'est connecté
            if(empty($_SESSION['niveau'])){
        ?>
            <p class='warning'>Access denied</p>
        <?php 
            }
        ?>
    </main>
</body>
</html>
</body>
</html>