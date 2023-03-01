<div class="banner">
    <img src="<?= imgLink("dons.png") ?>" alt="" class="banner-img">
</div>
<!-- <script src="https://www.paypalobjects.com/api/checkout.js"></script> -->

<div class="don-main">
    <h1> Aidez nous à rendre le monde meilleur ! </h1>

    <div class="gauche">
        <p class="center-text">
            Les derniers dons reçus par l'association
        </p>
        <div class="dons">
            <?php foreach ($dons as $don) : ?>
                <div class="don">
                    <h5>
                        don de <?= is_numeric($don->getDonataire()) ? $don->getUser()->getPrenom() . " " . $don->getUser()->getNom() : $don->getDonataire()  ?>
                    </h5>
                    <p>
                        <?= $don->getTaille() > 1 ? "Un total de " . $don->getTaille() . " objets donnés!" : "1 objet donné " ?>
                    </p>
                    <p>Merci infiniment </p>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <div class="droite">
         <div class="form-don">
            <div class="paiement">
                <div id='test-paypal'> futur paypal</div>
            </div>
            <form action="<?= lien("don","ajouterAbonne") ?>" method="post">
                <label for="description">Décrivez-nous votre don et nos bénévoles comfirmeront la réception avec l'aide de votre description</label>
                <textarea name="description" id="description" cols="30" rows="10" placeholder='ex: bonjour je vous envoi un colis avec 10 pulls bébé et 2 bonnets'></textarea>
            </form>
        </div>
    </div>
   

<!-- 
    <script>
        paypal.Button.render({
      env: 'sandbox', // Ou 'production',
      commit: true, // Affiche le bouton  "Payer maintenant"
      style: {
        color: 'gold', // ou 'blue', 'silver', 'black'
        size: 'responsive' // ou 'small', 'medium', 'large'
        // Autres options de style disponibles ici : https://developer.paypal.com/docs/integration/direct/express-checkout/integration-jsv4/customize-button/
      },
      payment: function(data, actions) {
        /* 
         * Création du paiement
         */
        console.log('paiement créé');
      },
      onAuthorize: function(data, actions) {
        /* 
         * Exécution du paiement 
         */
      },
      onCancel: function(data, actions) {
        /* 
         * L'acheteur a annulé le paiement
         */
      },
      onError: function(err) {
        /* 
         * Une erreur est survenue durant le paiement 
         */
      }
    }, '#test-paypal');
    </script> -->
</div>






