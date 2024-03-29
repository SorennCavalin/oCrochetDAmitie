<?php


namespace Controllers;

use Modeles\Bdd;
use Modeles\Session;
use PDO;

class AccueilController extends BaseController {

    public function afficher()
    {
        // setcookie("cookieTest", "test ok", time() + (86400), "/"); // 86400 = 1 day

        $this->affichage("accueil.html.php", [
            "css" => "accueil"
        ]);
    }
    public function afficherAdmin()
    {
        if (!Session::isAdmin()){
            return $this->redirection("accueil");
        }
        
        $this->affichageAdmin("stats.html.php",[
            "js" => "block_dates"
        ]);


    }

}