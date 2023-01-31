<?php



namespace Modeles;


class Session {


    static function getUser(){
        return $_SESSION["user"] ?? null;
    }

    static function getItemSession(string $cible)
    {
        if(!empty($_SESSION["$cible"])){
            return $_SESSION["$cible"];
        }
        return null;
    }


    static function messages(string $type, string $contenu)
    {
        $_SESSION["messages"][$type][] = $contenu;
    }


    static function getMessages()
    {
        $messages = $_SESSION["messages"] ?? null;

        if($messages){
            unset($_SESSION["messages"]);
        }

        return $messages;
        
    }

    static function connexion($user){
        $_SESSION["user"] = $user;
    }

    static function isConnected() {
        if(self::getUser()){
            return true;
        }
        return false;
    }


    static function isAdmin() {
        if(self::getUser()){
            if(self::getUser()->getRole() === "ROLE_ADMIN"){
                return true;
            }
        }
        return false;
    }

    static function logout(){
        unset($_SESSION["user"]);
    }



}