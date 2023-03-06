

<form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="nom">Nom du projet</label>
    <input type="text" name="nom" class="form-control" id="nom" value="<?= $nom ?? "" ?>" >
    <small class="text-muted">Plusieurs projets ne peuvent pas avoir le même nom</small>
  </div>
  <div class="row">
     <div class="mb-3 col-5">
     <label for="date_debut">Début du projet</label>
     <input type="date" class="form-control" value="<?= $date_debut ?? "" ?>" name="date_debut" id="date_debut" >
     </div>
     <div class="mb-3 offset-1 col-5">
     <label for="date_fin">Fin du projet</label>
     <input type="date" name="date_fin" value="<?= $date_fin ?? "" ?>" class="form-control" id="date_fin" >
     </div>
  </div>
  
  <div class="mb-3">
    <label for="page">Page html du projet</label>
    <textarea name="page" class="form-control" rows="10" placeholder="//code pour l'affichage projet"><?= $page ?? "" ?></textarea>
  </div>


  <button type="submit" class="btn btn-primary">Submit</button>
</form>
