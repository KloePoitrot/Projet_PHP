<?php 
session_start();
$message = null;
require_once "admin/connect.php";

// Determine sur quel page on se trouve
if(isset($_GET['page']) && !empty($_GET['page'])){
    $currentPage = (int) strip_tags($_GET['page']);
}else{
    $currentPage = 1;
}

$count = "SELECT COUNT(*) AS id_article FROM articles";
$countrequest = $db->prepare($count);
$countrequest->execute();

// Recupere le nombre d'articles
$countresult = $countrequest->fetch();
$nbArticles = (int) $countresult['id_article'];

// nombre d'article par page
$parPage = 10;
$pages = ceil($nbArticles / $parPage);

// premier article de la page
$premier = ($currentPage * $parPage) - $parPage;

$sql = 'SELECT * FROM articles ORDER BY id_article DESC LIMIT :premier, :nbparpage';
$query = $db->prepare($sql);
$query->bindValue(':premier', $premier, PDO::PARAM_INT);
$query->bindValue(':nbparpage', $parPage, PDO::PARAM_INT);
$query->execute();
$articles = $query->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <title>Liste articles</title>
    <style>
        img{
            width:50px
        }
    </style>
</head>
<body>
    <?php include_once "modules/header.php"; ?>
        <main>
            <h1 class="header">Liste des articles</h1>
            
            <?php 
                

                // Condition pur supprimer un compte
                if(isset($_GET['delete']) && isset($_GET['id'])){
                if($_GET['delete'] == 'y'){
                    $idDelete = $_GET['id'];
                    $request = "DELETE FROM articles WHERE id_article = :id";
                    $data = $db->prepare($request);
                    $data->execute(array(
                        'id' => $idDelete,
                    ));
                    $message = "<p class='success'>Article supprimé!</p>";
                }

                if($_SESSION['niveau'] != "admin"){
                    $message = "<p class='warning'>Action non-authorisé.</p>";
                }
                }

                ?>
                <?= $message?>
                    
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Image</th>
                            <th>Date de création</th>
                            <th>Catégorie</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    
                <?php
                foreach($articles as $article){
                ?>
                    <tr>
                        <td><?= $article["titre_article"]?></td>
                        <td><img class="avatar small" src="<?= $article["image_article"]?>" alt="image de l'article <?= $article["id_article"]?>"></td>
                        <td><?= $article["date_article"]?></td>
                        <td><?= $article["categorie_article"]?></td>
                        <td><a class="button" href="detailarticle.php?id=<?= $article['id_article']?>">Voir l'article</a></td>
                    </tr>
                <?php
                }
                ?>
                    </tbody>
                </table>


        <nav>
            <ul class="pagination">
                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                    <a href="listearticles.php?page=<?= $currentPage - 1 ?>" class="page-link"><</a>
                </li>
                <?php for($page = 1; $page <= $pages; $page++): ?>
                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                        <a href="listearticles.php?page=<?= $page ?>" class="page-link"><?= $page ?></a>
                    </li>
                <?php endfor ?>
                    <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                    <a href="listearticles.php?page=<?= $currentPage + 1 ?>" class="page-link">></a>
                </li>
            </ul>
        </nav>
    </main>
</body>
</html>