<div class="container">

    <div class="banniere"> 
        <img src="<?= imgLink("Projets.jpg")?>" alt="Projets"> 
    </div> 



    <div id="projets">
        <!--  -->
        <?php if (!isset($_GET['id'])) :?>
        <div class='fleche' id='Moins'>{</div>
        <div class="fleche" id='Plus'>}</div>
        <div id='nb'>1/<?= count($projets) ?></div>
        <?php endif?>
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
    <div class='tout'><a href="<?= lien("projet","tout") ?>" class='button'>Voir tout les projets</a></div>
</div>