<?php


namespace Controllers;

use Modeles\Bdd;
use Modeles\Session;
use PDO;

class AccueilController extends BaseController {

    public function afficher()
    {
        $this->affichage("accueil.html.php", [
            "css" => "accueil"
        ]);
    }
    public function afficherAdmin()
    {
        
        $this->affichageAdmin("stats.html.php");
    }

}