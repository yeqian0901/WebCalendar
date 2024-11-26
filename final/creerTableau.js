/*************************************************************************************/
/*                 Création de la table de l'emploi du temps                         */
/*************************************************************************************/

let nomJour = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"];
let nombreGroupe = 4;

/** Créer une liste ['8h00','8h15',......,'18h45','19h00'] */
function ajouteHoraire(horaireListe) {
  for (var i = 8; i <= 19; i++) {
    for (var j = 0; j <= 45; j += 15) {
      if (i == 19 && j != 0) break;
      else {
        var h = i + "h" + j;
        if (j == 0) {
          h = i + "h00";
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
  jours_.append("<td></td>");
  for (let i = 0; i < nomJour.length; i++) {
    jours_.append(
      '<th colspan="' +
        nombreGroupe +
        '" class="jour"><p>' +
        nomJour[i] +
        "</p></th>"
    );
  }
}

/** Créer la ligne des groupes du tableau, G1...G4 */
function creerGroupeTableau(idNom) {
  let div_ = $(idNom);
  div_.append('<tr id="groupes"></tr>');
  let jours_ = $("#groupes");
  jours_.append("<th></th>");
  for (let i = 0; i < nomJour.length; i++) {
    for (let j = 1; j <= nombreGroupe; j++) {
      jours_.append('<th class="groupe"><p>G' + j + "</p></th>");
    }
  }
}

/** Créer le contenue du tableau, les cellules du tableau avec un bouton */
function creerContenuTableau(idNom) {
  let div_ = $(idNom);

  //ajouter un corps au tablaeu et là récupère
  div_.append('<tbody id ="contenueCours"></tbody>');
  let tbody_ = $("#contenueCours");

  for (let i = 0; i < horaireListe.length; i++) {
    //ajouter la ligne et la récupère
    let temp = "ligne" + horaireListe[i];
    tbody_.append('<tr id="' + temp + '"></tr>');
    var ligne_cour = $("#" + temp);

    //ajoute la cellule des horaire
    ligne_cour.append(
      '<th class="horaire"><p>' + horaireListe[i] + "</p></th>"
    );

    //ajoute tout les cellule de la ligne et un bouton
    for (let j = 0; j < nomJour.length * nombreGroupe; j++) {
      let coord = "ligne " + i + " colonne " + j;
      ligne_cour.append('<td id = "' + coord + '"class="cour">    </td>');
    }
  }
}
