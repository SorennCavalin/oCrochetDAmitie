

<form method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label for="nom">Nom du projet</label>
    <input type="text" name="nom" class="form-control" id="nom" value="<?= $nom ?? "" ?>" >
  </div>
  <div class="form-row">
     <div class="form-group col-5">
     <label for="date_debut">Date de lancement du projet</label>
     <input type="date" class="form-control" value="<?= $date_debut ?? "" ?>" name="date_debut" id="date_debut" >
     </div>
     <div class="form-group offset-1 col-5">
     <label for="date_fin">Date de fin du projet</label>
     <input type="date" name="date_fin" value="<?= $date_fin ?? "" ?>" class="form-control" id="date_fin" >
     </div>
  </div>
  
  <div class="form-group">
    <label for="page">Page html du projet</label>
    <textarea name="page" class="form-control" rows="10" placeholder="//code du projet"></textarea>
  </div>


  <button type="submit" class="btn btn-primary">Submit</button>
</form>
