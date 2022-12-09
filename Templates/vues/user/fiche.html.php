<?php 

// echo $user->getParticipants();

?>

<ul class="list-group mb-3">
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>ID</strong></div>
            <div class="col-6"><?= $user->getId() ?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Nom prenom</strong></div>
            <div class="col-6"><?= $user->getNom() . " " .  $user->getPrenom()?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>E-mail</strong></div>
            <div class="col-6"><?= $user->getemail() ?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Adresse</strong></div>
            <div class="col-6"><?= $user->getRegion() . ", " . $user->getDepartement() . (($user->getAdresse() !== null) ? ", " . $user->getAdresse() : ", adresse exacte inconnue") ?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Téléphone</strong></div>
            <div class="col-6"><?= $user->getTelephone() ?? "cet abonné n'a pas donner son numero de téléphone" ?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Date d'inscription</strong></div>
            <div class="col-6"><?= $user->getDate_inscription() ?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Roles</strong></div>
            <div class="col-6">
                <?php if(in_array("ROLE_ADMIN",$user->getRoles())) : ?>
                    <?= "Administrateur" ?>
                <?php else : ?>
                    <?= "Bénévole" ?>
                <?php endif ?>
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Identité</strong></div>
            <div class="col-6"><?= $user->getprenom() . " " . $user->getnom() ?></div>
        </div>
    </li>

</ul>



<div class="d-flex justify-content-between">
    <a href="<?= lien('user') ?>" class="btn btn-primary">
        retour a la liste
    </a>
    <a href="<?= lien('user',"modifier",$user->getId()) ?>" class="btn btn-primary">
        modifier
    </a>
    <a id="suppr" class="btn btn-danger">
        supprimer
    </a>


</div>

<div class="popup closed" id="popup">
        <p>Vous êtes sur le point de supprimer <?= $user->getPrenom() ?> </p>
        <p>En etes vous sur ?</p>
        <div class="df">
            <a class="btn btn-primary" id="close">non</a>

            <a href="<?= lien("user","supprimer",$user->getId()) ?>" id="delete" class="btn btn-danger">
                oui
            </a>
            
        </div>
</div>