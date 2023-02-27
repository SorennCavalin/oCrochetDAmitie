<div class="container">

    <div class="banniere"> 
        <img src="<?= imgLink("Projets.jpg")?>" alt="Projets"> 
    </div> 



    <div id="projets">
        <div class="projet" id='<?= "projet" . $index ?>'>
            <h1 class="projet_titre"><?= $projet->getNom() ?></h1>
            <div class="projet_contenu"><?= $projet->getPage() ?></div>
            <div class="infoSup">
                projet prenant place entre le <?= $projet->getDate_debut() . " et le " . $projet->getDate_fin() ?>
            </div>
        </div>
    </div>
    <div class='tout'><a href="<?= lien("projet","tout") ?>" class='button'>Voir tout les projets</a></div>
</div>