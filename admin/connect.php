<?php 

// Manipulation des données avec PDO

// Connexion a la base de données
try {

    $db = new PDO(
        'mysql:host=localhost;dbname=creations_page_et_article;charset=utf8', 
        'AdminDB', // Nom d'utilisateur
        '4hd454hddyf948', // Mot de passe
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Active la gestion de l'erreurs
        ]
    );

} catch (Exception $e){
    echo "Access denied.  ";
    exit();
}



?>