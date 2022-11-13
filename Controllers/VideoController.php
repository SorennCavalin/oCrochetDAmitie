<?php 

namespace Controllers;

use Modeles\Bdd;
use Modeles\Session;

class VideoController extends BaseController{
    public function afficher(){
        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        // utilisation de id inutilisé dans l'url pour la pagination.
        $page = empty($_GET["id"]) ? 1 : $_GET["id"];
        $nbParPage = 10;
        $offset = (($page - 1) * $nbParPage) - 1;


      

       
        $videos = Bdd::selection(["table" => "video", "where" => "LIMIT " . ($page == 1 ? "" : "$offset," ) . " $nbParPage"]);

        $pageMax =  count($videos) !== 10 ?  true : false;  //ceil($nbLignes / $nbParPage);
        

        $this->affichageAdmin("video/liste.html.php",[
            "videos" => $videos,
            "pageMax" => $pageMax,
            "page" => $page
        ]);
    }

    public function ajouter(){

        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        if (!empty($_POST)){
            extract($_POST);

            isset($nom) ? ((strlen($nom) <= 40 && strlen($nom) >= 3) ? $tableau["nom"] = $this->traitementString($nom) : Session::messages("danger","Le nom doit faire entre 3 et 40 caractères ")) : Session::messages("danger","Un nom est obligatoire pour la vidéo");

            if (isset($lien)){
                if ($test = @get_headers($lien) ?? null || $test[0] == "HTTP/1.1 404 Not Found"){
                    $tableau["lien"] = $this->traitementString($lien);
                
                    $tableau["type"] = explode(".",$lien)[1];
                } else {
                    Session::messages("danger","Le lien n'est pas valable");
                }
            } else {
                Session::messages("danger","Un lien est nécessaire à la vidéo");
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
                    "nom" => $nom ?? null,
                    "lien" => $lien ?? null,
                    "type" => $type ?? null,
                ]);
            }
        }

        return $this->affichageAdmin("video/form.html.php");

    }

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

        $video = Bdd::selectionId(["table" => "video"],$id);

        $this->affichageAdmin("video/fiche.html.php",[
            'video' => $video
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

        Bdd::drop("video",$id);
        $this->redirection(lien("video"));
        
    }

}