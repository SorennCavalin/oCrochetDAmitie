<?php


namespace Controllers;

use Modeles\Bdd;
use Modeles\Session;

class AccueilController extends BaseController {

    public function afficher()
    {
        $this->affichage("accueil.html.php", [
            "css" => "accueil"
        ]);
    }
    public function afficherAdmin()
    {
        
        $this->affichageAdmin("stats.html.php");
    }

    public function recherche(){
        header('Access-Control-Allow-Origin: *');
        if($_POST){

            extract($_POST);


            // 2ème vérifiaction des données (on sait jamais)
           
            if($search === false || $table === false || $where === false){
                Session::messages('danger', "Je ne peux pas recherche sans savoir quoi chercher et comment");
                return "Recherche annulée";
            }



            if($where === "id" ){
                $recherche = Bdd::selectionId(["table"=>$table],$search);
                return $this->affichageRecherche("$table/fiche.html.php",[
                    "$table" => $recherche,
                ]);
            }

            if ($where === "projet_id" ){
                if (is_numeric($search)){
                    $recherche = Bdd::selectionId(["table"=>'projet'],$search);
                    $concours = $recherche->getConcours();
                    $plusieurs = count($concours) > 1;
                    return $this->affichageRecherche("$table/".( $plusieurs ? "liste" : "fiche" ).".html.php",[
                        "$table" =>  $plusieurs ? $concours : $concours[0]
                    ]);
                } else {
                    $nom = $this->traitementString($search);
                    $recherches = Bdd::selection(['table' => "projet","where" => "WHERE nom LIKE '%$nom%'"]);

                    foreach($recherches as $recherche){
                        foreach($recherche->getConcours() as $resultats){
                            $resultat[] = $resultats;
                        }
                    }
                    
                    return $this->affichageRecherche("concours/liste.html.php",[
                        "concours" => $resultat,
                        "resultat" => "Un total de " . count($resultat) . " résultats trouvés" 
                    ]);
                }
            }

            if ($where === "nom" || $where === "prenom") {
                $nom = ($table === "user") ? ucfirst(strtolower($this->traitementString($search))) : $this->traitementString($search);
                $recherche = Bdd::selection(["table" => $table, "where" => "WHERE nom = $nom"]);
                return $this->affichageRecherche("$table/liste.html.php",[
                    "$table" => $recherche,
                    "resultat" => (count($recherche) > 1) ? "Un total de " . count($recherche) . " résultats trouvés": "une seule correspondance trouvée" 
                ],false);
            }

            if (strpos($where, "date")){
                $dateRecherche = date("Y-m-d",strtotime($search));
                $recherche_date_exacte = Bdd::selection(["table" => $table, "where" => "WHERE $where = $dateRecherche"]);
                $recherche_date_anterieur = Bdd::selection(["table" => $table, "where" => "WHERE $where < $dateRecherche and $where > " . date("Y-m-d",strtotime("-3 months",$dateRecherche))]);
                $recherche_date_superieur = Bdd::selection(["table" => $table, "where" => "WHERE $where > $dateRecherche and $where > " . date("Y-m-d",strtotime("+3 months",$dateRecherche))]);
                

                if($recherche_date_exacte){
                    $this->affichageRecherche("$table/fiche.html.php", [
                        "$table" => $recherche_date_exacte
                    ]);
                }

                return $this->affichageRecherche("$table/liste.html.php",[

                ]);
                
            }



            // if (strpos($where,"date")){
            //     $where = date("Y-m-d",strtotime($where));
            // }


            
            // if ($check["limit"] === true ){
            //     $recherche = Bdd::selection(["table" => $table, "where" => "WHERE $where LIKE '%$search%' LIMIT $limit"]);
            // }
            // if($check["limit"] === false){
            //     $recherche = Bdd::selection(["table" => $table, "where" => "WHERE $where LIKE '%$search%'"]);
            // }

            

            return $this->affichageAdmin("$table/liste.html.php",[
               "$table" . "s" => $recherche
            ]);


        }
    }
}