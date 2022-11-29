window.addEventListener("load",() => {

    // version fiche
    var $popup = $('.popup');
    var $bouton = $('#delete');
    var $annuler = $('#close');

    $('#suppr').click(function (e) {
        e.preventDefault();
        $popup.removeClass('closed'); 
    })
    $annuler.click((e) => {
        $popup.addClass("closed");
    })


    // version liste

    var ligne = $("tbody tr");

    ligne.each((indice,valeur) => {
        let carre = valeur.children;
        // carre représente les cases du tableau.
        // le carré selectionné est le dernier (celui avec les boutons)
        // children cible le 3eme (et dernier) bouton, celui qui supprime
        carre[carre.length - 1].children[2].addEventListener("click",(e) => {
            e.preventDefault();
            $popup.removeClass("closed")
            $('#cible').text(carre[1].innerText)
            $bouton.attr("href", lien + "/" + carre[0].innerText)
        })
    })
})