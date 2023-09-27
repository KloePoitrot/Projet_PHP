<?php
session_start();

// Connexion et Deconnexion
$success = null;
if(isset($_GET['login']) == 'true'){
    $success = '<p>Vous êtes connecté!</p>';
}
if(isset($_GET['logout']) == 'true'){
    $success = '<p>Vous vous êtes déconnecté!</p>';
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
    <title>Accueil</title>
</head>
<body>
    <?php include_once "modules/header.php" ?>
    <main>
        <?= $success?>
        <h1>Bienvenu!</h1>

        <?php 
        $data = $db->prepare("SELECT id_article, titre_article, image_article, date_article, categorie_article FROM articles WHERE statut_article = :stat ORDER BY id_article DESC LIMIT 5");
        $data->execute(array(
            "stat" => 'publié',
        ));
        $results = $data->fetchAll();

        foreach($results as $result){
            ?>
                <article>
                    <div>
                        <h3><?= $result['titre_article']?></h3>
                        <a href="detailarticle.php?id=<?= $result['id_article']?>">Voir l'article</a>
                    </div>
                </article>
        <?php
        }
        ?>
        <a href="listearticles.php">Voir plus...</a>
    </main>
</body>
</html>