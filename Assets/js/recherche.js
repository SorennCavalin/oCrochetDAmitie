var entities = {
    projet: 
        ["id","Nom", "date_fin", "date_debut"],
    user:
        ["id","Nom","Email","Role","Telephone","Adresse","Prenom","date_inscription"],
    concours:
        ["id","Nom", "date_fin", "date_debut", "projet_id"],
    don:
        ["id","Organisme", "Donataire", "Date", "Type", "Quantite"],
    video:
        ["id","Nom","Type"]
};



window.addEventListener("load", () => {

    var table = $("#table");
    var selecteur = $("#selecteur");
    var check = $("#check")

    table.change(() => {
        selecteur.children().remove()
        Object.keys(entities).forEach(element => {
            if (table.val() === element) {
                entities[element].forEach((element, index) => {
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
                })
            }
        });
    })

    $('#checked').click(() => {
        check.children().remove();
        check.append(
            `<select id="classement" class="form-control">
            </select>`
        )
        Object.keys(entities).forEach(element => {
            if (table.val() === element) {
                entities[element].forEach((element) => {
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
                    $("#classement").append(
                        `<option value="${element}">${lecteur ? lecteur : element}</option>`
                    );
                })
            }
        });
        $('#change').html("Par quoi ?");
    })
})