<form method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label for="nom">Nom du concours</label>
    <input type="text" name="nom" class="form-control" id="nom" value="<?= $nom ?? "" ?>" required>
    <small class="text-muted">Plusieurs concours ne peuvent pas avoir le même nom</small>
  </div>

  <div class="form-group">
  <label for="projet">Projet en lien</label>
    <select name="projet" class="form-control" id='projet' placeholder="role" >
      <option hidden value='0' selected> Choisissez le projet en lien avec le concours</option>
      <?php foreach($projets as $projet) : ?>
      <option <?= (isset($select) && ($select === $projet->getId())) ? "selected" : ""?> value="<?=$projet->getId() ?>"><?= $projet->getNom() ?></option>
      <?php endforeach ?>
    </select>
    <small class="text-muted">Les projet sont affichés par dates décroissantes</small> 
    <br>
    <small class="text-muted">Les dates du concours seront automatiquement alignées avec celles du projet selectionné</small>
  </div>


  <div class="form-row">
     <div class="form-group col-5">
     <label for="date_debut">Date de lancement du concours</label>
     <input type="date" class="form-control" value="<?= $date_debut ?? "" ?>" name="date_debut" id="date_debut" required>
     </div>
     <div class="form-group offset-1 col-5">
     <label for="date_fin">Date de fin du concours</label>
     <input type="date" name="date_fin" value="<?= $date_fin ?? "" ?>" class="form-control" id="date_fin" required>
     </div>
  </div>


  <button type="submit" class="btn btn-primary">Submit</button>
</form>


<script>

    
  

  window.addEventListener("load",() => {
    $('#projet').change((e) => {
      var $projet = e.target.value
      var compte = 1
      console.log($projet)

      $.post(
        "http://127.0.0.1/oCrochetDAmitie/concours/recup/" + $projet,
        {
          
        },
        (data)  => {
          var dates = data.split(" ") ;
          $('input[name="date_debut"]').val(dates[0])
          $('input[name="date_fin"]').val(dates[1])
        }
      )
    })


  })
</script>
