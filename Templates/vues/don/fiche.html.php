<ul class="list-group mb-3">
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>ID</strong></div>
            <div class="col-6"><?= $don->getId() ?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong> <?= $don->getType() === "envoi" ? "Envoi à un organisme"  : "Reception de don"?></strong></div>
            <div class="col-6"><?= $don->getType() === "envoi" ? $don->getOrganisme() : "don de " . $don->getDonataire()  ?></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="d-flex">
            <div class="col-4"><strong>Date</strong></div>
            <div class="col-6"><?= $don->getDate()?></div>
        </div>
    </li>
    </ul>
    

<table class="table table-bordered table-hover text-center">
    <thead class="thead-dark">
        <tr>
        <th scope="col">nom de l'objet</th>
        <th scope="col">quantité</th>
        </tr>
    </thead>
        <tbody>
            <?php $compte = 0 ; foreach($don->getDetails() as $details) : ?>
                
                    <tr>
                    <th ><?= $details->getNom()?></th>
                    <td><?= $details->getQte() ?></td>
                    </tr>
            <?php endforeach ?>

            <tr class="thead-dark">
                <th >Total</th>
                <td> <?= count($don->getDetails()) . " objets différents, ".$don->getTaille()." dons en tout" ?></td>
            </tr>

        </tbody>
    </table>



<div class="d-flex justify-content-between">
    <a href="<?= lien('don') ?>" class="btn btn-primary">
        retour a la liste
    </a>
    <a href="<?= lien('don',"modifier",$don->getId()) ?>" class="btn btn-primary">
        modifier
    </a>
    <a id="suppr" class="btn btn-danger">
        supprimer
    </a>


</div>

<div class="popup closed" id="popup">
        <p>Vous êtes sur le point de supprimer <?= $don->getType() === "envoi" ? "l'envoi à " .$don->getOrganisme() : "le don de " . $don->getDonataire() ?> </p>
        <p>En etes vous sur ?</p>
        <div class="df">
            <a class="btn btn-primary" id="close">non</a>

            <a href="<?= lien("don","supprimer",$don->getId()) ?>" id="delete" class="btn btn-danger">
                oui
            </a>
            
        </div>
</div>