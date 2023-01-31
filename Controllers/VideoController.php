<?php 

namespace Controllers;

use Modeles\Bdd;
use Modeles\Session;
use Services\Verificateur;

class VideoController extends BaseController{
    public function afficher(){
        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        // utilisation de id inutilisé dans l'url pour la pagination.
        $page = empty($_GET["id"]) ? 1 : $_GET["id"];
        $nbParPage = 10;
        $offset = (($page - 1) * $nbParPage) - 1;


      

       
        $videos = Bdd::selection(["table" => "video", "where" => "LIMIT " . ($page == 1 ? "" : "$offset," ) . " $nbParPage" , "order" => "id DESC"]);

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
            if(($form = Verificateur::verifyNewVideo($_POST)) === true){
                $this->redirection(lien("video"));
            } else {
                extract($form);
                return $this->affichageAdmin("video/form.html.php",[
                    "nom" => $nom ?? null,
                    "lien" => $lien ?? null,
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
            if(($form = Verificateur::verifyModifVideo($_POST,$video)) === true){
                $this->redirection(lien("video"));
            } else {
                extract($form);
                return $this->affichageAdmin("video/form.html.php",[
                    "nom" => $nom ?? null,
                    "lien" => $lien ?? null,
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