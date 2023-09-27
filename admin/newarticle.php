<?php 
session_start();

// Initialisation des variables
$isFormOk = true;
$imgUpload = "";
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
        $message .= "<p>La catégorie n'est pas valable. (5 caractères minimum)</p>";
        $isFormOk = false;
    }

    // Test du statut
    if($_POST['statut'] == 'null'){
        $message .= "<p>Veuillez selectionner un statut.</p>";
        $isFormOk = false;
    }


    // Test de l'image
    if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK){

        // Verification du mime
        $info = getimagesize($_FILES['image']['tmp_name']);
        // Il y a bien un fichier, verifie l'extention de l'image
        if($info && ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg' || $info['mime'] == 'image/png')){
            // Genere un nom de fichier
            $nom_fichier = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['image']['tmp_name'], '../images/pages/'.$nom_fichier);
            $imgUpload = 'images/pages/'.$nom_fichier;
        } else {
            // sinon cest un echec
            $message = "<p>L'image uploadée est invalide.</p>";
        }
    } else {
        $message = '<p>le fichier doit etre au format jpeg ou png.</p>';
    }


    // Si le formulaire est correcte
    if($isFormOk){
        require_once "connect.php";
        $request = "INSERT INTO articles(titre_article, image_article, contenu_article, date_article, categorie_article, statut_article) VALUES(:titre, :img, :contenu, :dateupload, :categorie, :statut)";
        $request = $db->prepare($request);
        $request->execute(array(
            "titre" => $_POST['title'],
            "img" => $imgUpload,
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
    <title>Créer un article</title>
</head>
<body>
    <main>
        <?php include_once "../modules/headeradmin.php"; ?>
        <h1>Créer un article</h1>
        <?= $message?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Titre">
            <textarea name="contenu" placeholder="Contenu de votre article"></textarea>
            <input type="text" name="categorie" placeholder="Catégorie">
            <select name="statut" id="statut">
                <option value="null">--- Selectionner un statut ---</option>
                <option value="brouillon">Brouillon</option>
                <option value="en attente">Final</option>
            </select>
            
            <input type="file" name="image" id="image">
            <input type="submit" name="submit" value="Envoyer">


        </form>
    </main>
</body>
</html>