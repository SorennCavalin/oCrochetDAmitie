<?php use Modeles\Session; 



if(Session::getItemSession("messages")):?>
    <!-- div contenant les messages front -->
    <div class="messages">
    <!-- sépare l'array message en plusieurs groupes selon le type (danger, success, etc...) et pour chaque type affiche le nombre de message de l'array messages (x message success, x messages danger, etc ...) -->
    <?php foreach (Session::getMessages() as $type => $messages) : ?>
        
        <?php foreach ($messages as $message): ?>

            <div class="alert alert-<?= $type ?>">
                <?= $message ?>
            </div>

        <?php endforeach ?>

    <?php endforeach ?>
<!-- ferme la div message -->
    </div>
<?php endif ?>