<?php 

namespace Controllers;

use Modeles\Bdd;
use Modeles\Session;
use Services\Verificateur;

class DonController extends BaseController{
    public function afficher(){
        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        // utilisation de id inutilisé dans l'url pour la pagination.
        $page = empty($_GET["id"]) ? 1 : $_GET["id"];
        $nbParPage = 10;
        $offset = (($page - 1) * $nbParPage) - 1;


      

       
        $dons = Bdd::selection(["table" => "don", "where" => "LIMIT " . ($page == 1 ? "" : "$offset," ) . " $nbParPage"]);

        $pageMax =  count($dons) !== 10 ?  true : false;  //ceil($nbLignes / $nbParPage);
        

        $this->affichageAdmin("don/liste.html.php",[
            "dons" => $dons,
            "pageMax" => $pageMax,
            "page" => $page
        ]);
    }

    public function ajouter(){

        if (!Session::isAdmin()){
            $this->redirectionError();
        }

        if ($_POST){
            if (($form = Verificateur::verifyNewDon($_POST)) === true){
                $this->redirection(lien("don"));
            } else {
                $this->affichageAdmin("don/form.html.php",$form);
            }

        }


        return $this->affichageAdmin("don/form.html.php",[
            "js" => "ajouter_don"
        ]);
    }

    public function modifier($id){

        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        $don = Bdd::selectionId("don",$id);

        if (!empty($_POST)){
            if (($form = Verificateur::verifyModifDon($_POST,$don)) === true){
                $this->redirection(lien("don"));
            } else {
                return $this->affichageAdmin("don/form.html.php",$form);
            }
        }
         return $this->affichageAdmin("don/form.html.php" , [
                "cible" => $don->getCible(),
                "date" => $don->getDate(),
                "type" => $don->getType(),
                "details" => $don->getDetails(),
                "quantite" => $don->getQuantite(),
                "js" => "modifier_don"
                
            ]);
    }


    public function detail($id) {
        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        $don = Bdd::selectionId("don",$id);

        $this->affichageAdmin("don/fiche.html.php",[
            'don' => $don
        ]);
    } 

    public function accueil(){
        $videos = Bdd::selection(["table" => "video", "where" => "LIMIT 6"]);

        $this->affichage("video/accueil.html.php", [
            "videos" => $videos,
            "css" => "video"
        ]);
    }

    public function supprimer($id) {

        if (!Session::isAdmin()){
            $this->redirectionError();

        }
        Bdd::dropRelie("don",$id,"don_details");
        $this->redirection(lien("don"));
        
    }

}