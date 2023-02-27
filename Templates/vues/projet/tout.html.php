<?php
    $prev = $page - 1;
    $next = $page + 1;
?>
    <div class="banniere"> 
        <img src="<?= imgLink("Projets.jpg")?>" alt="Projets"> 
    </div> 

    <div class="tout-les-projets">
        <?php foreach ($projets as $projet) :?>
            <div class='projet'>
                <div class="border">
                    <a href="<?= lien("projet","afficher", slugify($projet->getNom()))?>" class='lien'>
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
        <!-- si page est == 1 (premiere page (24 elements)) et que c'est la page maximum alors n'affiche pas les boutons -->
        <?php if (!($page == 1 && $pageMax)) :?>  
        <div class="pagination">
            <!-- disabled ne fonctionne pas sur un lien mais avec js je gère le clique en fonction de l'attribut disabled -->
            <a href="<?= lien("projet", "tout", $prev) ?>" class='button' <?= $prev < 1 ? 'disabled' : '' ?>> précédent </a>
            <a href="<?= lien("projet", "tout", $next) ?>" class='button' <?= $pageMax ? 'disabled' : '' ?>> suivant </a>
        </div>
        <?php endif ?>
    </div>