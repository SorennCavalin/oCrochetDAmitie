<?php 


namespace Controllers;




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
    
}