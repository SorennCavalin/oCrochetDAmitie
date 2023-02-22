<?php 
require "Services/init.php";



//Tout est géré ici depuis les données extraites de l'url

//immaginons https://ocrochetdamitie.fr?controller=accueil


//Récupère les informations envoyées dans l'url


$controller = $_GET["controleur"] ?? "accueil";
$methode = $_GET["methode"] ?? "afficher";
$id = $_GET["id"] ?? null;
$page = $_GET["page"] ?? null;
//Création de la classe qui sera appellée selon l'url
//selon l'exemple plus haut la classe sera Controller\AccueilController
//ucFirst met la première lettre du string en paramètre en majuscule 
$classControleur = "Controllers\\" . ucfirst($controller) . "Controller";


$controleur = new $classControleur;

//lancement de la fonction pour l'affichage utilisateur
//selon l'url exemple la methode n'est pas designée et sera donc par défault affichage 
$controleur->$methode($id,$page);





