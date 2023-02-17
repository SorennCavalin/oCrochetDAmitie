<div class="container">
    <div class="banniere">
        <img src="<?= imgLink("Projets.jpg")?>" alt="Projets">
    </div>
    <h1> Aidons ensemble les plus démunis ! </h1>




    <p>ici vous pouvez voir les derniers projets menés par notre association ansi que revoir ceux qui sont déjà cloturés</p>

    <?php if ($pause) : ?>
        <p>Il n'y a aucun projet encore en activité mais voici les 3 derniers</p>
    <?php else : ?>
        <h4>Voici le ou les dernier projets encore en cours</h4>
    <?php endif ?>
    <div class="projets">
        <?php foreach ($projets as $projet) :?>
            <hr>
            <div class="projet ferme">
            <h2 class="projet_titre"><?= $projet->getNom() ?> <div class="arrow"></div></h2>
            <div class="projet_contenu"><?= $projet->getPage() ?></div>
            </div>
        <?php endforeach?>
        <hr>
    </div>
</div>