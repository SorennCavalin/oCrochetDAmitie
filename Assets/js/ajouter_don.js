window.addEventListener("load",() => {
    function message(type, message) {
        $("nav").after(`<div class="alert alert-${type}">${message}</div>`)
    }
    function clearMessages(){
        $("div.alert").remove()
    }

    var $plus = $('#plus');
    var $type = $('#type')
    var $donataire = $('#donataire');
    var $org = $('#organisme');
    var $date = $('#date');
    $org.css("display","none");
    $donataire.css("display","none");
    $date.css("display","none");
    var donNb = 0;
     


    // modification de l'input pour correspondre a la selection de l'utilisateur sur l'input #type
    $type.change(() => {
        if($('#type option:selected').text() === "Réception"){
            $date.css("display","block");
            $('#label_date').text("Date de réception");
            $org.css("display","none");
            $('#organisme input').val()
            $donataire.css("display","block");
        } else {
            $date.css("display","block");
            $('#label_date').text("Date d'envoi");
            $org.css("display","block");
            $('#donataire input').val("");
            $donataire.css("display","none");
        }
    })

    // rajout d'un input supplémentaire lors du clique sur le bouton #plus
    $plus.click((e) => {
        donNb++;
        e.preventDefault();
        $('.form-group').last().before(`
        <label for="nom" id="label_detail${donNb}">don n°${donNb}</label>
        <div class='form-row mb-2' id="detail${donNb}"><div class="col">  
            <input name="details[${donNb}][nom]" class="form-control noms" type="text" id='nom_don${donNb}' placeholder="nom de l'objet"> 
        </div> 
        <div class="col" >
            <input name="details[${donNb}][qte]" class="form-control qtes" type="text" id='qte_don${donNb}' placeholder='quantité'>
        </div>`);
    }) 

    // verification des valeurs et envoi d'un ou plusieurs messages si erreur 
    $("form").submit((e) => {
        e.preventDefault();
        clearMessages()

        var donataire = $('#donataire input').val();
        var organisme = $('#organisme input').val();
        var type= $('#type').val();
        var date = $('#date input').val();

        if (donataire == ""  && organisme == ""){

            message("danger", "Un don ne peut pas être reçu ou envoyé par personne")

        } else {

            if (donataire.length > 0 &&  organisme.length > 0) {

                message("danger", "Un don ne peut pas être à la fois reçu et envoyé")

            } else {

                if( organisme == "" &&(donataire.length > 50 || donataire.length < 3)){

                    message("danger", "le nom du donataire doit comporter de 3 a 50 caractères")
                
                }
                if(donataire == "" && (organisme.length > 50 || organisme.length < 3)){

                    message("danger", "le nom de l'organisme doit comporter de 3 a 50 caractères")
                    
                }
            }
            
        }
        
        if(type !== "envoi" && type !== "reception"){
            message("danger", "Le type fourni ne correspond pas au choix possibles")
        }
        if (new Date(date) == "Invalid Date"){
            message("danger",`La date entrée n'est pas valide`)
        }


        var details = {}
        var nb = 1
        $('.noms').each((ind,val) => {
            if(val.length < 3 && val.length > 30){
                message("danger", "le nom du détail n°" + nb + " est trop long ou trop court (entre 3 et 30 caractères)")
            }
            details[`detail${nb}`] = {}
                details[`detail${nb}`].nom = val.value;
            nb++
            
        })
        nb = 1
        $('.qtes').each((ind,val) => {
            if (isNaN(parseInt(val.value))){
                message("danger", "la quantité du détail n°" + nb + " n'est pas un nombre")
            }
            details[`detail${nb}`].qte = val.value;
            nb++
        })


        if (!$("div.alert")[0]) {
            // envoi d'une requete au serveur pour l'enregistrement des données et redirection sur la liste de tout les dons avec au moins une seconde de latence pour laisser le temps a php de traiter les données
            var requete = $.post(
                "http://127.0.0.1/oCrochetDAmitie/don/ajouterDetails",
                {
                    details: details, 
                    date: date, 
                    type: type, 
                    organisme: organisme, 
                    donataire : donataire
                },
                (data) => {
                    console.log(data)
                }
                )
                setTimeout(() => {
                    location.href  = location.href.split("don")[0] + "don"

                },1000)
        }
        
    })
})