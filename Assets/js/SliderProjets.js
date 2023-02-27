window.addEventListener("load", () => {
    
    class SliderProjet{

        projets = $(".projet").detach();
        idActuel = 0;


        // retourne l'array des projets
        getProjets() {
            return this.projets;
        }
        // retourne toute la div de projet (en gros la page quasi-entière)
        getDivProjet() {
            return $('#projets');
        }
        // retourne le projet qui possède l'id actif 
        getProjet() {
            return $(this.getProjets()[this.getIdActuel()]);
        }
        // change l'id actif
        setIdActuel(id) {
            this.idActuel = id;
        }
        // récupère l'id actif
        getIdActuel() {
            return this.idActuel;
        }
    
        // affiche le projet actif
        afficherProjet() {
            this.getDivProjet().append(this.getProjet());
        }

        /**
         * change l'idActuel et affiche le nouveau projet actif
         * 
         * @param {string} action 
         * L'action effectuée par la fonction (+ ou -)
         */
        changeIdActuel(action) {
            let id = this.getIdActuel();
            this.getProjet().remove();
            if (action === "Plus") {
                this.setIdActuel(id + 1);
                // si + que 2 (le 3eme projet) retourne au projet 1
                if (this.getIdActuel() >= this.getProjets().length) {
                    this.setIdActuel(0);
                }
                this.afficherProjet();
            } else if (action === "Moins") {
                this.setIdActuel(id - 1);
                // si - que 0 (le 1eme projet) va au projet 3 (indice 2)
                if (this.getIdActuel() < 0) {
                    this.setIdActuel(this.getProjets().length - 1);
                }
                this.afficherProjet();
            }
        }

        flechesChange() {
            $(".fleche").each((index, fleche) => {
                // askip fleche has already been declared alors que je le fait tout le temps mais bon (ノへ￣、)
                let flecheJQ = $(fleche);
                flecheJQ.on("click", () => {
                    let action = flecheJQ.attr("id");
                    this.changeIdActuel(action);
                    $('#nb').html((this.getIdActuel() + 1) + "/" + this.getProjets().length);
                })
            })
            
        }

        afficheIdActuel() {
            // lorsqu'une fleche est en mouseover(hover css) la div du compte apparaît et disparait lors du mouseleave(fin de hover)
            $('.fleche').on("mouseover", () => {
                // $('#nb').;
                $('#nb').css("opacity", 1);
            })
            $('.fleche').on("mouseleave", () => {
                $('#nb').css("opacity", 0);
            })
        }

        ajusteMobile() {
            if (document.documentElement.clientWidth > 900) {
                // si la taille de l'ecran est plus grand que 900 px (tablette paysage minimum) alors les fleches disparaissent et ne deviennent visibles que lors du mouseover
                $('.fleche').css("opacity", 0);
                $('.projet').on("mouseover", (e) => {
                    $('.fleche').css("opacity", 1);
                })
                $('.projet').on("mouseleave", (e) => {
                    $('.fleche').css("opacity", 0);
                })
                // pose l'evenement aussi sur les fleche pour qu'elles ne disparaissent pas lorsque la souris passe dessus (hors de .projet)
                $('.fleche').on("mouseover", (e) => {
                    $('.fleche').css("opacity", 1);
                })
                $('.fleche').on("mouseleave", (e) => {
                    $('.fleche').css("opacity", 0);
                })
            } 
            window.addEventListener("resize", () => {
                
                if (document.documentElement.clientWidth > 900) {
                    // exactement le meme que le if hors de l'event
                    $('.fleche').css("opacity", 0);
                    $('.projet').on("mouseover", (e) => {
                        $('.fleche').css("opacity", 1);
                    })
                    $('.projet').on("mouseleave", (e) => {
                        $('.fleche').css("opacity", 0);
                    })
                    $('.fleche').on("mouseover", (e) => {
                        $('.fleche').css("opacity", 1);
                    })
                    $('.fleche').on("mouseleave", (e) => {
                        $('.fleche').css("opacity", 0);
                    })
                } else {
                    // si la fenetre est plus petite que 900 px (taille tablette) les fleches apparaissent et reste tout le temps visibles
                    $('.fleche').css("opacity", 1);
                    $('.projet').off("mouseover");
                    $('.projet').off("mouseleave");
                    // comme j'ai retirer les évènements mouseover et mouseleave je dois reposer les evenements pour l'affichage du numéro du projet
                    // this.afficheIdActuel();
                }
            })
        }

        init() {
            // initialise l'objet en lançant les fonction necessaires
            // affiche le projet 1
            this.afficherProjet();
            // active les fleches permettant de slider
            this.flechesChange();
            // active le hover sur les fleches pour montrer le numéro du projet/3
            this.afficheIdActuel();
            // active la transparance des fleche pour le format mobile pour ne pas gener la lecture du projet
            this.ajusteMobile()
        }
    }


    let slider = new SliderProjet();
    slider.init();
    console.log(slider.getProjets())
})