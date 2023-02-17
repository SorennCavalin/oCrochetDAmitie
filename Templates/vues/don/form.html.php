
<form id="test"method="POST" enctype="multipart/form-data">
  <div class="mb-3">

  <label for="type">Type de don</label>
    <select name="type" class="form-select" id='type' placeholder="role">
      <option hidden> Choisissez si le don est un envoi ou une réception</option>
      <option <?= ( (isset($type) && $type === "reception") ?"selected" : "") ?> id='reception' value="reception">Réception</option>
      <option <?= ( (isset($type) && $type === "envoi") ? "selected" : "") ?> id='envoi' value="envoi">Envoi vers un organisme</option>
    </select>
  </div>

  <div class="mb-3" id="organisme">
    <label for="">Organisme</label>
    <input type="test" class="form-control" value="<?=(isset($type) && $type === "envoi") ? $cible : "" ?>" name="organisme"  >
  </div>

  <div class="mb-3" id="donataire" >
    <label for="donataire">Donataire</label>
    <input type="text" name="donataire" value="<?=(isset($type) && $type === "reception") ? $cible : "" ?>" class="form-control" >
  </div>
  
  <div class="mb-4" id="date">
    <label for="date" id="label_date"></label>
    <input name="date" class="form-control" type="date" value='<?= $date ?? "" ?>'>
  </div>
  <!-- initialise nb pour les tours de boucles si aucun détails-->
  <!-- foreach dans if pour ne pase avoir d'erreur lors de la création d'un don (aucune variable récupérée par le controlleur) -->
  <!-- quand nb atteint quantite un attr dernier apparait avec la valeur de nb pour que js puisse prendre la suite des details seul -->
  <?php $nb=0;  if (isset($details)) : foreach($details as $detail) : $nb++?>
    <label for="nom" id="label_detail<?=$nb?>">don n°<?=$nb?></label>
   
        <div class='row mb-2' id="detail<?=$nb?>" <?= ($nb === $quantite) ? "dernier='$nb'" : ""  ?>>
            <div class="col"> 
                <input name="donDetails[details<?=$nb?>][nom]" class="form-control noms" type="text" id='nom_don<?=$nb?>' value='<?=$detail->getNom()?>'>
            </div> 
            <div class="col" > 
                <input name="donDetails[details<?=$nb?>][qte]" class="form-control qtes" type="text" id='qte_don<?=$nb?>' value='<?=$detail->getQte()?>'>
            </div>
        </div>
  <?php endforeach; endif?>

  <div class="form-group"><button class="btn btn-primary m-1 mt-2" id="plus" type="button">ajouter un détail au don</button></div>
  
  <button type="submit" class="btn btn-primary m-1" id="conf">Submit</button>
</form>


<div></div>
