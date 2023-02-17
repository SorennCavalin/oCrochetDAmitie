window.addEventListener("load", () => {
    
    var $plus = $('#plus');
    var $type = $('#type')
    var $donataire = $('#donataire');
    var $org = $('#organisme');
    var $date = $('#date');

    // si il y a déja des div detail au chargement alors commence nb du dernier numéro et enleve ou non les input date et organisme/donataire
    if ($('div[dernier]').attr("dernier")) {
        var donNb = $('div[dernier]').attr("dernier");
        if ($type.val() === "envoi") {
            $donataire.css("display", "none");
        } else {
            $org.css("display", "none");
        }
    } else {
        $org.css("display","none");
        $donataire.css("display","none");
        $date.css("display", "none");
        var donNb = 0;
    }
    
     


    // modification de l'input pour correspondre a la selection de l'utilisateur sur l'input #type
    $type.change(() => {
        $date.css("display","block");
        if($('#type').val() === "reception"){
            // si l'utilisateur selectionne reception Le label de date ansi que l'input donataire apparait
            $('#label_date').text("Date de réception");
            $org.css("display","none");
            $('#organisme input').val()
            $donataire.css("display","block");
        } else if ($('#type').val() === "envoi") {
            // sinon si l'utilisateur choisi envoi le label de date sera différent et l'input organisme apparait 
            $('#label_date').text("Date d'envoi");
            $org.css("display","block");
            $('#donataire input').val("");
            $donataire.css("display","none");
        }
    })

    // rajout d'un input supplémentaire lors du clique sur le bouton #plus
    $plus.click((e) => {
        // incrémente donNb pour créer un nouvel id et name a chaque activation
        donNb++;
        e.preventDefault();
        // l'utilisateur peut cliquer pour rajouter autant de detailss que voulu.
        // afin de pouvoir les traiter du coté php on utilise un syntaxe d'array dans le name 
        // ansi l'input avec name = donDetails[details(nombre incrémenté)]["nom"] va rendre lors du submit dans un array appelé donDetails, un array avec un nom qui change a chaque nouvel input [detail(x)] qui contiendra la valeur du champs, c'est a dire nom
        // l'input qte quand a lui se servira du meme array donDetails dans celu ci le meme nom que nom (details(x)) mais cette fois une valeur qte
        // ce qui rend un $post comme ceci : $_POST[donDetails][details(x)][nom => "truc", qte => "1"]
        $('.form-group').last().before
        (`
        <label for="nom" id="label_detail${donNb}">don n°${donNb}</label>
        <div class='row mb-2' id="detail${donNb}">
            <div class="col"> 
                <input name="donDetails[details${donNb}][nom]" class="form-control noms" type="text" id='nom_don${donNb}' placeholder="nom de l'objet">
            </div> 
            <div class="col" > 
                <input name="donDetails[details${donNb}][qte]" class="form-control qtes" type="text" id='qte_don${donNb}' placeholder='quantité'>
            </div>
        </div>
        `);

        $plus.html("ajouter encore");
    }) 

   
})