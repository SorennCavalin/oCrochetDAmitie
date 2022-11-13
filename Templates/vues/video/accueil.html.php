<div class="container">
    <div class="banniere">
        <img src="<?= imgLink("podcast.jpg")?>" alt="Podcasts">
    </div>
    <h1>Podcast</h1>

    <p>Les dernières vidéos postées sur les réseaux sociaux sont affichées sur cette page. Pour toutes les retrouver je vous invite à nous rejoindre sur youtube,facebook,instagram et tik tok</p>


    <div class="grille">
        <?php foreach ($videos as $video) :?>
            <div class="case">
                <h4><?= $video->getNom() ?></h4>
                <iframe src="<?= $video->getlien() ?>"  style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" loading="lazy" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
            </div> 
        <?php endforeach?>
    </div>
</div>