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
            console.log(this.getProjets());
            console.log(this.getIdActuel());
            console.log(action);
            if (action === "Plus") {
                this.setIdActuel(id + 1);
                // si + que 2 (le 3eme projet) retourne au projet 1
                if (this.getIdActuel() > 2) {
                    this.setIdActuel(0);
                }
                this.afficherProjet();
            } else if (action === "Plus") {
                this.setIdActuel(id - 1);
                // si - que 0 (le 1eme projet) va au projet 3 (indice 2)
                if (this.getIdActuel() < 0) {
                    this.setIdActuel(2);
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
                })
            })
            
        }

        init() {
            // initialise l'objet en lançant les fonction necessaires
            // affiche le projet 1
            this.afficherProjet()
            // active les fleches permettant de slider
            this.flechesChange()
        }
    }


    let slider = new SliderProjet();
    slider.init();
    console.log(slider.getProjets())
})