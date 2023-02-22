<?php

use Modeles\Session;

include "Services/autoload.php";

session_start();
define("ROOT", __DIR__ . "/../");
// include ROOT . "assets/app.css";

function lien($controleur, $methode = "afficher", $id = null, $page = null) {
    // return ROOT . "?controleur=$controleur&methode=$methode" . ($id ? "&id=$id" : "");
    return "/oCrochetDAmitie/$controleur/$methode" . ($id ? "/$id" : "") . ($page ? "/$page" : "");
    
};
function lienAdmin($controleur, $methode = "afficher", $id = null) {
    // return ROOT . "?controleur=$controleur&methode=$methode" . ($id ? "&id=$id" : "");
    if(Session::isAdmin()){
        return "/oCrochetDAmitie/$controleur/$methode" . "Admin" . ($id ? "/$id" : "");
    } else {
        return "/oCrochetDAmitie/$controleur/$methode" . ($id ? "/$id" : "");
    }
};

function isValid($date, $format = 'Y-m-d'){
    $dt = DateTime::createFromFormat($format, $date);
    return $dt && $dt->format($format) === $date;
}

function imgLink($nom){
    return "/oCrochetDAmitie/Assets/img/" . $nom;
}

function debug($var){
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}

function d_exit($var){
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    exit;
}
function slugify($nom){
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nom)));
    return $slug;
}