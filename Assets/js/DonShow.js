import { urls } from "./urls";

window.addEventListener("load", () => {
    class DonShow {
        dons = $('.don')

        getDons() {
            return this.dons
        }
        setDons() {
            this.dons = $(".don")
        }

        applyMargins() {
            let dons = this.getDons()
            let premierDon = $(dons[0]);
            let secondDon = $(dons[1]);
            console.log(premierDon)
            premierDon.css("border-top", "none");
            secondDon.css("border-top", "1px solid #000");
        }

        refreshLive() {
            let dernierDon = $(this.getDons()[0])

            $.post(urls.urlDernierDon,
                {
                    "dernierDon" : dernierDon.attr("id")
                },
                function (data, textStatus, jqXHR) {
                    if (data) {
                        data.foreach((index,don) => {
                            $('.dons').prepend(don);
                        });
                    }
                },
                "text"
            );
        }

        init() {
            this.applyMargins()
        }
    }


    let donShow = new DonShow();
    donShow.init()
})