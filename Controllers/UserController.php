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
           
            if ($form = Verificateur::verifyConnexion($_POST) === true){
                return $this->redirection(lien("user","profil"));
            } else {
                return $this->affichage("user/connexion.html.php",["css" => "connexion","js" => "connexion", "email" => $form["email"]]);
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
        $user = Bdd::selectionId("user",$id);
        $this->affichageAdmin("user/fiche.html.php",[
             "user" => $user
        ]);
    }

 
    public function modifier($id) {
        if (!Session::isAdmin()){
            $this->redirectionError();
        }

        $user = Bdd::selectionId("user",$id);
        if (!empty($_POST)){

            
            if($form = Verificateur::verifyModifUser($_POST,$user) === true){
                $this->redirection(lien("user"));
            } else {
                return $this->affichageAdmin("user/form.html.php",[
                    "email" => $form["email"] ?? $user->getemail(),
                    "mdp" => $form["mdp"] ?? $user->getMdp(), 
                    "nom" => $form["nom"] ?? $user->getNom(), 
                    "prenom" => $form["prenom"] ?? $user->getprenom(), 
                    "telephone" => $form["telephone"] ?? $user->gettelephone(), 
                    "region" => $form["region"] ?? $user->getregion(),
                    "departement" => $form["departement"] ?? $user->getdepartement(), 
                    "adresse" => $form["adresse"] ?? $user->getadresse(),
                    "role" => $form["role"] ?? $user->getRole(),
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
        $to = "xodiha4984@ekcsoft.com";
        $subject = "Confirmation de compte ocrochetdamitié";
        $message = "test";
        // if (mail($to , $subject, $message)){
        //     
        // }
        if (Session::isConnected()){
            return $this->redirection(lien("user","profil"));
        }

        if (!empty($_POST)){
            if ($form = Verificateur::verifyNewUser($_POST)){
                return $this->redirection(lien('user','connexion'));
            } else {
                return $this->affichage('user/formClient.html.php',$form);
            }
        }


        return $this->affichage('user/formClient.html.php',[
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

