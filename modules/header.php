<header>
    <a href="index.php"><img src="" alt="Logo du site"></a>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <?php 
                $deco = null;
                if(isset($_SESSION['token'])){
            ?>
                <li><a href='profil.php'>Profil</a></li>    
                <li><a href='index.php?logout=true'>Deconnexion</a></li>    
            <?php 
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