<?php 

namespace Controllers;

use Controllers\BaseController;
use Modeles\Bdd;
use Modeles\Session;

class ProjetController extends BaseController{

     public function afficher(){
          if (!Session::isAdmin()){
               $this->redirectionError();
           }
          $page = empty($_GET["id"]) ? 1 : $_GET["id"];
          $nbParPage = 10;
          $offset = (($page - 1) * $nbParPage) - 1;

          $projets = Bdd::selection(["table" => "projet", "where" => "LIMIT " . ($page == 1 ? "" : "$offset," ) . " $nbParPage"]) ;

          $pageMax =  count($projets) !== 10 ?  true : false;
               
          $this->affichageAdmin("projet/liste.html.php" , [
               "projets" => $projets,
               "pageMax" => $pageMax,
               "page" => $page
          ]);

     }


     public function ajouter(){
          if (!Session::isAdmin()){
               $this->redirectionError();

           }
          if(!empty($_POST)){

               extract($_POST);

               if(isset($nom)){
                    if(strlen($nom) >= 3 && strlen($nom) <= 40){
                         $nom = $this->traitementString($nom);
                    } else {
                         Session::messages('danger', "Le nom est trop grand ou trop petit");
                    }
               } else {
                    Session::messages("danger" , "Un nom est necessaire au projet");
               }


               if(isset($date_debut) && isset($date_fin)){
                    $check = true;
                    if(!isValid($date_debut)){
                         Session::messages("danger" , "La date de début n'est pas conforme");
                         $check = false;
                    }
                    if(!isValid($date_fin)){
                         Session::messages("danger" , "La date de fin n'est pas conforme");
                         $check = false;
                    }
                    if($check && ($date_debut > $date_fin)){
                         Session::messages("danger", "Le projet ne peut pas commencer après sa fin verifiez les dates");
                    }
               } else {
                    Session::messages("danger" , "Une date de fin est necessaire au projet");
               }

               if(isset($page)){
                    $page = $this->traitementString($page);
               } else {
                    Session::messages("danger","Un affichage pour le projet est nécéssaire");
               }

               if(Bdd::selection([ "table" => "projet" , "where" => "WHERE nom = '$nom'"])){
                    Session::messages("danger", "Un projet avec le nom '$nom' existe déjà.");
               }
               if(!Session::getItemSession("messages")) //S'il n'y a pas d'erreur, on upload
               {
                    if(Bdd::insertBdd("projet",["nom" => $nom, "date_fin" => $date_fin , "date_debut" => $date_debut, "page" => $page]))
                         //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                    {
                         Session::messages("success" , "Le projet a bien été enregistré");
                         $this->redirection(lien("projet"));
                    }
                    else //Sinon (la fonction renvoie FALSE).
                    {    
                         Session::messages("danger" , "Erreur lors de l'enregistrement");
                    }
               } else {
                    $this->affichageAdmin("projet/form.html.php",
                    [
                         "nom" => $nom,
                         "date_debut" => $date_debut, 
                         "date_fin" => $date_fin,
                         "page" => $page
                    ]);
               }
          } else {
               $this->affichageAdmin("projet/form.html.php");
          }
     }

     public function modifier($id){
          if (!Session::isAdmin()){
               $this->redirectionError();

           }
          $projet = Bdd::selectionId([ "table" => "projet" ],$id);
          if(!empty($_POST)){
               extract($_POST);
               $tableau = [];

               if(isset($nom)){
                    if(strlen($nom) >= 3 && strlen($nom) <= 40){
                         if($this->traitementString($nom) !== $projet->getNom())
                         $tableau["nom"] = $this->traitementString($nom);
                    } else {
                         Session::messages('danger', "Le nom est trop grand ou trop petit");
                    }
               }


               if(isset($date_debut) && isset($date_fin)){
                    $check = true;
                    if(!isValid($date_debut)){
                         Session::messages("danger" , "La date de début n'est pas conforme");
                         $check = false;
                    }
                    if(!isValid($date_fin)){
                         Session::messages("danger" , "La date de fin n'est pas conforme");
                         $check = false;
                    }
                    if($check && ($date_debut > $date_fin)){
                         Session::messages("danger", "Le projet ne peut pas commencer après sa fin verifiez les dates");
                    }
                    if ($check && ($date_debut < $date_fin)){
                         if ($date_debut !== $projet->getDate_debut()){
                              $tableau["date_debut"] =   $date_debut;
                         }
                         if ($date_fin !== $projet->getDate_fin()){
                              $tableau["date_fin"] = $date_fin;
                         }
                         
                    }

               }
               
               // traitement du fichier envoyé
               if (!empty($_FILES)){
                    $fichier = basename($_FILES['page']['name']);
                    if ($fichier !== $projet->getPage()){
                         $tableau["page"] = $fichier;
                    }
                    $fichier = strtr($fichier, 
                         'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
                         'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                    $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
     
                    $taille_maxi = 100000;
     
                    $taille = filesize($_FILES['page']['tmp_name']);
     
                    $extensions = array('.html', '.php');
     
                    $extension = strrchr($_FILES['page']['name'], '.'); 
     
                    //Début des vérifications de sécurité...
                    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
                    {
                         Session::messages("danger",'Vous devez uploader un fichier de type html, php');
                    }
                    if($taille>$taille_maxi)
                    {
                         Session::messages("danger", 'Le fichier fait plus de 100Mo');
                    }
                    
                    if($existe = Bdd::selection([ "table" => "projet" , "where" => "WHERE nom = '$nom' AND id != " . $projet->getId()])){
                         Session::messages("danger", "Un projet avec le nom '$nom' existe déjà.");
                    }
                    if(!Session::getItemSession("messages")) //S'il n'y a pas d'erreur, on upload
                    {
                         if(!file_exists("./Templates/vues/projets/" . $fichier)){
                              if(move_uploaded_file($_FILES['page']['tmp_name'], "./Templates/vues/projets/" . $fichier)){
                                   if(Bdd::update("projet",$tableau,$projet->getId()))
                                   //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                                   {
                                        Session::messages("success" , "Le projet a bien été modifié");
                                   }
                                   else //Sinon (la fonction renvoie FALSE).
                                   {    
                                        if(unlink("./Templates/vues/projets/" . $fichier)){
                                             Session::messages("danger" , "Le projet n'a pas pu etre modifié");
                                        } else {
                                             Session::messages("danger", "erreur fatal le projet n'a pas été enregistré et le fichier n'a pas été supprimer du dossier, appelez à l'aide!");
                                        }
                                   
                                   }
                              }  else {
                              Session::messages("danger" , "Une Erreur s'est produit lors de l'upload du fichier");
                              }
                         } else {
                         if(Bdd::update("projet",$tableau,$projet->getId())){
                              Session::messages("success" , "Le projet a bien été modifié");
                              $this->redirection(lien("projet"));
                         }
                        
                    } 
                    }
               } else {
                    if(Bdd::update("projet",$tableau,$projet->getId())){
                         Session::messages("success" , "Le projet a bien été modifié");
                         $this->redirection(lien("projet"));
                    }
               }
          }

          $this->affichageAdmin("projet/form.html.php",[
               "nom" => $projet->getNom(),
               "date_debut" => $projet->getdate_debut(),
               "date_fin" => $projet->getdate_fin(),
               "page" => $projet->getPage(),
               "mode" => "modify"
          ]);
     }

     public function supprimer($id) {

          if (!Session::isAdmin()){
              $this->redirectionError();
  
          }
  
          Bdd::dropRelie("projet",$id,"concours");
          $this->redirection(lien("projet"));
          
     }
  

     public function detail($id){
          if (!Session::isAdmin()){
               $this->redirectionError();

          }

          $projet = Bdd::selectionId([ "table" => "projet" ],$id);
          $this->affichageAdmin("projet/fiche.html.php",[
               "projet" => $projet
          ]);
     }

     public function accueil(){
          if(!$projets = Bdd::selection(["table" => "projet", "where" => "WHERE date_fin >= '". date("Y-m-d",time()) ."' ORDER BY date_fin DESC"])){
               $pause = true;
               $projets = Bdd::selection(["table" => "projet", "where" => "ORDER BY date_fin DESC LIMIT 3"]);
          }
          

          $this->affichage("projet/accueil.html.php",[
               "projets" => $projets,
               "css" => "projet",
               "js" => "projets",
               "pause" => isset($pause) ? $pause : false
          ]);   
     }
}