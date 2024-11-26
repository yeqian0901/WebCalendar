let date = new Date;


/********************************************************************************************/
/*           Calcul le premier jour de la semaine (lundi) de la date donnée                 */
/*                                et affiche en titre                                       */
/********************************************************************************************/

/* Calcule le nnombre de jour du mois février en fonction l'année, bissextile ou pas */
function nbJourFevrier(annee) {
    if (annee % 4 == 0) return 29;
    else return 28;
}


/**Renvoie le nombre de jour total du mois
 * 0 -> janvier ... 11 -> décembre
 */
function nbJourDuMois(annee, mois) {
    switch (mois) {
        case 0: case 2: case 4: case 6: case 7: case 9: case 11: return 31;
        case 3: case 5: case 8: case 10: return 30;
        case 1: return nbJourFevrier(annee);
    }

}


/* Test si c'est un lundi */
function estLundi(jourSemaine) {
    if (jourSemaine == 1) return true;
    else return false;
}

/* Test si c'est un dimanche */
function estDimanche(jourSemaine) {
    if (jourSemaine == 0) return true;
    else return false;
}

/* Calcul la date du premier jour de la semaine (lundi) de la date donnée  */
function calculSemaineActuel(date) {
    var annee = date.getFullYear();
    var mois = date.getMonth();
    var jour = date.getDate();
    var jourSemaine = date.getDay();

    if (estLundi(jourSemaine))
        return date;
    else if (estDimanche(jourSemaine)) { // car date.getDay() renvoie 0 pour dimanche
        if (jour > 6)
            date.setDate(jour - 6);
        else if (mois != 0) {
            date.set(mois - 1);
            date.setDate(nbJourDuMois(annee, mois - 1) + (jour - 6));
        }
        else {
            date.setYear(annee - 1);
            date.setMonth(11);
            date.setDate(nbJourDuMois(annee, 11) + (jour - 6));
        }
        return date;
    }
    else {
        if (jour > (jourSemaine - 1))
            date.setDate(jour - (jourSemaine - 1));
        else if (mois != 0) {
            date.setMonth(mois - 1);
            date.setDate(nbJourDuMois(annee, mois - 1) + (jour - (jourSemaine - 1)));
        }
        else {
            date.setYear(annee - 1);
            date.setMonth(11);
            date.setDate(nbJourDuMois(annee, 11) + (jour - (jourSemaine - 1)));
        }
        return date;
    }
}

function afficheSemaineActuel(idNom) {
    document.getElementById(idNom).innerText = calculSemaineActuel(date).toLocaleDateString("fr");
}

afficheSemaineActuel("semaine");

/*************************************************************************************/
/*         Calcul le premier jour de la semaine (lundi) de la date donnée            */
/*                    de la semaine précédent et suivante                            */
/*************************************************************************************/

/* Calcul la date du lundi de semaine précédente */
function semaineAvant(date) {
    var annee = date.getFullYear();
    var mois = date.getMonth();
    var jour = date.getDate();

    if (jour > 7)
        date.setDate(jour - 7);
    else if (mois != 0) {
        date.setMonth(mois - 1);
        var temp = nbJourDuMois(annee, mois - 1) + (jour - 7);
        date.setDate(temp);
    }
    else {
        date.setYear(annee - 1);
        date.setMonth(11);
        var temp = nbJourDuMois(annee, 11) + (jour - 7)
        date.setDate(temp);
    }
    return date;
}


/* Calcul la date du lundi de semaine suivante */
function semaineApres(date) {
    var annee = date.getFullYear();
    var mois = date.getMonth();
    var jour = date.getDate();

    if (jour <= nbJourDuMois(annee, mois) - 7)
        date.setDate(jour + 7);
    else if (mois != 11) {
        date.setMonth(mois + 1);
        var temp = nbJourDuMois(annee, mois) - jour - 7;
        date.setDate(-temp);

    }
    else {
        date.setYear(annee + 1);
        date.setMonth(0);
        var temp = nbJourDuMois(annee, mois) - jour - 7;
        date.setDate(-temp);
    }
    return date;
}


