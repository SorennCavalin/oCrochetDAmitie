

<form action="" method="post">
    <div class="mb-3">
      <label for="nom">nom</label>
      <input type="text" name="nom" class="form-control" id="nom" placeholder="nom de la video" required value='<?= $nom ?? ''?>'>
      <small class="text-muted">Plusieurs vidéos ne peuvent pas avoir le même nom</small>
    </div>

    <div class="mb-3">
      <label for="lien">lien</label>
      <input type="url" name="lien" class="form-control" id="lien" placeholder="url de la video externe" pattern="https://.*" required value='<?= $lien ?? ''?>'>
    </div><div class="mb-3">
      <label for="type">type</label>
      <select name="type" class="form-select" id="type" required>
        <option value="live" <?= ( (isset($type) && $type === "live") ? "selected" : "")?>>Live</option>
        <option value="présentation" <?= ( (isset($type) && $type === "presentation") ? "selected" : "")?>>Présentation</option>
        <option value="tuto" <?= ( (isset($type) && $type === "tuto") ? "selected" : "")?>>Tuto</option>
        <option value="autre" <?= ( (isset($type) && $type === "autre") ? "selected" : "")?>>Autre</option>
      </select>
      
    </div>
    
    <button type="submit" class="btn btn-primary mt-3">enregistrer</button>
 
</form>