<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .display{
            display:grid;
            grid-template-columns: repeat(6, 1fr);
        }
        img{
            width:50px
        }
    </style>
</head>
<body>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        ?>
        
            <h1>Dashboard</h1>
            
            <article>
                <h2>Gestion</h2>
                <ul>
                    <li><a href="">Créer une nouvelle page</a></li>
                    <li><a href="">Créer un nouvel article</a></li>
                    <li><a href="listeutilisateurs.php">Gérer les comptes utilisateur</a></li>
                </ul>
            </article>

            <section>
                <h2>Activité</h2>
                <article class="display">
                    <div>
                        <h3>5 dernières pages</h3>
                        <a href="listepages.php">Tout afficher</a>
                    </div>
                    <?php 
                        require_once "connect.php";
                        // recupère les 5 dernières données
                        $data = $db->prepare("SELECT id_page, titre_page, image_page, date_page, statut_page FROM pages ORDER BY id_page DESC LIMIT 5;");
                        // execute les données
                        $data->execute();
                        $results = $data->fetchAll();

                        // affiche les données
                        foreach($results as $result){
                            ?>
                                <div>
                                    <img src="<?= $result['image_page']?>" alt="<?= $result['titre_page']?>">
                                    <div>
                                        <h3><?= $result['titre_page']?></h3>
                                        <div>
                                            <p><?= $result['date_page']?></p>
                                            <p><?= $result['statut_page']?></p>
                                        </div>
                                    </div>
                                </div>
                        <?php
                        }
                        ?>
                </article>

                <article class="display">
                    <div>
                        <h3>5 derniers articles</h3>
                        <a href="listearticles.php">Tout afficher</a>
                    </div>
                    <?php 
                        require_once "connect.php";
                        $data = $db->prepare("SELECT id_article, titre_article, image_article, date_article, categorie_article, statut_article FROM articles ORDER BY id_article DESC LIMIT 5;");
                        $data->execute();
                        $results = $data->fetchAll();

                        foreach($results as $result){
                            ?>
                                <div>
                                    <img src="<?= $result['image_article']?>" alt="<?= $result['titre_article']?>">
                                    <div>
                                        <h3><?= $result['titre_article']?></h3>
                                        <div>
                                            <p><?= $result['categorie_article']?></p>
                                            <p><?= $result['statut_article']?></p>
                                            <p><?= $result['date_article']?></p>
                                        </div>
                                    </div>
                                </div>
                        <?php
                        }
                        ?>
                </article>

                <article class="display">
                    <div>
                        <h3>5 derniers utilisateurs</h3>
                        <a href="listeutilisateurs.php">Tout afficher</a>
                    </div>
                    <?php 
                        require_once "connect.php";
                        $data = $db->prepare("SELECT id_user, pseudo_user, avatar_user FROM users ORDER BY id_user DESC LIMIT 5;");
                        $data->execute();
                        $results = $data->fetchAll();

                        foreach($results as $result){
                            ?>
                                <div>
                                    <img src="../<?= $result['avatar_user']?>" alt="<?= $result['pseudo_user']?>">
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
</body>
</html>