<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="calculSemaine.js" defer></script>
    <script src="creerTableau.js" defer></script>
    <script src="afficheSlot.js" defer></script>
    <script src="enregistrerSupprimerModifierSlot.js" defer></script>
    <title>Coordinateur Pédagogique</title>
</head>

<?php
function afficheListeHoraire()
{
    for ($i = 8; $i <= 19; $i++) {
        for ($j = 0; $j <= 45; $j += 15) {
            if ($i == 19 && $j != 0) {
                break;
            } else {
                $heure = $i . 'h' . $j;
                if ($j == 0) {
                    $heure = $i . 'h00';
                }
                print('<option value="' . $heure . '">' . $heure . '</option>');
            }
        }
    }
}
?>

<?php
$jsonFile1 = file_get_contents('listeMatiere.json');
$listeMatiere = json_decode($jsonFile1, true);

$jsonFile2 = file_get_contents('listeEnseignant.json');
$listeEnseignant = json_decode($jsonFile2, true);

$jsonFile3 = file_get_contents('listeSalle.json');
$listeSalle = json_decode($jsonFile3, true);

?>

<body>
    <button class="border"><a href="login.html">Deconnecter</a></button>

    <h1 id="titre">Emploi du temps de la semaine de
        <span id="semaine">
        </span>
    </h1>



    <form id="formulaire" method="post" action="coordinateurPedagogique.php" style="display:none">
        <p>
            <label for="dateCours"> Date du cours : </label>
            <input type="date" id="dateCours" name="dateCours" required>
            <br>

            <label for="horaireDebutCours">Horaire du début de cours: </label>
            <select name="horaireDebutCours" id="horaireDebutCours" class="questionFormulaire">
                <option value="">--Choisir un horaire--</option>
                <?php afficheListeHoraire(); ?>
            </select>
            <br>

            <label for="horaireFinCours">Horaire de la fin du cours : </label>
            <select name="horaireFinCours" id="horaireFinCours" class="questionFormulaire">
                <option value="">--Choisir un horaire--</option>
                <?php afficheListeHoraire(); ?>
            </select>
            <br>

            <label for="typeDeCour">Choisir un type de cours : </label>
            <select name="typeDeCour" id="typeDeCour" class="questionFormulaire">
                <option value="">--Choisir un type--</option>
                <option value="Cours">TP</option>
                <option value="TD">TD</option>
                <option value="Examen">Examen</option>
                <option value="Soutenance">Soutenance</option>
                <option value="Amphi">Amphi</option>
            </select>
            <br>

            <label for="matiere">Choisir une matière : </label>
            <select name="matiere" id="matiere" class="questionFormulaire">
                <option value="">--Choisir une matière--</option>
                <?php
                for ($i = 0; $i < count($listeMatiere); $i++)
                    echo "<option value='" . $listeMatiere[$i] . "'>" . $listeMatiere[$i] . "</option>";
                ?>
            </select>
            <br>

            <label for="enseignant">Choisir un enseignant : </label>
            <select name="enseignant" id="enseignant" class="questionFormulaire">
                <option value="">--Choisir un prof--</option>
                <?php
                for ($i = 0; $i < count($listeEnseignant); $i++)
                    echo "<option value='" . $listeEnseignant[$i] . "'>" . $listeEnseignant[$i] . "</option>";
                ?>
            </select>
            <br>

            <label for="salle">Choisir une salle : </label>
            <select name="salle" id="salle" class="questionFormulaire">
                <option value="">--Choisir une salle--</option>
                <<?php
                for ($i = 0; $i < count($listeSalle); $i++)
                    echo "<option value='" . $listeSalle[$i] . "'>" . $listeSalle[$i] . "</option>";
                ?>
            </select>
            <br>

            <label for="groupeDuCours">Choisir un groupe : </label>
            <select name="groupeDuCours" id="groupeDuCours" class="questionFormulaire">
                <option value="">--Choisir un groupe--</option>
                <option value="G1">G1</option>
                <option value="G2">G2</option>
                <option value="G3">G3</option>
                <option value="G4">G4</option>
            </select>
            <br>

            <label for="choixCouleur">Choisir une couleur :</label>
            <input type="color" id="choixCouleur" name="choixCouleur" value="#92DD7F">
            </br>

            <button type="submit" class="btn btn-success btn-sm " id="validerFormulaire"
                style="display: none">Valider</button>
            <button id="annuler" class="btn btn-danger btn-sm">Annuler</button>

            <button id="supprimer" class="btn btn-primary btn-sm" style="display: none">supprimer</button>
            <button id="modifier" class="btn btn-success btn-sm " style="display: none">modifier</button>


        </p>
    </form>





    <div id="avantEtapres">
        <button id="avant" class="calendrierdBouton btn-primary btn-lg">◄</button>
        <button id="apres" class="calendrierdBouton btn-primary btn-lg">►</button>
    </div>


    <div id="emploiDuTemp"></div>



    <div>

    </div>

</body>

</html>