// function afficheSemaineAvant( idSemaine){
//    document.getElementById(idSemaine).innerText = semaineAvant(date).toLocaleDateString("fr");
// }

/*************************************************************************************/
/*      Actualise la page lors du click pour voir emploi du temps de la              */
/*                      semaine précédente et suivante                               */
/*************************************************************************************/

document.getElementById("avant").onclick = function () {
    let nouveauDate = semaineAvant(date);
    document.getElementById("semaine").innerText = nouveauDate.toLocaleDateString("fr");
    initTable("emploiDuTemp");
    ouvrirFormulaire("bouttonAjouteCours", "formulaire");
    console.log(nouveauDate);
    actualise(nouveauDate);
    console.log(date);
};

document.getElementById("apres").onclick = function () {
    let nouveauDate = semaineApres(date)
    document.getElementById("semaine").innerText = nouveauDate.toLocaleDateString("fr");
    initTable("emploiDuTemp");
    ouvrirFormulaire("bouttonAjouteCours", "formulaire");
    actualise(nouveauDate);
};


/*************************************************************************************/
/*                 Création de la table de l'emploi du temps                         */
/*************************************************************************************/

let nomJour = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"];
let nombreGroupe = 4;

/** Créer une liste ['8h00','8h15',......,'18h45','19h00'] */
function ajouteHoraire(horaireListe) {
    for (var i = 8; i <= 19; i++) {
        for (var j = 0; j <= 45; j += 15) {
            if (i == 19 && j != 0)
                break;
            else {
                var h = i + 'h' + j;
                if (j == 0) {
                    h = i + 'h00';
                }
                horaireListe.push(h);
            }
        }
    }
}

let horaireListe = [];
ajouteHoraire(horaireListe);


/** Créer la ligne des jours du tableau, lundi .... dimanche */
function creerJourTableau(idNom) {
    let div_ = $(idNom);
    div_.append('<tr id="jours"></tr>');
    let jours_ = $("#jours");
    jours_.append('<td></td>');
    for (let i = 0; i < nomJour.length; i++) {
        jours_.append('<th colspan="' + nombreGroupe + '" class="jour">' + nomJour[i] + '</th>');
    }
}

/** Créer la ligne des groupes du tableau, G1...G4 */
function creerGroupeTableau(idNom) {
    let div_ = $(idNom);
    div_.append('<tr id="groupes"></tr>');
    let jours_ = $("#groupes");
    jours_.append('<th></th>');
    for (let i = 0; i < nomJour.length; i++) {
        for (let j = 1; j <= nombreGroupe; j++) {
            jours_.append('<th class="groupe">G' + j + '</th>');
        }
    }
}

/** Créer le contenue du tableau, les cellules du tableau avec un bouton */
function creerContenuTableau(idNom) {
    let div_ = $(idNom);
    div_.append('<tbody id ="contenueCours"></tbody>');
    let tbody_ = $("#contenueCours");
    for (let i = 0; i < horaireListe.length; i++) {
        let temp = "ligne" + horaireListe[i];
        tbody_.append('<tr id="' + temp + '"></tr>');
        var ligne_cour = $("#" + temp);

        ligne_cour.append('<th class="horaire">' + horaireListe[i] + '</th>');
        for (let j = 0; j < (nomJour.length * nombreGroupe); j++) {
            let coord = 'ligne' + i + 'colonne' + j;
            ligne_cour.append('<td id = "' + coord + '"class="cour"> <button id = "bouton' + coord + '" class="bouttonAjouteCours" > </button>     </td>');
        }
    }
}

/** Créer le tableau (emploi du temp) */
function creerTableCours(idNom) {
    var div_ = $("#" + idNom);
    div_.append('<table id="table"></table>');
    var table_ = $("#table");
    creerJourTableau(table_);
    creerGroupeTableau(table_);
    creerContenuTableau(table_);
}

creerTableCours("emploiDuTemp");


/** Initialise le tableau */
function initTable(idNom) {
    let div_ = document.getElementById(idNom);
    div_.innerHTML = "";
    creerTableCours("emploiDuTemp");

}


