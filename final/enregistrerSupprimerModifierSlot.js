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
  ouvrirFormulaire("bouttonAjouteCours", "formulaire", nouveauDate);
  actualise(nouveauDate);
};

document.getElementById("apres").onclick = function () {
  let nouveauDate = semaineApres(date);
  document.getElementById("semaine").innerText =
    nouveauDate.toLocaleDateString("fr");
  initTable("emploiDuTemp");
  ouvrirFormulaire("bouttonAjouteCours", "formulaire", nouveauDate);
  actualise(nouveauDate);
};

/*************************************************************************************/
/*                 Création de la table de l'emploi du temps vide                    */
/*************************************************************************************/

function ajouterBoutonDansTableau() {
  let cellules = document.getElementsByClassName("cour");

  for (let i = 0; i < cellules.length; i++) {
    let id = cellules[i].id;
    cellules[i].innerHTML =
      '<button id = "bouton ' +
      id +
      '" class="bouttonAjouteCours border-primary rounded" > </button>';
  }
}

function creerTableCours(idNom) {
  var div_ = $("#" + idNom);
  div_.append('<table id="table" class="table-striped"></table>');
  var table_ = $("#table");
  creerJourTableau(table_);
  creerGroupeTableau(table_);
  creerContenuTableau(table_);
  ajouterBoutonDansTableau();
}

creerTableCours("emploiDuTemp");

/** Initialise le tableau */
function initTable(idNom) {
  let div_ = document.getElementById(idNom);
  div_.innerHTML = "";
  creerTableCours("emploiDuTemp");
}

/*****************************************************************************/
/*            interdit de choisir le week end dans le formulaire              */
/*****************************************************************************/

const datePicker = document.getElementById("dateCours");

datePicker.addEventListener("input", function () {
  const dateValue = new Date(datePicker.value);
  const dayOfWeek = dateValue.getDay();
  //console.log(dayOfWeek);

  // Désactive les week-ends (samedi et dimanche)
  if (dayOfWeek === 0 || dayOfWeek === 6) {
    datePicker.setCustomValidity("Veuillez choisir un jour de semaine");
  } else {
    datePicker.setCustomValidity("");
  }
});

/*****************************************************************************/
/*        Envoie les données du formulaire au fichier enregistrer.php        */
/*****************************************************************************/
function enregistrerFormulaire() {
  // Récupérer l'élément formulaire
  const form = document.getElementById("formulaire");

  // Ajouter un écouteur d'événement sur l'envoi du formulaire
  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Empêcher l'envoi du formulaire

    // Créer un objet FormData
    const formData = new FormData(form);

    // Obtenir les valeurs des champs du formulaire
    const dateCours = formData.get("dateCours");
    const horaireDebutCours = formData.get("horaireDebutCours");
    const horaireFinCours = formData.get("horaireFinCours");
    const typeDeCour = formData.get("typeDeCour");
    const matiere = formData.get("matiere");
    const enseignant = formData.get("enseignant");
    const salle = formData.get("salle");
    const groupeDuCours = formData.get("groupeDuCours");
    const choixCouleur = formData.get("choixCouleur");

    // Ajouter les données dans une liste
    let liste = [];
    liste.push(dateCours);
    liste.push(horaireDebutCours);
    liste.push(horaireFinCours);
    liste.push(typeDeCour);
    liste.push(matiere);
    liste.push(enseignant);
    liste.push(salle);
    liste.push(groupeDuCours);
    liste.push(choixCouleur);

    // appel une requete si le formulaire est remplit
    if (!liste.includes("")) {
      $.ajax({
        type: "POST",
        url: "enregistrer.php",
        dataType: "json",
        data: {
          dateCours: dateCours,
          horaireDebutCours: horaireDebutCours,
          horaireFinCours: horaireFinCours,
          typeDeCour: typeDeCour,
          matiere: matiere,
          enseignant: enseignant,
          salle: salle,
          groupeDuCours: groupeDuCours,
          choixCouleur: choixCouleur,
        },
      });
    } else {
      //afficher le message d'erreur
      //document.getElementById("message").style = "display:visible";
      alert("Veuillez remplir tous les champs requis.");
    }

    form.submit();
  });
}

/*****************************************************************************/
/*                 Ajoute les boutons edit au slot et                        */
/*             cache le button pour ouvrir le formulaire                     */
/*****************************************************************************/

function ajouterEditBoutonEtCacherButton() {
  let slots = document.getElementsByClassName("slots");
  // console.log(slots);

  for (let i = 0; i < slots.length; i++) {
    let id = slots[i].id;

    let t = id.split(" ");

    //t = ["slot", "ligne", "l", "colonne", "c"]
    let l = t[2];
    let c = t[4];

    let coord = "ligne " + t[2] + " colonne " + t[4];

    //récupère la cellule
    let parent = document.getElementById(coord);

    //creer un bouton pour modifier le cours
    let editBouton = document.createElement("button");
    editBouton.setAttribute("class", "editBouton border rounded");
    editBouton.innerText = "Edit";
    editBouton.setAttribute("id", "bouton edit ligne " + l + " colonne " + c);

    //ajoute a al cellule
    parent.insertBefore(editBouton, slots[i].nextSilibling);

    //cache le bouton pour ouvrir le formulaire
    document
      .getElementById("bouton " + coord)
      .setAttribute("style", "display:none");
  }
}

