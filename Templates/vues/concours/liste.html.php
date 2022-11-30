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
            <th>Nom</th>
            <th>date de debut</th>
            <th>date de fin</th>
            <th>projet en lien</th>
            <th>action</th>
        </tr>
    </thead>

    <tbody>
        <!-- vérification de $concours pour afficher correctement les résultats lors de la recherche qui renvoi un array d'array -->
        <?php  if( is_array($concours[0])) : foreach($concours as $concoursArray): foreach($concoursArray as $concoursResultat) :  ?>
            <tr>
                <td>
                    <?= $concoursResultat->getId() ?>
                </td>
                <td>
                    <?= $concoursResultat->getNom() ?>
                </td>
                <td>
                    <?= $concoursResultat->getDate_fin() ?>
                </td>
                <td>
                    <?= $concoursResultat->getDate_debut() ?>
                </td>
                <td>
                    
                     <a class="text-decoration-none text-dark " href="<?= lien("projet","details",$concoursResultat->getProjet()->getId()) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                        </svg>
                        <?= $concoursResultat->getProjet()->getNom() ?>
                    </a>
                </td>
                <td>
                    <a href="<?= lien("concours","detail",$concoursResultat->getId())  ?>" class="btn btn-primary">
                        details
                    </a>
                    <a href="<?=lien("concours","modifier",$concoursResultat->getId()) ?>" class="btn btn-secondary">
                        modifier
                    </a>
                    <a href="<?= lien("concours","supprimer",$concoursResultat->getId()) ?>" class="btn btn-danger">
                        supprimer
                    </a>

                    
                </td>
            </tr>
        <?php endforeach; endforeach; else :?>

        <?php foreach($concours as $concours): ?>
            <tr>
                <td>
                    <?= $concours->getId() ?>
                </td>
                <td>
                    <?= $concours->getNom() ?>
                </td>
                <td>
                    <?= $concours->getDate_fin() ?>
                </td>
                <td>
                    <?= $concours->getDate_debut() ?>
                </td>
                <td>
                    
                     <a class="text-decoration-none text-dark " href="<?= lien("projet","details",$concours->getProjet()->getId()) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                        </svg>
                        <?= $concours->getProjet()->getNom() ?>
                    </a>
                </td>
                <td>
                    <a href="<?= lien("concours","detail",$concours->getId())  ?>" class="btn btn-primary">
                        details
                    </a>
                    <a href="<?=lien("concours","modifier",$concours->getId()) ?>" class="btn btn-secondary">
                        modifier
                    </a>
                    <a href="<?= lien("concours","supprimer",$concours->getId()) ?>" class="btn btn-danger">
                        supprimer
                    </a>

                    
                </td>
            </tr>
        <?php endforeach; endif ?>
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
    <p>Vous êtes sur le point de supprimer le projet : <span id='cible'></span> </p>
    <p>En etes vous sur ?</p>
    <div class="df">
        <div class="btn btn-primary" id="close">non</div>
        <a id="delete" class="btn btn-danger">oui</a>
    </div>
</div>


<script>
    var lien = "<?= lien('don',"supprimer") ?>";
</script>