<?php 
session_start();
require_once "admin/connect.php";
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Recupère les données de l'article selectionné
$reqDisplay = "SELECT titre_article, image_article, contenu_article, categorie_article FROM articles WHERE id_article = :id";
$reqDisplay = $db->prepare($reqDisplay);
$reqDisplay->execute(array(
    "id" => $id,
));
$dataDisplay = $reqDisplay->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <title>Article</title>
</head>
<body>
    <?php include_once "modules/header.php"; ?>
    <main>
    
    <?php 
    if(isset($_GET['id']) && filter_var($id, FILTER_VALIDATE_INT) && $dataDisplay){
        ?>
    <article class="imgArticle">
        <img src="<?= $dataDisplay['image_article'] ?>" alt="Image de l'article <?= $dataDisplay['titre_article'] ?>">
        <div>
            <h1 class="header"><?= $dataDisplay['titre_article'] ?></h1>
            <p class="margin-b">Catégorie: <?= $dataDisplay['categorie_article'] ?></p>
            <p><?= $dataDisplay['contenu_article'] ?></p>
        </div>    
    </article>
    


<?php }

if(!isset($_GET['id']) || filter_var($id, FILTER_VALIDATE_INT) === false || !$dataDisplay){

?>

    <h2>Erreur!</h2>
    <p class='warning'>Aucun article sélectionné. <a href="index.php">Accueil</a></p>

<?php 
} 
?>
</main>
</body>
</html>