/*************************************************************************************/
/*                     Bouton pour ouvrir le formulaire                              */
/*************************************************************************************/

/**
 * Calcul la date d'une cellule du tableau
 * @param {*} index index de la colonne du tableau
 * @param {*} date la date de la semaine actuel
 */
function calculDate(index, date) {
  var res = new Date();
  res.setDate(date.getDate());
  res.setFullYear(date.getFullYear());
  res.setMonth(date.getMonth());

  //res.setDate(date.getDate());
  var annee = res.getFullYear();
  var mois = res.getMonth();
  var jour = res.getDate();

  //le jour de la semaine 0->lundi .. 4->vendredi
  var jourSemaine = Math.floor(index / 4);

  if (jour < nbJourDuMois(annee, mois) - jourSemaine) {
    res.setDate(jour + jourSemaine);
  } else if (mois != 11) {
    res.setMonth(mois + 1);
    var temp = nbJourDuMois(annee, mois) - jour - jourSemaine;
    res.setDate(-temp);
  } else {
    res.setYear(annee + 1);
    res.setMonth(0);
    var temp = nbJourDuMois(annee, mois) - jour - jourSemaine;
    res.setDate(-temp);
  }
  return res;
}

/**
 * Calcul le groupe d'une cellule du tableau
 * @param {*} index de la colonne du tableau
 * @returns
 */
function calculGroupe(index) {
  if (index % 4 == 0) return "G1";
  else if (index % 4 == 1) return "G2";
  else if (index % 4 == 2) return "G3";
  else if (index % 4 == 3) return "G4";
}

//un tableau qui permet retrouve les information sur la cellule
function creerInfoCellule(date) {
  let infoCellule = [];
  for (let i = 0; i < horaireListe.length; i++) {
    infoCellule[i] = []; // initialisation avec la taille pour la dimension j
    for (let j = 0; j < nombreGroupe * nomJour.length; j++) {
      infoCellule[i][j] = {
        date: calculDate(j, date), // exemple de date en format aaaa-mm-jj
        horaireDebutCours: horaireListe[i],
        groupe: calculGroupe(j),
      };
    }
  }
  return infoCellule;
}

function ouvrirFormulaire(classBouton, idFormulaire, date) {
  let boutons = document.getElementsByClassName(classBouton);
  // console.log(boutons);
  // console.log(boutons.length);
  for (let i = 0; i < boutons.length; i++) {
    boutons[i].onclick = function () {
      let formulaire = document.getElementById(idFormulaire);
      //window.open("index.php", formulaire, "width=400,height=400");
      formulaire.setAttribute("style", "display:visible");
      document
        .getElementById("validerFormulaire")
        .setAttribute("style", "display:visble");
      document
        .getElementById("supprimer")
        .setAttribute("style", "display:none");
      document.getElementById("modifier").setAttribute("style", "display:none");

      //recupère l'id du bouton
      let idBouton = boutons[i].id;

      // renvoir tableau=["bouton","ligne",index_L,"colonne","index_C"]
      let tableau = idBouton.split(" ");

      let index_L = tableau[2];
      let index_C = tableau[4];

      let infoCellule = creerInfoCellule(date);

      //préremplit le formulaire a l'aire des info de infoCellule
      document.getElementById("groupeDuCours").value =
        infoCellule[index_L][index_C]["groupe"];
      document.getElementById("dateCours").value = infoCellule[index_L][
        index_C
      ]["date"]
        .toISOString()
        .substring(0, 10); // converti en format aaaa-mm-jj
      document.getElementById("horaireDebutCours").value =
        infoCellule[index_L][index_C]["horaireDebutCours"];

      document.getElementById("horaireFinCours").value = "";
      document.getElementById("matiere").value = "";
      document.getElementById("enseignant").value = "";
      document.getElementById("salle").value = "";
      document.getElementById("typeCours").value = "";
      document.getElementById("choixCouleur").value = "#92DD7F";
    };
  }
}

ouvrirFormulaire("bouttonAjouteCours", "formulaire", date);

/*************************************************************************************/
/*         Bouton du formulaire pour valider  : ajouter un cours                     */
/*************************************************************************************/
function validerFormulaire(idBouton) {
  document.getElementById(idBouton).onclick = function () {
    enregistrerFormulaire();
    initTable("emploiDuTemp");
    ouvrirFormulaire("bouttonAjouteCours", "formulaire", date);
    actualise(date);
  };
}
validerFormulaire("validerFormulaire");

function actualise(d) {
  let dateText = d.toLocaleDateString("fr");
  //console.log(dateText);
  $.ajax({
    type: "post",
    url: "getSemaine.php",
    dataType: "json",
    data: { date: dateText },
  })
    .done(function (data) {
      afficheCours(data);
      ajouterEditBoutonEtCacherButton();
      ouvrirFormulaireEdit("editBouton", "formulaire", date);
      //console.log(data);
    })
    .fail(function (data, statut) {
      console.log(data);
      console.log(statut);
    });
}

