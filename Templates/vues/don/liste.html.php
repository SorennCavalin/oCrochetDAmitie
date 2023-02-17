<?php
    $page = $_GET["id"] ?? 1;
    $prev = $page - 1;
    $next = $page + 1;
    // $next = $next <= $pageMax ? $next : $pageMax;
?>


<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>type</th>
            <th>destinataire/organisme</th>
            <th>taille</th>
            <th>date</th>
            <th>reception</th>
            <th>action</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($dons as $don): ?>
            <tr>
                <td>
                    <?= $don->getId() ?>
                </td>
                <td>
                    <?= $don->gettype() ?>
                </td>
                <td>
                    <?= $don->getDonataire() ? "donataire : " . $don->getDonataire() : "organisme : " . $don->getOrganisme() ?>
                </td>
                <td>
                    <?= $don->getTaille() . " objet " ?>
                </td>
                <td>
                    <?= $don->getDate() ?>
                </td>
                <td>
                    <input type="checkbox" name="reception" class="checkReception" idReception='<?= $don->getId() ?>' nom='<?= $don->getDonataire() ? $don->getDonataire() : $don->getOrganisme() ?>'  <?= $don->getReception() ? "checked" : "" ?>><span id='etat<?= $don->getId() ?>' class="pl-1"> <?= $don->getReception() ? "reçu" : "attente" ?></span>
                </td>
                <td>
                    <a href="<?= lien("don","detail",$don->getId())  ?>" class="btn btn-primary">
                        details
                    </a>
                    <a href="<?=lien("don","modifier",$don->getId()) ?>" class="btn btn-secondary">
                        modifier
                    </a>
                    <a href="<?= lien("don","supprimer",$don->getId()) ?>" class="btn btn-danger">
                        supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>



<div class="d-flex justify-content-around">
    <a href="<?= lien("don", "afficher",$prev)  ?>" class="btn btn-primary <?= $prev < 1 ? 'disabled' : '' ?>">
        précédent
    </a>
    <a href="<?=lien("don", "afficher",$next)  ?>" class="btn btn-primary <?=  $pageMax ? 'disabled' : '' ?>">
        suivant
    </a>
</div>


<div class="popup closed" id="popup">
    <p>Vous êtes sur le point de supprimer le don : '<span id='cible'></span>' </p>
    <p>En etes vous sur ?</p>
    <div class="df">
        <div class="btn btn-primary" id="close">non</div>
        <a id="delete" class="btn btn-danger">oui</a>
    </div>
</div>


<script>
    var lien = "<?= lien('don',"supprimer") ?>";
</script>