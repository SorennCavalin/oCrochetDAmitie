<?php

/**
 * La fonction 'chargementClasse' sera donc appelé quand une classe sera requise.
 * L'argument sera le nom de la classe requise.
*/
function chargementClasse($nomClasseACharger){
    // On remplace les \ qui sont dans le nom de la classe à charger par des / qui est le séparateur de dossier
    // utilisé dans la plupart des systèmes d'exploitation
    // ⚠ RAPPEL : dans les namespaces, on ne peut utiliser que les \
    $nomClasseACharger = str_replace("\\", "/", $nomClasseACharger);
    require __DIR__ . "/../" . $nomClasseACharger . ".php";
}

/*
*La fonction spl_autoload_register permet de définir la fonction qui sera 
*exécutée à chaque fois qu'une classe sera requise par le code (par exemple,
*quand on utilise le mot-clé 'new' pour instancier un objet)
*/
spl_autoload_register("chargementClasse");