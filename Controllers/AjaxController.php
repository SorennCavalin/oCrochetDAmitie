<?php


namespace Controllers;

use Modeles\Bdd;
use Modeles\Session;

class AjaxController extends BaseController {

    public function recherche(){
        header('Access-Control-Allow-Origin: *');
        if($_POST){

            extract($_POST);

            // $this->d_exit($_POST);


            
            // vérification rapide des données minimum requises (on sait jamais)
            if (!$search){
                $search = "*";
            }
           
            if($table === false || $where === false){
                Session::messages('danger', "Je ne peux pas recherche sans savoir quoi chercher et comment");
                return "Recherche annulée";
            }

            // donne une valeur vide a limit et order si l'utilisateur n'en a pas choisi
            if (!isset($limit)){
                $limit = "";
            }
            if (!isset($order) || $order === "Ne pas classer"){
                $order = "" ;
            }


            // recherche avec id classique retourne 1 seul resultat sous forme de fiche
            if($where === "id" ){
                $recherche = Bdd::selectionId($table,$search);
                return $this->affichageAjax("$table/fiche.html.php",[
                    "$table" => $recherche,
                ]);
            }

            // recherche les projets qui sont en lien avec un projet précis 
            // un nom ou un id peut être entré et la recherche se fera dans les 2 cas
            // en cas de recherche avec prenom plusieurs projets peuvent être sélectionnés, ce qui réduira la precision de la recherche
            // si un seul concours est trouvé a partir de la recherche fiche.html.php sera utilisée 
            // sinon une liste sera affichée pour que l'utilisateur puisse choisir le concours qu'il veut
            elseif ($where === "projet_id" ){
                if (is_numeric($search)){
                    $recherche = Bdd::selectionId('projet',$search);
                    $concours = $recherche->getConcours();
                    if (!$concours){
                        return $this->affichageAjax("rechercheRate.html.php", [
                            'messageErreur' => 'Un concours relié au projet n°' . $search . " nommé " . $recherche->getNom()
                        ]);
                    }
                    // \d_exit($recherche);
                    $plusieurs = count($concours) > 1;
                    return $this->affichageAjax("$table/".( $plusieurs ? "liste" : "fiche" ).".html.php",[
                        "$table" =>  $plusieurs ? $concours : $concours[0]
                    ]);
                } else {
                    $nom = $this->traitementString($search);
                    $recherches = Bdd::selection(['table' => "projet", "compare" => "nom", "like" => "'%$nom%'","limit" => $limit, "order" => $order]);

                    foreach($recherches as $recherche){
                        foreach($recherche->getConcours() as $resultats){
                            $resultat[] = $resultats;
                        }
                    }
                    $plusieurs = count($resultat) > 1;
                    return $this->affichageAjax("concours/".( $plusieurs ? "liste" : "fiche" ).".html.php",[
                        "concours" => ($plusieurs ? $resultat : $resultat[0]),
                    ]);
                }
            }

            // si on recherche avec un nom 
            // un user sera cherché avec une maj et tout minuscule les autres entreront tel quel dans la recherche (avec un traitement anti intrusion dans les 2 cas)
            // retourne une liste
            elseif ($where === "nom") {
                // si on cherche un user, met tout en minuscule avec une maj en premier sinon le mot est cherché tel quel (avec un peu de sécu quand même )
                $nom = ($table === "user") ? ucfirst(strtolower($this->traitementString($search))) : $this->traitementString($search);
                $recherche = Bdd::selection(["table" => $table, "compare" => "$where", "like" => "'%$nom%'", "limit" => $limit, "order" => $order]);
                $plusieurs = count($recherche);
                if ($plusieurs){
                    return $this->affichageAjax("$table/liste.html.php",[
                        "$table" . "s" => $recherche,
                        "reponse" => true
                    ]);
                } else {
                    return $this->affichageAjax("$table/fiche.html.php",[
                        ($table === "concours") ? "$table" : "$table" . "s" => $recherche,
                        "reponse" => true
                    ]);
                }
                
            }


            // si la recherche est une date
            // utilise $precision et $search pour recherche une date exacte ou dans les 3 mois d'ecarts au maximum
            // retourne une liste si plusieurs résultat sont retournés (même en date exacte) et une fiche si la date exacte ne représente qu'un seul enregistrement
            elseif (strpos($where, "date") !== false){
                // garantie le bon format de la date reçu
                $dateRecherche = date("Y-m-d",strtotime($search));
                if($precision === 0){
                    if($recherche_date_exacte = Bdd::selection(["table" => $table, "compare" => $where, "where" => "= $dateRecherche", 'limit' => $limit , "order" => $order])){
                        // vérifie si plusieurs dates sont pareils que celle reçue et s'adapte
                        if(count($recherche_date_exacte) > 1){
                            return $this->affichageAjax("$table/liste.html.php", [
                                "$table" . "s" => $recherche_date_exacte,
                            ]);
                        }
                        return $this->affichageAjax("$table/fiche.html.php", [
                            "$table" => $recherche_date_exacte
                        ]);
                    }
                    

                } 
                elseif ($precision === 1){
                    // strtotime("+/- n, y/m/d") permet de rajouter ou retirer du temps du timestamp donné à la fonction
                    // ex: strtotime("+1 day", "2022-12-01") => 2022-12-02
                    $recherche_date_anterieur = Bdd::selection(
                        [
                            "table" => $table,
                            "compare" => "$where",
                            "where" => "< $dateRecherche",
                            "and" => true,
                            "andCompare" => $where,
                            "andWhere" => ">" . date("Y-m-d",strtotime("-1 months",$dateRecherche)),
                            "limit" => $limit,
                            "order" => $order
                        ]);

                    $recherche_date_superieur = Bdd::selection(
                        [
                            "table" => $table,
                            "compare" => "$where",
                            "where" => "< $dateRecherche",
                            "and" => true,
                            "andCompare" => $where,
                            "andWhere" => "<" . date("Y-m-d",strtotime("+1 months",$dateRecherche)),
                            "limit" => $limit,
                            "order" => $order
                        ]);
                    foreach ($recherche_date_anterieur as $date){
                        $dates[] = $date;
                    } 
                    foreach ($recherche_date_superieur as $date){
                        $dates[] = $date;
                    }
                    $plusieurs = count($dates) > 1;
                    return $this->affichageAjax("$table/".( $plusieurs ? "liste" : "fiche" ).".html.php",[
                        "$table" . "s" => ($plusieurs ? $dates : $dates[0])
                    ]);
                }
                elseif($precision === 3){
                    $recherche_date_anterieur = Bdd::selection( 
                        [
                            "table" => $table,
                            "compare" => "$where",
                            "where" => "< $dateRecherche",
                            "and" => true,
                            "andCompare" => $where,
                            "andWhere" => ">" . date("Y-m-d",strtotime("-3 months",$dateRecherche)), 
                            "limit" => $limit,
                            "order" => $order
                        ]);
                    $recherche_date_superieur = Bdd::selection(
                        [
                            "table" => $table,
                            "compare" => "$where",
                            "where" => "< $dateRecherche",
                            "and" => true,
                            "andCompare" => $where,
                            "andWhere" => "<" . date("Y-m-d",strtotime("+3 months",$dateRecherche)), 
                            "limit" => $limit,
                            "order" => $order
                        ]);
                    foreach ($recherche_date_anterieur as $date){
                        $dates[] = $date;
                    } 
                    foreach ($recherche_date_superieur as $date){
                        $dates[] = $date;
                    }
                    return $this->affichageAjax("$table/".( $plusieurs ? "liste" : "fiche" ).".html.php",[
                        "$table" . "s" => ($plusieurs ? $dates : $dates[0])
                    ]);
                }
                    if($table === "user"){
                        $date = "date d'inscription";
                    }
                    elseif($table === "don"){
                        $date = "date du don";
                    } else {
                        if(strpos($where,"debut")){
                            $date = "date de debut du $table";
                        } else{
                            $date = "date de fin du $table";
                        }
                    }

                    $rattrapage["dateAvant"] = Bdd::selection(["table" => $table,"compare" => $where, "where" => "< $dateRecherche", "limit" => 1]);
                    $rattrapage["dateApres"] = Bdd::selection(["table" => $table,"compare" => $where, "where" => "> $dateRecherche", "limit" => 1]);


                    
                    ($table === "user" ? $table = "utilisateur" : "");
                    return $this->aucuneReponse($table,"un $table avec une $date". (($precision) ? " d'environ $precision mois autour du $dateRecherche" : "le $dateRecherche"),"date",($rattrapage ?? false));
            }

            elseif ($where === "type"){
                $type = $this->traitementString($search);

                Bdd::selection(["table" => $table]);
            }
            else {
                $recherche = Bdd::selection(["table" => $table, "compare" => "$where", "like" => "'%$where%'", "limit" => $limit, "order" => $order]);
                $plusieurs = count($recherche);
                if ($plusieurs){
                    return $this->affichageAjax("$table/liste.html.php",[
                        "$table" . "s" => $recherche,
                        "reponse" => true
                    ]);
                } else {
                    return $this->affichageAjax("$table/fiche.html.php",[
                        ($table === "concours") ? "$table" : "$table" . "s" => $recherche,
                        "reponse" => true
                    ]);
                }
            }


            // // si aucun des cas spécifiques plus haut n'est recherché une recherche basique sera effectuée
            // $search = $this->traitementString($search);
            // $recherche = Bdd::selection(["table" => $table, "where" => "WHERE $where LIKE '%$search%' $limit"]);
            // return $this->affichageAjax("$table/".( $plusieurs ? "liste" : "fiche" ).".html.php",[
            //    ($table === "concours") ? "$table" : "$table" . "s" => ($plusieurs ? $recherche : $recherche[0])
            // ]);


        }
    }

