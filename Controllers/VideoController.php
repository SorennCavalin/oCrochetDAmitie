<?php 

namespace Controllers;

use Modeles\Bdd;
use Modeles\Session;
use Services\Verificateur;

class VideoController extends BaseController{
    public function afficherAdmin($page = 1){
        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        // utilisation de id inutilisé dans l'url pour la pagination.
        $page = $page ? $page : 1;
        $nbParPage = 30;
        $offset = (($page - 1) * $nbParPage) - 1;


      

       
        $videos = Bdd::selection(["table" => "video", "limit" => ($page == 1 ? "" : "$offset," ) . " $nbParPage", "order" => "id DESC" ]);

        $pageMax =  count($videos) !== 30 ?  true : false;  //ceil($nbLignes / $nbParPage);
        

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
                $this->redirection(lienAdmin("video"));
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

        $video = Bdd::selectionId("video",$id);

        if (!empty($_POST)){
            if(($form = Verificateur::verifyModifVideo($_POST,$video)) === true){
                $this->redirection(lienAdmin("video"));
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

        $video = Bdd::selectionId("video",$id);

        $this->affichageAdmin("video/fiche.html.php",[
            'video' => $video
        ]);
    } 

    public function accueil(){

        $videos = Bdd::selection(["table" => "video", "limit" => "6", "order" => "id DESC"]);

        $this->affichage("video/accueil.html.php", [
            "videos" => $videos,
            "css" => "video",
            'js' => "VideoStand"
        ]);
    }

    public function tout($page = 1) {
        // le code pour la pagination
        $page = $page ?? 1;
        $nbParPage = 24;
        $offset = (($page - 1) * $nbParPage) - 1;

        $videos = Bdd::selection(["table" => "video", "limit" => ($page == 1 ? "" : "$offset," ) . " $nbParPage", "order" => "id DESC" ]);

        $pageMax =  count($videos) !== 24 ?  true : false;

        return $this->affichage("video/tout.html.php",[
             "videos" => $videos,
             "pageMax" => $pageMax,
             "page" => $page,
             "css" => "video"
        ]);
    }

    public function afficher(null|string $slug) {
        // la fonction selection renvoi un tableau
        $video = Bdd::selection(["table" => "video", "compare" => "slug", "where" => " = '$slug'"])[0];
        // \d_exit($video);
        return $this->affichage("video/afficherVideo.html.php",[
             "video" => $video,
             "css" => "video",
        ]);

    }

    public function supprimer($id) {

        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        Bdd::drop("video",$id);
        $this->redirection(lienAdmin("video"));
        
    }

}