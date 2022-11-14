<?php use Modeles\Session;

 ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O'Crochet d'amitié</title>

  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
        
    <link rel="stylesheet" href="/oCrochetDAmitie/Assets/css/popup_suppr.css">
    <link rel="stylesheet" href="/oCrochetDAmitie/Assets/css/tooltip.css">
    
    <script src="/oCrochetDAmitie/Assets/js/popup_suppr.js"></script>

    <?php if (isset($js)) :?>
        <script src="/oCrochetDAmitie/Assets/js/<?= $js ?>.js"></script>
    <?php endif ?>

    <script src="/oCrochetDAmitie/Assets/js/recherche.js"></script>


</head>
<body>
<div class="container">

<?php 
include "navbar_admin.html.php";

    if(Session::getItemSession("messages")):
        foreach (Session::getMessages() as $type => $messages) :
            foreach ($messages as $message) :
?>

    <div class="alert alert-<?= $type ?>">
            <?= $message ?>
    </div>

<?php endforeach; endforeach; endif; ?>



<form action="<?= lien("accueil","recherche") ?>" class="recherche">
    <div class="row">
        <div class="form-group col">
            <label for="table">Quel type recherchez-vous ?</label>
            <select id="table" class="form-control" name="table">
                <option value='user'>utilisateur</option>
                <option value="projet">projet</option>
                <option value="concours">concours</option>
                <option value="don">dons</option>
                <option value="video">videos</option>
            </select>
        </div>
        <div class="form-group col">
            <label for="selecteur">par quoi rechercher ?</label>
            <select id="selecteur" class="form-control">
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
    </div>
    <div class="form-group">
        <div>
            <label id="change">Les classer ?</label>
        </div>
      <div class="form-check form-check-inline" id="check">
        <div id="checked">
            <input class="form-check-input" type="checkbox" id="oui">
            <label class="form-check-label" for="oui" >oui</label>
        </div>
      </div>
    </div>
    <div class="row">
        <div class="form-group col-4">
            <label for="classement">Par quoi ?</label>
            <select id="classement" class="form-control">
                <option value='id'>identifiant</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="classement">nombre de reponse max.</label>
        <input type="range" class="form-control-range" id="formControlRange">
        <small class="maximum"></small>
    </div>
</form>


