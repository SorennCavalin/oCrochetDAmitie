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
        
        // foreach ($dons as $don ){
        //     var_dump($don->getUser());
        // }die;

        $pageMax =  count($dons) !== 10 ?  true : false;  //ceil($nbLignes / $nbParPage);
        

        $this->affichageAdmin("don/liste.html.php",[
            "dons" => $dons,
            "pageMax" => $pageMax,
            "page" => $page,
            "js" => 'don_back',
            "incl" => ["div_confirmation"]
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
            "js" => "don_form"
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
                // comme form est mis en parametre plutot qu'un tableau fait mains. Je met le js avant d'appeler la fonction affichage
                $form["js"] = "don_form";
                return $this->affichageAdmin("don/form.html.php",$form);
            }
        }
         return $this->affichageAdmin("don/form.html.php" , [
                "cible" => $don->getCible(),
                "date" => $don->getDate(),
                "type" => $don->getType(),
                "details" => $don->getDetails(),
                "quantite" => $don->getQuantite(),
                "js" => "don_form"
            ]);
    }


    public function detail($id) {
        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        $don = Bdd::selectionId("don",$id);

        $this->affichageAdmin("don/fiche.html.php",[
            'don' => $don,
            'js' => "don_back"
        ]);
    } 

    public function accueil(){

        if ($_POST){
            if ($form = Verificateur::verifyNewDonAbonne($_POST) === true){
                return $this->redirection(lien("don","accueil"));
            } else {
                return $this->affichage("don/accueil.html.php", $form ?? []);
            }
        } else {
            $dons = Bdd::selection(["table" => "don", "compare" => "type" , "where" => " = 'reception'", 'order' => "id DESC"]);

            $this->affichage("don/accueil.html.php", [
                "dons" => $dons,
                "css" => "don",
                "js" => "donShow"
            ]);
        }
    }



    public function supprimer($id) {

        if (!Session::isAdmin()){
            $this->redirectionError();
        }
        Bdd::dropRelie("don",$id,'don_details');
        $this->redirection(lien("don"));
        
    }

    public function recover(int $id) {
        if (!Session::isAdmin()){
            $this->redirectionError();
        }

        Bdd::recoverRelie('don',$id,'don_details');
        $this->redirection(lien("don"));

    }


}