<div class="container">

    <div id="projets">
        <div class='fleche' id='Moins'>{</div>
        <div class="fleche" id='Plus'>}</div>
        <?php foreach ($projets as $index => $projet) :?>
            <div class="projet" id='<?= "projet" . $index ?>'>
                <h1 class="projet_titre"><?= $projet->getNom() ?></h1>
                <div class="projet_contenu"><?= $projet->getPage() ?></div>
                <div class="infoSup">
                    projet prenant place entre le <?= $projet->getDate_debut() . " et le " . $projet->getDate_fin() ?>
                </div>
            </div>
        <?php endforeach?>
    </div>
    <div class='autreProjets '><a href="<?= lien("projet","tout") ?>" class='button'>Voir tout les projets</a></div>
</div>