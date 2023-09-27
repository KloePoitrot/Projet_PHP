<?php 
$deco = null;
if(isset($_SESSION['token'])){
    $deco = "<li><a href='../index.php?logout=true'>Deconnexion</a></li>";
}
?>

<header>
    <a href="index.php"><img src="" alt="Logo du site"></a>
    <nav>
        <ul>
            <li><a href="../index.php">Accueil</a></li>
            <?php 
                if(isset($_SESSION['niveau'])){
                    if($_SESSION['niveau'] == 'admin' || $_SESSION['niveau'] == 'moderateur'){
                        ?>
                <li><a href="../admin/dashboard.php">Dashboard</a></li>
                <li><a href="../admin/listepages.php">Pages</a></li>
                <li><a href="../admin/listearticles.php">Articles</a></li>
                <li><a href="../admin/listeutilisateurs.php">Utilisateurs</a></li>
            <?php 
                    }
                }
            ?>
            <?= $deco?>
            <?php 
                if(!isset($_SESSION['token'])){
            ?>
            <li><a href="../admin/connexion.php">Administration</a></li>
            <?php 
                }
            ?>
        </ul>
    </nav>
</header>