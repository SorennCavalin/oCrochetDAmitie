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
function slugify($str){
    // remplace tout les caracteres indésirable par la veleur de la clef qui correspond (ù sera remplacé par u)
    $unwanted_array = ['ś'=>'s', 'ą' => 'a', 'à' => 'a', 'ć' => 'c', 'ç' => 'c', 'ę' => 'e', 'è' => 'e', 'é' => 'e', 'û' => 'u', 'ù' => 'u', 'ê' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ô' => 'o', 'ź' => 'z', 'ż' => 'z','Ś'=>'s', 'Ą' => 'a', 'Ć' => 'c', 'Ç' => 'c', 'Ę' => 'e','Ê' => 'e', 'Ł' => 'l', 'Ń' => 'n', 'Ó' => 'o','Ô' => 'o', 'Ź' => 'z', 'Ż' => 'z'];
    $str = strtr( $str, $unwanted_array );

    $slug = strtolower(trim(preg_replace('/[\s-]+/', "-", preg_replace('/[^A-Za-z0-9-]+/', "-", preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), "-"));
    return $slug;
}