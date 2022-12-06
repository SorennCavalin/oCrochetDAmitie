<?php


namespace Controllers;

use Modeles\Bdd;
use Modeles\Session;
use PDO;

class AjaxController extends BaseController {

    public function recherche(){
        header('Access-Control-Allow-Origin: *');
        if($_POST){

            extract($_POST);



            // vérification rapide des données minimum requises (on sait jamais)
           
            if($search === false || $table === false || $where === false){
                Session::messages('danger', "Je ne peux pas recherche sans savoir quoi chercher et comment");
                return "Recherche annulée";
            }

            // vérifie ici même $limit et lui donne le bout de requete nécessaire. Si limit n'est pas set lui donne un string vide pour ne pas casser les requete
            if (isset($limit)){
                $limit = "LIMIT $limit";
            } else {
                $limit = "";
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
                    $recherches = Bdd::selection(['table' => "projet","where" => "WHERE nom LIKE '%$nom%' $limit"]);

                    foreach($recherches as $recherche){
                        foreach($recherche->getConcours() as $resultats){
                            $resultat[] = $resultats;
                        }
                    }
                    
                    return $this->affichageAjax("concours/liste.html.php",[
                        "concours" => $resultat,
                        "resultat" => "Un total de " . count($resultat) . " résultats trouvés" 
                    ]);
                }
            }

            // si on recherche avec un nom 
            // un user sera cherché avec une maj et tout minuscule les autres entreront tel quel dans la recherche (avec un traitement anti intrusion dans les 2 cas)
            // retourne une liste
            if ($where === "nom") {
                // si on cherche un user, met tout en minuscule avec une maj en premier sinon le mot est cherché tel quel (avec un peu de sécu quand même )
                $nom = ($table === "user") ? ucfirst(strtolower($this->traitementString($search))) : $this->traitementString($search);
                $recherche = Bdd::selection(["table" => $table, "where" => "WHERE $where LIKE '%$nom%' $limit"] );
                return $this->affichageAjax("$table/liste.html.php",[
                    ($table === "concours") ? "$table" : "$table" . "s" => $recherche
                ],false);
            }


            // si la recherche est une date
            // utilise $precision et $search pour recherche une date exacte ou dans les 3 mois d'ecarts au maximum
            // retourne une liste si plusieurs résultat sont retournés (même en date exacte) et une fiche si la date exacte ne représente qu'un seul enregistrement
            if (strpos($where, "date")){
                // garantie le bon format de la date reçu
                $dateRecherche = date("Y-m-d",strtotime($search));
                if($precision === 0){
                    if($recherche_date_exacte = Bdd::selection(["table" => $table, "where" => "WHERE $where = $dateRecherche $limit"])){
                        // vérifie si plusieurs dates sont pareils que celle reçue et s'adapte
                        if(count($recherche) > 1){
                            return $this->affichageAjax("$table/liste.html.php", [
                                "$table" . "s" => $recherche_date_exacte
                            ]);
                        }
                        return $this->affichageAjax("$table/fiche.html.php", [
                            "$table" => $recherche_date_exacte
                        ]);
                    }
                    

                } 
                if ($precision === 1){
                    // strtotime("+/- n y/m/d") permet de rajouter ou retirer du temps du timestamp donné à la fonction
                    // ex: strtotime("+1 day", "2022-12-01") => 2022-12-02
                    $recherche_date_anterieur = Bdd::selection(["table" => $table, "where" => "WHERE $where < $dateRecherche and $where > " . date("Y-m-d",strtotime("-1 months",$dateRecherche) . " $limit") ($limit) ]);
                    $recherche_date_superieur = Bdd::selection(["table" => $table, "where" => "WHERE $where > $dateRecherche and $where > " . date("Y-m-d",strtotime("+1 months",$dateRecherche) . " $limit")]);
                    foreach ($recherche_date_anterieur as $date){
                        $dates[] = $date;
                    } 
                    foreach ($recherche_date_superieur as $date){
                        $dates[] = $date;
                    }
                    return $this->affichageAjax("$table/liste.html.php",[
                        "$table" . "s" => $dates
                    ]);
                }
                if($precision === 3){
                    $recherche_date_anterieur = Bdd::selection(["table" => $table, "where" => "WHERE $where < $dateRecherche and $where > " . date("Y-m-d",strtotime("-3 months",$dateRecherche) . " $limit")]);
                    $recherche_date_superieur = Bdd::selection(["table" => $table, "where" => "WHERE $where > $dateRecherche and $where > " . date("Y-m-d",strtotime("+3 months",$dateRecherche) . " $limit")]);
                    foreach ($recherche_date_anterieur as $date){
                        $dates[] = $date;
                    } 
                    foreach ($recherche_date_superieur as $date){
                        $dates[] = $date;
                    }
                    return $this->affichageAjax("$table/liste.html.php",[
                        "$table" . "s" => $dates
                    ]);
                }
            }


            // si aucun des cas spécifiques plus haut n'est recherché une recherche basique sera effectuée
            $search = $this->traitementString($search);
            $recherche = Bdd::selection(["table" => $table, "where" => "WHERE $where LIKE '%$search%' $limit"]);
            return $this->affichageAjax("$table/liste.html.php",[
               ($table === "concours") ? "$table" : "$table" . "s" => $recherche
            ]);


        }
    }

    public function dynaForm(){
        header('Access-Control-Allow-Origin: *');

        // recuperation des nom des tables et des colonnes de la base de donnée

        $toutesColonnes = Bdd::connexion()->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'ocrochet'")->fetchAll(PDO::FETCH_ASSOC);
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