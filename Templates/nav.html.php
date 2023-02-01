<?php  use Modeles\Session; ?>
<header>
    <nav>
        <div class="logo">
            <a href="<?= lienAdmin("accueil","afficher")?>">
                <img src="<?= imgLink("logo3.png") ?>" class="logo-asso">
            </a>
        </div>
        <div class="header-network network">
            <?php include 'network.php' ?>
        </div> 
        <div class="hamburger" id="hamburger">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
        </div>
        <ul class="nav-ul transition" id="nav-ul">
            <li><a href="<?= lien("accueil") ?>">Accueil</a></li>
            <li><a href="<?= lien("projet","accueil") ?>">Projets</a></li>
            <li><a href="<?= lien("video","accueil") ?>">Vidéos</a></li>
            <li><a href="<?= lien("don","accueil") ?>">Dons</a></li>
            <li><a href="">Partenaires</a></li>
            <li><a href="">Adhésions</a></li>
            <li><a href="<?= Session::isConnected() ? lien("user", "profil") : lien("user","connexion") ?>"><?= Session::isConnected() ? "Profil" : "Connexion" ?></a></li>
        </ul>
    </nav>
</header>

