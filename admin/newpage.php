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

    // Test du statut
    if($_POST['statut'] == 'null'){
        $message .= "<p>Veuillez selectionner un statut.</p>";
        $isFormOk = false;
    }


    // Si le formulaire est correcte
    if($isFormOk){
        require_once "connect.php";
        $request = "INSERT INTO pages(titre_page, contenu_page, date_page, statut_page) VALUES(:titre, :contenu, :dateupload,  :statut)";
        $request = $db->prepare($request);
        $request->execute(array(
            "titre" => $_POST['title'],
            "contenu" => $_POST['contenu'],
            "dateupload" => date('Y-m-d'),
            "statut" => $_POST['statut'],
        ));

        $message = "<p>Page créée!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une page</title>
</head>
<body>
    <?php include_once "../modules/headeradmin.php"; ?>
    <h1>Créer une page</h1>
    <?= $message?>
    <form action="" method="post">
        <input type="text" name="title" placeholder="Titre">
        <textarea name="contenu" placeholder="Contenu de votre article"></textarea>
        <select name="statut" id="statut">
            <option value="null">--- Selectionner un statut ---</option>
            <option value="brouillon">Brouillon</option>
            <option value="en attente">Final</option>
        </select>
        <input type="submit" name="submit" value="Envoyer">


    </form>
</body>
</html>