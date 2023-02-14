<?php 

namespace Controllers;

use Modeles\Bdd;
use Modeles\Session;
use Services\Verificateur;

class ConcoursController extends BaseController{
    public function afficher(){
        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        // utilisation de id inutilisé dans l'url pour la pagination.
        $page = empty($_GET["id"]) ? 1 : $_GET["id"];
        $nbParPage = 10;
        $offset = (($page - 1) * $nbParPage) - 1;


      

       
        $concours = Bdd::selection(["table" => "concours", "limit" => ($page == 1 ? "" : "$offset," ) . " $nbParPage"]);

        $pageMax =  count($concours) !== 10 ?  true : false;  //ceil($nbLignes / $nbParPage);
        

        $this->affichageAdmin("concours/liste.html.php",[
            "concours" => $concours,
            "pageMax" => $pageMax,
            "page" => $page
        ]);
    }

    public function ajouter(){

        $projets = Bdd::selection(["table"=>"projet"]);

        if (!Session::isAdmin()){
            $this->redirectionError();
        }
        if(!empty($_POST)){
            if (($form = Verificateur::verifyNewConcours($_POST)) === true){
                return $this->redirection(lien("concours"));
            } else {
                $form["projets"] = $projets;
                return $this->affichageAdmin("concours/form.html.php", $form);
            }
        } 
        return $this->affichageAdmin("concours/form.html.php", [
            "projets" => $projets,
        ]);
      
    }
        
            
    public function recup($id) {
        header('Access-Control-Allow-Origin: *');
        $projet = Bdd::selectionId("projet", $id);
        $retour = ["date_debut" => $projet->getDate_debut(), "date_fin" => $projet->getDate_fin()];
        echo $retour["date_debut"] . " " . $retour["date_fin"];
    }
        
       

    

    public function modifier($id){

        $projets = Bdd::selection(["table"=>"projet"]);
        $concours = Bdd::selectionId("concours",$id);

        if (!Session::isAdmin()){
            $this->redirectionError();
        }
        if(!empty($_POST)){
            if (($form = Verificateur::verifyModifConcours($_POST,$concours)) === true){
                return $this->redirection(lien("concours"));
            } else {
                return $this->affichageAdmin("concours/form.html.php", [
                    "projets" => $projets,
                    "nom" => $form["nom"] ?? $concours->getNom(),
                    "date_debut" => $form["date_debut"] ?? $concours->getDate_debut(), 
                    "date_fin" => $form["date_fin"] ?? $concours->getDate_fin(),
                    "select" => $form["projet"] ?? $concours->getProjet_id()
                ]);
            }
        } 
        return $this->affichageAdmin("concours/form.html.php", [
            "projets" => $projets,
            "nom" => $concours->getNom(),
            "date_debut" => $concours->getDate_debut(), 
            "date_fin" => $concours->getDate_fin(),
            "select" => $concours->getProjet_id()
        ]);
      
    }


    public function detail($id) {
        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        $concours = Bdd::selectionId("concours",$id);

        $this->affichageAdmin("concours/fiche.html.php",[
            'concours' => $concours
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

        Bdd::dropRelie("concours",$id,"participants");
        $this->redirection(lien("concours"));
        
    }

}