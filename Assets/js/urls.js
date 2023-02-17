let urlDebut = "http://localhost/oCrochetDAmitie/";

const urls = {

    /**
     * l'url pour récupérer les adresses. Attention pour la recherche un minimum de 3 caractères son nécessaires
     * 
     * @param {string} q 
     * l'adresse rentrée dans l'input (q stands for query)
     * @param {string} limit
     * assez explicite
     * @param {string} autocomplete
     * 
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
    TablesUrl: urlDebut + "ajax/dynaFormTables",

    /**
    * L'url pour la récupération des colonnes de la table sélectionnée
    */
    ColonnesUrl:  urlDebut + "ajax/dynaFormColonnes",
    

    /**
     * L'url pour confirmer la réception du colis
     */
    confirmationColis :  urlDebut + "ajax/confirmerColis",

}

export { urls };