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
                // pour les détails des verifs veuillez vous référer a Services/Verificateur
                
                
                if (isset($adresse)){
                    $user["adresse"] = $this->traitementString($adresse);
                }
                
                

                // verif roles
                if($roles === "ROLE_BENEVOLE,ROLE_ADMIN"){
                    $user["roles"] = serialize(explode(",",$roles));
                }
                elseif ($roles === "ROLE_BENEVOLE" ){
                    $user["roles"] = serialize(array($roles));
                } 
                else{
                    $toutBon = false;
                    Session::messages("danger" , "Les roles que vous avez entrés ne sont pas conformes");
                }

                if($toutBon){
                    if(Bdd::insertBdd("user",$user)){
                        Session::messages("success", "L'inscription est un success");
                        $this->redirection(lien("user"));
                    } else {
                        Session::messages("danger","L'inscription a échouer");
                    }
                } else {
                    return $this->affichageAdmin("user/form.html.php",[
                        "email" => $email ?? null, 
                        "nom" => $nom ?? null, 
                        "prenom" => $prenom ?? null, "telephone" => $telephone ?? null, "region" => $region ?? null,"departement" => $departement ?? null, "adresse" => $adresse ?? null,
                        "roles" => array(explode(",",$roles)) ?? null,
                    ]);
                }
                
              
            

            } else{
            return $this->affichageAdmin("user/form.html.php");
           
        } 
    }

    

    public function connexion(){
        if(Session::isConnected()){
            Session::messages("secondary",'Vous êtes déja connecté');
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
            extract($_POST);
            $toutBon = true;
            $tableau = [];

            if (isset($email)){
                $emailVerifie = Verificateur::verifyEmail($email,$user->getEmail());
                if($emailVerifie && $emailVerifie !== 1){
                    $tableau["email"] = $emailVerifie;
                }
            }

            if(isset($nom)){
                $nomVerifie = Verificateur::verifyString($nom,$user->getNom());
                if($nomVerifie && $nomVerifie !== 1){
                    $tableau["nom"] = $nomVerifie;
                }
            }

            if(isset($prenom)){
               $prenomVerifier = Verificateur::verifyString($prenom,$user->getPrenom());
               if($prenomVerifier && $prenomVerifier !== 1){
                   $tableau["prenom"] = $prenomVerifier;
               }
            }
            

            if (isset($region) && $region !== $user->getRegion()){
                if (!ctype_digit($region)){
                    $tableau["region"] = $this->traitementString($region);
                } else {
                    $toutBon = false;
                    Session::messages("danger","Une région ne peut pas comporter de chiffres");
                }
                
            }
            
            if (isset($departement) && $departement !== $user->getDepartement()){
                if(strlen($departement) < 8){
                    $tableau["departement"] = $departement;
                } else {
                    $toutBon = false;
                    Session::messages("danger", "Le departement n'est pas valable");
                }
            }

            if(isset($telephone)&& $telephone !== $user->getTelephone() && $telephone !== ''){
                // verification du string telephone si >= a 10 (numero classique) ou <= a 13 (avec +33 ou plus selon le pays)
                if(preg_match("^[+]?[0-9]{9,12}^", $telephone)){
                    $tableau["telephone"] = $this->traitementString($telephone);
                } else {
                    $toutBon = false;
                    Session::messages("danger", "Le numéro de téléphone n'est pas valide");
                }
            }
            
            if (isset($adresse) && $adresse !== $user->getAdresse()){
                $user["adresse"] = $this->traitementString($adresse);
            }
            
            $roles = explode(",",$roles);
            if ($roles !== $user->getRoles()){
                if($roles === ["ROLE_ABONNE","ROLE_ADMIN"]){
                $user["roles"] = serialize($roles);
                }
                elseif ($roles === ["ROLE_ABONNE"]){
                    $user["roles"] = serialize($roles);
                } 
                else{
                    $toutBon = false;
                    Session::messages("danger" , "Les roles que vous avez entrés ne sont pas conformes");
                }
            }

            if($toutBon){
                if(Bdd::update("user",$tableau,$id)){
                    Session::messages("success", "La modifcation est un succes");
                    $this->redirection(lien("user"));
                } else {
                    Session::messages("danger","La modification a échouer");
                    $this->redirection(lien("user"));
                }
            } else {
                return $this->affichageAdmin("user/form.html.php",[
                    "email" => $tableau["email"] ?? null, 
                    "nom" => $tableau["nom"] ?? null, 
                    "prenom" => $tableau["prenom"] ?? null, 
                    "telephone" => $tableau["telephone"] ?? null, 
                    "region" => $tableau["region"] ?? null,
                    "departement" => $tableau["departement"] ?? null, 
                    "adresse" => $tableau["adresse"] ?? null,
                    "roles" => $tableau["roles"] ?? null,
                ]);
            }
            
            
            
        }

       

        $this->affichageAdmin("user/form.html.php",[
            "nom" => $user->getNom(),
            "prenom" => $user->getprenom(),
            "telephone" => $user->gettelephone() ?? "",
            "adresse" => $user->getadresse() ?? "",
            "region" => $user->getregion(),
            "departement" => $user->getdepartement(),
            "email" => $user->getemail(),
            "roles" => $user->getroles(),
            "mode" => "modify"
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
        $this->affichage("user/profil.html.php", [
            "user" => $user,
            "css" => "profil"
        ]);
    }
}

