<div class="recherche" id="div_recherche">
    <form id='recherche' action="<?= lien("accueil","recherche") ?>">
        <h4 class="text-center mb-4">Vous allez faire une recherche spécifique. <br> Nous avons quelques question à poser pour vous rendre le meilleur résultat</h4>
        <div class="form-group table">
            <label for="table">Quel type recherchez-vous ?</label>
            <select id="table" class="form-control" name="table">
                <option value='user'>utilisateur</option>
                <option value="projet">projet</option>
                <option value="concours">concours</option>
                <option value="don">dons</option>
                <option value="video">videos</option>
            </select>
        </div>
        <div class="form-group selecteur">
            <label for="selecteur">par quoi rechercher ?</label>
            <select id="selecteur" class="form-control" name='selecteur'>
                <option value="id">Identifiant</option>
                <option value="Nom">Nom</option>
                <option value="Email">Email</option>
                <option value="Role">Role</option>
                <option value="Telephone">Telephone</option>
                <option value="Adresse">Adresse</option>
                <option value="Prenom">Prenom</option>
                <option value="date_inscription">Date d'inscription</option>
            </select>
        </div>

        <div class='form-group' id='text_recherche'>
            <label for="">Entrez votre recherche</label>
            <input class='form-control' type="number" id='text_search' name='search'>
        </div>

        <div class="form-group classement">
            <label id="change">Par quoi les classer ?</label>
            <select id="classement" class="form-control" name="classement" disabled>
                <option selected> Ne pas classer </option>
                <option value="id">Identifiant</option>
                <option value="Nom">Nom</option>
                <option value="Role">Role</option>
                <option value="Prenom">Prenom</option>
                <option value="date_inscription">Date d'inscription</option>
            </select>
        </div>

        <div class="form-group limit">
            <label for="classement">choisir une limite ?</label>
            <input type="range" class="form-control-range" id="limit" name="limit" disabled>
            <p id="maximum" class="text-center"></p>
        </div>

        <button type="submit" class="btn btn-primary">lancer la recherche</button>
        <button id="close2" class="btn btn-danger"> fermer </button>
    </form>
</div>