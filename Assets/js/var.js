const urls = {
    urlAPIAdresse: "https://api-adresse.data.gouv.fr/search/",
    /**
    * Les parametres en get de la requete ajax pour recuperer les regions sont 
    * @param {*} nom 
    * le nom du département recherchée
    * @param {*} fields
    * les données souhaitée, nom et code pour ma part
    */
    UrlAPIDepartement: (recherche) => {
        let code = true
        if (isNaN(parseInt(recherche))) {
            code = false;
        }
        return "https://geo.api.gouv.fr/departements" + code ? "nom=" + recherche : "code=" + recherche;
    },
    /**
    * Les parametres en get de la requete ajax pour recuperer les regions sont 
    * @param {*} nom 
    * le nom de la region recherchée
    * @param {*} fields
    * les données souhaitée, nom et code pour ma part
    */
    UrlAPIRegion: "https://geo.api.gouv.fr/regions/",
    /**
    * 
    * @param {*} codeDepartement 
    * Le code de la région dont on veut récuperer les départements
    * @param {*} fields
    * Les données à recupérer depuis la requete ajax, nom pour ma part
    */
    UrlAPIDepartementDeRegion : (codeRegion) => {
        return "https://geo.api.gouv.fr/regions/" + codeRegion + "/departements";
    }

}

export { urls };

// $("#inputRue").keyup(function(event) {
//     // Stop la propagation par défaut
//           event.preventDefault();
//           event.stopPropagation();
  
//           let rue = $("#inputRue").val();
//           $.get('https://api-adresse.data.gouv.fr/search/', {
//               q: rue,
//               limit: 15,
//               autocomplete: 1
//           }, function (data, status, xhr) {
//               let liste = "";
//               $.each(data.features, function(i, obj) {
//                   console.log(obj.properties);
//                   // données phase 1 (obj.properties.label) & phase 2 : name, postcode, city
//                   // J'ajoute chaque élément dans une liste
//                   liste += '<li><a href="#" name="'+obj.properties.label+'" data-name="'+obj.properties.name+'" data-postcode="'+obj.properties.postcode+'" data-city="'+obj.properties.city+'">'+obj.properties.label+'</a></li>';
//               });
//               $('.adress-feedback ul').html(liste);
  
//               // ToDo: Au clic du lien voulu, on envoie l'info en $_POST
//               $('.adress-feedback ul>li').on("click","a", function(event) {
//                   // Stop la propagation par défaut
//                   event.preventDefault();
//                   event.stopPropagation();
  
//                   let adresse = $(this).attr("name");
  
//                   $("#inputRue").val($(this).attr("data-name"));
//                   $("#inputCodePostal").val($(this).attr("data-postcode"));
//                   $("#inputVille").val($(this).attr("data-city"));
  
//                   $('.adress-feedback ul').empty();
//               });
  
//           }).error(function () {
//               // alert( "error" );
//           }).always(function () {
//               // alert( "finished" );
//           }, 'json');
//       });