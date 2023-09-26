<?php 
$deco = null;
if(isset($_SESSION['token'])){
    $deco = "<li><a href='?logout=true'>Deconnexion</a></li>";
}
?>

<header>
    <a href="index.php"><img src="" alt="Logo du site"></a>
    <nav>
        <ul>
            <?= $deco?>
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="../admin/dashboard.php">dashboard</a></li>
            <li><a href="../admin/connexion.php">Administration</a></li>
        </ul>
    </nav>
</header>