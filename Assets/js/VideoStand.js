$(window)

window.addEventListener("load", () => {
    console.clear()
    
    class VideoStand {

        videos = $('.case');
        videoActive = 0;

        getVideo() {
            return $(this.videos[this.videoActive]);
        }

        setVideoActive(id) {
            this.videoActive = id;
        }
        

        highlight() {
            // pose la vidéo dans la div highlight
            this.getVideo().appendTo("#highlight");
            // retire la div blocage
            this.getVideo().find('.div_blocage').remove();
            // retire l'eventement click pour changer la vidéo
            $('#highlight .case').off("click")
            // modifie la taille du iframe pour aller avec la div highlight
            $('#highlight iframe').css("height", () => {
                // codé avant setHeight ça fonctionne très bien ne pas y toucher
                // ajuste le height en fonction de la taille de la div highltight moins la taille du titre de la vidéo pour ne pas sortir de la div
                // récupère la taille avec css("height") puis casse le rendu (ex: 10px) avec "px" ce qui rend ['10', ''] et on prend l'index 0 (10) 
                // tout ça moins la meme chose mais pour le titre de la video et on rajoute px à la fin pour que la taille soit reconnue par le navigateur
                return ($("#highlight").css("height").split("px")[0] - $("#highlight h4").css("height").split("px")[0]) + "px";
            });
        }
        
        changeHighlight() {
            // pose la div de blocage du clique
            this.setBlocage($('#highlight .case'));
            // remet une taille adéquate pour la div grille (n'importe quelle [1,2, etc] aurait suffit elle font toute la meme taille)
            this.setHeight($('#highlight iframe'), $('.grille .case')[0])
            // remet l'event click
            this.changeVideo($('#highlight .case'));
            // remet la div case dans la grille
            $('#highlight .case').appendTo(".grille");
            // pose la div cliquée dans le highlight
            this.highlight();
        }

        changeVideo(cible) {
            $(cible).on("click", (e) => {
                this.setVideoActive($(e.target).parents(".case").attr("id"));
                this.changeHighlight();
            })
        }

        setBlocage(cible){
            cible = $(cible);
            cible.prepend("<div class='div_blocage'></div>");
        }

        setHeight(cible, parent){
            parent = $(parent);
            cible = $(cible);
            cible.css("height", () => {
                return (parent.css("height").split("px")[0] - parent.find("h4").css("height").split("px")[0]) + "px";
            }) 
            return cible.css('height');
        }

        init() {
            // initialise l'objet en lançant les fonctions necessaires

            // pose des id pour reconnaitre et manipuler les vidéos 
            let nb = 0
            this.videos.each((index, video) => {
                $(video).attr('id', nb);
                nb++;
            })

            $('.grille .case').each((index, cible) => {
                this.setBlocage(cible)
                this.setHeight($(cible).find("iframe"), cible)
            })

            // met en avant la premiere vidéo (plus récente)
            this.highlight();

            // active le changement de vidéo au clique sur une vidéo dans la grille 
            this.changeVideo($('.grille .case'))

            // resize les vidéos lors de l'evenement resize pour ne pas avoir de casse
            window.addEventListener('resize', () => {
                console.log(this.setHeight($('iframe'),$('iframe').parents('.case')))
                this.setHeight($('.grille .case iframe'),$('.grille .case iframe').parents('.case'))
                this.setHeight($('#highlight iframe'),$('iframe').parents('#highlight'))
            })

        }
        
    }

    
    let stand = new VideoStand();
    stand.init();
})