    <div class="banniere"> 
        <img src="<?= imgLink("Projets.jpg")?>" alt="Projets"> 
    </div> 



    <div class="tout-les-projets">
        <?php foreach ($projets as $projet) :?>
            <div class='projet'>
                <div class="border">
                    <a href="<?= lien("projet","accueil", slugify($projet->getNom()))?>" class='lien'>
                        <p>
                            <?= $projet->getNom() ?>
                        </p>
                        <p class="infoSup">
                            <?= "prend place entre le " . $projet->getDate_debut() . " et le " . $projet->getDate_fin() ?>
                        </p>
                    </a>
                </div>
            </div class='projet'>
        <?php endforeach ?>
        <div class="pagination">
            <a href="<?= lien() ?>" class="button"></a><a href="" class="button"></a>
        </div>
    </div>