actualise(date);

/*************************************************************************************/
/*                 Bouton pour ouvrir le formulaire averc edit                       */
/*************************************************************************************/

function ouvrirFormulaireEdit(classBouton, idFormulaire, date) {
  let boutons = document.getElementsByClassName(classBouton);

  for (let i = 0; i < boutons.length; i++) {
    boutons[i].onclick = function () {
      let formulaire = document.getElementById(idFormulaire);

      formulaire.setAttribute("style", "display:visible");
      document
        .getElementById("modifier")
        .setAttribute("style", "display:visible");
      document
        .getElementById("supprimer")
        .setAttribute("style", "display:visible");
      document
        .getElementById("validerFormulaire")
        .setAttribute("style", "display:none");

      //recupère l'id du bouton
      let idBouton = boutons[i].id;

      // renvoir tableau=["bouton","edit","ligne",index_L,"colonne","index_C"]
      let tableau = idBouton.split(" ");

      let index_L = tableau[3];
      let index_C = tableau[5];

      let infoCellule = creerInfoCellule(date);
      let groupe = infoCellule[index_L][index_C]["groupe"];
      let dateCours = infoCellule[index_L][index_C]["date"];
      let horaireDebutCours =
        infoCellule[index_L][index_C]["horaireDebutCours"];

      lireSlot(date, dateCours, groupe, horaireDebutCours);
    };
  }
}

/*************************************************************************************/
/*                 ouvrir le formulaire preremplit avec edit                       */
/*************************************************************************************/

function lireSlot(dateSemaine, dateCours, groupe, horaireDebut) {
  let dateSemanieText = dateSemaine.toLocaleDateString("fr");
  let dateCoursText = dateCours.toLocaleDateString("fr");

  $.ajax({
    type: "post",
    url: "getCours.php",
    dataType: "json",
    data: {
      dateSemaine: dateSemanieText,
      dateCours: dateCoursText,
      groupe: groupe,
      horaireDebut: horaireDebut,
    },
  })
    .done(function (data) {
      //convertir la date en format aaaa-mm-jj
      let d = data["date"].split("/");
      let dateText = d[2] + "-" + d[1] + "-" + d[0];

      //préremplit le formulaire en fonction des données du bouton appuiyé
      document.getElementById("groupeDuCours").value = data["groupeDuCours"];
      document.getElementById("dateCours").value = dateText;
      document.getElementById("horaireDebutCours").value =
        data["horaireDebutCours"];
      document.getElementById("horaireFinCours").value =
        data["horaireFinCours"];
      document.getElementById("typeDeCour").value = data["typeDeCour"];
      document.getElementById("salle").value = data["salle"];
      document.getElementById("enseignant").value = data["enseignant"];
      document.getElementById("matiere").value = data["matiere"];
      document.getElementById("choixCouleur").value = data["choixCouleur"];

      //supprime le cours si le bouton est appuiyer
      supprimerCours(data);

      //modifie le cours si le bouton est apuiyer
      modifierCours(data);
      //console.log(data);
      console.log("succes lireSlot");
    })
    .fail(function (data, statut) {
      console.log(statut);
      console.log(data);
    });
}

/*************************************************************************************/
/*                    Bouton pour supprimer le cours                                 */
/*************************************************************************************/
function supprimerCours(information) {
  const boutonSuprimmer = document.getElementById("supprimer");

  boutonSuprimmer.addEventListener("click", function () {
    $.ajax({
      type: "post",
      url: "supprimerCours.php",
      dataType: "json",
      data: { information: information },
    })
      .done(function () {
        initTable("emploiDuTemp");
        ouvrirFormulaire("bouttonAjouteCours", "formulaire", date);
        //actualise(date);
        console.log("succes supprimerCours");
      })
      .fail(function (data, statut) {
        console.log(data);
        console.log(statut);
      });
  });
}

/*************************************************************************************/
/*                     Bouton pour modifier le cours                                 */
/*************************************************************************************/

function modifierCours(information) {
  const boutonModifier = document.getElementById("modifier");
  boutonModifier.addEventListener("click", function () {
    $.ajax({
      type: "post",
      url: "modifierCours.php",
      dataType: "json",
      data: {
        ancienceInformation: information,
        groupeDuCours: document.getElementById("groupeDuCours").value,
        dateCours: document.getElementById("dateCours").value,
        horaireDebutCours: document.getElementById("horaireDebutCours").value,
        horaireFinCours: document.getElementById("horaireFinCours").value,
        typeDeCour: document.getElementById("typeDeCour").value,
        salle: document.getElementById("salle").value,
        enseignant: document.getElementById("enseignant").value,
        matiere: document.getElementById("matiere").value,
        choixCouleur: document.getElementById("choixCouleur").value,
      },
    })
      .done(function () {
        initTable("emploiDuTemp");
        ouvrirFormulaire("bouttonAjouteCours", "formulaire", date);
        actualise(date);
        console.log("succes modifierCours");
      })
      .fail(function (data, statut) {
        console.log(data);
        console.log(statut);
      });
  });
}
