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
    console.log(ligne);

    ligne.each((indice,valeur) => {
        let carre = valeur.children;
        console.log(carre)
        console.log(carre[carre.length - 1].children[2])
        carre[carre.length - 1].children[2].addEventListener("click",(e) => {
            e.preventDefault();
            $popup.removeClass("closed")
            $('#cible').text(carre[1].innerText)
            $bouton.attr("href", lien + "/" + carre[0].innerText)
        })
    })
})