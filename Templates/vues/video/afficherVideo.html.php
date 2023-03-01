<div class="container">
    <div class="banniere">
        <img src="<?= imgLink("podcast.jpg")?>" alt="Podcasts">
    </div>
    <h1>Podcast</h1>


    <div class='video-stand'>
        <div id='highlight'> 
            <div class="case">
                <h4 class='center-text'><?= $video->getNom() ?></h4>
                <iframe src="<?= $video->getlien() ?>"  style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" loading="lazy" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
            </div>  
        </div>

    <div class="tout">
        <a href="<?= lien("video","tout") ?>" class="button">Voir toutes les vidéos</a>
    </div>
    
    <script>
        window.addEventListener("load" , () => {
            $("#highlight .iframe").css("height",($("#highlight").css("height").split("px")[0] - $("#highlight h4").css("height").split("px")[0]) + "px");

        })
    </script>
</div>