<div class="mt-3">
    <ul class="list-group mb-3">
        <li class="list-group-item">
            <div class="d-flex">
                <div class="col-4"><strong>ID</strong></div>
                <div class="col-6"><?= $video->getId() ?></div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="d-flex">
                <div class="col-4"><strong>Nom</strong></div>
                <div class="col-6"><?= $video->getNom()?></div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="d-flex">
                <div class="col-4"><strong>Vidéo</strong></div>
                <div class="col-6"><iframe src="<?= $video->getlien() ?>"  style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" loading="lazy" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe></div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="d-flex">
                <div class="col-4"><strong>Type</strong></div>
                <div class="col-6"><?= $video->getType() ?></div>
            </div>
        </li>

    </ul>



    <div class="d-flex justify-content-between">
        <a href="<?= lien('video') ?>" class="btn btn-primary">
            retour a la liste
        </a>
        <a href="<?= lien('video',"modifier",$video->getId()) ?>" class="btn btn-primary">
            modifier
        </a>
        <a id="suppr" class="btn btn-danger">
            supprimer
        </a>


    <div class="popup closed" id="popup">
        <p>Vous êtes sur le point de supprimer la video suivante : "<?= $video->getNom() ?>" </p>
        <p>En etes vous sur ?</p>
        <div class="df">
            <a class="btn btn-primary" id="close">non</a>

            <a href="<?= lien("video","supprimer",$video->getId()) ?>" id="delete" class="btn btn-danger">
                oui
            </a>
            
        </div>
    </div>    
</div>

