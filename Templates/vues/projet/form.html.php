

<form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="nom">Nom du projet</label>
    <input type="text" name="nom" class="form-control" id="nom" value="<?= $nom ?? "" ?>" >
    <small class="text-muted">Plusieurs projets ne peuvent pas avoir le même nom</small>
  </div>
  <div class="row mb-3">
     <div class="col-5">
     <label for="date_debut">Début du projet</label>
     <input type="date" class="form-control" value="<?= $date_debut ?? "" ?>" name="date_debut" id="date_debut" >
     <small class="text-muted">La date de début doit être plus vieille que celle de fin</small>
     </div>
     <div class="offset-1 col-5">
     <label for="date_fin">Fin du projet</label>
     <input type="date" name="date_fin" value="<?= $date_fin ?? "" ?>" class="form-control" id="date_fin" >
     <small class="text-muted">La date de fin doit être plus récente que celle de début</small>
     </div>
     
  </div>
  
  <div class="mb-3">
    <label for="page">Page html du projet</label>
    <textarea name="page" class="form-control mb-3" rows="10" placeholder="//code pour l'affichage projet"><?= $page ?? "" ?></textarea>
    <button class='btn btn-primary' type="button" id='recupCss'>Récuperer le template</button>
  </div>


  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<script>
 window.addEventListener("load",() => {
    let dateDebut = $('#date_debut');
    let dateFin = $('#date_fin');

    dateDebut.on("change", () => {
      dateFin.attr("min",dateDebut.val())
    });
    dateFin.on("change", (e) => {
      dateDebut.attr("max",dateFin.val())
    })

    $('#recupCss').on('click', () => {
      navigator.clipboard.writeText("text copié");
    })
})
</script>
