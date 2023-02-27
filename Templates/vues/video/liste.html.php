<?php
    $prev = $page - 1;
    $next = $page + 1;
?>
<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>nom</th>
            <th>plateforme</th>
            <th>type</th>
            <th>actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($videos as $video): ?>
            <tr>
                <td>
                    <?= $video->getId() ?>
                </td>

                <td>
                    <?= $video->getNom() ?>
                </td>
                <td>
                    <?= $video->getPlateforme()?>
                </td>
                <td>
                    <?= $video->getType() ?>
                </td>
                
                <td>
                    <a href="<?= lien("video","detail",$video->getId())  ?>" class="btn btn-primary">
                        details
                    </a>
                    <a href="<?=lien("video","modifier",$video->getId()) ?>" class="btn btn-secondary">
                        modifier
                    </a>
                    <a href="<?= lien("video","supprimer",$video->getId()) ?>" class="btn btn-danger">
                        supprimer
                    </a>

                    
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<div class="d-flex justify-content-around">
    <a href="<?= lien("video", "afficher",$prev)  ?>" class="btn btn-primary <?= $prev < 1 ? 'disabled' : '' ?>">
        précédent
    </a>
    <a href="<?=lien("video", "afficher",$next)  ?>" class="btn btn-primary <?=  $pageMax ? 'disabled' : '' ?>">
        suivant
    </a>
</div>

<div class="popup closed" id="popup">
    <p>Vous êtes sur le point de supprimer le projet : '<span id='cible'></span>'</p>
    <p>En etes vous sur ?</p>
    <div class="df">
        <div class="btn btn-primary" id="close">non</div>
        <a id="delete" class="btn btn-danger">oui</a>
    </div>
</div>


<script>
    var lien = "<?= lien('video',"supprimer") ?>";
</script>