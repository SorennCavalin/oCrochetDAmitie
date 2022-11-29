var $appel = '<a href="tel:+33612153980" class="appel"> 06 12 15 39 80</a>'



function mobilecheck() {
    return (typeof window.orientation !== "undefined") 
      || (navigator.userAgent.indexOf('IEMobile') !== -1
      );
};

function getUrl(parametre) {
    return "http://127.0.0.1/oCrochetDAmitie/" + parametre
}


window.addEventListener('load', ()=> {

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


    // deplacement du network , ajout de classes , et reinitialisation de toutes les modif depuis la taille de l'ecran

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

    // redimension du body et ajustement du mb pour placer le footer en bas  

    $('body').css("min-height",$(window).height()+"px")

    $(window).resize(() => {
        $('body').css("min-height",$(window).height()+"px")
    })

    $('body').css("padding-bottom",$('footer').outerHeight()+ "px")
})


