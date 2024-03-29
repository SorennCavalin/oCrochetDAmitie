<?php 


namespace Services;

use Modeles\Bdd;
use Modeles\Entities\Projet;
use Modeles\Entities\User;
use Modeles\Entities\Video;
use Modeles\Entities\Don;
use Modeles\Entities\Concours;
use Modeles\Session;

class Verificateur {

    /**
     * transforme le string entré en string-intrusion-proof
     */
    static function traitementString($string){
        return addslashes(trim($string));
    }

    /**
     * VerifyCara permet de vérifier la présence d'un certain caractère dans un string (true si présent)
     * 
     * @param string $string
     * Le string qui sera découpé et analysé
     * 
     * @param string $verif
     * Un string qui permet de choisir quoi vérifier (nombre,majuscule,minuscule,carSpe(recherche précise))
     * 
     * @param array $carSpe
     * [optionnel]
     * Un array qui contient le ou les caractères dont on vérifier la présence
     */
    static function verifyCara(string $string,string $verif,array $carSpe = []): bool {
        $caracteres = str_split($string,1);
            foreach($caracteres as $cara){
                switch ($verif) {
                    case 'nombre':
                        if($cara >= '0' && $cara <= '9'){
                            return true;
                        }
                        break;
                    case 'maj':
                        if($cara >= 'A' && $cara <= 'Z'){
                            return true;
                        }
                        break;
                    case 'min':
                        if($cara >= 'a' && $cara <= 'z'){
                            return true;
                        }
                        break;
                    case 'carSpe':
                        if(in_array($cara,$carSpe)){
                            return true;
                        }
                        break;
                }
            }
            return false;
    }

