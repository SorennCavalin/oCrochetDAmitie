<?php
    $prev = $page - 1;
    $next = $page + 1;
?>
    <div class="banniere">
        <img src="<?= imgLink("podcast.jpg")?>" alt="Podcasts">
    </div>

    <div class="ligne"> <a href="<?= lien("video","accueil") ?>" class="button">retourner à l'accueil</a>  </div>
    <div class="toutes-les-videos">
        <?php foreach ($videos as $video) :?>
            <div class='video'>
                <div class="border">
                    <a href="<?= lien("video","afficher", slugify($video->getNom()))?>" class='lien'>
                        <p>
                            <?= $video->getNom() ?>
                        </p>
                        <p class="infoSup">
                            
                        </p>
                    </a>
                </div>
            </div>
        <?php endforeach ?>
        <!-- si page est == 1 (premiere page (24 elements)) et que c'est la page maximum alors n'affiche pas les boutons -->
        <?php if (!($page == 1 && $pageMax)) :?>  
        <div class="pagination">
            <!-- disabled ne fonctionne pas sur un lien mais avec js je gère le clique en fonction de l'attribut disabled -->
            <a href="<?= lien("video", "tout", $prev) ?>" class='button' <?= $prev < 1 ? 'disabled' : '' ?>> précédent </a>
            <a href="<?= lien("video", "tout", $next) ?>" class='button' <?= $pageMax ? 'disabled' : '' ?>> suivant </a>
        </div>
        <?php endif ?>
    </div>