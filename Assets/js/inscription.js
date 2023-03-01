import { urls } from "./urls.js";

window.addEventListener("load", () => {
    $(".roles").remove();
    let divReponse =
        `<div class='reponse' id='reponse'>
            <ul>
            </ul>
        </div>`;
    $("#adresse").on("keydown", () => {
        
        let adresse = $("#adresse").val();
        if (adresse.length > 3) {
            // si l'input possede plus de 3 caracteres alors la recherche s'effectue
            $.ajax({
                type: "GET",
                url: urls.urlAPIAdresse,
                data: {
                    q: adresse, // un string d'une taille minimum de 3
                    limit: 10, //la limite pour le nombre de résultats reçus
                    autocomplete : 1 // booléen pour l'auto-complétion des navigateurs
                },
                success: (data) => {
                    // créer 2 variables 
                    // reponse sera un string qui contient toutes les réponse qui serront affichée (3 max)
                    let reponse = '';
                    let tour = 1;
                    $(data.features).each((i, obj) => {
                        if (tour <= 3) {
                            tour++;
                            console.log(obj.properties);
                            let prop = obj.properties;
                            if (prop.type !== 'housenumber') {
                                tour += -1;
                            } else {
                                // je récupère la region depuis la propriété contexte qui contient " numéro du département , nom du département , nom de la région " (donc je divise le string avec split() sur les virgules et ne prend que l'index 2 (3eme information/nom de la region))
                                let region = prop.context.split(',')[2]
                                reponse += `<li val='${prop.name}' region='${region}' postal='${prop.postcode}'>${prop.label}</li>`;
                            }
                            
                        }
                        
                    })
                    if (reponse) {
                        if (!$("#reponse")[0]) {
                            $("#adresse").parents(".col-md-6").append(divReponse);
                        }
                        $('#reponse ul').html(reponse);
                        $('#reponse li').each(function (index, li) {
                            $('#reponse li')[index].addEventListener("click", () => {
                                $('#adresse').val($($('#reponse li')[index]).text());
                                $('#region').val($($('#reponse li')[index]).attr("region"));
                                $('#departement').val($($('#reponse li')[index]).attr("postal"));
                                $('#reponse').remove()
                            })
                            
                        });
                    } else {
                        $("#reponse").remove();
                    }
                    
                },
                dataType : "json"
            });
        } else {
            // retire la div de reponse ajax si l'input possede moins de 3 caracteres
            if ($("#reponse")[0]) {
                $("#reponse").remove();
            }
        }
    })
    

})