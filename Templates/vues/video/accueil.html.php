<div class="container">
    <div class="banniere">
        <img src="<?= imgLink("podcast.jpg")?>" alt="Podcasts">
    </div>
    <h1>Podcast</h1>

    <p class="center-text">Les dernières vidéos mises en lignes sur les réseaux sociaux sont affichées sur cette page.</p>


    <div class='video-stand'>
        <div id='highlight'></div>


        <div class="grille">
            <?php foreach ($videos as $video) : ?>
                <div class="case">
                    <h4 class='center-text'><?= $video->getNom() ?></h4>
                    <iframe src="<?= $video->getlien() ?>"  style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" loading="lazy" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                </div> 
            <?php endforeach?>
        </div>
    </div>

    <div class="tout">
        <a href="<?= lien("video","tout") ?>" class="button">Voir toutes les vidéos</a>
    </div>
    
</div>