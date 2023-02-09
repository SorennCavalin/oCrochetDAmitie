

<form action="" method="post">
  <div class="form-row mt-2">
    <div class="form-group col">
      <label for="email">Email</label>
      <input type="email" name="email" class="form-control" id="email" placeholder="email@email.com" required value='<?= $email ?? ''?>'>
    </div>
    <div class="form-group col div_tool">
      <label for="mdp">Mot de passe</label>
      <input type="password" name="mdp" class="form-control" id="mdp" placeholder="mot de passe" >
      <div class="tool-tip top slideIn">Sans espace, un caractère spécial ($*_-!?.), une majuscule, un chiffre et de 5 à 16 caractères</div>
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="nom">Nom</label>
      <input type="text" name="nom" class="form-control" id="nom" placeholder="votre nom" required value='<?= $nom ?? ''?>'>
    </div>
    <div class="form-group col-md-6">
      <label for="prenom">Prenom</label>
      <input type="text" name="prenom" class="form-control" id="prenom" placeholder="votre prenom" required value='<?= $prenom ?? ''?>'>
    </div>
  </div>
        <div class="form-group">
        <label for="telephone">Telephone</label>
        <input type="tel" class="form-control" name='telephone'id="telephone" placeholder="+336 ou 06" pattern="^[+]?[0-9]{9,12}$" title='Numero de telephone commençant en + ou avec le 0. ' value='<?= $telephone ?? ''?>'>
    </div>
  <div class="form-row">
    <div class="form-group col-md-6 position">
      <label for="adresse">Adresse</label>
      <input type="text" class="form-control" autocomplete='off' name="adresse" id="adresse" value='<?= $adresse ?? ''?>'>
      
    </div>
    <div class="form-group col-md-2">
      <label for="departement">Code_postal</label>
      <input type="text" id="departement" name="departement" id="departement" class="form-control" pattern="^[0-9]{5}" title="ex: 94200" required value='<?= $departement ?? ''?>'>
      </select>
    </div>
    <div class="form-group col-md-4">
      <label for="region">Région</label>
      <input type="text" class="form-control" name="region" id="region" required value='<?= $region ?? ''?>'>
    </div>
  </div>
  <div class="form-row roles">
  <div class="form-group col-md-4">
    <label for="role"></label>
    <select name="role" class="form-control" id='role'>
      <option <?= ( isset($role) && $role !== "ROLE_ADMIN" ? "selected" : "") ?> value="ROLE_BENEVOLE">Role bénévole </option>
      <option <?= (  isset($role) && $role === "ROLE_ADMIN" ? "selected" : "") ?> value="ROLE_ADMIN">Role admin </option>
    </select>
  </div>  
 </div> 
 <div class='centrer'>
   <button type="submit" class="btn btn-primary">enregistrer</button>
 </div>
 
</form>