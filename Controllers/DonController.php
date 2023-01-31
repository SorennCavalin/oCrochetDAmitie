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
    
        

    
    // public function ajouterDetails(){
        // header('Access-Control-Allow-Origin: *');
        



       
        // if(!Session::getItemSession("messages")){
        //     $detail = [];
        //     $detail["don_id"] = $id[0]->getId();
        //     $boucle = 1;
        //     if(isset($details)){
        //         foreach($details as $d){
        //             if(isset($d["nom"])){
        //                 $detail["nom"] = ucfirst($this->traitementString($d["nom"]));
        //             } else {
        //                 Session::messages('danger', "Veuillez rentrer un nom pour le détail n°$boucle");
        //             }

        //             if(isset($d["qte"])){
        //                 if(ctype_digit($d["qte"])){
        //                     $detail["qte"] = $d["qte"];
        //                 } else {
        //                     Session::messages("danger","La quantité du don n°$boucle n'est pas un nombre");
        //                 }
        //             } else {
        //                 Session::messages('danger', "La quantité n'a pas été spécifiée au don n°$boucle");
        //             } 
                    
                    
        //             if(!Session::getItemSession("messages")){
        //                 if(!Bdd::insertBdd("don_details",$detail)){
        //                     Session::messages("danger", "Erreur lors de l'enregistrement du détail n°$boucle (". $detail["nom"] . " " . $detail["qte"] . ")");
        //                 }
        //             }
        //             $boucle++;
        //         }
        //         Session::messages("success", "Le don ansi que les détails ont bien été enregistrés");
        //     }
        // } 
    // }
        
            

        
       

    

    public function modifier($id){

        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        $video = Bdd::selectionId(["table" => "video"],$id);

        if (!empty($_POST)){
            $tableau = [];

            extract($_POST);

            isset($nom) && $nom !== $video->getNom() ? ((strlen($nom) <= 40 && strlen($nom) >= 3) ? $tableau["nom"] = $this->traitementString($nom) : Session::messages("danger","Le nom doit faire entre 3 et 40 caractères ")) : "" ;


            if (isset($lien) && $lien !== $video->getlien() ){
                if ($test = @get_headers($lien) ?? null || $test[0] == "HTTP/1.1 404 Not Found"){
                    $tableau["lien"] = $this->traitementString($lien);
                    $tableau["type"] = explode(".",$lien)[1];
                } else {
                    Session::messages("danger","Le lien n'est pas valable");
                }
            }


            if (!$tableau) {
                Session::messages("danger", "Au moins une chose doit changer pour modifier la vidéo");
            }
            
            if(!Session::getItemSession("messages")){
                if(Bdd::insertBdd("video",$tableau)){
                    Session::messages("success", "La vidéo a été enregistrée avec succes");
                    $this->redirection(lien("video"));
                } else {
                    Session::messages("danger", "Erreur lors de l'enregistrement");
                }
            } else {
                return $this->affichageAdmin("video/form.html.php",[
                    "nom" => $nom ? $nom : $video->getNom(),
                    "lien" => $lien ? $lien : $video->getlien(),
                    "type" => $type ? $type : $video->gettype(),
                ]);
            }
        }
         return $this->affichageAdmin("video/form.html.php" , [
                "nom" => $video->getNom(),
                "lien" => $video->getlien(),
                "type" => $video->gettype(),
            ]);
    }


    public function detail($id) {
        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        $don = Bdd::selectionId(["table" => "don"],$id);

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