<?php 
include_once "admin/connect.php"
?>

<header>
    <a href="index.php"><img src="" alt="Logo du site"></a>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <?php 
                $deco = null;
                if(isset($_SESSION['token'])){
            ?>
                <li><a href='listearticles.php'>Articles</a></li>    
                <li><a href='profil.php'>Profil</a></li>    

            <?php 
                    $data = $db->prepare("SELECT id_page, titre_page FROM pages WHERE statut_page = :stat ORDER BY id_page DESC");
                    $data->execute(array(
                        "stat" => "publié",
                    ));
                    $resultsheader = $data->fetchAll();
                    foreach($resultsheader as $resultheader){            
            ?>
                <li><a href="detailpage.php?id=<?= $resultheader['id_page'] ?>"><?= $resultheader['titre_page'] ?></a></li>
            <?php 
                    }
            ?>
                <li><a href='index.php?logout=true'>Deconnexion</a></li>    
            <?php 
                    if($_SESSION['niveau'] == "admin" || $_SESSION['niveau'] == "moderateur"){
            ?>
                <li><a href='admin/dashboard.php'>Dashboard</a></li>    
            <?php 
                    }
                }
            ?>
            <?= $deco?>
            <?php 
                if(!isset($_SESSION['token'])){
            ?>
                    <li><a href="inscription.php">Inscription</a></li>
                    <li><a href="connexion.php">Connexion</a></li>
                    <li><a href="admin/connexion.php">Administration</a></li>
            <?php 
                }
            ?>
        </ul>
    </nav>
</header>