    public function dynaFormTables(){
        header('Access-Control-Allow-Origin: *');

       
        
        // je choisi ici les tables que je ne veut pas afficher dans ma recherche
        $tablesIndesirables = ["participant", "don_details"];
        $tables = [];
        // si la requete a bien fonctionner le tableau ne devrait pas rendre false
        // recuperation des nom des tables et des colonnes de la base de donnée
        if($toutesTables = Bdd::getTablesNames('ocrochet')){
            // pour chaque ligne renvoyée par la bdd on verifie que le nom de la table ne fais pas partie de ceux qui sont dans le tableau indésirable
                foreach($toutesTables as $table){
                    if (!in_array($table["TABLE_NAME"],$tablesIndesirables)){
                        // création de ['table'] pour pouvoir afficher les noms des tables du côté js
                                $tables[] = $table["TABLE_NAME"];
                        }
                }
        }
        echo json_encode($tables);
    }
    public function dynaFormColonnes(){
        header('Access-Control-Allow-Origin: *');

        // recuperation, depuis la base de donnée, des nom des colonnes de la table choisie par l'utilisateur
        $table = $_POST["table"];
        $toutesTables = Bdd::getColumnsNames('ocrochet',$table);
        // je choisi ici les colonnes que je ne veut pas afficher dans ma recherche
        // $champsIndesirables = ["role",'lien','page','mdp',"telephone",];
        $colonnes = [];
        // si la requete a bien fonctionner le tableau ne devrait pas rendre false
        if($toutesTables){
            // pour chaque ligne renvoyée par la bdd on verifie que le nom de la colonne ne fais pas partie de ceux qui sont dans le tableau indésirable
                foreach($toutesTables as $colonne){
                        // création de ['colonne'] pour pouvoir afficher les noms des colonnes du côté js et de type pour les colonnes qui ne peuvent avoir que des valeurs prédéfinies
                        if ($colonne["COLUMN_COMMENT"] !== ""){
                            if (stristr($colonne["COLUMN_COMMENT"],"/")){
                                $colonnes["type"] = explode("/",$colonne["COLUMN_COMMENT"]);
                                $colonnes["colonnes"][] = $colonne["COLUMN_NAME"];
                            }
                        } else {
                            $colonnes["colonnes"][] = $colonne["COLUMN_NAME"];
                        }
                }
        }
        echo json_encode($colonnes);
    }
        
}



/**
 * paternes 
 *  
 * $plusieurs = count($concours) > 1;
 *   return $this->affichageAjax("$table/".( $plusieurs ? "liste" : "fiche" ).".html.php",[
 *       "$table" =>  $plusieurs ? $concours : $concours[0]
 *   ]);
 * 
 * permet de rendre une liste ou une fiche selon le nombre de résultats retournés 
 * le parametre rendu dans affichage permet de rendre le tableau possèdant plusieur entitée ou un seule selon le nombre de retour de la base de donnée 
 * 
 * 
 */ 


