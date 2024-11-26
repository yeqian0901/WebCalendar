/*************************************************************************************/
/*                              Affiche slot                                         */
/*************************************************************************************/

/** Calcul l'index de la ligne du tableau en fonction de l'horaire */
function indexH(h) {
  for (let i = 0; i < horaireListe.length; i++) {
    if (horaireListe[i] == h) {
      return i;
    }
  }
}

/** Calcul l'index de la colonne du tableau en fonction du jour et groupe */
function indexColonne(jourSemaine, groupe) {
  switch (groupe) {
    case "G1":
      return (jourSemaine - 1) * 4;
    case "G2":
      return (jourSemaine - 1) * 4 + 1;
    case "G3":
      return (jourSemaine - 1) * 4 + 2;
    case "G4":
      return (jourSemaine - 1) * 4 + 3;
  }
}

/** Affiche les slot dans le tableau */
function afficheCours(information) {
  Object.keys(information).forEach((key) => {
    Object.keys(information[key]).forEach((key2) => {
      Object.keys(information[key][key2]).forEach((key3) => {
        let dateCours = information[key][key2][key3]["date"];
        let debutCours = information[key][key2][key3]["horaireDebutCours"];
        let finCours = information[key][key2][key3]["horaireFinCours"];

        let typeDeCour = information[key][key2][key3]["typeDeCour"];
        let matiere = information[key][key2][key3]["matiere"];
        let enseignant = information[key][key2][key3]["enseignant"];
        let salle = information[key][key2][key3]["salle"];
        let groupeDuCours = information[key][key2][key3]["groupeDuCours"];
        let jourSemaine = information[key][key2][key3]["jourSemaine"];
        let choixCouleur = information[key][key2][key3]["choixCouleur"];

        //calcul les index des cellules
        let indexH1 = indexH(debutCours);
        let indexH2 = indexH(finCours);
        let n = indexH2 - indexH1 + 1;
        let indexC = indexColonne(jourSemaine, groupeDuCours);

        //recupere la cellule de la ligne d'horaire debutCours et colonne du jour
        let cellule = document.getElementById(
          "ligne " + indexH1 + " colonne " + indexC
        );

        //fusionne les cellules
        cellule.setAttribute("rowspan", n);

        //mettre la couleur
        cellule.style.background = choixCouleur;

        //creer du texte dans la cellule
        let slot = document.createElement("p");
        slot.classList.add("slots");
        slot.id = "slot ligne " + indexH1 + " colonne " + indexC;
        slot.innerHTML =
          typeDeCour + " <br>" + matiere + "<br>" + salle + "<br>" + enseignant;

        // met les element dans la cellule
        cellule.append(slot);

        //cache la cellule d'horaire entre debutCours et finCours
        for (let i = indexH1 + 1; i <= indexH2; i++) {
          document
            .getElementById("ligne " + i + " colonne " + indexC)
            .setAttribute("style", "display:none");
        }
      });
    });
  });
}
