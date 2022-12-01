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
    <link rel="stylesheet" href="/oCrochetDAmitie/Assets/css/recherche.css">
    
    <script src="/oCrochetDAmitie/Assets/js/popup_suppr.js"></script>

    <?php if (isset($js)) :?>
        <script src="/oCrochetDAmitie/Assets/js/<?=$js?>.js"></script>
    <?php endif ?>

    <script src="/oCrochetDAmitie/Assets/js/recherche.js"></script>


</head>
<body>
<div class="container">

<?php 
 echo date('Y-m-d',strtotime("-3 months", strtotime("2022-12-01")));
    include "navbar_admin.html.php";
    // include "recherche.html.php";

if (isset($resultat)) {
    echo '<div class="alert alert-primary"> <?= $resultat ?> </div>';
}




    if(Session::getItemSession("messages")):
        foreach (Session::getMessages() as $type => $messages) :
            foreach ($messages as $message) :
?>

    <div class="alert alert-<?= $type ?>">
            <?= $message ?>
    </div>



<?php endforeach; endforeach; endif; ?>








