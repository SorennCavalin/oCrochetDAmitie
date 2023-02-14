<ul class="list-group my-3">
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>ID</strong></div>
            <div class="col-6"><?= $projet->getId() ?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Nom du projet</strong></div>
            <div class="col-6"><?= $projet->getNom() ?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Date de debut</strong></div>
            <div class="col-6"><?= $projet->getDate_debut()?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Date de fin</strong></div>
            <div class="col-6"><?= $projet->getDate_fin()?></div>
        </div>
    </li>
    <?php foreach($projet->getConcours() as $concours) : ?>
        <li class="list-group-item">
            <div class="d-flex">
                <div class="col-4"><strong>Concours liés</strong></div>
                <div class="col-6">
                     <a class="text-decoration-none text-dark " href="<?= lien("concours","detail",$concours->getId()) ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                    <?= $concours->getNom() ?>
                </a>
                </div>
            </div>
        </li>
    <?php endforeach ?>
</ul>

<div class="d-flex justify-content-center mb-5">
    <a class="text-decoration-none text-white btn btn-primary " href="<?= lien("projet", "preview", $projet->getId()) ?>"> 
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
        </svg>
        voir le rendu du projet
    </a>
</div>



<div class="d-flex justify-content-between">
    <a href="<?= lien('projet') ?>" class="btn btn-primary">
        retour a la liste
    </a>
    <a href="<?= lien('projet',"modifier",$projet->getId()) ?>" class="btn btn-primary">
        modifier
    </a>
    <a id="delete" href="<?= lien('projet',"supprimer",$projet->getId()) ?>" class="btn btn-danger">
        supprimer
    </a>


</div>

<div class="popup closed" id="popup">
        <p>Vous êtes sur le point de supprimer le projet : <?= $projet->getnom() ?> </p>
        <p>En etes vous sur ?</p>
        <div class="df">
            <a class="btn btn-primary" id="close">non</a>

            <a href="<?= lien("projet","supprimer",$projet->getId()) ?>" id="delete" class="btn btn-danger">
                oui
            </a>
            
        </div>
</div>