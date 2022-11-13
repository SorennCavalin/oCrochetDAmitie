

<form id="test"method="POST" enctype="multipart/form-data" action="<?= lien("don","ajouterDetails") ?>">
  <div class="form-group">

  <label for="type">Type de don</label>
    <select name="type" class="form-control" id='type' placeholder="role">
      <option hidden selected> Choisissez si le don est un envoi ou une réception</option>
      <option <?= ( (isset($type) && $type === "reception") ?"selected" : "") ?> id='reception' value="reception">Réception</option>
      <option <?= ( (isset($type) && $type === "envoi") ? "selected" : "") ?> id='envoi' value="envoi">Envoi vers un organisme</option>
    </select>
  </div>

  <div class="form-group" id="organisme">
    <label for="">Organisme</label>
    <input type="test" class="form-control" value="<?= $organisme ?? "" ?>" name="organisme"  >
  </div>

  <div class="form-group" id="donataire" >
    <label for="donataire">Donataire</label>
    <input type="text" name="donataire" value="<?= $donataire ?? "" ?>" class="form-control" >
  </div>
  
  <div class="form-group" id="date">
    <label for="date" id="label_date"></label>
    <input name="date" class="form-control" type="date" >
  </div>
  <div class="form-group"><button class="btn btn-primary" id="plus" type="button">ajouter un détail au don</button></div>


  
  <button type="submit" class="btn btn-primary" id="conf">Submit</button>
</form>
