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
           
            if($search === false || $table === false || $where === false){
                Session::messages('danger', "Je ne peux pas recherche sans savoir quoi chercher et comment");
                return "Recherche annulée";
            }

            // donne une valeur vide a limit au cas ou l'utilisateur n'en a pas choisi
            if (!isset($limit)){
                $limit = "";
            }
            if (!isset($order) || $order === "Ne pas classer"){
                $order = "" ;
            }


            // recherche avec id classique retourne 1 seul resultat sous forme de fiche
            if($where === "id" ){
                $recherche = Bdd::selectionId(["table"=>$table],$search);
                return $this->affichageAjax("$table/fiche.html.php",[
                    "$table" => $recherche,
                ]);
            }

            // recherche les projets qui sont en lien avec un projet précis 
            // un nom ou un id peut être entré et la recherche se fera dans les 2 cas
            // en cas de recherche avec prenom plusieurs projets peuvent être sélectionnés, ce qui réduira la precision de la recherche
            // si un seul concours est trouvé a partir de la recherche fiche.html.php sera utilisée 
            // sinon une liste sera affichée pour que l'utilisateur puisse choisir le concours qu'il veut
            if ($where === "projet_id" ){
                if (is_numeric($search)){
                    $recherche = Bdd::selectionId(["table"=>'projet'],$search);
                    $concours = $recherche->getConcours();
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
            if ($where === "nom") {
                // si on cherche un user, met tout en minuscule avec une maj en premier sinon le mot est cherché tel quel (avec un peu de sécu quand même )
                $nom = ($table === "user") ? ucfirst(strtolower($this->traitementString($search))) : $this->traitementString($search);
                $recherche = Bdd::selection(["table" => $table, "compare" => "$where", "like" => "'%$nom%'", "limit" => $limit, "order" => $order]);
                return $this->affichageAjax("$table/liste.html.php",[
                    ($table === "concours") ? "$table" : "$table" . "s" => $recherche
                ],false);
            }


            // si la recherche est une date
            // utilise $precision et $search pour recherche une date exacte ou dans les 3 mois d'ecarts au maximum
            // retourne une liste si plusieurs résultat sont retournés (même en date exacte) et une fiche si la date exacte ne représente qu'un seul enregistrement
            if (strpos($where, "date") !== false){
                // garantie le bon format de la date reçu
                $dateRecherche = date("Y-m-d",strtotime($search));
                if($precision === 0){
                    if($recherche_date_exacte = Bdd::selection(["table" => $table, "compare" => $where, "where" => "= $dateRecherche", 'limit' => $limit , "order" => $order])){
                        // vérifie si plusieurs dates sont pareils que celle reçue et s'adapte
                        if(count($recherche_date_exacte) > 1){
                            return $this->affichageAjax("$table/liste.html.php", [
                                "$table" . "s" => $recherche_date_exacte
                            ]);
                        }
                        return $this->affichageAjax("$table/fiche.html.php", [
                            "$table" => $recherche_date_exacte
                        ]);
                    }
                    

                } 
                elseif ($precision === 1){
                    // strtotime("+/- n y/m/d") permet de rajouter ou retirer du temps du timestamp donné à la fonction
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
                    // return $this->aucuneReponse($table,"un $table avec une $date". (($precision) ? " d'environ $precision mois autour du $dateRecherche" : "le $dateRecherche"),"date",($rattrapage ?? false));
            }

            if ($where === "type"){
                $type = $this->traitementString($search);

                Bdd::selection(["table" => $table]);
            }


            // // si aucun des cas spécifiques plus haut n'est recherché une recherche basique sera effectuée
            // $search = $this->traitementString($search);
            // $recherche = Bdd::selection(["table" => $table, "where" => "WHERE $where LIKE '%$search%' $limit"]);
            // return $this->affichageAjax("$table/".( $plusieurs ? "liste" : "fiche" ).".html.php",[
            //    ($table === "concours") ? "$table" : "$table" . "s" => ($plusieurs ? $recherche : $recherche[0])
            // ]);


        }
    }

    public function dynaForm(){
        header('Access-Control-Allow-Origin: *');

        // recuperation des nom des tables et des colonnes de la base de donnée

        $toutesColonnes = Bdd::getTablesNames('ocrochet');
        // je choisi ici les tables et colonnes que je ne veut pas afficher dans ma recherche
        $champsIndesirables = ["roles",'lien','page','mdp',"telephone"];
        $colonnesIndesirables = ["participant", "don_details"];
        $entites = [];
        // si la requete a bien fonctionner le tableau ne devrait pas rendre false
        if($toutesColonnes){
            // pour chaque ligne renvoyée par la bdd on verifie que le nom de la table ne fais pas partie de ceux qui sont dans le tableau indésirable, idem pour les colonnes
                foreach($toutesColonnes as $colonne){
                    if (!in_array($colonne["TABLE_NAME"],$colonnesIndesirables)){
                        // création de ['table'] pour pouvoir afficher les noms des tables du côté js
                        // pour le premier tour on pose directement le nom de la table dans le tableau mais a partir du 2ème on vérifie que le nom n'est pas déjà dedans (pour eviter les doublons qui casseront le code) (il y a autant de nom de table que de colonnes donc filtrer est nécessaire)
                        if(isset($entites["tables"])){
                            if(!in_array($colonne["TABLE_NAME"],$entites["tables"])){
                                $entites["tables"][] = $colonne["TABLE_NAME"];
                            }
                        } else {
                            $entites["tables"][] = $colonne["TABLE_NAME"];
                        }
                        // créations d'arrays qui auront comme noms les noms des tables avec dedans les noms de chaques colonnes
                        if(!in_array($colonne["COLUMN_NAME"],$champsIndesirables)){
                            $entites['colonnes'][$colonne["TABLE_NAME"]][] = $colonne["COLUMN_NAME"];
                            
                        }
                    }
            }
            echo json_encode($entites);
        } else {
            echo "Aucune colonne trouvée";
        }
        
    }
}