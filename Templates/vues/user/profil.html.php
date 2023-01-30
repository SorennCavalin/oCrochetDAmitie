
<div class="profil">
    <h2><?= $user->getPrenom() . " " . $user->getNom() ?></h2>
    <div class="infos">
        <ul class='demi gauche'> 
            <li class="ul_titre"> <h4>Contacts</h4> </li> 
            <li class="li_label">email : <?= $user->getEmail() ?></li>
            <li class="li_label">téléphone : 
                <?php if($user->getTelephone()) : ?>
                    <?= $user->getTelephone() ?>
                <?php else : ?>
                    aucun numéro enregistré
                <?php endif ?>
            </li> 
        </ul>
        <div class='demi droite'>
            <h4 class="adresse_label">Adresse</h4>
            <?php if($user->getAdresse()) : ?>
                <p class="adresse"><?= $user->getAdresse() . ", " . $user->getDepartement(). ", " . $user->getRegion() ?></p>
            <?php else : ?>
                <p class="adresse"><?= $user->getDepartement(). ", " . $user->getRegion() ?></p>
            <?php endif ?>
        </div>
    </div>

    <div class='participation'>
        
        <div class='projets'>
            <h4>
                Participations au concours
            </h4>
            <?php if (count($user->getParticipations()) === 0) :?>
                <p> 
                    Aucune participation aux concours jusqu'a présent
                </p>
            <?php else : ?>
                <?php foreach ($user->getParticipations() as $participations) : ?>
                <p>
                    <?= "Vous avez participer à " . count($user->getParticipations()) . " concours" ?>
                    <br>
                    <a href="<?=  lien("user","mesConcours") ?>">voir mes participations</a> 
                </p>
            <?php endforeach; endif; ?>
            
        </div>

        <div class='dons'>

        </div>

    </div>

    <a class="modif" href="<?= lien("user", "edit") ?>" title="modifier son profil">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M144 272L128 384l112-16L436.7 171.3l-96-96L144 272zM512 96L416 0 363.3 52.7l96 96L512 96zM32 64H0V96 480v32H32 416h32V480 320 288H384v32V448H64V128H192h32V64H192 32z"/></svg>
    </a>
    <div class="liens">
            <a class="disconnect button" href="<?= lien("user","deconnexion") ?>" title="Déconnexion">
           Déconnexion
            </a>
    </div>
</div>