/*************************************************************************************/
/*                     Bouton pour ouvrir le formulaire                              */
/*************************************************************************************/

function ouvrirFormulaire(classBouton, idFormulaire) {
    let boutons = document.getElementsByClassName(classBouton);
    for (let i = 0; i < boutons.length; i++) {

        boutons[i].onclick = function () {
            let formulaire = document.getElementById(idFormulaire);
            if (formulaire.style.display == "none")
                formulaire.setAttribute("style", "display:visible");
            else
                formulaire.setAttribute("style", "display:none");
        }
    }
}

ouvrirFormulaire("bouttonAjouteCours", "formulaire");


/*************************************************************************************/
/*                     Bouton pour la validation du formulaire                       */
/*************************************************************************************/

// function validationFormulaire() {
//     // Récupération du formulaire
//     const form = document.getElementById('validerFormulaire');

//     // Écoute de l'événement "submit" du formulaire
//     form.addEventListener('submit', (event) => {
//         // Empêcher la soumission classique du formulaire
//         event.preventDefault();

//         // Récupération des données POST
//         const data = new FormData(form);

//         // Envoi des données POST à un script PHP avec AJAX
//         const xhr = new XMLHttpRequest();
//         xhr.open('POST', 'index.php');
//         xhr.onload = function () {
//             // Traitement de la réponse du script PHP
//             console.log(this.responseText);
//         };
//         xhr.send(data);
//     });
// }


/*************************************************************************************/
/*                              Affiche slot                                         */
/*************************************************************************************/

/** Calcul l'index de la ligne du tableau en fonction de l'horaire */
function indexH(h) {
    for (let i = 0; i < horaireListe.length; i++) {
        if (horaireListe[i] == h) {
            //console.log(i);
            return i;
        }
    }
}

/** Calcul l'index de la colonne du tableau en fonction du jour et groupe */
function indexColonne(jourSemaine, groupe) {
    switch (groupe) {
        case "G1": return (jourSemaine - 1) * 4;
        case "G2": return (jourSemaine - 1) * 4 + 1;
        case "G3": return (jourSemaine - 1) * 4 + 2;
        case "G4": return (jourSemaine - 1) * 4 + 3;
    }
}



// function afficheCours(h1, h2, couleur) {
//     let indexH1 = indexH(h1);
//     let indexH2 = indexH(h2);
//     let n = indexH2 - indexH1 + 1;

//     let cellule =document.getElementById('ligne' + indexH1 + 'colonne0');
//     cellule.setAttribute("rowspan", n);
//     cellule.style.background = couleur;
//     document.getElementById('boutonligne' + indexH1 + 'colonne0').remove();

//     for (let i = indexH1 + 1; i <= indexH2; i++) {
//         document.getElementById('boutonligne' + i + 'colonne0').remove();
//         document.getElementById('ligne' + i + 'colonne0').remove();
//     }
// }


/** Affiche les slot dans le tableau */
function afficheCours(information) {
    Object.keys(information).forEach(key => {

        let dateCours = information[key]['date'];
        let debutCours = information[key]['horaireDebutCours'];
        let finCours = information[key]['horaireFinCours'];

        let typeDeCour = information[key]['typeDeCour'];
        let matiere = information[key]['matiere'];
        let enseignant = information[key]['enseignant'];
        let salle = information[key]['salle'];
        let groupeDuCours = information[key]['groupeDuCours'];
        let jourSemaine = information[key]['jourSemaine'];


        //calcul les index des cellules 
        let indexH1 = indexH(debutCours);
        let indexH2 = indexH(finCours);
        let n = indexH2 - indexH1 + 1;
        let indexC = indexColonne(jourSemaine, groupeDuCours);

        // console.log(debutCours);
        // console.log("indexH1 : " + indexH1);
        // console.log("indexC : " + indexC);

        //recupere la cellule de la ligne d'horaire debutCours et colonne du jour
        let cellule = document.getElementById('ligne' + indexH1 + 'colonne' + indexC);

        //fusion les cellules
        cellule.setAttribute("rowspan", n);

        //mettre la couleur
        cellule.style.background = "red";

        //creer du texte dans la cellule
        let slot = document.createElement("p");
        slot.innerHTML = typeDeCour + " <br>" + matiere + "<br>" + salle + "<br>" + enseignant;
        cellule.appendChild(slot);

        //enleve le boutton de la cellule  d'horaire debutCours et colonne du jour
        document.getElementById('boutonligne' + indexH1 + 'colonne' + indexC).remove();

        //enleve le button et la cellule d'horaire entre debutCours et finCours
        for (let i = indexH1 + 1; i <= indexH2; i++) {
            document.getElementById('boutonligne' + i + 'colonne' + indexC).remove();
            document.getElementById('ligne' + i + 'colonne' + indexC).remove();
        }
    })
}


