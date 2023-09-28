<?php
session_start();

// Connexion et Deconnexion
$success = null;
if(isset($_GET['login']) == 'true'){
    $success = '<p class="success">Vous êtes connecté!</p>';
}
if(isset($_GET['logout']) == 'true'){
    $success = '<p class="success">Vous vous êtes déconnecté!</p>';
    unset($_SESSION['token']);
    unset($_SESSION['id']);
    unset($_SESSION['niveau']);
}


include_once "admin/connect.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <title>Accueil</title>
</head>
<body>
    <?php include_once "modules/header.php" ?>
    <main>
        <?= $success?>
        <h1 class="header">Bienvenue!</h1>

        <div class="articles">
            <?php 
            $data = $db->prepare("SELECT id_article, titre_article, image_article, date_article, categorie_article FROM articles WHERE statut_article = :stat ORDER BY id_article DESC LIMIT 5");
            $data->execute(array(
                "stat" => 'publié',
            ));
            $results = $data->fetchAll();

            foreach($results as $result){
                ?>
                    <article>
                        <img src="<?= $result['image_article']?>" alt="Image de l'article <?= $result['titre_article']?>">
                        <div>
                            <h3><?= $result['titre_article']?></h3>
                            <a class="button" href="detailarticle.php?id=<?= $result['id_article']?>">Voir l'article</a>
                        </div>
                    </article>
            <?php
            }
            ?>
        </div>
        <a class="button margin" href="listearticles.php">Voir plus...</a>
    </main>
</body>
</html>