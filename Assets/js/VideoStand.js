$(window)

window.addEventListener("load", () => {
    console.clear()
    
    class VideoStand {

        videos = $('.case');
        videoActive = 0;
        getVideo() {
            return $(this.videos[this.videoActive]);
        } 
        highlight() {
            this.getVideo().appendTo("#highlight");
            $('#highlight iframe').css("height", () => {
                // ajuste le height en fonction de la taille de la div highltight moins la taille du titre de la vidéo
                return ($("#highlight").css("height").split("px")[0] - $("#highlight h4").css("height").split("px")[0]) + "px";
            });
        }

        init() {
            // initialise l'objet en lançant les fonction necessaires
        }
        
    }


    let stand = new VideoStand();
    stand.highlight();
    console.log(stand.getVideo());
})