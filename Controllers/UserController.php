<?php 

namespace Controllers;

use Controllers\BaseController;
use Modeles\Bdd;
use Modeles\Entities\User;
use Modeles\Session;
use Services\Verificateur;

class UserController extends BaseController{

    public function afficher(){
        if (!Session::isAdmin()){
            
            $this->redirectionError();

        }
        

        // utilisation de id inutilisé dans l'url pour la pagination.
        $page = empty($_GET["id"]) ? 1 : $_GET["id"];
        $nbParPage = 10;
        $offset = (($page - 1) * $nbParPage) - 1;

        $users = Bdd::selection(["table" => "user", "where" => "LIMIT " . ($page == 1 ? "" : "$offset," ) . " $nbParPage"]);
        $nbLignes = count($users);

        $pageMax =  count($users) !== 10 ?  true : false; 

        
        
        $this->affichageAdmin("user/liste.html.php" , [
            "users" => $users,
            "pageMax" => $pageMax,
            "page" => $page
        ]);

    }

    public function ajouter(){
        if (!Session::isAdmin()){
            $this->redirectionError();
        }

            if (!empty($_POST)){

                // pour les détails des verifs veuillez vous référer a Services/Verificateur->verifyNewUser()

                if(($form = Verificateur::verifyNewUser($_POST)) === true){
                    $this->redirection(lien("user"));
                } else {
                    return $this->affichageAdmin("user/form.html.php",$form);
                }
            } else {
            return $this->affichageAdmin("user/form.html.php");
        } 
    }

    

    public function connexion(){
        if(Session::isConnected()){
            $this->redirection(lien("user","profil"));
        }

        if (!empty($_POST)){
            extract($_POST);
            $toutBon = false;
            
            if(isset($email)){
                if(preg_match("^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}^",$email)){
                    if(filter_var($email,FILTER_VALIDATE_EMAIL)){ 
                        $toutBon = true;
                        $email = $this->traitementString($email);
                    } else{
                       
                        Session::messages("danger","Veuillez rentrer une adresse mail valide ex : email@email.com.");
                    }
                } else{
                    Session::messages("danger","Veuillez rentrer une adresse mail valide ex : email@email.com.");
                }
            } else {
                Session::messages("danger","Une adresse mail est requise pour la connexion");
            }

            if (Session::getItemSession("messages")){
                return $this->affichage("user/connexion.html.php",["css" => "connexion","js" => "connexion"]);
            }

            if(isset($mdp)){
                if( strlen($mdp) >= 5 || strlen($mdp) <= 16 ){
                    $caracteres = str_split($mdp, 1);
                    $minusucle = false;
                    $majuscule = false;
                    $chiffre = false;
                    $special = false;
                    foreach($caracteres as $car){
                        if( $car >= 'a' && $car <= 'z' ){
                            $minusucle = true;
                        }
            
                        if( $car >= 'A' && $car <= 'Z' ){
                            $majuscule = true;
                        }
            
                        if( $car >= '0' && $car <= '9' ){
                            $chiffre = true;
                        }
                        if( in_array($car, ['$', '*', '_', '-','!','?','.',',' ]) ){
                            $special = true;
                        }
                    }
                    if( $minusucle && $majuscule && $chiffre && $special ){
                        
                        $mdp = $this->traitementString($mdp);
                        $toutBon = true;
                        } else {
                            $toutBon = false;
                            Session::messages('danger',"Le mot de passe doit contenir au moins 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial parmi ($ * ? , . ! - _ )") ;
                        }
                    } else {
                        $toutBon = false;
                        Session::messages('danger',"Le mot de passe doit faire au minimum 5 caractères et au maximum 16.") ;
                    }
                } else {
                    
                    $toutBon = false;
                    Session::messages("danger","Veuillez rentrer un mot de passe");
                }

                if (Session::getItemSession("messages")){
                    return $this->affichage("user/connexion.html.php",["css" => "connexion","js" => "connexion"]);
                }
                
                
            if($toutBon){
                if( Bdd::autentication($mdp,$email)){
                    $this->redirection(lien("user","profil"));
                } else {
                    Session::messages("error", "L'adresse mail ou mot de passe sont érronés");
                    return $this->affichage("user/connexion.html.php",["css" => "connexion","js" => "connexion","email" =>$email]);
                }
            }
        }
        return $this->affichage("user/connexion.html.php",["css" => "connexion","js" => "connexion"]);
    }

