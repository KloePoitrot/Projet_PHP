<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <?php include "modules/header.php" ?>
    <main>
        <h1>Inscription</h1>
        <form action="" method="post">
            <input type="text" placeholder="Pseudo">
            <input type="text" placeholder="Nom">
            <input type="text" placeholder="prenom">
            <input type="mail" placeholder="Email">
            <input type="password" placeholder="Mot de passe">
            <input type="file">
            <input type="submit" name="submit" value="S'inscrire">
        </form>
    </main>
</body>
</html>