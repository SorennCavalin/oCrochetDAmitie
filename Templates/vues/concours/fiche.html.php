

<ul class="list-group mb-3">
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>ID</strong></div>
            <div class="col-6"><?= $concours->getId() ?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Nom du concours</strong></div>
            <div class="col-6"><?= $concours->getNom()  ?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Date de début</strong></div>
            <div class="col-6"><?= $concours->getDate_debut()?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Date de fin</strong></div>
            <div class="col-6"><?= $concours->getDate_fin()?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Projet en lien</strong></div>
            <div class="col-6">
                <?php if ($concours->getProjet()) :?>
                <a class="text-decoration-none text-dark " href="<?= lien("projet","detail",$concours->getProjet()->getId()) ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                    </svg>
                    <?= $concours->getProjet()->getNom() ?>
                </a>
                <?php else : ?>
                    <span>Aucun projet relié a ce concours</span>
                <?php endif ?>
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Participants</strong></div>
            <?php if ($concours->getParticipants()) :?>
            <div class="col-6">
            <a class="text-decoration-none text-dark " href="<?= lien("concours","participation",$concours->getId()) ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                </svg>
                Voir les <?= count($concours->getParticipants())?>  participants (wip)
            </a>
            </div>
            <?php else : ?>
                <span>Aucun Participant</span>
            <?php endif ?>
        </div>
    </li>
</ul>



<div class="d-flex justify-content-between">
    <a href="<?= lien('concours') ?>" class="btn btn-primary">
        retour a la liste
    </a>
    <a href="<?= lien('concours',"modifier",$concours->getId()) ?>" class="btn btn-primary">
        modifier
    </a>
    <a id="suppr" class="btn btn-danger">
        supprimer
    </a>


</div>

<div class="popup closed" id="popup">
        <p>Vous êtes sur le point de supprimer <?= $concours->getNom()?> </p>
        <p>En etes vous sur ?</p>
        <div class="df">
            <a class="btn btn-primary" id="close">non</a>
            <a href="<?= lien("concours","supprimer",$concours->getId()) ?>" id="delete" class="btn btn-danger">oui</a>
            
        </div>
</div>