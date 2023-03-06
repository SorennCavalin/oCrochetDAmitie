<?php
    $prev = isset($page) ? $page - 1 : 0;
    $next = isset($page) ? $page + 1 : 0;
?>

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>nom</th>
            <th>date de debut</th>
            <th>date de fin</th>
            <th>actif</th>
            <th>actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($projets as $projet): ?>
            <tr>
                <td><?= $projet->getId() ?></td>

                <td>
                    <?= $projet->getNom() ?>
                </td>
                <td>
                    <?= $projet->getDate_debut() ?>
                </td>
                <td>
                    <?= $projet->getDate_fin() ?>
                </td>
                <td>
                    <?= ($projet->getDate_fin() > date("Y-m-d")) ? "ce projet est encore en cours" : "ce projet est terminé et n'est plus visible" ?>
                </td>
                <td>
                    <a href="<?= lien("projet","detail",$projet->getId())  ?>" class="btn btn-primary">
                        afficher
                    </a>
                    <a href="<?=lien("projet","modifier",$projet->getId()) ?>" class="btn btn-secondary">
                        modifier
                    </a>
                    <a  class="btn btn-danger">
                        supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (!isset($reponse)) :?>
    <div class="d-flex justify-content-around">
        <a href="<?= lienAdmin("projet", "afficher", $prev)  ?>" class="btn btn-primary <?= $prev < 1 ? 'disabled' : '' ?>">
            précédent
        </a>
        <a href="<?= lienAdmin("projet", "afficher", $next)  ?>" class="btn btn-primary <?=  $pageMax ? 'disabled' : '' ?>">
            suivant
        </a>
    </div>
<?php endif ?>

<div class="popup closed" id="popup">
    <p>Vous êtes sur le point de supprimer le projet : <span id='cible'></span> </p>
    <p>En etes vous sur ?</p>
    <div class="df">
        <div class="btn btn-primary" id="close">non</div>
        <a href="" id="delete" class="btn btn-danger">oui</a>
    </div>
</div>

<script>
    var lien = "<?= lien('projet',"supprimer") ?>";
</script>