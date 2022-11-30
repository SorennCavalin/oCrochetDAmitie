var entities = {
    projet: 
        ["id","Nom", "date_fin", "date_debut"],
    user:
        ["id","Nom","Email","Telephone","Adresse","Prenom","date_inscription"],
    concours:
        ["id","Nom", "date_fin", "date_debut", "projet_id"],
    don:
        ["id","Organisme", "Donataire", "Date", "Type", "Quantite"],
    video:
        ["id","Nom","Type"]
};


window.addEventListener("load", () => {

    function message(type, message,cible) {
        $(cible).append(`<div class="alert alert-${type} mt-1">${message}</div>`)
    }

    var table = $("#table");
    var selecteur = $("#selecteur");
    var check = $("#check")
    var limit = $('#limit')
    var maximum = $('#maximum')


    // ajout de l'evenement change a mon input table pour que tout les autres input contiennent les propriétés de la table selectionnée
    // suppression des option des select pour les remplacer par les nouvelles et condition avec le classement pour le laisser optionnel
    table.change(() => {
        console.log(table.val())

        $('#classement').children().remove()
        $('#classement').append(`<option> Ne pas classer</option>`)
        selecteur.children().remove()
        Object.keys(entities).forEach(element => {
            if (table.val() === element) {
                entities[element].forEach((element, index) => {
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
                    selecteur.append(
                        `<option value="${element}">${lecteur ? lecteur : element}</option>`
                    );
                    $('#classement').append(`<option value="${element}">${lecteur ? lecteur : element}</option>`)
                })
            }
        });

        // désactivation de classement et limit lors du changement de selecteur du fait que id est automatiquement choisi au changement
        $('#limit').attr("disabled", "true")
        $('#classement').attr("disabled", 'true')
            
        // modification du champs de saisie pour la cohérence avec le selecteur choisi
        // desactivation du classement et de la limite pour la recherche d'identifiant 

        selecteur.change((e) => {
            console.log(selecteur.val())
            if (selecteur.val().includes("date")) {
                $('#text_search').attr("type", "date") 
                $('#text_search').attr("placeholder", "Veuillez rentrer une date valide ex: 31/12/2022") 
                
                $('#limit').removeAttr("disabled")
                $('#classement').removeAttr("disabled")
                
            } else if (selecteur.val() === "id") {
                $('#text_search').attr("type", "number") 
                $('#text_search').attr("placeholder", "veuillez rentrer l'identifiant numérique recherché") 
    
                $('#limit').attr("disabled", "true")
                $('#classement').attr("disabled",'true')
            } else {
                $('#text_search').attr("type", "text") 
                $('#text_search').attr("placeholder", "")  
    
                $('#limit').removeAttr("disabled")
                $('#classement').removeAttr("disabled")
            }
    
            
            
        })
    })

   

    // retiré

    // ajout de l'evenement click pour la checkbox de classement
    // lors du clique la checkbox devien un select qui contient les propriétées de la table sélectionnée au préalable
    // les options de classement disponibles s'aligneront au changement de la table grâce à la fonction du dessus
    // $('#checked').click((e) => {
    //     ouvert = true
    //     check.children().remove();
    //     Object.keys(entities).forEach(element => {
    //         if (table.val() === element) {
    //             entities[element].forEach((element) => {
    //                 if (element === "date_debut") {
    //                     var lecteur = "Date de début";
    //                 }
    //                 if (element === "date_fin") {
    //                     var lecteur = "Date de fin";
    //                 }
    //                 if (element === "date_inscription") {
    //                     var lecteur = "Date d'inscription";
    //                 }
    //                 if (element === "id") {
    //                     var lecteur = "Identifiant";
    //                 }
    //                 if (element === "projet_id") {
    //                     var lecteur = "Projet en lien";
    //                 }
    //                 $("#classement").append(
    //                     `<option value="${element}">${lecteur ? lecteur : element}</option>`
    //                 );
    //             })
    //         }
    //     });
    // })

    

    // affichage de la valeur de l'input range pour la précision du choix de l'utilisateur
    // 2 évènements pour la compatibilité de certains navigateurs


    limit.val(0)
    limit[0].addEventListener("input",() => {
        maximum.html("limite de " + limit.val() + " résultats")
    })
    limit[0].addEventListener("change",() => {
        maximum.html("limite de " + limit.val() + " résultats")
    })


    // ajout de l'évènement click au bouton de la nav et affichage du pop up de recherche suivi d'une requete ajax pour l'affichage du résultat de la recherche
    $("#search").on("click", (e) => {
        e.preventDefault();
        $('#div_recherche').removeClass('recherche')
    })

    // empeche les clic externes au pop up de recherche
    $(document).click((e) => {
        if (!$('#div_recherche').hasClass("recherche")) {
            // je n'ai pas réussi a le faire fonctionner dans l'autre sens alors bon...
            if (!($('#div_recherche').is(e.target) || !$('#div_recherche').has(e.target).length)) {
            } else {
                e.preventDefault()
            }
        }
    })

    // ajout du bouton de fermeture (span)
    $(".span-bouton-2").click((e) => {
        if (!$('#div_recherche').hasClass('recherche')) {
            $('#div_recherche').addClass('recherche')
        }
    })
    

    // clonage du formulaire vierge pour le retour en arrière si nouvelle recherche voulu
    var retour = $('#recherche').clone(true);

    $('#recherche').submit((e) => {
        
        // annulation de l'envoi du formulaire et retire les messages d'erreur lors d'un nouveau submit pour permettre l'envoi si plus d'erreur
        e.preventDefault();
        $("div.alert").remove()

        let $table = $('#table')
        let $selecteur = $('#selecteur')
        let $classement = $('#classement')
        let $limit = $('#limit')
        let $tableau = new Object;

        // verification de table selecteur et classement pour empecher l'entrée de donnée non-prévues dans le système mais permet tout de meme de ne pas classer ou de ne pas mettre une limite à la recherche
        if ($table = $table.val()) {
            if ($table === "user" || $table === "projet" || $table === "concours" || $table === "don" || $table === "video") {
                $tableau["table"] = $table;
            } else {
                message("danger","Aucun enregistrement de ce type n'existe",".table")
            }
        } else {
            message("danger","Une recherche ne peut pas s'effectuer sans savoir quoi chercher.",".selecteur")
        }

        if (($selecteur = $selecteur.val())) {
            if (typeof $selecteur === "string") {
                if(entities[$table].includes($selecteur)) {
                    $tableau["where"] = $selecteur;
                } else {
                    message("danger","le selecteur choisi n'est pas reconnu",".selecteur")
                }
            }
        } else {
            message("danger","Un selecteur est obligatoire.",".selecteur")
        }
        
        if (($classement = $classement.val())) {
            if (typeof $classement === "string") {
                console.log($classement)
                if(entities[$table].includes($classement) || $classement === "Ne pas classer") {
                    $tableau["order"] = $classement;
                } else {
                    message("danger","le classement choisi n'est pas reconnu",".classement")
                }
            }
        }

        if ($limit = $limit.val()) {
            if (parseInt($limit) !== NaN) {
                if ($limit != 0) {
                    $tableau["limit"] = $limit
                }
            }
        }

        // obligation de remplir l'input search pour effectuer une recherche
        if (($recherche = $('#text_search').val()) !== "") {
            $tableau["search"] = $recherche
        } else {
            message("danger","Une recherche ne peut pas s'effectuer sans savoir quoi chercher.","#text_recherche")
        }


        // si aucun message d'erreur n'à été envoyé, on procède a la requête
        if (!$("div.alert")[0]) {
            
            $.post($('#recherche').attr("action"), $tableau,
                function (data, textStatus, jqXHR) {
                    // rejout de la classe resultat au pop-up pour s'ajuster au format des fichiers renvoyés
                    $('#div_recherche').addClass("resultat");
                    // on retire le formulaire et on insert les données renvoyées par le serveur dans une div qui sera supprimée lors du retour
                    $('#recherche').remove();
                    $('#div_recherche').append("<div id='resultat_recherche' class='mt-3'></div>");
                    $('#resultat_recherche').append(data);
                    // ajout de l'evenement pour le retour en arrière sur le span bouton 1 (flèche)
                    $(".span-bouton-1").click(() => {
                        $("#resultat_recherche").remove();
                        $('#div_recherche').append(retour);
                        retour = "";
                        // retire la classe resultat pour reprendre le format du formulaire
                        $('#div_recherche').removeClass("resultat");

                    })

                    // remise de l'evenement fermeture sur la croix (obsolete avec le clone)
                    // $(".span-bouton-2").click((e) => {
                    //     if (!$('#div_recherche').hasClass('recherche')) {
                    //         $('#div_recherche').addClass('recherche')
                    //     }
                    // })

                }
            );
        }
    })
    

})