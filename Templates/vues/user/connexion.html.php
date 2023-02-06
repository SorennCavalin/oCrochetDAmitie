<form action="" method="post">

    <h1>connexion</h1>

    <div class="input_label">
        <label for="email">E-mail</label>
        <input type="email" name="email" id='email' placeholder="email@email.com">
    </div>
    <div class="input_label">
        <label for="mdp">Mot de passe</label>
        <input type="password" name="mdp" id="mdp" placeholder="">
        <div class="loupe">
            &#x1F50D;
        </div>
    </div>

    <button type="submit" class="unclicked">Se connecter</button>

    <p class='inscription'>Vous n'avez pas de compte? <br> <a href="<?= lien("user","inscription") ?>">inscrivez-vous ici</a></p>
    
</form>