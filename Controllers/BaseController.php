<?php 


namespace Controllers;

use Modeles\Bdd;

class BaseController {

    public function affichage($fichier, array $parametres = [])
    {
        extract($parametres);

        include "Templates/base.html.php";
        include "Templates/vues/$fichier";
        include "Templates/footer.html.php";
    }


    public function affichageAdmin($fichier, array $parametres = [], $navbar = true)
    {
        extract($parametres);

        include "Templates/base_admin.html.php";
        include "Templates/vues/$fichier";
    }

    public function affichageAjax($fichier, array $parametres = []){
        
        extract($parametres);

        include "Templates/vues/$fichier";
    }

    // public function aucuneReponse(string $table,string $recherche,string $type,array|bool $rattrapage = false){
    //     // $this->d_exit();
    //     // création de la variable aucuneReponse 
    //     $aucuneReponse =  "<div class='card'>
    //         <div class='card-header'>
    //             Aucun(e) $type trouvé
    //         </div>
    //         <div class='card-body'>
    //             <h5 class='card-title'>aucun(e) $table n'a été trouvé selon vos critères</h5>
    //             <p class='card-text'>votre recherche : $recherche</p>
    //             <p class='card-text'>voici 2 enregistrement qui se rapproche de votre recherche</p>";


    //     if ($rattrapage){
    //         foreach($rattrapage as $rattrap){
    //             $class = explode("\\",get_class($rattrapage["dateAvant"][0]))[2];
    //             $class
    //             $aucuneReponse .= 
    //             "<div class='card'>
    //                 <div class='card-body'>
    //                     <h5 class='card-title'>". explode("\\",get_class($rattrapage["dateAvant"][0]))[2] . " n°" . $rattrap->getId() ."</h5>
    //                     <p class='card-text'>With supporting text below as a natural lead-in to additional content.</p>";
                        
                        
    //             $aucuneReponse .= "<a href='".lien()."' class='btn btn-primary'>voir</a></div></div>";
    //         }
           
    //     }
       
    //     $aucuneReponse .= "</div></div>";
    // }
    
    

    public function traitementString($string){
        return addslashes(trim($string));
    }


    public function redirection($url){
        header("Location: $url");
        exit;
    }
    public function redirectionError(){
        header(lien("erreur","404"));
        exit;
    }
    public function debug($var){
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }
    
    public function d_exit($var){
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
        exit;
    }
}