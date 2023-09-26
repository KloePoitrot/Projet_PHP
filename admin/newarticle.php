<?php 
session_start();

// Initialisation des variables
$isFormOk = true;
$message = null;

// Test d'envoi du formulaire
if(isset($_POST['submit'])){

    // Test du titre
    if(empty($_POST['title']) || strlen($_POST['title']) < 5){
        $message .= "<p>Votre titre n'est pas assez long. (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test du contenu
    if(empty($_POST['contenu']) || strlen($_POST['contenu']) < 120){
        $message .= "<p>Votre contenu n'est pas assez long. (120 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test de la categorie
    if(empty($_POST['categorie']) || strlen($_POST['categorie']) < 5){
        $message .= "<p>Catégorie n'est pas valable. (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test du statut
    if($_POST['statut'] == 'null'){
        $message .= "<p>Veuillez selectionner un statut.</p>";
        $isFormOk = false;
    }


    // Si le formulaire est correcte
    if($isFormOk){
        require_once "connect.php";
        $request = "INSERT INTO articles(titre_article, contenu_article, date_article, categorie_article, statut_article) VALUES(:titre, :contenu, :dateupload, :categorie, :statut)";
        $request = $db->prepare($request);
        $request->execute(array(
            "titre" => $_POST['title'],
            "contenu" => $_POST['contenu'],
            "dateupload" => date('Y-m-d'),
            "categorie" => $_POST['categorie'],
            "statut" => $_POST['statut'],
        ));

        $message = "<p>Article créé!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un article </title>
</head>
<body>
    <h1>Créer un article</h1>
    <?= $message?>
    <form action="" method="post">
        <input type="text" name="title" placeholder="Titre">
        <textarea name="contenu" placeholder="Contenu de votre article"></textarea>
        <input type="text" name="categorie" placeholder="Catégorie">
        <select name="statut" id="statut">
            <option value="null">--- Selectionner un statut ---</option>
            <option value="brouillon">Brouillon</option>
            <option value="en attente">Final</option>
        </select>
        <input type="submit" name="submit" value="Envoyer">


    </form>
</body>
</html>