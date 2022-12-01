window.addEventListener("load", () => {
        
    class Recherche{

        div_resultat = "<div id='resultat_recherche' class='mt-3'></div>"
        div_recherche =
            `<div id="div_recherche" >
                <div class="d-flex justify-content-between div-boutons"><span class="span-bouton-1 recherche"></span><span class="span-bouton-2">x</span></div>
                <form id='recherche' action="http://localhost/oCrochetDAmitie/accueil/recherche" method="POST">
                    <h4 class="text-center mb-4">Vous allez faire une recherche spécifique. <br> Nous avons quelques question à poser pour vous rendre le meilleur résultat</h4>
                    <div class="form-group table">
                        <label for="table">Quel type recherchez-vous ?</label>
                        <select id="table" class="form-control" name="table">
                            <option value='user'>utilisateur</option>
                            <option value="projet">projet</option>
                            <option value="concours">concours</option>
                            <option value="don">dons</option>
                            <option value="video">videos</option>
                        </select>
                    </div>
                    <div class="form-group selecteur">
                        <label for="selecteur">par quoi rechercher ?</label>
                        <select id="selecteur" class="form-control" name='selecteur'>
                            <option value="id">Identifiant</option>
                            <option value="Nom">Nom</option>
                            <option value="Email">Email</option>
                            <option value="Role">Role</option>
                            <option value="Telephone">Telephone</option>
                            <option value="Adresse">Adresse</option>
                            <option value="Prenom">Prenom</option>
                            <option value="date_inscription">Date d'inscription</option>
                        </select>
                    </div>
        
                    <div class='form-group' id='text_recherche'>
                        <label for="">Entrez votre recherche</label>
                        <input class='form-control' type="number" id='text_search' name='search'>
                    </div>
        
                    <div class="form-group classement">
                        <label id="change">Par quoi les classer ?</label>
                        <select id="classement" class="form-control" name="classement" disabled>
                            <option selected> Ne pas classer </option>
                            <option value="id">Identifiant</option>
                            <option value="Nom">Nom</option>
                            <option value="Role">Role</option>
                            <option value="Prenom">Prenom</option>
                            <option value="date_inscription">Date d'inscription</option>
                        </select>
                    </div>
        
                    <div class="form-group limit">
                        <label for="classement">choisir une limite ?</label>
                        <input type="range" class="form-control-range" id="limit" name="limit" disabled>
                        <p id="maximum" class="text-center"></p>
                    </div>
        
                    <button type="submit" class="btn btn-primary">lancer la recherche</button>
                </form>
            </div>`;
        div_dates_dist = 
           `<div class="form-group precision">
                <label for="precision">précision de la recherche</label>
                <select id="precision" class="form-control" name='precision'>
                    <option value="0">Date exacte</option>
                    <option value="1">Environ 1 mois</option>
                    <option value="3">Environ 3 mois</option>
                </select>
            </div>`
        entities = {
            projet: 
                ["id","Nom", "date_debut", "date_fin"],
            user:
                ["id","Nom","Prenom","Email","Telephone","Adresse","date_inscription"],
            concours:
                ["id","Nom", "date_fin", "date_debut", "projet_id"],
            don:
                ["id","Organisme", "Donataire", "Date", "Type", "Quantite"],
            video:
                ["id","Nom","Type"]
        };
        
        getTable(val = null) {
            if (val) {
                return $("#table").val();
            }
            return $("#table");
        }

        getSelecteur(val = null) {
            if (val) {
                return $("#selecteur").val();
            }
            return $("#selecteur");
        }

        getTable(val = null) {
            if (val) {
                return $("#table").val();
            }
            return $("#table");
        }

        getLimit(val = null) {
            if (val) {
                return $("#limit").val();
            }
            return $("#limit");
        }

        getClassement(val = null) {
            if (val) {
                return $("#classement").val();
            }
            return $("#classement");
        }


        getClassement(val = null) {
            if (val) {
                return $("#classement").val();
            }
            return $("#classement");
        }

        getSearch(val = null) {
            if (val) {
                return $("#text_search").val();
            }
            return $("#text_search");
        }

        getDivRecherche() {
            return $('#div_recherche');
        }

        getBoutons(num) {
            if (num === 1) {
                return $(".span-bouton-1");
            } 
            if (num === 2) {
                return $(".span-bouton-2");
            }
        }

        getForm() {
            return $('#recherche');
        }

        toggleDivRecherche(etat) {
            if (etat) {
                $('.container').append(this.div_recherche);
            } else {
                this.getDivRecherche().remove();
            }

        }

        tableChange() {
            // ajout de l'evenement change a mon input table pour que tout les autres input contiennent les propriétés de la table selectionnée
            // suppression des option des select pour les remplacer par les nouvelles et condition avec le classement pour le laisser optionnel
            this.getTable().change(() => {
                this.getClassement().children().remove()
                this.getClassement().append(`<option> Ne pas classer</option>`)
                this.getSelecteur().children().remove()
                Object.keys(this.entities).forEach(element => {
                    if (this.getTable(true) === element) {
                        this.entities[element].forEach((element, index) => {
                            // ajustement de certaines données affichées à l'utilisateur pour améliorer la compréhension
                            if (element === "date_debut") {
                                var lecteur = "Date de début";
                            }
                            if (element === "date_fin") {
                                var lecteur = "Date de fin";
                            }
                            if (element === "date_inscription") {
                                var lecteur = "Date d'inscription";
                            }
                            if (element === "id") {
                                var lecteur = "Identifiant";
                            }
                            if (element === "projet_id") {
                                var lecteur = "Projet en lien";
                            }
                            this.getSelecteur().append(
                                `<option value="${element}">${lecteur ? lecteur : element}</option>`
                            );
                            this.getClassement().append(`<option value="${element}">${lecteur ? lecteur : element}</option>`)
                        })
                    }
                });
        
                // désactivation de classement et limit lors du changement de selecteur du fait que id est automatiquement choisi au changement
                this.getLimit().attr("disabled", "true")
                this.getClassement().attr("disabled", 'true')
                    
                // retire la div de precision au changement de table
                if ($('.precision')[0]) {
                    $('.precision').remove()
                }

                // remet search au type number au changement
                this.getSearch().attr("type", "number")
                this.getSearch().attr("placeholder", "veuillez rentrer l'identifiant numérique recherché")
            })
                
        }

        selecteurChange() {
            this.getSelecteur().change(() => {
                if (this.getSelecteur(true).includes("date")) {
                    this.getSearch().attr("type", "date") 
                    this.getSearch().attr("placeholder", "Veuillez rentrer une date valide ex: 31/12/2022") 
                    
                    this.getLimit().removeAttr("disabled")
                    this.getClassement().removeAttr("disabled")
                    
                } else if (this.getSelecteur(true) === "id") {
                    this.getSearch().attr("type", "number") 
                    this.getSearch().attr("placeholder", "veuillez rentrer l'identifiant numérique recherché") 
        
                    this.getLimit().attr("disabled", "true")
                    this.getClassement().attr("disabled",'true')
                } else {
                    this.getSearch().attr("type", "text") 
                    this.getSearch().removeAttr("placeholder")  
                    
                    this.getLimit().removeAttr("disabled")
                    this.getClassement().removeAttr("disabled")
                }
                // rajout d'une div avec une donnée supplémentaire qui permet de choisir la précision des dates
                if (!this.getSelecteur(true).search("date")) {
                    if (!$('.precision')[0]) {
                        $(this.div_dates_dist).insertAfter('#text_recherche')
                    }
                } else {
                    $('.precision').remove()
                }
            })
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
            // ajout de l'évènement click au bouton de la nav et affichage du pop up de recherche suivi d'une requete ajax pour l'affichage du résultat de la recherche;
                e.preventDefault();
                this.toggleDivRecherche(true)
        }

        fermetureRecherche() {
         // ajout du bouton de fermeture (span)
            this.getBoutons(2).click((e) => {
                this.toggleDivRecherche(false);
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

        antiClick() {
            // empeche les clic externes au pop up de recherche
            $(document).click((e) => {
                if ($('#div_recherche')) {
                    // je n'ai pas réussi a le faire fonctionner dans l'autre sens alors bon...
                    if (!($('#div_recherche').is(e.target) || !$('#div_recherche').has(e.target).length)) {
                    } else {
                        this.toggleDivRecherche(false)
                    }
                }
            })
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
        
                // verification de table selecteur et classement pour empecher l'entrée de donnée non-prévues dans le système mais permet tout de meme de ne pas classer ou de ne pas mettre une limite à la recherche

                // vérification de l'existantce de la table choisie
                if (table) {
                    if (Object.keys(this.entities).includes(table)) {
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
                        if(this.entities[table].includes(selecteur)) {
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
                    if(this.entities[table].includes(classement) || classement === "Ne pas classer") {
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
                    tableau["search"] = search
                } else {
                    this.message("danger","Une recherche ne peut pas s'effectuer sans savoir quoi chercher.","#text_recherche")
                }
        
        
                // si aucun message d'erreur n'à été envoyé, on procède a la requête
                if (!$("div.alert")[0]) {
                    // donne accès a l'objet avec obj pour utiliser ses propriétées dans la fonction ajax
                    let obj = this
                    $.post(this.getForm().attr("action"), tableau,
                        function (data, textStatus, jqXHR) {
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
            // fermeture de la div si clique en dehors
            // this.antiClick();
            // affichage de la valeur de limit
            this.afficherLimitVal()
            // modifications lors du changement de la valeur de select (voir dans la classe pour plus d'infos)
            this.tableChange()
            // modifications de search pour s'accorder avec le type de données selectionnées
            this.selecteurChange()
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
