import { urls } from "./urls.js";

window.addEventListener("load", () => {

    

    class Recherche{
        /**
         * La div dans laquelle le résultat de la recherche apparaîtra
         */
        div_resultat = "<div id='resultat_recherche' class='mt-3'></div>";
        /**
         * l'entièretée du pop up de recherche
         */
        div_recherche =
            `<div id="div_recherche" class=' position-fixed border border-dark top-50 start-50 translate-middle p-5 lg-p-4 bg-white' style='z-index : 2000'>
                <div class="d-flex justify-content-between div-boutons"><span class="span-bouton-1 recherche"></span><span class="span-bouton-2">x</span></div>
                <form id='recherche' action="http://localhost/oCrochetDAmitie/ajax/recherche" method="POST">
                    <h4 class="text-center mb-4">Vous allez faire une recherche spécifique. <br> Nous avons quelques question à poser pour vous rendre le meilleur résultat</h4>
                    <div class="mb-3 table">
                        <label for="table">Quel type recherchez-vous ?</label>
                        <select id="table" class="form-control" name="table">
                        </select>
                    </div>
                    <div class="mb-3 selecteur">
                        <label for="selecteur">par quoi rechercher ?</label>
                        <select id="selecteur" class="form-control" name='selecteur'>
                        </select>
                    </div>
        
                    <div class='mb-3 ' id='text_recherche'>
                        <label for="">Entrez votre recherche</label>
                        <input class='form-control' type="number" id='text_search' name='search'>
                    </div>
        
                    <div class="mb-3 classement">
                        <label id="change">Par quoi les classer ?</label>
                        <select id="classement" class="form-control" name="classement" disabled>
                            <option selected> Ne pas classer </option>
                            <option value="id">Identifiant</option>
                            <option value="nom">Nom</option>
                            <option value="role">Role</option>
                            <option value="prenom">Prenom</option>
                            <option value="date_inscription">Date d'inscription</option>
                        </select>
                    </div>
        
                    <div class="mb-3 limit">
                        <label for="classement">choisir une limite ?</label>
                        <input type="range" class="form-range" id="limit" name="limit" disabled>
                        <p id="maximum" class="text-center"></p>
                    </div>
        
                    <button type="submit" class="btn btn-primary">lancer la recherche</button>
                </form>
            </div>`;
        /**
         * la div pour la précision de la recherche pour les dates
         */
        div_dates_pre =
            `<div class="mb-3 precision">
                <label for="precision">précision de la recherche</label>
                <select id="precision" class="form-control" name='precision'>
                    <option value="0">Date exacte</option>
                    <option value="1">Environ 1 mois</option>
                    <option value="3">Environ 3 mois</option>
                </select>
            </div>`;

        /**
         * les url pour la récupération des noms des tables/colonnes
         */ 
        recupTableUrl = "http://localhost/oCrochetDAmitie/ajax/dynaFormTables";
        recupColonnesUrl = "http://localhost/oCrochetDAmitie/ajax/dynaFormColonnes";

        /**
         * contient les choix possibles pour certaines recherches
         */
        types = [];

         /**
         * recupère l'input table (1er) par défaut / la valeur si (true)
         * 
         */ 
        getTable(val = null) {
            if (val) {
                return $("#table").val();
            }
            return $("#table");
        }

        /**
         * recupère l'input sélecteur (2eme) par défaut / la valeur si (true)
         * 
         */ 
        getSelecteur(val = null) {
            if (val) {
                return $("#selecteur").val();
            }
            return $("#selecteur");
        }

         /**
         * recupère l'input limit (dernier/la barre) par défaut / la valeur si (true)
         * 
         */ 
        getLimit(val = null) {
            if (val) {
                return $("#limit").val();
            }
            return $("#limit");
        }

         /**
         * recupère l'input classement (4eme) par défaut / la valeur si (true)
         * 
         */ 
        getClassement(val = null) {
            if (val) {
                return $("#classement").val();
            }
            return $("#classement");
        }

         /**
         * recupère l'input search (3eme/champs a remplir) par défaut / la valeur si (true)
         * 
         */ 
        getSearch(val = null) {
            if (val) {
                return $("#text_search").val();
            }
            return $("#text_search");
        }

         /**
         * recupère la div contenant l'input search permettant des modification de l'input search comme le remplacer par un select
         */ 
        getDivSearch() {
            return $("#text_recherche");
        }

         /**
         * recupère toute la div de recherche (le pop up entier)
         * 
         */ 
        getDivRecherche() {
            return $('#div_recherche');
        }

        /*
        *  recupère le bouton fermer (taper 2) ou le bouton retour (taper 1)
        */
        getBoutons(num) {
            if (num === 1) {
                return $(".span-bouton-1");
            } 
            if (num === 2) {
                return $(".span-bouton-2");
            }
        }

         /**
         * recupère le formulaire pour recupérer les données ou l'url/supprimer le formulaire
         * 
         */ 
        getForm() {
            return $('#recherche');
        }

        setSelect(array = false , api = false) {
            let cible = this.getDivSearch()
            if (!array) {
                cible.children().remove()
                cible.append(
                    `<label for="">Entrez votre recherche</label>
                    <input class='form-control' type="number" id='text_search' name='search' autocomplete='off'>
                    <div  class='position-relative' id='geolocal'>
                        <ul class="list-group position-absolute" id='adresse_trouve'></ul>
                    </div>
                    `
                )
               
            } else {
                cible.children().remove();
                cible.append(
                    `<label for="">Sélectionnez le type recherché</label>
                    <select name='search' id='text_search' class='form-control'></select>
                   `
                )
                array.forEach((val) => {
                    $('#text_search').append(
                        `<option value="${val}">${val}</option>`
                    )
                })
            }
        }

        toggleDivRecherche(etat) {
            if (etat) {
                $('.container').append(this.div_recherche);
                $('body').css("overflow","hidden");
            } else {
                this.getDivRecherche().remove();
                $('body').css("overflow","auto");
            }

        }

        toggleCLassementLimit(actif) {
            if (actif) {
                this.getLimit().removeAttr("disabled")
                this.getClassement().removeAttr("disabled")
            } else {
                this.getLimit().attr("disabled", "true")
                this.getClassement().attr("disabled", 'true')
            }
        }

        toggleAttrSearch(attr, val, remove = false) {
            !remove ? this.getSearch().attr(attr, val) : this.getSearch().removeAttr(attr);
        }

        recupTables() {
            let obj = this;
            // console.log(urls.TablesUrl)
            $.ajax({
                type: "POST",
                url: urls.TablesUrl,
                success: (data) => {
                    
                    // rajoute une propriété a mon objet pour la validation des données au submit
                    obj.tables = data
                    // place tout les noms de tables dans le select table
                    data.forEach(el => {
                        let elMaj = el.charAt(0).toUpperCase() + el.substr(1);
                        obj.getTable().append(`<option value='${el !== 'user' ? el + "'" : el + "' selected"}>${el !== "user" ? elMaj : "Utilisateur"}</option>`);
                    })
                },
                dataType: "json"
            }).done(
                // user étant selectionné par default, le mettre en brut ici ne dérange pas
                obj.recupColonnes("user")
            )
        }

        recupColonnes(table) {
            let obj = this;
            $.ajax({
                type: "POST",
                data: {"table" : table},
                url: urls.ColonnesUrl,
                success: (data) => {
                    // Place tout les noms de colonnes dans selecteur et classement
                    let colonnes = data.colonnes;
                    // si un choix (uniquement pour type de don en l'occurence) entre des valeurs prédéfinies est obligatoire, un array supplémentaire est reçu par le serveur contenant les choix dispo
                    // les choix sont stockés dans la propriété types de l'objet
                    let types = data.type ?? false;
                    if (types) {
                        obj.types = types;
                    }
                    obj.getSelecteur().children().remove()
                    obj.getClassement().children().remove()
                    colonnes.forEach(el => {
                        if (el === "date_debut") {
                            var lecteur = "Date de début";
                        }
                        if (el === "date_fin") {
                            var lecteur = "Date de fin";
                        }
                        if (el === "date_inscription") {
                            var lecteur = "Date d'inscription";
                        }
                        if (el === "id") {
                            var lecteur = "Identifiant";
                        }
                        if (el === "projet_id") {
                            var lecteur = "Projet en lien";
                        }
                        let elMaj = el.charAt(0).toUpperCase() + el.substr(1);
                        obj.getSelecteur().append(
                            `<option value="${el}">${lecteur ? lecteur : elMaj}</option>`
                        );
                        obj.getClassement().append(
                            `<option value="${el}">${lecteur ? lecteur : elMaj}</option>`
                        );
                    })
                    // rajoute 2 propriétés a mon objet pour la validation des données au submit
                    obj.types = types
                    obj.colonnes = colonnes
                },
                dataType: "json"
            })
        }

        recupAdresseAPI (recherche) {
            let reponse = "";
            let docEvent = false;
            let objet = this;
            if (recherche.length > 3) {
                $.ajax({
                    type: "GET",
                    url : urls.urlAPIAdresse,
                    data: {
                        q: recherche.toString(),
                        limit: 3,
                        autocomplete : 1
                    },
                    success: (data) => {
                        $(data.features).each((i,obj) => {
                            console.log(obj.properties);
                            let prop = obj.properties
                            reponse += `<li class="list-group-item reponse_adresse" val='${prop.name}'>${prop.label}</li>`
                        })
                        $('#adresse_trouve').html(reponse);
                        $('.reponse_adresse').each((i, li) => {
                            $(li).click(() => {
                                objet.getSearch().val($(li).attr("val"));
                                $('#adresse_trouve').empty();
                            })
                        })
                    },
                    dataType : "json"
                }).done(() => {
                    if (!docEvent) {
                        docEvent = true;
                        $(document).one("click",(e) => {
                            if ($(e.target) !== objet.getSearch()) {
                                $('#adresse_trouve').empty();
                            }
                            docEvent = false;
                        })
                    }
                })
            }
            
        }

        recupRegionAPI (recherche) {
            let reponse = "";
            let docEvent = false;
            let objet = this;
            console.log(urls.UrlAPIRegion(recherche));
            $.ajax({
                type: "GET",
                url : urls.UrlAPIRegion(recherche),
                data: {
                    fields : "nom",
                },
                success: (data) => {
                    $(data).each((i, region) => {
                        reponse += `<li class="list-group-item reponse_adresse" val='${region.nom}'>${region.nom}</li>`
                    })
                    $('#adresse_trouve').html(reponse);
                    $('.reponse_adresse').each((i, li) => {
                        $(li).click(() => {
                            objet.getSearch().val($(li).attr("val"));
                            $('#adresse_trouve').empty();
                        })
                    })
                },
                dataType : "json"
            }).done(() => {
                if (!docEvent) {
                    docEvent = true;
                    $(document).one("click",(e) => {
                        if ($(e.target) !== objet.getSearch()) {
                            $('#adresse_trouve').empty();
                        }
                        docEvent = false;
                    })
                }
            })
        }

        recupDepartementAPI (recherche) {
            let reponse = "";
            let docEvent = false;
            let objet = this;
            $.ajax({
                type: "GET",
                url : urls.UrlAPIDepartement(recherche),
                data: {
                    fields : "nom",
                },
                success: (data) => {
                    $(data).each((i, departement) => {
                        reponse += `<li class="list-group-item reponse_adresse" val='${departement.nom}'>${departement.nom}</li>`
                    })
                    $('#adresse_trouve').html(reponse);
                    $('.reponse_adresse').each((i, li) => {
                        $(li).click(() => {
                            objet.getSearch().val($(li).attr("val"));
                            $('#adresse_trouve').empty();
                        })
                    })
                },
                dataType : "json"
            }).done(() => {
                if (!docEvent) {
                    docEvent = true;
                    $(document).one("click",(e) => {
                        if ($(e.target) !== objet.getSearch()) {
                            $('#adresse_trouve').empty();
                        }
                        docEvent = false;
                    })
                }
            })
            
        }

        tableChange() {
            // ajout de l'evenement change a mon input table pour que tout les autres input contiennent les propriétés de la table selectionnée
            // suppression des option des select pour les remplacer par les nouvelles et condition avec le classement pour le laisser optionnel
            this.getTable().change((e) => {
                // recupère les colonnes et modifie classement/text_search/selecteur
                this.recupColonnes($(e.target).val())
        
                // désactivation de classement et limit lors du changement de selecteur du fait que id est automatiquement choisi au changement
                this.toggleCLassementLimit(false)
                    
                // retire la div de precision au changement de table
                if ($('.precision')[0]) {
                    $('.precision').remove()
                }
                // reset/remet l'input au changement de table 
                this.setSelect()
            })
        }

        selecteurChange() {
            this.getSelecteur().change(() => {
                // un tableau pour choisir les valeurs de selecteur qui utilisent l'api pour les adresses/departements
                let useAPI = ["adresse", "region", "departement"];
                console.log(this.selecteurChange(true));
                // change ou remet en premier l'input/select search pour que les changement du type de input se fasse bien par la suite
                if (this.getSelecteur(true) === "type") {
                    this.setSelect(this.type);
                } else if (useAPI.includes(this.getSelecteur(true))) {
                    // si la valeur de select est dans le tableau useAPI alors la fonction qui appelle les api est appelée a chaque entrée dans l'input search
                    this.setSelect(false, true);
                    this.searchChange(this.getSelecteur(true));
                } else {
                    this.setSelect();
                    this.searchChange("",false);
                }

                // vérifie que le search soit bien un input et pas un select
                if (this.getSearch().get(0).tagName.toLowerCase() === "input") {

                    // transforme #search en type date
                    if (this.getSelecteur(true).includes("date")) {

                        this.toggleAttrSearch("type", "date");
                        this.toggleAttrSearch("placeholder", "Veuillez rentrer une date valide ex: 31/12/2022");
                        // active limite et classement qui peuvent etre utilisé avec date
                        this.toggleCLassementLimit(true);

                        // transforme #search en type number
                    } else if (this.getSelecteur(true) === "id") {
                        this.toggleAttrSearch("type", "number") 
                        this.toggleAttrSearch("placeholder", "veuillez rentrer l'identifiant numérique recherché") 
                        // desactive limite et classement inutiles à une recherche id
                        this.toggleCLassementLimit(false);
                        
                        // remet #search en type text
                    } else {
                        this.toggleAttrSearch("type", "text") 
                        this.toggleAttrSearch("placeholder")  
                        // active limite et classement qui peuvent etre utilisé avec une recherche de texte
                        this.toggleCLassementLimit(true);
                    }
                }
                


                
                // rajout d'une div avec une donnée supplémentaire qui permet de choisir la précision des dates
                // négation a cause de ce que renvoie .search (-1 si pas trouvé 0 si oui)
                if (!this.getSelecteur(true).search("date")) {
                    if (!$('.precision')[0]) {
                        $(this.div_dates_pre).insertAfter('#text_recherche')
                    }
                } else {
                    $('.precision').remove()
                }

            })
        }

        searchChange(recup,actif = true) {
            let obj = this;
            if (actif) {
                switch (recup) {
                    case "adresse":
                        this.getSearch().off("keyup");
                        console.log("adresse activer")
                        this.getSearch().on("keyup", () => {
                            obj.recupAdresseAPI(obj.getSearch(true))
                        });
                        break;
                    case "region":
                        this.getSearch().off("keyup");
                        console.log("region activer")
                        this.getSearch().on("keyup", () => {
                            obj.recupRegionAPI(obj.getSearch(true))
                        });
                        break;
                    case "departement":
                        this.getSearch().off("keyup");
                        console.log("departement activer")
                        this.getSearch().on("keyup", () => {
                            obj.recupDepartementAPI(obj.getSearch(true))
                        });
                        break;
                }
            } else {
                this.getSearch().off("keyup");
            }
        }

        afficherLimitVal() {
        // affichage de la valeur de l'input range pour la précision du choix de l'utilisateur
        // 2 évènements pour la compatibilité de certains navigateurs
            this.getLimit().val(0);
            this.getLimit().on("input",() => {
                $('#maximum').html("limite de " + this.getLimit(true) + " résultats")
            })
            this.getLimit().on("change",() => {
                $('#maximum').html("limite de " + this.getLimit(true) + " résultats")
            })
        }

        ouvreRecherche(e) {
            // lancé dans le init pour ouvrir la div recherche et empecher le bouton de submit dans le vide
                e.preventDefault();
                this.toggleDivRecherche(true)
        }

        fermetureRecherche() {
         // ajout du bouton de fermeture (span)
            this.getBoutons(2).click((e) => {
                this.toggleDivRecherche(false);
                this.toggleAntiClick(false);
            })  
        }
        
        retourRecherche() {
            this.getBoutons(1).removeClass("recherche");
            this.getBoutons(1).click(() => {
                // supprime la div de recherche et repose une nouvelle avec une nouvelle initiation
                this.getDivRecherche().remove();
                this.toggleDivRecherche(true)
                this.init(false, false);
                this.getBoutons(1).addClass("recherche");
            })
        }

        toggleAntiClick(actif = true) {
            // empeche les clic externes au pop up de recherche
            if (actif) {
                $('body').append(`<div class='div_blocage'></div>`);
            } else {
                $('.div_blocage').remove();
            }
        }

        message(type, message,cible) {
            $(cible).append(`<div class="alert alert-${type} mt-1">${message}</div>`)
        }

        supprMessages() {
            $("div.alert").remove()
        }

        onSubmit() {
            this.getForm().submit((e) => {
                // annulation de l'envoi du formulaire et retire les messages d'erreur lors d'un nouveau submit pour permettre l'envoi si plus d'erreur
                e.preventDefault();
                this.supprMessages();

                // initialisation des variables pour les valeurs à tester
                let table = this.getTable(true);
                let selecteur = this.getSelecteur(true);
                let classement = this.getClassement(true);
                let limit = this.getLimit(true);
                let search = this.getSearch(true);
                let tableau = new Object;

                // vérification de l'existantce de la table choisie
                if (table) {
                    if (this.tables.includes(table)) {
                        tableau["table"] = table;
                    } else {
                        this.message("danger","Aucun enregistrement de ce type n'existe",".table")
                    }
                } else {
                    this.message("danger","Une recherche ne peut pas s'effectuer sans savoir quoi chercher.",".selecteur")
                }

                // vérification de l'existance de la colonne recherchée
        
                if ((selecteur)) {
                    if (typeof selecteur === "string") {
                        if(this.colonnes.includes(selecteur)) {
                            tableau["where"] = selecteur;
                        } else {
                            this.message("danger","le selecteur choisi n'est pas reconnu",".selecteur")
                        }
                    }
                } else {
                    this.message("danger","Un selecteur est obligatoire.",".selecteur")
                }

                // Vérification du type de données rentrée dans classement et de l'existance de la colonne qui servira a classer (si besoin de classer)
                
                if ((classement)) {
                    if(this.colonnes.includes(classement) || classement !== "id") {
                        tableau["order"] = classement;
                    } else {
                        this.message("danger","le classement choisi n'est pas reconnu",".classement")
                    }
                }

                // verification de la valeur de limit poour ne pas faire une recherche de 0 éléments
        
                if (limit) {
                    if (parseInt(limit) !== NaN) {
                        if (limit != 0) {
                            tableau["limit"] = limit;
                        }
                    }
                }
        
                // obligation de remplir l'input search pour effectuer une recherche
                if (search  !== "") {
                    tableau["search"] = search;
                } else {
                    if (selecteur === "id") {
                        this.message("danger", "Une recherche ne peut pas s'effectuer sans savoir quoi chercher.", "#text_recherche");
                    } else {
                        tableau["search"] = null;
                    }
                    
                }
                
                // si l'input precision est présent, récupère les données de #precision et verifie que la valeur est bien égale à celles disponibles
                if ($('#precision')) {
                    let precision = $('#precision').val()
                    let choix = [0, 1, 3]

                    choix.forEach(val => {
                        if (precision == val) {
                            tableau["precision"] = precision
                        }
                    })
                }
        
        
                // si aucun message d'erreur n'à été envoyé, on procède a la requête
                if (!$("div.alert")[0]) {
                    // donne accès a l'objet avec obj pour utiliser ses propriétées dans la fonction ajax
                    let obj = this
                    $.post(this.getForm().attr("action"), tableau,
                        function (data, textStatus, jqXHR) {
                            console.log(tableau)
                            console.log(data)
                            //ajout de la classe resultat pour s'ajuster au format des données reçues
                            obj.getDivRecherche().addClass("resultat");
                            // on retire le formulaire et on rentre les données renvoyées par le serveur dans une div qui sera supprimée lors du retour
                            obj.getForm().remove();
                            obj.getDivRecherche().append(obj.div_resultat);
                            $('#resultat_recherche').append(data);

                            // ajout de l'evenement pour le retour en arrière sur le span bouton 1 (flèche)
                            obj.retourRecherche()
                        }
                    );
                }
            })
            
        
        }

        init(e, retour = true) {
            // lancement de toute les fonctions nécessaire au fonctionnement de la recherche

            // pose de la div de recherche (prevent default integré)
            if (retour) {
                this.ouvreRecherche(e);
            }

            // recupération des tables et colonnes de la bdd et rempli le tableau entite avec (lancement en premier pour pouvoir utiliser les entite dans toutes le fonctions qui suivent, mais après la pose du form pour remplir table et selecteur)
            // les colonnes sont récupérées à la suite de la fonction recupTable
            this.recupTables();

            // fermeture de la div si clique en dehors
            this.toggleAntiClick();

            // affiche la valeur de limit au changement de celle-ci
            this.afficherLimitVal()

            // modifications lors du changement de la valeur de select (voir dans la classe pour plus d'infos)
            this.tableChange()

            // modifications de search pour s'accorder avec le type de données selectionnées
            this.selecteurChange()

            this.recupRegionAPI()

            // fermeture de la div si clique sur la croix
            this.fermetureRecherche()

            // activation de la vérification des données et lancement de la requête ajax au submit
            this.onSubmit()

            // rajout du placeholder de search pour ne pas empecher l'overwrite (jsp pourquoi ça marche pas)
            this.getSearch().attr("placeholder", "veuillez rentrer l'identifiant numérique recherché")

        }
    }



    $('#search').click((e) => {
        // lancement de l'objet recherche
        let recherche = new Recherche()
        recherche.init(e)
    })
})
