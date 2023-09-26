<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion admin</title>
</head>
<body>
    <?php include_once "../modules/headeradmin.php" ?>
    <main>
        <?php 
            // Verifie si on est connecté
            if(!empty($_SESSION['niveau'])){
                // Verifie si le compte est de niveau admin ou moderateur
                if($_SESSION['niveau'] == "admin" || $_SESSION['niveau'] == "moderateur" ){    
        ?>
        
            <h1>Dashboard</h1>
            <a href="../index.php?logout=true">Deconnexion</a>
            
            <article>
                <h2>Gestion</h2>
                <ul>
                    <li><a href="">Créer une nouvelle page</a></li>
                    <li><a href="">Créer un nouvel article</a></li>
                    <li><a href="">Gérer les comptes utilisateur</a></li>
                </ul>
            </article>

            <section>
                <h2>Activité</h2>
                <article>
                    <div>
                        <h3>5 dernières pages</h3>
                        <a href="listepages.php">Tout afficher</a>
                    </div>
                    <p>Afficher pages ici</p>
                </article>

                <article>
                    <div>
                        <h3>5 derniers articles</h3>
                        <a href="listearticles.php">Tout afficher</a>
                    </div>
                    <p>Afficher articles ici</p>
                </article>

                <article>
                    <div>
                        <h3>5 derniers utilisateur</h3>
                        <a href="listeutilisateurs.php">Tout afficher</a>
                    </div>
                    <p>Afficher utilisateurs ici</p>
                </article>
            </section>

        <?php
                }
                // Sinon refuser l'acces 
                if($_SESSION['niveau'] == "admin" && $_SESSION['niveau'] == "moderateur"){ 
        ?>
            <p>Acces denied</p>
        <?php 
                }
            }

            // Refuser l'acces si personne n'est connecté
            if(empty($_SESSION['niveau'])){
        ?>
            <p>Acces denied</p>
        <?php 
            }
        ?>
    </main>
</body>
</html>
</body>
</html>