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

    public function aucuneReponse(string $table,string $recherche,string $type,array|bool $rattrapage = false){
        // création de la variable aucuneReponse 
        $aucuneReponse =  "<div class='card'>
            <div class='card-header'>
                Aucun(e) $type trouvé
            </div>
            <div class='card-body'>
                <h5 class='card-title'>aucun(e) $table n'a été trouvé selon vos critères</h5>
                <p class='card-text'>votre recherche : $recherche</p>
                <p class='card-text'>voici le ou les enregistrements qui se rapprochent de votre recherche</p>";


        // si le paramettre rattrapage a été envoyé, affiche les entitées les plus proches de la recherche infructueuse
        if ($rattrapage){
            foreach($rattrapage as $rattrap){
                // $this->debug($rattrap);
                // vérifie que $rattrap ne soit pas vide ou false et si il est bien un objet (typé dans les paramettres plus haut) fait le reste, si il ne l'est pas ne fais rien
                if ($rattrap){
                    // rattrapage étant un array d'array mais $rattrap, ne contenant qu'un seul objet a chaque fois, ne requiert pas de foreach. je met donc l'objet de $rattrap dans une variable
                    $objet = $rattrap[0];
                    $class = explode("\\",get_class($rattrap[0]))[2];
                    if ($class === "User"){
                        $class = "Utilisateur";
                    }
                    $aucuneReponse .= 
                    "<div class='card'>
                        <div class='card-body'>
                            <h5 class='card-title'>". $class . " n°" . $objet->getId() ."</h5>";

                    if ($class === "Don"){
                        $aucuneReponse .= "<p class='card-text'>Ce don à été ". ($objet->getType() === "envoi" ? "envoyé à " . $objet->getOrganisme() : "reçu de la part de " . $objet->getDonataire()) . " le " . $objet->getDate() ." </p>";
                    }
                            
                            
                            
                    $aucuneReponse .= "<a href='".lien($class,"detail",$objet->getId())."' class='btn btn-primary'>voir</a></div></div>";
                }
                
            }
           
        }
       
        $aucuneReponse .= "</div></div>";

        echo $aucuneReponse;
    }
    
    

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