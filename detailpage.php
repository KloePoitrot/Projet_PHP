<?php 
session_start();
require_once "admin/connect.php";
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Recupère les données de la page selectionné
$reqDisplay = "SELECT titre_page, image_page, contenu_page, statut_page FROM pages WHERE id_page = :id";
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
    <title>Page</title>
</head>
<body>
    <?php include_once "modules/header.php"; ?>
    <main>
    
    <?php 
    if(isset($_GET['id']) && filter_var($id, FILTER_VALIDATE_INT) && $dataDisplay){
        ?>
    <article class="imgArticle">
        <img src="<?= $dataDisplay['image_page'] ?>" alt="Image de la page <?= $dataDisplay['titre_page'] ?>">
        <div>
            <h1 class="header"><?= $dataDisplay['titre_page'] ?></h1>
            <p><?= $dataDisplay['contenu_page'] ?></p>
        </div>    
    </article>

<?php }

if(!isset($_GET['id']) || filter_var($id, FILTER_VALIDATE_INT) === false || !$dataDisplay){

?>

    <h2 class="header">Erreur!</h2>
    <p class='warning'>Aucune page sélectionné. <a href="index.php">Accueil</a></p>

<?php 
} 
?>
</main>
</body>
</html>