    /**
     * function verifyEmail sert à verifier que les emails soient bien conformes
     * 
     * @param string $string
     * Le string que vous souhaitez verifier et traiter
     * 
     * @param string $modifier
     * [optionnel]
     * Le string dont vous souhaitez verifier l'égalité avec $string
     * 
     * @param string $existe
     * [optionnel]
     * Vérifie l'existance de cet email dans la bdd (l'email étant unique a user)
     */
    static function verifyEmail(string $email,string $modifier = "", bool $existe = false): bool|int|string {
        
        // verification de modifier en premier pour ne pas avoir a faire des opérations inutiles en cas d'egalité
        if ($modifier && $modifier === $email){
            return 1;
        }

        // trim et addslash a email
        $email = self::traitementString($email);
        // utilise un regex pour vérifier que le string ressemble a un email 
        if(preg_match("^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}^",$email)){
            // utilise le filtre email pour garantir la validitée de l'email
            if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                // si le parametre $existe a été activé, va chercher dans la bdd si un enregistrement avec cet email existe déjà (évite les erreur sql constaint)
                if ($existe){
                    // si la recherche porte ses fruits renvoi false sinon continu le code jusqu'au return email plus bas
                    if (Bdd::selection(["compare" => "email" , "where" => "= '$email'" , "table" => "user"])){
                        Session::messages("danger" , "Un compte est déjà relié à cette adresse mail");
                        return false;
                    }
                }
                return $email;
            } else{
                Session::messages("danger","l'adresse email n'est pas valide");
                return false;
            }
        } else{
            Session::messages("danger","Veuillez rentrer une adresse email valable");
            return false;
        }
    }

    /**
     * function verifyString sert à verifier non-seulement les noms mais aussi tout les string qui ont une taille limitée.
     * La fonction est par défaut reglée pour verifier des nom/pernom de 2 a 30 caractères
     * @param string $string
     * Le string que vous souhaitez verifier et traiter
     * 
     * @param string $modifier
     * [optionnel]
     * Le string dont vous souhaitez verifier l'égalité avec $string
     * 
     * @param array $paraSupp
     * [optionnel]
     * Un tableau pouvant comporter les variables suivantes selon vos besoins :
     * 
     * @param string tailleMini
     * Taille minimum que le string doit faire 
     * @param string taillemax
     * Taille maximum que le string doit faire
     * @param bool verifierTaille
     * Verifie ou non la taille 
     * @param string stringVerifier
     * Le nom du string que l'on vérifie (pour les messages d'erreur) "nom" par default
     * @param string pasString
     * message d'erreur si $string n'est pas un string
     * @param string mauvaiseTaille 
     * message d'erreur si le string ne fait pas entre tailleMini et tailleMax
     * @param string maj
     * false par defaut, rend le string en strtolower ucfirst
     */
    static function verifyString(string $string, string $modifier = "", array $paraSupp = []): int|bool|string{

        // verification de modifier en premier pour ne pas avoir a faire des opérations inutiles en cas d'egalité
        if ($modifier && $modifier === $string){
            return 1;
        }

        if($paraSupp){
            extract($paraSupp);
        }

        $maj = $maj ?? false;
        $tailleMini = $tailleMini ?? 2;
        $tailleMax = $tailleMax ?? 30;
        $verifierTaille = $verifierTaille ?? true;
        $stringVerifie = $stringVerifie ?? "nom";
        $mauvaiseTaille = $mauvaiseTaille ?? "la taille du $stringVerifie doit faire entre $tailleMini et $tailleMax lettres";


        if (is_string($string)) {
            if ($verifierTaille){
                if (strlen($string) >= $tailleMini && strlen($string) <= $tailleMax){
                    
                    return $maj ? ucfirst(strtolower(self::traitementString($string))) : self::traitementString($string);
                } else {
                    Session::messages('danger', $mauvaiseTaille);
                    return false;
                }
            } else {
                return $maj ? ucfirst(strtolower(self::traitementString($string))) : self::traitementString($string);
            }
        } else {
            return false;
        }
        
    }

    /**
     * function verifyPassword sert à verifier et hasher les mot de passes
     * 
     * @param string $mdp
     * Le mot de passe que vous souhaitez verifier et traiter
     * 
     * @param int $userId
     * [optionnel]
     * L'id de l'utilisateur qui va changer de mot de passe (pour ne pas remplacer le mot de passe par le même)
     * 
     * @param array $paraSupp
     * [optionnel]
     * Un tableau pouvant comporter les variables suivantes selon vos besoins :
     * 
     * @param string tailleMini
     * Taille minimum que le string doit faire 
     * @param string taillemax
     * Taille maximum que le string doit faire
     * @param bool verifierTaille
     * Verifie ou non la taille 
     * @param string stringVerifier
     * Le nom du string que l'on vérifie (pour les messages d'erreur) "nom" par default
     * @param string pasString
     * message d'erreur si $string n'est pas un string
     * @param string mauvaiseTaille 
     * message d'erreur si le string ne fait pas entre tailleMini et tailleMax
     */
    static function verifyPassword(string $mdp, string $modifier = "", array $paraSupp = []): bool|int|string {

        // recupère l'utilisateur avec l'id et compare les 2 mots de passe en premier pour ne pas avoir a faire des opérations inutiles en cas d'egalité
        if($modifier){
            if (password_verify($mdp,$modifier)){
                return 1;
            }
        }

        if ($paraSupp){
            extract($paraSupp);
        }

        $tailleMini = $tailleMini ?? 5;
        $tailleMax = $tailleMax ?? 16;
        $hasSpace = $hasSpace ?? "le mot de passe ne peut pas contenir d'espace";
        $mauvaiseTaille = $mauvaiseTaille ?? "la taille du mot de passe doit faire entre $tailleMini et $tailleMax caractères";
        $mauvaisCaracteres = $mauvaisCaracteres ?? "Le mot de passe doit contenir au moins 1 minuscule, 1 majuscule, 1 chiffre, 1 caractère spécial parmi ($ * ? , . ! - _ )";

        if (!strpos(" " , $mdp)){
            if( strlen($mdp) >= $tailleMini || strlen($mdp) <= $tailleMax ){
                if  (
                        self::verifyCara($mdp,"nombre")
                        &&
                        self::verifyCara($mdp,"maj") 
                        &&
                        self::verifyCara($mdp,"min")
                        &&
                        self::verifyCara($mdp,"carSpe",['$', '*', '_', '-','!','?','.',',' ])
                    ){
                    return PasswordHasher::hashPassword($mdp);
                } else {
                    Session::messages('danger',$mauvaisCaracteres) ;
                    return false;
                }
            } else {
                Session::messages('danger',$mauvaiseTaille) ;
                return false;
            }
        } else {
            Session::messages('danger',$hasSpace) ;
            return false;
        }
        
    }

    /**
     * function verifyPhone sert à verifier que les numéros de téléphones soient bien conformes
     * 
     * @param string $telephone
     * Le numéro de téléphone que vous souhaitez verifier et traiter
     * 
     * @param string $modifier
     * [optionnel]
     * Le numéro de téléphone dont vous souhaitez verifier l'égalité avec $telephone
     */
    static function verifyPhone(string $telephone,string $modifier = ""): bool|int|string {
        
        // verification de modifier en premier pour ne pas avoir a faire des opérations inutiles en cas d'egalité
        if ($modifier && $modifier === $telephone){
            return 1;
        }

        if(isset($telephone)){
            // verification du string telephone si >= a 10 (numero classique) ou <= a 13 (avec +33 en france)
            if(preg_match("^[+]?[0-9]{9,12}^", $telephone)){
                return $telephone;
            } else {
                Session::messages("danger", "Le numéro de téléphone entré n'est pas un numéro valide");
            }
        }

    }

    /**
     * function verifyDate sert à verifier que les dates soient bien conformes
     * 
     * @param string $date
     * La date que vous souhaitez verifier et traiter
     * 
     * @param string $modifier
     * [optionnel]
     * La date dont vous souhaitez verifier l'égalité
     */
    static function verifyDate(string $date, string $modifier = ""){

        if (isValid($date)){
            if ($modifier){ 
                if (strtotime($date) === strtotime($modifier)){
                    return 1;
                }
            }  
            return $date;
        } else {
            Session::messages("danger","La Date entrée n'est pas une date valide");
            return false;
        }
        

    }

    /**
     * verifyIdRelie vérifie que les id qui entrent dans les colonnes x_id soient correctent et correspondent à une entitée existante
     * @param string $id
     * l'id qui vas être vérifié
     * @param string $table
     * la table qui possède $id en tant qu'id et pas x_id
     * @param string $modifier 
     * [optionnel]
     * l'id déjà existant lors de la modification 
     * 
     */
    static function verifyIdRelie(int $id, string $table, string $modifier = ""){
        if ($modifier){
            if($id === $modifier){
                return 1;
            }
        }

        if(Bdd::selectionId($table,$id)){
            return $id;
        }
        return false;
    }

    /**
     * Verify new user permet d'alleger le controller en effectuant toutes les modification nécessaire a la création d'un compte utilisateur
     * 
     * @param array $form
     * Le formulaire retourné par l'utilisateur/administrateur ($_POST peut etre envoyé)
     * 
     * @param bool $verification
     * booléen qui gère l'envoi de mail pour la vérification du compte utilisateur (à mettre en false si côté admin)
     */

    static function verifyNewUser(array $form, bool $verification = true){
        extract($form);

        $tableau = [];

        
        // verification de email avec la fonction verifyemail ligne 73
        if(isset($email) && !empty($email)){
            if($emailVerifie = self::verifyEmail($email, "", true)){
                $tableau["email"] = $emailVerifie;
            }
        } else {
            Session::messages("danger", "Veuillez rentrer un email");
        }
        
        // verification des variable nom et prenom avec verifyString ligne 123
        if(isset($nom) && !empty($nom)){

            if($nomVerifie = self::verifyString($nom,"",["maj" => true])){
                $tableau["nom"] = $nomVerifie;
            };
        } else {
            Session::messages("danger", "Veuillez rentrer votre nom");
        }

        if(isset($prenom) && !empty($prenom)){

           if($prenomVerifie = self::verifyString($prenom,"",["stringVerifie" => "prenom","maj" => true])){
               $tableau["prenom"] = $prenomVerifie;
           }
        } else {
            Session::messages("danger", "Veuillez rentrer votre prenom");
        }

        // verification de mdp avec verifyPassword ligne 187
        if (isset($mdp) && !empty($mdp)){
            if ($mdpVerifie = self::verifyPassword($mdp)){
                $tableau["mdp"] = $mdpVerifie;
            }
        } else {
            Session::messages("danger", "Veuillez rentrer un mot de passe");
        }

        // region avec verifyString
        if (isset($region) && !empty($region)){
            if ($regionVerifie = self::verifyString($region,"",["stringVerifie" => "région","verifierTaille" => false])){
                $tableau["region"] = $regionVerifie;
            }
        } else {
            Session::messages("danger","Veuillez choisir votre région");
        }

        // role n'ayant que 2 choix possible pas besoin de compliquer avec des verify juste un if suffit

        if (isset($role) && !empty($role)){
            if ($role === "ROLE_ADMIN" || $role === "ROLE_BENEVOLE"){
                $tableau["role"] = $role;
            } else {
                $tableau["role"] = "ROLE_BENEVOLE";
            }
        } else {
            $tableau["role"] = "ROLE_BENEVOLE";
        }

        // departement signifie le code postal, le client pouvant avoir des bénévoles dans les pays étrangés le code postal ne peut pas etre un nombre uniquemnet  verifyString est donc la solution (avec une restriction de taille de 10 max ou 5 min)
        if (isset($departement) && !empty($departement)){
            if ($departementVerifie = self::verifyString($departement,"",["stringVerifie" => "code postal du departement", "tailleMini" => 5, "tailleMax" => 10])){
                $tableau["departement"] = $departementVerifie;
            }
        } else {
            Session::messages("danger","Veuillez rentrer le code postal de votre département");
        }

        // adresse avec verifyString
        if (isset($adresse) && !empty($adresse)){
            if ($adresseVerifie = self::verifyString($adresse,"",["stringVerifie" => "adresse","verifierTaille" => false])){
                $tableau["adresse"] = $adresseVerifie;
            }
        }

        // vérification du numéro de téléphone avec verifyPhone ligne 244
        if(isset($telephone) && !empty($telephone)){
            if ($telephoneVerifie = self::verifyPhone($telephone)){
                $tableau["telephone"] = $telephoneVerifie;
            }
        }

        // verifie la présence d'erreur si non rentre le nouvel utilisateur en bdd, si oui renvoi le formulaire au controlleur qui affichera les messages d'erreur et remettra les valeurs dans leurs champs respectifs
        if (!Session::getItemSession("messages")){
            if(Bdd::insertBdd("user",$tableau)){
                if ($verification){
                    $to = $tableau["email"];
                    $subject = "Confirmation de compte ocrochetdamitié";
                    $message = "test";
                    mail($to , $subject, $message);
                }
                Session::messages("success", "L'inscription est un success");
                return true;
            } else {
                Session::messages("danger","L'inscription a échouer");
                return $form;
            }
        } else {
            return $form;
        }
    }

    /**
     * Verify modif user permet d'alleger le controller en effectuant toutes les modification nécessaire a la modification d'un compte utilisateur
     * 
     * @param array $form
     * Le formulaire retourné par l'utilisateur/administrateur ($_POST peut etre envoyé)
     * 
     * @param User $user
     * L'utilisateur qui va être modifié
     * 
     * @param bool $recupUser
     * Définit la modification à été effectuée par un utilisateur ou un admin
     * si true reconnecte l'utilisateur avec l'id de l'utilisateur modifié
     */
    static function verifyModifUser(array $form,User $user, bool $recupUser = false){
        extract($form);

        $tableau = [];


        // verification des variable nom et prenom
        
        if(isset($email) && !empty($email)){
            if($emailVerifie = Verificateur::verifyEmail($email,$user->getEmail(),true)){
                if ( $emailVerifie !== 1){
                    $tableau["email"] = $emailVerifie;
                }
            }
        }

        if(isset($nom) && !empty($nom)){

            if($nomVerifie = self::verifyString($nom,$user->getNom(),["maj" => true]) ){
                if ($nomVerifie !== 1){
                    $tableau["nom"] = $nomVerifie;
                }
            };
        }

        if(isset($prenom) && !empty($prenom)){
            if($prenomVerifie = self::verifyString($prenom,$user->getPrenom(),["stringVerifier" => "prenom","maj" => true])){
                if ($prenomVerifie !== 1){
                    $tableau["prenom"] = $prenomVerifie;
                }
                
            }
        }

        if (isset($mdp) && !empty($mdp)){
            if ($mdpVerifie = self::verifyPassword($mdp,$user->getMdp())){
                if ($mdpVerifie !== 1){
                    $tableau["mdp"] = $mdpVerifie;
                }
                
            }
        }

        if (isset($region) && !empty($region)){
            if ($regionVerifie = self::verifyString($region,$user->getRegion(),["stringVerifie" => "région","verifierTaille" => false])){
                if ( $regionVerifie !== 1) {
                    $tableau["region"] = $regionVerifie;
                }
                
            }
        }

        if (isset($role) && !empty($role) && $role !== $user->getRole()){
            //verifie si lee role est bien admin ou benevole et si le role est différent de celui deja enrgistré 
            // si le role n'est pas conforme le role benevole sera attribué par defaut
            if ($role === "ROLE_ADMIN" || $role === "ROLE_BENEVOLE"){
                $tableau["role"] = $role;
            } else {
                $tableau["role"] = "ROLE_BENEVOLE";
            }
        }

        if (isset($departement) && !empty($departement)){
            if ($departementVerifie = self::verifyString($departement,$user->getDepartement(),["stringVerifie" => "code postal du departement", "tailleMini" => 5, "tailleMax" => 10])){
                if ($departementVerifie !== 1){
                    $tableau["departement"] = $departementVerifie;
                }
                
            }
        }

        if (isset($adresse) && !empty($adresse)){
            if ($adresseVerifie = self::verifyString($adresse,$user->getAdresse() ?? "",["stringVerifie" => "adresse","verifierTaille" => false])){
                if ($adresseVerifie !== 1){
                    $tableau["adresse"] = $adresseVerifie;
                }
            }
        }

        // vérification du numéro de téléphone
        if(isset($telephone) && !empty($telephone)){
            if ($telephoneVerifie = self::verifyPhone($telephone,$user->getTelephone())){
                if ( $telephoneVerifie !== 1) {
                    $tableau["telephone"] = $telephoneVerifie;
                }
            }
        }

        // d_exit($tableau);
        if (!Session::getItemSession("messages")){
            if ($tableau){
                if(Bdd::update("user",$tableau,$user->getId())){
                    Session::messages("success modification", "La modification de l'utilisateur " . $user->getPrenom() . " a bien été effectuée");
                    // si la modification vient du coté recupUser alors php met a jour l'utilisateur en session depuis les données du formulaire reçu (utilise le mdp de $form étant donné que si il arrive la alors le mdp est bon (juste un coup de traitement string suffira) et que le mdp de tableau est une version hashée qui ne peut pas etre utilisée pour l'autentification)
                    if($recupUser){
                        $userModifie = Bdd::selectionId("user",$user->getId());
                        Session::connexion($userModifie);
                    } 
                    return true;
                } else {
                    Session::messages("danger","La modification a échouer");
                    return false;
                }
            } else {
                Session::messages("secondary modification","Aucune modification n'a été effectuée sur l'utilisateur " . $user->getPrenom() );
                return true;
            }
        } else {
                return $form;
            }
    }

    /**
     * verifyConnexion permet la vérification du formulaire de connexion et la mise en session de l'utilisateur 
     * 
     * @param array $form
     * le formulaire rempli avec les données de l'utilisateur (email et mdp)
     */
    static function verifyConnexion(array $form){
        extract($form);

        $tableau = [];

        if (isset($mdp) && !empty($mdp)){
            if ($mdpVerifie = self::verifyPassword($mdp)){
                $tableau["mdp"] = $mdpVerifie;
            }
        } else {
            Session::messages("danger mdp","Veuillez entrer un mot de passe");
        }


        if (isset($email) && !empty($email)){
            if ($emailVerifie = self::verifyEmail($email)){
                $tableau["email"] = $emailVerifie;
            }
        } else {
            Session::messages("danger email","Une adresse mail est requise pour la connexion");
        }

        if (!Session::getItemSession("messages")){
            if (Bdd::autentication(self::traitementString($mdp),$tableau['email'])){
                return true;
            } else {
                Session::messages("danger misc", "Une erreur c'est produite lors de la connexion, veuillez réessayer.");
                return $form;
            }
        } return $form;
    }

    /**
     * Verify new project permet d'alleger le controller en effectuant toutes les modification nécessaire a la création d'un nouveau projet
     * 
     * @param array $form
     * Le formulaire retourné par l'administrateur ($_POST peut etre envoyé)
     */
    static function verifyNewProject(array $form) {
        extract($form);

        $tableau = [];

        if (isset($nom) && !empty($nom)){
            if ($nomVerifie = self::verifyString($nom)){
                // vérification de l'existance d'un projet avec ce nom
                if(Bdd::selection([ "table" => "projet" , "compare" => "nom" , "where" => "= '$nomVerifie'"])){
                    Session::messages("danger", "Un projet avec le nom '$nomVerifie' existe déjà.");
                } else {
                    $tableau["nom"] = $nomVerifie;
                    $tableau["slug"] = slugify($nomVerifie);
                }
            }
        } else {
            Session::messages("danger","Veuillez entrer un nom pour le projet");
        }


        if ((isset($date_debut) && !empty($date_debut))){
            if (isset($date_fin) && !empty($date_fin)){
                if ($date_debut > $date_fin){
                    Session::messages("danger","La date de début ne peut pas être après la date de fin");
                }
                if (!Session::getItemSession("messages")){
                    if ($date_debutVerifie = self::verifyDate($date_debut)){
                        $tableau["date_debut"] = $date_debutVerifie;
                    }
                    if ($date_finVerifie = self::verifyDate($date_fin)){
                    $tableau["date_fin"] = $date_finVerifie;
                    }
                }
            } else {
                Session::messages("danger","Veuillez entrer une date pour la fin du projet");
            }
        } else {
            Session::messages("danger","Veuillez entrer une date pour le début du projet");
        } 

        if (isset($page) && !empty($page)){
            if ($pageVerifie = self::verifyString($page,"",["verifierTaille" => false,  ]))
            $tableau["page"] = $pageVerifie;
        }

        if (!Session::getItemSession("messages")){
            if (Bdd::insertBdd("projet",$tableau)){
                Session::messages("success","La création du projet " . $tableau["nom"] . " est un succès");
                return true;
            } else {
                Session::messages("danger","La création du projet a échoué");
                return $form;
            }
        } else {
            return $form;
        }

    }

    /**
     * Verify modif project permet d'alleger le controller en effectuant toutes les modification nécessaire a la modification d'un projet
     * 
     * @param array $form
     * Le formulaire retourné par l'administrateur ($_POST peut etre envoyé)
     * 
     * @param Projet $projet
     * Le projet qui va être modifié
     * 
     */
    static function verifyModifProject(array $form, Projet $projet) {
        extract($form);

        $tableau = [];

        if (isset($nom) && !empty($nom)){
            if ($nomVerifie = self::verifyString($nom,$projet->getNom())){
                if($nomVerifie !== 1){
                    // vérification de l'existance d'un projet avec ce nom
                    if(Bdd::selection([ "table" => "projet" , "compare" => "nom" , "where" => "= '$nomVerifie'"])){
                        Session::messages("danger", "Un projet avec le nom '$nomVerifie' existe déjà.");
                    } else {
                        $tableau["nom"] = $nomVerifie;
                        $tableau["slug"] = slugify($nomVerifie);
                    }
                }
                
            }
        }


        if ((isset($date_debut) && !empty($date_debut))){
            if (isset($date_fin) && !empty($date_fin)){
                if ($date_debut > $date_fin){
                    Session::messages("danger","La date de début ne peut pas être après la date de fin");
                }
                if (!Session::getItemSession("messages")){
                    if ($date_debutVerifie = self::verifyDate($date_debut,$projet->getDate_debut())){
                        if($date_debutVerifie !== 1){
                            $tableau["date_debut"] = $date_debutVerifie;
                        }
                    }
                    if ($date_finVerifie = self::verifyDate($date_fin,$projet->getDate_fin())){
                        if($date_finVerifie !== 1){
                            $tableau["date_fin"] = $date_finVerifie;
                        }
                    }
                }
            }
        }

        if (isset($page) && !empty($page)){
            // $page étant un string ne passant pas dans verifyString() traitementString() sera la solution pour eviter les intrusions 
            if(self::traitementString($page) !== $projet->getPage()){
                $tableau["page"] = self::traitementString($page);
            }
            
        }

        if (!Session::getItemSession("messages")){
            if ($tableau){
                if (Bdd::update("projet",$tableau,$projet->getId())){
                    Session::messages("success","La modification du projet " . $tableau["nom"] . " est un succès");
                    return true;
                } else {
                    Session::messages("danger","La modification du projet a échoué");
                    return false;
                }
            } else {
                Session::messages("secondary","Aucune modification n'a été effectuée sur le projet " . $tableau["nom"]);
                return true;
            }
        } else {
            return $form;
        }

    }

    //  la suite des verification n'aura pas ou peu de commentaire concernant le fonctionnement des fonctions verify donc si quelque chose est étrange se referer a newUser/modifyUser ou aller lire directement les fonction verify

    /**
     * Verify new video permet d'alleger le controller en effectuant toutes les modification nécessaire a la création d'une vidéo
     * 
     * @param array $form
     * Le formulaire retourné par l'administrateur ($_POST peut etre envoyé)
     * 
     */
    static function verifyNewVideo(array $form){

        extract($form);

        $tableau = [];

        if (isset($nom) && !empty($nom)){
            if($nomVerifie = self::verifyString($nom)){
                if(Bdd::selection([ "table" => "video" , "compare" => "nom" , "where" => "= '$nomVerifie'"])){
                    Session::messages("danger", "Une vidéo avec le nom '$nomVerifie' existe déjà.");
                } else {
                    $tableau["nom"] = $nomVerifie;
                    $tableau["slug"] = slugify($nomVerifie);
                }
               
            }
        } else {
             Session::messages("danger","Veuillez renter un nom pour la vidéo");
        }

        if (isset($lien) && !empty($lien)){
            if (!filter_var($lien, FILTER_VALIDATE_URL) === false){
                // je ne suis pas sur qu'il soit necessaire de traiter si l'url est bonne mais on sait jamais
                if ($lienVerifie = self::verifyString($lien,'',["verifierTaille" => false , "stringVerifie" => "lien", ])){
                    $tableau["lien"] = $lienVerifie;

                    // récupération de la plateforme qui heberge la vidéo (facebook, youtube, etc...) depuis l'url en le découpant
                    $tableau["plateforme"] = explode(".",$lienVerifie)[1];
                }
            } else {
                 Session::messages("danger","Le lien n'est pas valide");
            }
        } else {
             Session::messages("danger","Veuiller entrer un lien pour la vidéo");
        }

        if (isset($type) && !empty($type)){
            $typesDispo = ["live","presentation","tuto","autre"];
            if (in_array($type,$typesDispo)){
                if ($typeVerifie = self::verifyString("$type","",["stringVerifie" => "type","verifierTaille" => false])){
                    $tableau["type"] = $typeVerifie;
                }
            } else {
                Session::messages("danger","Le type choisi n'est pas conforme");
            }
        } else {
            Session::messages("danger","Veuiller choisir un type pour la vidéo");
        }

        if (!Session::getItemSession("messages")){
            if (Bdd::insertBdd("video",$tableau)){
                Session::messages("success","La vidéo a bien été enregistrée");
                return true;
            } else {
                Session::messages("danger","Un problème est survenu lors de l'enregistrement, veuillez réessayer");
                return $form;
            }
        }

       
    }

    /**
     * Verify modif video permet d'alleger le controller en effectuant toutes les modification nécessaire a la modification d'une video
     * 
     * @param array $form
     * Le formulaire retourné par l'administrateur ($_POST peut etre envoyé)
     * 
     * @param Video $video
     * La vidéo qui va être modifiée
     * 
     */
    static function verifyModifVideo(array $form, Video $video){

        extract($form);

        $tableau = [];

        if (isset($nom) && !empty($nom)){
            if($nomVerifie = self::verifyString($nom, $video->getNom(),["maj"=> false])){
                if ($nomVerifie !== 1){
                    if(Bdd::selection([ "table" => "video" , "compare" => "nom" , "where" => "= '$nomVerifie'"])){
                        Session::messages("danger", "Une vidéo avec le nom '$nomVerifie' existe déjà.");
                    } else {
                        $tableau["nom"] = $nomVerifie;
                        $tableau["slug"] = slugify($nomVerifie);
                    }
                }
            }
        }

        if (isset($lien) && !empty($lien)){
            if (!filter_var($lien, FILTER_VALIDATE_URL) === false){
                // je ne suis pas sur qu'il soit necessaire de traiter si l'url est bonne mais on sait jamais
                if ($lienVerifie = self::verifyString($lien,$video->getLien(),["verifierTaille" => false , "stringVerifie" => "lien",  ])){
                    if ($lienVerifie !== 1){
                        $tableau["lien"] = $lienVerifie;
                        // récupération la plateforme qui heberge la vidéo (facebook, youtube, etc...) depuis l'url en le découpant
                        $tableau["plateforme"] = explode(".",$lien)[1];
                    }
                }
            } else {
                Session::messages("danger","Le lien n'est pas valide");
            }
        }
        
        if (isset($type) && !empty($type)){
            $typesDispo = ["live","presentation","tuto","autre"];
            if (in_array($type,$typesDispo)){
                if ($typeVerifie = self::verifyString("$type",$video->getType(),["stringVerifie" => "type","verifierTaille" => false])){
                    if ($typeVerifie != 1){
                        $tableau["type"] = $typeVerifie;
                    }
                }
            } else {
                Session::messages("danger","Le type choisi n'est pas conforme");
            }
        } else {
            Session::messages("danger","Veuiller choisir un type pour la vidéo");
        }

        if (!Session::getItemSession("messages")){
            if ($tableau) {
                if (Bdd::Update("video",$tableau,$video->getId())){
                    Session::messages("success","La vidéo a bien été modifiée");
                    return true;
                } else {
                    Session::messages("danger","Un problème est survenu lors de la modification, veuillez réessayer");
                    return $form;
                }
            } else {
                Session::messages("secondary","Aucune modification apportée à la vidéo " . $video->getNom());
                return true ;
            }
        } else {
            return $form;
        }
    }

    /** 
     * Verify new don permet d'alleger le controller en effectuant toutes les modification nécessaire a la création d'un don
     * 
     * @param array $form
     * Le formulaire retourné par l'administrateur ($_POST peut etre envoyé)
     * 
     * 
     */
    static function verifyNewDon(array $form){
        
        extract($form);
        if (isset($type) && !empty($type)){
            if ($type === "reception" || $type === "envoi"){
                $tableau["type"] = self::verifyString($type);

                // la vérification de donataire et organisme se fait ici pour eviter les problemes si type n'existe pas pour ne pas voir a refaire un if
                if($type === "reception"){
                    if(isset($donataire) && !empty($donataire)){
                        if ($donataireVerifie = self::verifyString($donataire,"",["verifieTaille" => false])){
                            $tableau["donataire"] = $donataireVerifie;
                        }
                    } else {
                         Session::messages("danger","Veuillez saisir un donataire");
                    }
                }
        
                if($type === "envoi"){
                    if(isset($organisme) && !empty($organisme)){
                        if ($organismeVerifie = self::verifyString($organisme,"",["verifieTaille" => false])){
                            $tableau["organisme"] = $organismeVerifie;
                        }
                    } else {
                         Session::messages("danger","Veuillez saisir un organisme");
                    }
                }
            } else {
                 Session::messages("danger","Le type de don n'est pas reconnu");
            }
        } else {
             Session::messages("danger","Veuillez choisir si le don est un envoi ou une réception avec le champs type");
        }

        if (isset($date) && !empty($date)){
            if($dateVerifie = self::verifyDate($date)){
                $tableau["date"] = $dateVerifie;
            } else {
                Session::messages("danger","La date n'est pas valable");
            }
        } else {
            Session::messages("danger","Une date est nécessaire pour enregistrer le don");
        }
        if(!Session::getItemSession("messages")){
              // afin de pouvoir traiter un nombre inconnu de valeur on utilise un array dans le name de l'input (pour plus de précision aller voir Assets/js/ajouter_don ligne 42)

            // initialise une variable pour mettre toute les erreur mais permettre de continuer la vérification des details suivants
            $erreur = [];
            // 
            $boucle = 0;
            $tableauDetails = [];
            foreach ($donDetails as $index => $details){
                $boucle++;
                // verifie le nom et la quantite
                // donDetails est un array d'array donc je dois utiliser les clef nom et qte 
                // extract est aussi un choix possible pour se servir de variable plutot que de propriétées d'array
                if ($detailNomVerifie = self::verifyString($details["nom"],"",["verifieTaille" => false])){
                    $tableauDetails["detail" . $index]["nom"] = $detailNomVerifie;
                } else {
                    // je met dans le tableau erreur le numéro de la boucle qui correspond au detail erroné et met true a nom ou qte  
                    $erreur["$boucle"]["nom"] = true;
                }
                // n'ayant pas de test pour les nombre dans Verificateur un simple is_int suffira 
                if (is_int( (int) $details["qte"])){
                    $tableauDetails["detail" . $index]["qte"] = $details["qte"];
                } else {
                    $erreur["$boucle"]["qte"] = true;
                }
            }

            if($erreur){
                // j'utilise le tableau erreur pour savoir si les details sont corrects
                // si erreur n'est pas vide des messages adaptés a l'erreur s'afficheront
                // $index étant le numéro de la boucle avec l'erreur mais aussi le numéro du détail erroné
                // si une erreur est détectée la mise en bdd ne commencera pas
                foreach ($erreur as $index => $detail){
                    
                    if (isset($detail["nom"] )&& isset($detail["qte"])){

                         Session::messages("danger detail","Le nom et la quantité du don don n°". $index . " ne sont pas corrects");

                    } elseif (isset($detail["nom"])) {

                         Session::messages("danger detail","Le nom du don n°". $index . " n'est pas conforme");

                    } elseif (isset($detail["qte"])) {

                        // echo "$index";exit;
                        Session::messages("danger detail","La quantité du don n°". $index  . " n'est pas un nombre");

                    }
                }
                return $form;
            }

            if(Bdd::insertBdd("don",$tableau)){
                // si l'enregistrement du don a fonctionner on passe au détails qui ont été rajoutés en quantité inconnue par l'utilisateur
                // recupération du don qui vient d'être créé (pour récupérer l'id)
                $nouveauDon = Bdd::selection([ "table" => "don", "order" => "id DESC", "limit" => 1])[0];
                // je réinitialise boucle pour le réutiliser dans les messages d'erreurs (boucle commence toujours a 1 et la taille de tableauDetail est la même que pour les vérification sur donDetails)
                $boucle = 1;
                foreach ($tableauDetails as $index => $detail){
                    if (!Bdd::insertBdd("don_details",["nom" => $detail["nom"], "qte"=> $detail["qte"],"don_id" => $nouveauDon->getId()])){
                        Session::messages("danger","erreur lors de l'enregistrement du don n°". $index + 1 . "(" . $detail["nom"] . " : " . $detail["qte"] . ")");
                    }
                } return true;
            } else {
                Session::messages("danger","Erreur lors de l'enregistrement du don");
                return $form;
            }
        } else {
            return $form;
        }
    } 
    
    /** 
     * Verify new don abonné permet d'alleger le controller en effectuant toutes les modification nécessaire a la création d'un don par un abonné
     * 
     * @param array $form
     * Le formulaire retourné par l'abonné ($_POST peut etre envoyé)
     * 
     * 
     */
    static function verifyNewDonAbonne(array $form){
        
        extract($form);

        $tableau["type"] = "reception";
        $tableau["donataire"] = Session::getUser()->getId();
        $tableau["date"] = date("Y-m-d");

        if (isset($description) && !empty($description)){
            if ($descVerifie = self::verifyString($description,"",["stringVerifie" => "description","verifierTaille" => false])){
                $tableau["description"] = $descVerifie;
            }
        } else {
            Session::messages("danger","Veuiller décrire votre don pour que l'association puisse traiter votre don dans les meilleurs conditions");
        }
        

        if(!Session::getItemSession("messages")){
            
            if(!Bdd::insertBdd("don",$tableau)){
                Session::messages("danger","Erreur lors de l'enregistrement du don");
                return $form;
            } else {
                return true;
            }
        } else {
            return $form;
        }

       
    }

    /**
     * Verify modif don permet d'alleger le controller en effectuant toutes les modification nécessaire a la modification d'un don
     * 
     * @param array $form
     * Le formulaire retourné par l'administrateur ($_POST peut etre envoyé)
     * 
     * @param Don $don
     * Le don qui va être modifié
     * 
     */
    static function verifyModifDon(array $form,Don $don){
        
        extract($form);

        if (isset($type) && !empty($type)){
            if ($type === "reception" || $type === "envoi"){
                if (($typeVerifie = self::verifyString($type)) !== $don->getType() ){
                    $tableau["type"] = self::verifyString($type);
                }

                // la vérification de donataire et organisme se fait ici pour eviter les problemes si type n'existe pas pour ne pas voir a refaire un if
                if($type === "reception"){
                    if(isset($donataire) && !empty($donataire)){
                        if ($donataireVerifie = self::verifyString($donataire,$don->getDonataire(),["verifieTaille" => false])){
                            if ($donataireVerifie !== 1){
                                $tableau["donataire"] = $donataireVerifie;
                            }
                            
                        }
                    }
                }
        
                if($type === "envoi"){
                    if(isset($organisme) && !empty($organisme)){
                        if ($organismeVerifie = self::verifyString($organisme,$don->getOrganisme(),["verifieTaille" => false])){
                            if ($organismeVerifie !== 1){
                                $tableau["organisme"] = $organismeVerifie;
                            }
                        }
                    }
                }
            } else {
                 Session::messages("danger","Le type de don n'est pas reconnu");
            }
        }

        if (isset($date) && !empty($date)){
            if($dateVerifie = self::verifyDate($date,$don->getDate())){
                if ($dateVerifie !== 1){
                    $tableau["date"] = $dateVerifie;
                }
            }
        }
        // verifie que le don ne comporte pas d'erreur avant de verifier les details
        if(!Session::getItemSession("messages")){

            // afin de pouvoir traiter un nombre inconnu de valeur on utilise un array dans le name de l'input (pour plus de précision aller voir Assets/js/ajouter_don ligne 42)

            // initialise une variable pour mettre toute les erreur mais permettre de continuer la vérification des details suivants
            $erreur = [];
            // une variable pour connaitre créer un nouvel index numérique correspondant au numéro du détail défectueux servant pour les erreur a chaque boucle (utiliser index de foreach n'aurait pas fonctionner, les index étant des string)
            $boucle = 0;
            $tableauDetails = [];
            $detailsExistants = $don->getDetails();
            foreach ($donDetails as $index => $details){
                // variable identique sert dans la vérification des détails inchangés lors de la modification et sera réinitialisée a chaque détail
                $identique = false;
                // vérifie si le nom et la quantité du don son identique a ceux déjà existants
                // un array d'objet en objet puis getnom ou qte pour vérifier 
                // si nom et qte du meme detail sont identique il ne le rentrera pas dans tableauDetails
                foreach($detailsExistants as $detail){
                    if ($details['nom'] === $detail->getNom() && (int)$details["qte"] === $detail->getQte()){
                        $identique = true;
                        break;
                    }
                }
                $id = $detailsExistants[$boucle]->getId();
                $boucle++;
                if (!$identique){
                    // verifie le nom et la quantite
                    // donDetails est un array d'array donc je dois utiliser les clef nom et qte 
                    // extract est aussi un choix possible pour se servir de variable plutot que de propriétées d'array
                    // la fonction verifyString ne se sert pas de sa capacité à comparer pour des raisons de clartée du code
                    if ($detailNomVerifie = self::verifyString($details["nom"],"",["verifieTaille" => false])){
                        $tableauDetails[$index]["nom"] = $detailNomVerifie;
                    } else {
                        // je met dans le tableau erreur le numéro de la boucle qui correspond au detail erroné et met true a nom ou qte  
                        $erreur["$boucle"]["nom"] = true;
                    }
                    // n'ayant pas de test pour les nombre dans Verificateur un simple is_int suffira 
                    if (is_int((int) $details["qte"])){
                        $tableauDetails[$index]["qte"] = $details["qte"];
                    } else {
                        $erreur["$boucle"]["qte"] = true;
                    }
                    $tableauDetails[$index]['id'] = $id;
                }
            }

            if($erreur){
                // j'utilise le tableau erreur pour savoir si les details sont corrects
                // si erreur n'est pas vide des messages adaptés a l'erreur s'afficheront
                // $index étant le numéro de la boucle avec l'erreur mais aussi le numéro du détail erroné
                // si une erreur est détectée la mise en bdd ne commencera pas et le formulaire est renvoyé
                foreach ($erreur as $index => $detail){
                    if (isset($detail["nom"] )&& isset($detail["qte"])){
                         Session::messages("danger detail","Le nom et la quantité du don don n°". $index . " ne sont pas corrects");
                    } elseif (isset($detail["nom"])) {
                         Session::messages("danger detail","Le nom du don n°". $index . " n'est pas conforme");
                    } elseif (isset($detail["qte"])) {
                        Session::messages("danger detail","La quantité du don n°". $index . " n'est pas un nombre");
                    }
                }
                return $form;
            }

            // verifie encore une fois les messages d'erreur avant de mettre a jour
            if (!Session::getItemSession("messages")){
                if ($tableau || $tableauDetails){
                    if ($tableau){
                        if(!Bdd::update("don",$tableau,$don->getId())){
                            Session::messages("danger","Erreur lors de la modification du don");
                            return $form;
                        }
                    }
                    
                    if ($tableauDetails){
                        foreach ($tableauDetails as $index => $detail){
                            if (!Bdd::update("don_details",["nom" => $detail["nom"], "qte" => $detail["qte"]],$detail["id"])){
                                $message = "erreur la modification du détails du don"; 
                                $message .= " n°". $index + 1 ;
                                $message .= " (" . $detail["nom"] . " : " . $detail["qte"] . ")";
                                Session::messages("danger",$message);
                            }
                        } return true;
                    }
                } else {
                    $message = "Aucune modification n'a été effectuée sur le don" ;
                    $message .= ($don->getType() === "envoi") ? " reçu de " . $don->getOrganisme() : "envoyé à " .$don->getDonataire();
                    Session::messages("secondary modification", $message);
                    return true;
                }
            } else {
                return $form;
            }
            
        } else {
            return $form;
        }

       
    }

    /**
     * Verify new concours permet d'alleger le controller en effectuant toutes les modification nécessaire a la création d'un concours
     * 
     * @param array $form
     * Le formulaire retourné par l'administrateur ($_POST peut etre envoyé)
     * 
     */
    
    static function verifyNewConcours(array $form){
        extract($form);

        if(isset($projet) && $projet != 0){
            if (is_numeric($projet)){
                if($projetVerifie = self::verifyIdRelie($projet,"projet")){
                    $tableau["projet_id"] = $projetVerifie;
                } else {
                    Session::messages("danger" , "Le projet sélectionné n'est pas reconnu par le serveur");
                }
            } else {
                Session::messages("danger" , "Le projet sélectionné n'est pas reconnu par le serveur");
            }
        }


        if(isset($nom)){
            if($nomVerifie = self::verifyString($nom,"",["verifierTaille" => false])){
                if (!Bdd::selection(["table" => 'concours', "compare" => "nom" , "where" => " = '$nomVerifie'"])){
                    $tableau["nom"] = $nomVerifie;
                } else {
                    Session::messages("danger" , "Un concours avec ce nom existe déjà");
                }
            }
        } else {
            Session::messages("danger" , "Un nom est necessaire au concours");
        }


        if (isset($date_debut)){
            if ($date_debutVerifie = self::verifyDate($date_debut)){
                $tableau["date_debut"] = $date_debutVerifie;
            }
        } else {
            Session::messages("danger","Une date de début est nécessaire au concours");
        }

        if (isset($date_fin)){
            if ($date_finVerifie = self::verifyDate($date_fin)){
                $tableau["date_fin"] = $date_finVerifie;
            }
        } else {
            Session::messages("danger","Une date de fin est nécessaire au concours");
        }

        if(!Session::getItemSession("messages"))
        {
             if(Bdd::insertBdd("concours",$tableau))
                  //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
             {
                  Session::messages("success" , "Le concours a bien été enregistré");
                  return true;
             }
             else //Sinon (la fonction renvoie FALSE).
             {    
                  Session::messages("danger" , "Erreur lors de l'enregistrement");
                  return $form;
             }
        } else {
            return $form;
        }
    }

    /**
     * Verify modif concours permet d'alleger le controller en effectuant toutes les modification nécessaire a la modification d'un concours
     * 
     * @param array $form
     * Le formulaire retourné par l'administrateur ($_POST peut etre envoyé)
     * 
     * @param Concours $concours
     * le concours qui va être modifié
     */
    
    static function verifyModifConcours(array $form,Concours $concours){

        extract($form);
        $tableau = [];

        if(isset($projet)){
            if (is_numeric($projet) && $projet != $concours->getProjet_id()){
                if($projetVerifie = self::verifyIdRelie($projet,"projet",$concours->getProjet_Id() ?? "") ){
                    if ($projetVerifie != 1){
                        $tableau["projet_id"] = $projetVerifie;
                    }
                } else {
                    Session::messages("danger" , "Le projet sélectionné n'est pas reconnu par le serveur");
                }
            } else {
                Session::messages("danger" , "Le projet sélectionné n'est pas reconnu par le serveur");
            }
        }


        if(isset($nom)){
            if($nomVerifie = self::verifyString($nom,$concours->getNom(),["verifierTaille" => false])){
                if ($nomVerifie !== 1){
                     if (!Bdd::selection(["table" => 'concours', "compare" => "nom" , "where" => " = $nomVerifie"])){
                        $tableau["nom"] = $nomVerifie;
                    } else {
                        Session::messages("danger" , "Un concours avec ce nom existe déjà");
                    }
                }
            }
        }

        if (isset($date_debut)){
            if ($date_debutVerifie = self::verifyDate($date_debut,$concours->getDate_debut())){
                if ($date_debutVerifie !== 1){
                    $tableau["date_debut"] = $date_debutVerifie;
                }
            }
        }

        if (isset($date_fin)){
            if ($date_finVerifie = self::verifyDate($date_fin,$concours->getDate_fin())){
                if ($date_finVerifie !== 1){
                    $tableau["date_fin"] = $date_finVerifie;
                }
            }
        }

        if(!Session::getItemSession("messages"))
        {
            if ($tableau){
                if(Bdd::update("concours",$tableau,$concours->getId())){
                    Session::messages("success" , "Le concours a bien été modifié");
                    return true;
                } else {    
                    Session::messages("danger" , "Erreur lors de l'enregistrement");
                    return false;
                }
            } else {
                Session::messages("secondary", "Aucune modification apportée au concours '" . $concours->getNom() . "'");
                return true;
            }
        } else {
            return $form;
        }
    }
}