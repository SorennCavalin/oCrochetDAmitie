<div class="container">
    <div class="banniere">
        <img src="<?= imgLink("Projets.jpg")?>" alt="Projets">
    </div>
    <h1> Lorem ipsum dolor sit, amet consectetur adipisicing elit. Molestiae, saepe commodi eos nostrum ducimus tempora hic adipisci maxime veniam! Mollitia aliquam cum nihil repellendus suscipit iure minus deleniti optio ratione? </h1>




    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti nesciunt sint porro velit ipsa, mollitia, hic ex error illum necessitatibus consequuntur est doloremque esse. Dolorum cum omnis perferendis dolore aspernatur!</p>

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