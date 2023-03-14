<div class="container">
    <div class="banniere">
        <img src="<?= imgLink("podcast.jpg")?>" alt="Podcasts">
    </div>
    <h1>Podcast</h1>

    <div class="ligne"> <a href="<?= lien("video","accueil") ?>" class="button">retourner à l'accueil</a>  </div>

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
            $("#highlight .iframe").css("height",);$('#highlight iframe').css("height", () => {
                // codé avant setHeight ça fonctionne très bien ne pas y toucher
                // ajuste le height en fonction de la taille de la div highltight moins la taille du titre de la vidéo pour ne pas sortir de la div
                // récupère la taille avec css("height") puis casse le rendu (ex: 10px) avec "px" ce qui rend ['10', ''] et on prend l'index 0 (10) 
                // tout ça moins la meme chose mais pour le titre de la video et on rajoute px à la fin pour que la taille soit reconnue par le navigateur
                return ($("#highlight").css("height").split("px")[0] - $("#highlight h4").css("height").split("px")[0]) + "px";
            });

        })
    </script>
</div>