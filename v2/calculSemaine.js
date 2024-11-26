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
    case 0:
    case 2:
    case 4:
    case 6:
    case 7:
    case 9:
    case 11:
      return 31;
    case 3:
    case 5:
    case 8:
    case 10:
      return 30;
    case 1:
      return nbJourFevrier(annee);
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

  if (estLundi(jourSemaine)) return date;
  else if (estDimanche(jourSemaine)) {
    // car date.getDay() renvoie 0 pour dimanche
    if (jour > 6) date.setDate(jour - 6);
    else if (mois != 0) {
      date.setMonth(mois - 1);
      date.setDate(nbJourDuMois(annee, mois - 1) + (jour - 6));
    } else {
      date.setYear(annee - 1);
      date.setMonth(11);
      date.setDate(nbJourDuMois(annee, 11) + (jour - 6));
    }
    return date;
  } else {
    if (jour > jourSemaine - 1) date.setDate(jour - (jourSemaine - 1));
    else if (mois != 0) {
      date.setMonth(mois - 1);
      date.setDate(nbJourDuMois(annee, mois - 1) + (jour - (jourSemaine - 1)));
    } else {
      date.setYear(annee - 1);
      date.setMonth(11);
      date.setDate(nbJourDuMois(annee, 11) + (jour - (jourSemaine - 1)));
    }
    return date;
  }
}

function afficheSemaineActuel(idNom, date) {
  document.getElementById(idNom).innerText =
    calculSemaineActuel(date).toLocaleDateString("fr");
}

/*************************************************************************************/
/*         Calcul le premier jour de la semaine (lundi) de la date donnée            */
/*                    de la semaine précédent et suivante                            */
/*************************************************************************************/

/* Calcul la date du lundi de semaine précédente */
function semaineAvant(date) {
  var annee = date.getFullYear();
  var mois = date.getMonth();
  var jour = date.getDate();

  if (jour > 7) date.setDate(jour - 7);
  else if (mois != 0) {
    date.setMonth(mois - 1);
    var temp = nbJourDuMois(annee, mois - 1) + (jour - 7);
    date.setDate(temp);
  } else {
    date.setYear(annee - 1);
    date.setMonth(11);
    var temp = nbJourDuMois(annee, 11) + (jour - 7);
    date.setDate(temp);
  }
  return date;
}

/* Calcul la date du lundi de semaine suivante */
function semaineApres(date) {
  var annee = date.getFullYear();
  var mois = date.getMonth();
  var jour = date.getDate();

  if (jour <= nbJourDuMois(annee, mois) - 7) date.setDate(jour + 7);
  else if (mois != 11) {
    date.setMonth(mois + 1);
    var temp = nbJourDuMois(annee, mois) - jour - 7;
    date.setDate(-temp);
  } else {
    date.setYear(annee + 1);
    date.setMonth(0);
    var temp = nbJourDuMois(annee, mois) - jour - 7;
    date.setDate(-temp);
  }
  return date;
}
