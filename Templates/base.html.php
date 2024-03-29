<?php use Modeles\Session;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O'Crochet d'amitié</title>

        
    <link rel="stylesheet" href="/oCrochetDAmitie/Assets/app.css">
    
    <?php if (isset($css)) :?>
        <link rel='stylesheet' href='/oCrochetDAmitie/Assets/css/<?= "$css" ?>.css'>
    <?php endif ?>
    <link rel='stylesheet' href='/oCrochetDAmitie/Assets/css/tooltip.css'>


    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <script src="/oCrochetDAmitie/Assets/app.js"></script>

    <?php if (isset($js)) :?>
        <script type='module' src="/oCrochetDAmitie/Assets/js/<?= $js ?>.js"></script>
    <?php endif ?>


</head>
<body>
<?php include "nav.html.php" ?>
<?php include "messages.html.php" ?>





