<?php 


namespace Services;

use Modeles\Session;

class Verificateur {

    static function traitementString($string){
        return addslashes(trim($string));
    }

    static function verifyEmail(string $email,string $modifier = ""){
        if ($modifier){
            if ($modifier === $email){
                return 1;
            }
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
     * function verifyNom sert à verifier non-seulement les noms mais aussi tout les string qui ont une taille limitée.
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
     * @param bool VerifierTaille
     * Verifie ou non la taille 
     * @param string stringVerifier
     * Le nom du string que l'on vérifie (pour les messages d'erreur) "nom" par default
     * @param string pasString
     * message d'erreur si $string n'est pas un string
     * @param string mauvaiseTaille 
     * message d'erreur si le string ne fait pas entre tailleMini et tailleMax
     */
    static function verifyString(string $string, string $modifier = "", array $paraSupp = []){

        if($paraSupp){
            extract($paraSupp);
        }

        $tailleMini = $tailleMini ?? 2;
        $tailleMax = $tailleMax ?? 30;
        $VerifierTaille = $VerifierTaille ?? true;
        $stringVerifier = $stringVerifier ?? "nom";
        $PasString = $PasString ?? "le $stringVerifier ne peut pas être un nombre";
        $mauvaiseTaille = $mauvaiseTaille ?? "la taille du $stringVerifier doit faire entre $tailleMini et $tailleMax lettres";

        if ($modifier){
            if ($modifier === $string){
                return 1;
            }
        }

        if (strlen($string) >= $tailleMini && strlen($string) <= $tailleMax){
            if(!ctype_digit($string)){
                return ucfirst(strtolower(self::traitementString($string)));
            } else {
                Session::messages('danger', $PasString);
                return false;
            }
        } else {
            Session::messages('danger', $mauvaiseTaille);
            return false;
        }
    
    }

}