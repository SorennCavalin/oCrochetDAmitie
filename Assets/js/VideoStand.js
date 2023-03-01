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
            this.getVideo().appendTo("#highlight");
            $('#highlight iframe').css("height", () => {
                // ajuste le height en fonction de la taille de la div highltight moins la taille du titre de la vidéo pour ne pas sortir de la div
                // récupère la taille avec css("height") puis casse le rendu (ex: 10px) avec "px" ce qui rend ['10', ''] et on prend l'index 0 (10) 
                // tout ça moins la meme chose mais pour le titre de la video et on rajoute px à la fin pour que la taille soit reconnue par le navigateur
                return ($("#highlight").css("height").split("px")[0] - $("#highlight h4").css("height").split("px")[0]) + "px";
            });
        }

        changeVideo() {
            $('.grille iframe').on("click", (e) => {
                e.preventDefault();
                console.log(e.target);
            })
        }

        init() {
            // initialise l'objet en lançant les fonction necessaires

            // pose des id pour reconnaitre et manipuler les vidéos 
            let nb = 0
            this.videos.each((index, video) => {
                $(video).attr('id', nb);
                nb++;
            })

            // met en avant la premiere vidéo (plus récente)
            this.highlight();

            // active le changement de vidéo au clique sur une vidéo dans la grille 
            this.changeVideo()


        }
        
    }

    
    let stand = new VideoStand();
    stand.init();
})