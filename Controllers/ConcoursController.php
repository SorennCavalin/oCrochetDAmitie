<?php 

namespace Controllers;

use Modeles\Bdd;
use Modeles\Session;

class ConcoursController extends BaseController{
    public function afficher(){
        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        // utilisation de id inutilisé dans l'url pour la pagination.
        $page = empty($_GET["id"]) ? 1 : $_GET["id"];
        $nbParPage = 10;
        $offset = (($page - 1) * $nbParPage) - 1;


      

       
        $concours = Bdd::selection(["table" => "concours", "where" => "LIMIT " . ($page == 1 ? "" : "$offset," ) . " $nbParPage"]);

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
            $tableau = [];
            extract($_POST);

            if(isset($projet)){
                foreach ($projets as $p) {
                    if ($p->getId() === (int) $projet ){
                       $tableau["projet_id"] = $projet;
                       if(isset($message)){
                        unset($message);
                       }
                       break;
                    } else {
                        $message = "Le projet sélectionné n'existe pas";
                    }
                }
                if(isset($message)){
                    Session::messages("danger", $message);
                }
            }

            if(isset($nom)){
                if(strlen($nom) >= 3 && strlen($nom) <= 40){
                   $tableau["nom"] = $this->traitementString($nom);
                } else {
                   Session::messages('danger', "Le nom est trop grand ou trop petit");
                }
            } else {
                Session::messages("danger" , "Un nom est necessaire au concours");
            }


            if(isset($date_debut) && isset($date_fin)){
                $check = true;
                if(!isValid($date_debut)){
                     Session::messages("danger" , "La date de début n'est pas conforme");
                     $check = false;
                }
                if(!isValid($date_fin)){
                     Session::messages("danger" , "La date de fin n'est pas conforme");
                     $check = false;
                }
                if($check && ($date_debut > $date_fin)){
                   $check = false;
                   Session::messages("danger", "Le concours ne peut pas commencer après sa fin verifiez les dates");
                }
                if($check){
                   $tableau["date_fin"] = $date_fin;
                   $tableau["date_debut"] = $date_debut;
                }
            } else {
                 Session::messages("danger" , "Une date de fin est necessaire au concours");
            }

            if(Bdd::selection([ "table" => "concours" , "where" => "WHERE nom = '". $tableau["nom"] ."'"])){
                 Session::messages("danger", "Un concours avec le nom '". $tableau["nom"] ."' existe déjà.");
            }
            if(!Session::getItemSession("messages"))
            {
                 if(Bdd::insertBdd("concours",$tableau))
                      //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                 {
                      Session::messages("success" , "Le concours a bien été enregistré");
                      $this->redirection(lien("concours"));
                 }
                 else //Sinon (la fonction renvoie FALSE).
                 {    
                      Session::messages("danger" , "Erreur lors de l'enregistrement");
                 }
            } else {
                return $this->affichageAdmin("concours/form.html.php",
                 [
                      "nom" => $nom,
                      "date_debut" => $date_debut, 
                      "date_fin" => $date_fin,
                      "projets" => $projets,
                      "select" => $projet
                 ]);
            }
        } 
        return $this->affichageAdmin("concours/form.html.php", [
            "projets" => $projets,
        ]);
      
    }
        
            
    public function recup($id) {
        header('Access-Control-Allow-Origin: *');
        $projet = Bdd::selectionId(["table" => "projet"], $id);
        $retour = ["date_debut" => $projet->getDate_debut(), "date_fin" => $projet->getDate_fin()];
        echo $retour["date_debut"] . " " . $retour["date_fin"];
    }
        
       

    

    public function modifier($id){

        $projets = Bdd::selection(["table"=>"projet"]);
        $concours = Bdd::selectionId(["table" => "concours"],$id);

        if (!Session::isAdmin()){
            $this->redirectionError();
        }
        if(!empty($_POST)){
            $tableau = [];
            extract($_POST);

            if(isset($projet) && (int) $projet !== $concours->getProjet_id()){
                foreach ($projets as $p) {
                    if ($p->getId() === (int) $projet ){
                       $tableau["projet_id"] = $projet;
                       break;
                    }
                }
                if(isset($message)){
                    Session::messages("danger", "Le projet sélectionné n'existe pas");
                }
            }

            if(isset($nom) && $nom !== $concours->getNom()){
                if(strlen($nom) >= 3 && strlen($nom) <= 40){
                   $tableau["nom"] = $this->traitementString($nom);
                } else {
                   Session::messages('danger', "Le nom est trop grand ou trop petit");
                }
            }


            if(isset($date_debut) && isset($date_fin)){
                $check = true;
                if(!isValid($date_debut)){
                     Session::messages("danger" , "La date de début n'est pas conforme");
                     $check = false;
                }
                if(!isValid($date_fin)){
                     Session::messages("danger" , "La date de fin n'est pas conforme");
                     $check = false;
                }
                if($check && ($date_debut > $date_fin)){
                   $check = false;
                   Session::messages("danger", "Le concours ne peut pas commencer après sa fin verifiez les dates");
                }
                if($check){
                    if($date_debut !== $concours->getDate_debut()){
                        $tableau["date_debut"] = $date_debut;
                    }
                    if($date_fin !== $concours->getDate_fin()){
                        $tableau["date_fin"] = $date_fin;
                    }
                   
                }
            }

            if(Bdd::selection([ "table" => "concours" , "where" => "WHERE nom = '". $tableau["nom"] ."'"])){
                 Session::messages("danger", "Un concours avec le nom '". $tableau["nom"] ."' existe déjà.");
            }
            if(!Session::getItemSession("messages"))
            {
                if(empty($tableau)){
                    Session::messages("secondary","Aucune modification n'a été effectué");
                    $this->redirection(lien("concours"));
                }
                 if(Bdd::update("concours",$tableau,$id))
                      //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                 {
                      Session::messages("success" , "Le concours a bien été modifié");
                      $this->redirection(lien("concours"));
                 }
                 else //Sinon (la fonction renvoie FALSE).
                 {    
                      Session::messages("danger" , "Erreur lors de la modification");
                 }
            } else {
                return $this->affichageAdmin("concours/form.html.php",
                 [
                      "nom" => $nom,
                      "date_debut" => $date_debut, 
                      "date_fin" => $date_fin,
                      "projets" => $projets,
                      "select" => $projet
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

        $concours = Bdd::selectionId(["table" => "concours"],$id);

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
        Bdd::dropRelie("don",$id,"don_details");
        $this->redirection(lien("don"));
        
    }

}