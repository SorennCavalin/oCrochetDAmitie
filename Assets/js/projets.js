window.addEventListener("load",() => {
    var $projets = $('.projet');
    var $boutons = $('.arrow');


    $boutons.each(($indice,$bouton) => {
        $bouton.addEventListener("click",function () {
            console.log($projets[$indice])
            $projets[$indice].classList.toggle("ferme");
            $bouton.classList.toggle("retourne");
        })
    })

})