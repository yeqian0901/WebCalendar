let date = new Date();

afficheSemaineActuel("semaine", date);

/*************************************************************************************/
/*      Actualise la page lors du click pour voir emploi du temps de la              */
/*                      semaine précédente et suivante                               */
/*************************************************************************************/

document.getElementById("avant").onclick = function () {
  let nouveauDate = semaineAvant(date);
  document.getElementById("semaine").innerText =
    nouveauDate.toLocaleDateString("fr");
  initTable("emploiDuTemp");
  actualise(nouveauDate);
};

document.getElementById("apres").onclick = function () {
  let nouveauDate = semaineApres(date);
  document.getElementById("semaine").innerText =
    nouveauDate.toLocaleDateString("fr");
  initTable("emploiDuTemp");
  actualise(nouveauDate);
};

/*************************************************************************************/
/*                 Création de la table de l'emploi du temps vide                    */
/*************************************************************************************/

/** Créer le tableau (emploi du temp) */
function creerTableCours(idNom) {
  var div_ = $("#" + idNom);
  div_.append('<table id="table" class="table-striped"></table>');
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
/*                          Affiche les cours dans edt                               */
/*************************************************************************************/

function actualise(d) {
  let dateText = d.toLocaleDateString("fr");

  $.ajax({
    type: "post",
    url: "getSemaine.php",
    dataType: "json",
    data: { date: dateText },
  })
    .done(function (data) {
      afficheCours(data);
    })
    .fail(function (data, statut) {
      console.log(data);
      console.log(statut);
    });
}

actualise(date);
