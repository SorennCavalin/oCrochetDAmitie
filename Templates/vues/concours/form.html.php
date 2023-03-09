<form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="nom">Nom du concours</label>
    <input type="text" name="nom" class="form-control" id="nom" value="<?= $nom ?? "" ?>" required>
    <small class="text-muted">Plusieurs concours ne peuvent pas avoir le même nom</small>
  </div>

  <div class="mb-3">
  <label for="projet">Projet en lien</label>
    <select name="projet" class="form-select" id='projet' placeholder="role" >
      <option hidden value='0' selected> Choisissez le projet en lien avec le concours</option>
      <?php foreach($projets as $projet) : ?>
      <option <?= (isset($select) && ($select === $projet->getId())) ? "selected" : ""?> value="<?=$projet->getId() ?>"><?= $projet->getNom() ?></option>
      <?php endforeach ?>
    </select>
    <small class="text-muted">Les projet sont affichés par dates décroissantes</small> 
    <br>
    <small class="text-muted">Les dates du concours seront automatiquement alignées avec celles du projet selectionné</small><br>
    <small class="text-muted">Liaison optionnelle</small>
  </div>


  <div class="row">
     <div class="mb-3 col">
     <label for="date_debut">Date de lancement du concours</label>
     <input type="date" class="form-control" value="<?= $date_debut ?? "" ?>" name="date_debut" id="date_debut" required>
     </div>
     <div class="mb-3 col">
     <label for="date_fin">Date de fin du concours</label>
     <input type="date" name="date_fin" value="<?= $date_fin ?? "" ?>" class="form-control" id="date_fin" required>
     </div>
  </div>


  <button type="submit" class="btn btn-primary">Submit</button>
</form>


<script>

    
  

  window.addEventListener("load",() => {
    let dateDebut = $('#date_debut');
    let dateFin = $('#date_fin');
    let dateDebutProjet;
    let dateFinProjet;
    $('#projet').change((e) => {
      var $projet = e.target.value

      $.post(
        "http://127.0.0.1/oCrochetDAmitie/concours/recup/" + $projet,
        {

        },
        (data)  => {
          var dates = data.split(" ");

          $('input[name="date_debut"]').val(dates[0]);
          dateDebutProjet = dates[0];
          dateDebut.attr("max",dates[1]);

          $('input[name="date_fin"]').val(dates[1]);
          dateFinProjet = dates[1];
          dateFin.attr("min",dates[0]);

        }
      )
    })
    dateDebut.on("change", () => {
      dateFin.attr("min",dateDebut.val())
    });
    dateFin.on("change", (e) => {
      dateDebut.attr("max",dateFin.val())
    })


  })
</script>
