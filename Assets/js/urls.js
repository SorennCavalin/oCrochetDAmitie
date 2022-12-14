const urls = {

    /**
     * l'url pour récupérer les adresses 
     * 
     * @param {string} q 
     * l'adresse rentrée dans l'input (q stands for query)
     * @param {string} limit
     * assez explicite
     * @param {string} autocomplete
     * 
     */

    urlAPIAdresse: "https://api-adresse.data.gouv.fr/search/",


    /**
     * @param {string} recherche
     * la valeur de l'input qui souhaite recupérer les départements
     * 
    * @important Le parametre en get de la requete ajax pour recuperer les regions est
    * @param {string} fields
    * les données souhaitée, nom et code pour ma part
    */
     UrlAPIDepartement: function (recherche) {
        return "https://geo.api.gouv.fr/departements?" + ((isNaN(parseInt(recherche))) ? "nom=" + recherche : "code=" + recherche);
    },
     
    
    /**
     * @param {String} recherche
     * la valeur de l'input qui souhaite recupérer les regions
     * 
    * @important Le parametre en get de la requete ajax pour recuperer les regions est
    * @param {String} fields
    * les données souhaitée, nom et code pour ma part
    */
     UrlAPIRegion: function (recherche) {
        return "https://geo.api.gouv.fr/regions?" + ((isNaN(parseInt(recherche))) ? "nom=" + recherche : "code=" + recherche);
    },
     
     
    /**
    * @param {Number} codeRegion 
    * Le code de la région dont on veut récuperer les départements.
    * 
    * @important Le parametre en get de la requete ajax pour recuperer les regions est :
    * 
    * @param {*} fields
    * Les données à recupérer depuis la requete ajax, nom pour ma part
    */
    UrlAPIDepartementDeRegion : (codeRegion) => {
        return "https://geo.api.gouv.fr/regions/" + codeRegion + "/departements";
    },

    /**
    * L'url pour la récupération des noms des tables de la bdd
    */
    TablesUrl: "http://localhost/oCrochetDAmitie/ajax/dynaFormTables",

    /**
    * L'url pour la récupération des colonnes de la table sélectionnée
    */
    ColonnesUrl : "http://localhost/oCrochetDAmitie/ajax/dynaFormColonnes"
}

export { urls };