    public function deconnexion(){
        if(Session::isConnected()){
            Session::logout();
            $this->redirection(lien("accueil"));
        }
        Session::messages("danger","Vous n'etes pas connecté");
        $this->redirection(lien("accueil"));

    }

    public function detail($id){

        if (!Session::isAdmin()){
            $this->redirectionError();

        }
        $user = Bdd::selectionId([ "table" => "user" ],$id);
        $this->affichageAdmin("user/fiche.html.php",[
             "user" => $user
        ]);
    }

 
    public function modifier($id) {
        if (!Session::isAdmin()){
            $this->redirectionError();
        }

        $user = Bdd::selectionId(["table" => "user"],$id);
        if (!empty($_POST)){

            
            if($tableau = Verificateur::verifyModifUser($_POST,$user) === true){
                $this->redirection(lien("user"));
            } else {
                return $this->affichageAdmin("user/form.html.php",[
                    "email" => $tableau["email"] ?? $user->getemail(),
                    "mdp" => $tableau["mdp"] ?? $user->getMdp(), 
                    "nom" => $tableau["nom"] ?? $user->getNom(), 
                    "prenom" => $tableau["prenom"] ?? $user->getprenom(), 
                    "telephone" => $tableau["telephone"] ?? $user->gettelephone(), 
                    "region" => $tableau["region"] ?? $user->getregion(),
                    "departement" => $tableau["departement"] ?? $user->getdepartement(), 
                    "adresse" => $tableau["adresse"] ?? $user->getadresse(),
                    "role" => $tableau["role"] ?? $user->getRole(),
                ]);
            }
        }

        return $this->affichageAdmin("user/form.html.php",[
            "nom" => $user->getNom(),
            "prenom" => $user->getprenom(),
            "telephone" => $user->gettelephone() ?? "",
            "adresse" => $user->getadresse() ?? "",
            "region" => $user->getregion(),
            "departement" => $user->getdepartement(),
            "email" => $user->getemail(),
            "mdp" => $user->getMdp(),
            "role" => $user->getRole(),
            "mode" => "Modification"
        ]);
    }

    public function supprimer($id) {

        if (!Session::isAdmin()){
            $this->redirectionError();

        }

        Bdd::drop("user",$id);
        $this->redirection(lien("user"));
        
    }

    public function profil(){
        if(!Session::isConnected()){
            $this->redirection(lien("user","connexion"));
        }

        $user = Session::getUser();

        // afin d'alleger la partie php du coté affichage et les requetes sur la bdd quelques valeurs sont créees ici et transmises dans affichage()
        $userDons = $user->getDons();
        $totalDons = 0;
        foreach($userDons as $don){
            $totalDons.= $don->getTaille();
        }

        $this->affichage("user/profil.html.php", [
            "user" => $user,
            "userParticipations" => $user->getParticipations(),
            "userDons" => $userDons,
            "totalDons" => $totalDons,
            "css" => "profil"
        ]);
    }

    public function inscription(){

        if (Session::isConnected()){
            return $this->redirection(lien("user","profil"));
        }

        if (!empty($_POST)){
            if ($form = Verificateur::verifyNewUser($_POST)){
                return $this->redirection(lien('user','profil'));
            } else {
                return $this->affichage('user/formClient.html.php',$form);
            }
        }


        $this->affichage('user/inscription.html.php',[
            'css' => 'inscription',
            'js' => 'inscription',
        ]);

    }

    public function editer(){
        if (!Session::isConnected()){
            return $this->redirection(lien("user","connexion"));
        }

        $user = Session::getUser();

        if ($_POST){
            if (($form = Verificateur::verifyModifUser($_POST,$user,true))){
                return $this->redirection(lien("user","profil"));
            } else {
                return $this->affichage('user/formClient.html.php',$form);
            }
        }

        return $this->affichage('user/formClient.html.php',[
            "nom" => $user->getNom(),
            "prenom" => $user->getprenom(),
            "telephone" => $user->gettelephone() ?? "",
            "adresse" => $user->getadresse() ?? "",
            "region" => $user->getregion(),
            "departement" => $user->getdepartement(),
            "email" => $user->getemail(),
            "mdp" => $user->getMdp(),
            'js' => "inscription",
            'css' => "inscription",
            // mode servira pour afficher modification au lieu de inscription sur la page
            'mode' => "Modification",
        ]);


    }
}

