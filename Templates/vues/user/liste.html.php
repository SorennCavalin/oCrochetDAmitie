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
            <th>nom</th>
            <th>prenom</th>
            <th>email</th>
            <th>telephone</th>
            <th>adresse</th>
            <th>inscription</th>
            <th>role</th>
            <th>actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach($users as $user): ?>
            <tr>
                <td>
                    <?= $user->getId() ?>
                </td>

                <td>
                    <?= $user->getNom() ?>
                </td>
                <td>
                    <?= $user->getPrenom() ?>
                </td>
                <td>
                    <?= $user->getEmail() ?>
                </td>
                <td>
                    <?= $user->getTelephone() ?>
                </td>
                <td >
                    <?= $user->getregion() . ", " . ($user->getAdresse() ?? "") ?>
                </td>
                <td>
                    <?= date("d/m/Y",strtotime($user->getDate_inscription())) ?>
                </td>
                <td>
                    
                    <?php if ($user->getRole() === "ROLE_ADMIN") : ?>
                        Admin 
                    <?php else :?>
                        Bénévole
                    <?php endif ?>
                </td>
                <td>
                    <a href="<?= lien("user","detail",$user->getId())  ?>" class="btn btn-primary">
                        details
                    </a>
                    <a href="<?=lien("user","modifier",$user->getId()) ?>" class="btn btn-secondary">
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



<div class="d-flex justify-content-around mb-3">
    <a href="<?= lien("user", "afficher",$prev)  ?>" class="btn btn-primary <?= $prev < 1 ? 'disabled' : '' ?>">
        précédent
    </a>
    <a href="<?=lien("user", "afficher",$next)  ?>" class="btn btn-primary <?=  $pageMax ? 'disabled' : '' ?>">
        suivant
    </a>
</div>


<div class="popup closed" id="popup">
    <p>Vous êtes sur le point de supprimer l'utilisateur : '<span id='cible'></span>' </p>
    <p>En etes vous sur ?</p>
    <div class="df">
        <div class="btn btn-primary" id="close">non</div>
        <a id="delete" class="btn btn-danger">oui</a>
    </div>
</div>

<script>
    var lien = "<?= lien('user',"supprimer") ?>";
</script>