
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O'Crochet d'amitié</title>

  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
        
    <link rel="stylesheet" href="/oCrochetDAmitie/Assets/css/popup_suppr.css">
    <link rel="stylesheet" href="/oCrochetDAmitie/Assets/css/tooltip.css">
    <link rel="stylesheet" href="/oCrochetDAmitie/Assets/css/recherche.css">
    
    <script src="/oCrochetDAmitie/Assets/js/popup_suppr.js"></script>

    <?php if (isset($js)) :?>
        <script type='module' src="/oCrochetDAmitie/Assets/js/<?=$js?>.js"></script>
    <?php endif ?>

    <script type='module' src="/oCrochetDAmitie/Assets/js/Recherche.js"></script>


</head>
<body>
<div class="container">

<?php 
    include "navbar_admin.html.php";
    include "messages.html.php";
?>








