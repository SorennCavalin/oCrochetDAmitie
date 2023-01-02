<?php 


namespace Services;

use Modeles\Bdd;
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
                        return ($cara >= '0' && $cara <= '9') ? true : false;
                        break;
                    case 'maj':
                        return ($cara >= 'A' && $cara <= 'Z') ? true : false;
                        break;
                    case 'min':
                        return ($cara >= 'a' && $cara <= 'z') ? true : false;
                        break;
                    case 'maj':
                        return (in_array($cara,$carSpe)) ? true : false;
                        break;
                }
            }
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
     */
    static function verifyEmail(string $email,string $modifier = ""): bool|int|string {
        
        // verification de modifier en premier pour ne pas avoir a faire des opérations inutiles en cas d'egalité
        if ($modifier && $modifier === $email){
            return 1;
        }

        $email = self::traitementString($email);
        if(preg_match("^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}^",$email)){
            if(filter_var($email,FILTER_VALIDATE_EMAIL)){
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
     */
    static function verifyString(string $string, string $modifier = "", array $paraSupp = []): int|bool|string{

        // verification de modifier en premier pour ne pas avoir a faire des opérations inutiles en cas d'egalité
        if ($modifier && $modifier === $string){
            return 1;
        }

        if($paraSupp){
            extract($paraSupp);
        }

        $tailleMini = $tailleMini ?? 2;
        $tailleMax = $tailleMax ?? 30;
        $verifierTaille = $verifierTaille ?? true;
        $stringVerifier = $stringVerifier ?? "nom";
        $PasString = $PasString ?? "le $stringVerifier ne peut pas être un nombre";
        $mauvaiseTaille = $mauvaiseTaille ?? "la taille du $stringVerifier doit faire entre $tailleMini et $tailleMax lettres";


        if ($verifierTaille) {
            if (strlen($string) >= $tailleMini && strlen($string) <= $tailleMax){

                if(!self::verifyCara($string,"nombre")){
                    return ucfirst(strtolower(self::traitementString($string)));
                } else {
                    Session::messages('danger', $PasString);
                    return false;
                }
            } else {
                Session::messages('danger', $mauvaiseTaille);
                return false;
            }
        } else {
            if(!ctype_digit($string)){
                return ucfirst(strtolower(self::traitementString($string)));
            } else {
                Session::messages('danger', $PasString);
                return false;
            }
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
        $mauvaiseTaille = $mauvaiseTaille ?? "la taille du mot de passe doit faire entre $tailleMini et $tailleMax lettres";
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

    static function verifyNumber(int $number, int $modifier = 0, array $paraSupp = []){

        if ($modifier){
            if ($number === $modifier){
                return 1;
            }
        }

        if ($paraSupp){
            extract($paraSupp);
        }

        $tailleMax = $tailleMax ?? 10 ; 
        $tailleMini = $tailleMini ?? 1 ; 
        $tailleErrone = $tailleErrone ?? "La taille du nombre n'est pas conforme et doit être entre $tailleMini et $tailleMax";
        $isString = $isString ?? "Le nombre entré n'est pas un vrai nombre";


        if (ctype_digit($number)){
            if ($number >= $tailleMini && $number <= $tailleMax){
                return $number;
            } else {
                Session::messages("danger", $tailleErrone);
            }
        } else {
            Session::messages("danger", $isString);
        }
            
    }

    /**
     * function verifyPhone sert à verifier que les numéros de téléphones soient bien conformes
     * 
     * @param string $string
     * Le numéro de téléphone que vous souhaitez verifier et traiter
     * 
     * @param string $modifier
     * [optionnel]
     * Le numéro de téléphone dont vous souhaitez verifier l'égalité avec $telephone
     */
    static function verifyPhone(string $telephone,string $modifier = "", string $telephoneInvalide = ""): bool|int|string {
        
        // verification de modifier en premier pour ne pas avoir a faire des opérations inutiles en cas d'egalité
        if ($modifier && $modifier === $telephone){
            return 1;
        }

        $telephoneInvalide = $telephoneInvalide ?? "Le numéro de téléphone entré ,'est pas conforme";

        if(isset($telephone)){
            // verification du string telephone si >= a 10 (numero classique) ou <= a 13 (avec +33 en france)
            if(preg_match("^[+]?[0-9]{9,12}^", $telephone)){
                return $telephone;
            } else {
                Session::messages("danger", $telephoneInvalide);
            }
        }

    }


    static function verifyUser($form,$modif = false,$userExistant = 0){
        extract($form);

        // toutBon servira a la fin de toute les verifications
        $toutBon = true;

        $tableau = [];

        // verification des variable nom et prenom
        
        if(isset($email) && !empty($email)){
            if ($modif){
                $user = Bdd::selectionId(["table" => "user"],$userExistant);
            }
            if($emailVerifie = Verificateur::verifyEmail($email, ($user ? $user->getEmail() : ""))){
                $tableau["email"] = $emailVerifie;
            }
        } else {
            Session::messages("danger", "Veuillez rentrer un email");
        }
        if(isset($nom) && !empty($nom)){

            if($nomVerifie = Verificateur::verifyString($nom, ($user ? $user->getNom() : ""))){
                $tableau["nom"] = $nomVerifie;
            };
        } else {
            Session::messages("danger", "Veuillez rentrer votre nom");
        }

        if(isset($prenom) && !empty($prenom)){

           if($prenomVerifie = Verificateur::verifyString($prenom, ($user ? $user->getPrenom() : ""),["stringVerifier" => "prenom"])){
               $tableau["prenom"] = $prenomVerifie;
           }
        } else {
            Session::messages("danger", "Veuillez rentrer votre prenom");
        }

        if (isset($mdp) && !empty($mdp)){
            if ($mdpVerifie = self::verifyPassword($mdp, ($user ? $user->getMdp() : ""))){
                $tableau["mdp"] = $mdpVerifie;
            }
        } else {
            Session::messages("danger", "Veuillez rentrer un mot de passe");
        }

        if (isset($region) && !empty($region)){
            if ($regionVerifie = self::verifyString($prenom, ($user ? $user->getRegion() : ""),["stringVerifier" => "région"])){
                $tableau["region"] = $regionVerifie;
            }
        } else {
            Session::messages("danger","Veuillez choisir votre région");
        }

        if (isset($departement) && !empty($departement)){
            if ($departementVerifie = self::verifyString($prenom, ($user ? $user->getDepartement() : ""),["stringVerifier" => "département"])){
                $tableau["departement"] = $departementVerifie;
            }
        } else {
            Session::messages("danger","Veuillez rentrer le code postal de votre département");
        }

        



    }

}