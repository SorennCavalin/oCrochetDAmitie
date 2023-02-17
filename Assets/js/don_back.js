import { urls } from "./urls.js";

window.addEventListener("load", () => {
    let checks = $(".checkReception");
    let confirmation = $("#divConfirmation");

    checks.each(function (index, check) {
        // je met check dans une variable pour le retransformer en obj jquerry pour une meilleur utilisation
        let soi = $(check);
        soi.on("click", () => {
            // je pose une div qui bloque les cliques externes au popup
            $("body").append("<div id='div_blocage'></div>");
            // recupere l'id du don coché
            let id = soi.attr("idreception");
            // je prend le span a coté du check cliqué
            let etat = $("#etat" + id);
            // is(":checked") revoi true si la checkbox est cochée false sinon
            let checkVal = soi.is(":checked");
            // retire la classe bootstrap display none du popup de confirmation
            confirmation.removeClass("d-none");
            // chang le texte du pop up selon la checkbox cliquée
            $("#id").html(id);
            $("#nom").html(soi.attr("nom"));
            // le texte change en fonction de la checkbox 
            if (checkVal === false) {
                $('#confirmation').html("d'annuler la confirmation de réception");
            } else {
                $('#confirmation').html("de confirmer la réception")
            }
            // si le popup est confirmer alors ajax
            $("#confirmer").on("click", (e) => {
                e.preventDefault();
                $.ajax({
                    type: "post",
                    url: urls.confirmationColis,
                    data: {
                        // j'envoi l'id du coli et la directive (false ou true)
                        id: id,
                        checked : checkVal
                    },
                    dataType: "json",
                    success: function () {
                        // change le span a coté de la checkbox cliqué si le changement réussi
                        etat.html(`${ checkVal ? "reçu" : "attente" }`)
                        // retire div bloquage
                        $('#div_blocage').remove();
                    },
                    error: function () {

                    }
                });
            })
            // si clique sur annuler le popup se ferme, la div blocage disparait et la checkbox retourne a son état initiale
            $("#annuler").on("click", (e) => {
                e.preventDefault();
                // remet display none au popup confirmation
                confirmation.addClass("d-none");
                // retire les evenement click sur les botutons lorsqu'ils ne sont pas affichés
                $('#confirmer').off("click");
                $('#confirmer').off("click");
                // retire div bloquage
                $('#div_blocage').remove();
            })
        })
        
    });
    

})