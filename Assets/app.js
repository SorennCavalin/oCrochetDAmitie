var $appel = '<a href="tel:+33612153980" class="appel"> 06 12 15 39 80</a>'



function mobilecheck() {
    return (typeof window.orientation !== "undefined") 
      || (navigator.userAgent.indexOf('IEMobile') !== -1
      );
};



window.addEventListener('load', () => {

    let $network = $('.network');
    let $nav = $('.nav-ul');
    let $hamburger = $('.hamburger');
    let $tel = $('#telephone')
    

    // verification mobile pour le lien telephone

    if(mobilecheck()){
        $tel.html($appel);
    }


    // menu hamburger
    $hamburger.click(() => {
        $hamburger.toggleClass("open")
        $nav.toggleClass("slide")
        $('body').toggleClass("noscroll")
    })


    // deplacement du network , ajout de classes , et réinitialisation de toutes les modif en fonction la taille de l'ecran

    if (document.documentElement.clientWidth < 900){


        // deplacement de network dans nav-ul si la page charge a moins de 900 px de width (taille a la quelle le hamburger apparait)
        $nav.prepend($network);


        // ajout et retrait de classes pour les modifiaction css necessaire a l'apparition du hamburger
        $nav.addClass("color");
        $network.removeClass("header-network");
        $nav.addClass("haut");


    }
    window.addEventListener("resize",(e) => {
        if (document.documentElement.clientWidth < 900){
            // ajout et retrait de classes pour les modifiaction css necessaire a l'apparition du hamburger ainsi que le deplacement de network dans nav-ul
            $nav.addClass("haut");
            $nav.addClass("color");
            $nav.addClass("transition");
            $network.removeClass("header-network");
            $nav.prepend($network);
        } else {
            // modification network
            $network.addClass("header-network");

            // modifications nav-ul
            $nav.removeClass("color");
            $nav.removeClass("slide");
            $nav.removeClass("transition");
            $nav.removeClass("haut");

            //modification hamburger
            $hamburger.removeClass("open");

            // modification body
            $('body').removeClass("noscroll");
            
            // remise de network dans nav
            $('nav').children().first().after($('.nav-ul .network'));
        };
    })


    // ajout d'une classe au header lors du scroll vers le bas et retire la classe lorsque le site est tout en haut

    // if (window.scrollY > $("header").height()) {
    //         $("body").addClass("sans-header");
    //         $("header").addClass("decolle");
    // }

    // window.addEventListener("scroll", () => {
    //     if (window.scrollY > $("header").height()) {
    //         $("header").addClass("decolle");
    //         $("body").addClass("sans-header");
    //     } else if (window.scrollY === 0) {
    //         $('header').removeClass("decolle");
    //         $('body').removeClass("sans-header");
    //     }
    // })


    // pose un boutton qui ferme les alert

    $('.alert').each(function (index, boite) { 
        boite = $(boite);
        boite.append('<div class="suppr">x</div>');
        if (boite.hasClass("alert-success") || boite.hasClass("alert-secondary")) {
            setTimeout(() => {
                console.log("ok")
                boite.remove();
            },5000)
        }
        
    })
    $('.suppr').each((i, suppr) => {
        suppr = $(suppr);
        suppr.on("click", () => {
            let parent = suppr.parent("div.alert");
            suppr.parents("div.alert").remove();
            
        })
    });
    

})