/*
function afficheCours(information) {
    Object.keys(information).forEach(key => {

         //console.log(information[key]['date']);

        let dateCours = information[key]['date'];
        let debutCours = information[key]['horaireDebutCours'];
        let finCours = information[key]['horaireFinCours'];

        let typeDeCour = information[key]['typeDeCour'];
        let matiere = information[key]['matiere'];
        let enseignant = information[key]['enseignant'];
        let salle = information[key]['salle'];
        let groupeDuCours = information[key]['groupeDuCours'];
        let jourSemaine = information[key]['jourSemaine'];

         //console.log(jourSemaine);
        // console.log(dateCours);
        // console.log(debutCours);
        // console.log(finCours);
        // console.log(typeDeCour);
        // console.log(enseignant);
        // console.log(salle);
        // console.log(groupeDuCours);



    let indexH1 = indexH(debutCours);
    let indexH2 = indexH(finCours);
    let n = indexH2 - indexH1 + 1;
    let indexC = indexColonne(jourSemaine,groupeDuCours);
    
    //console.log(indexColonne);

    // console.log(indexH1);
    // console.log(indexH2);

    let cellule =document.getElementById('ligne' + indexH1 + 'colonne'+ indexC);
    
    // console.log('ligne' + indexH1 + 'colonne'+ indexC);
    // console.log(cellule);
    // cellule.setAttribute("rowspan", n);
    // cellule.style.background = "red";

    let slot = document.createElement("p");
    slot.innerHTML = typeDeCour+" <br>"+ matiere+ "<br>"+salle +"<br>"+enseignant;
    cellule.appendChild(slot);
    document.getElementById('boutonligne' + indexH1 + 'colonne' + indexC).remove();

    for (let i = indexH1 + 1; i <= indexH2; i++) {
        document.getElementById('boutonligne' + i + 'colonne'+ indexC).remove();
        document.getElementById('ligne' + i + 'colonne'+indexC).remove();
    }


})


}

*/
/*************************************************************************************/
/*               Lancer une rêquete pour récupérer les données de json               */
/*************************************************************************************/
/*
function actualise() {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = Action;
    xhttp.open("POST", 'data.json');
    xhttp.send();
    console.log("actualise");

}

function Action() {
    if (this.readyState == 4 && this.status == 200) {
        //let tbody = document.getElementById("contenueCours");
        let information = JSON.parse(this.response);

        if (information[date.toLocaleDateString("fr")] !== undefined) {
            //console.log(information[date.toLocaleDateString("fr")]);
            afficheCours(information[date.toLocaleDateString("fr")]);
            //console.log(date.toLocaleDateString("fr"));
        }
    }
}

actualise();
*/





function actualise(d) {
    let dateText = d.toLocaleDateString("fr");
    $.ajax({
        type: "post",
        url: "getData.php",
        dataType: "json",
        data: { "date": dateText }
    }).done(function (data) {
        //console.log(data);
        afficheCours(data);
        //console.log(date.toLocaleDateString("fr"));
    });
}

actualise(date);

/*
document.getElementById("validerFormulaire").onclick = function () {
    
    // document.getElementById("semaine").innerText = date.toLocaleDateString("fr");
    // initTable("emploiDuTemp");
    // ouvrirFormulaire("bouttonAjouteCours", "formulaire");
     actualise(date);
};
*/
