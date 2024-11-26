<?php

/*************************************************************************************/
/*                      Calcul le lundi de la date donnée                            */
/*************************************************************************************/

/* Test si l'année est bissextile */
function nbJourFevrier($annee)
{
    if ($annee % 4 == 0)
        return 29;
    else
        return 28;
}


/**renvoie le nombre de jour total du mois
 * 0 -> janvier ... 11 -> décembre
 */
function nbJourDuMois($annee, $mois)
{
    switch ($mois) {
        case "01":
        case "03":
        case "05":
        case "07":
        case "08":
        case "10":
        case "12":
            return 31;
        case "04":
        case "06":
        case "09":
        case "11":
            return 30;
        case "02":
            return nbJourFevrier($annee);
    }
}

/* Test si c'est un lundi */
function estLundi($jourSemaine)
{
    if ($jourSemaine == 1)
        return true;
    else
        return false;
}

/* Calcul la date du lundi de la date donnée  */
function calculSemaineActuel($date)
{
    $annee = date('Y', strtotime($date));
    $mois = date('m', strtotime($date));
    $jour = date('d', strtotime($date));
    $jourSemaine = date("N", strtotime($date));


    if (estLundi($jourSemaine))
        return $date;
    else {
        if ($jour > ($jourSemaine - 1)) {
            $jour = $jour - ($jourSemaine - 1);
            return $annee . '-' . $mois . '-' . $jour;
        } else if ($mois != 0) {
            $mois = $mois - 1;
            $jour = nbJourDuMois($annee, $mois - 1) + ($jour - ($jourSemaine - 1));
            return $annee . '-' . $mois . '-' . $jour;
        } else {
            $annee = $annee - 1;
            $mois = 12;
            $jour = nbJourDuMois($annee, 11) + ($jour - ($jourSemaine - 1));
            return $annee . '-' . $mois . '-' . $jour;
        }
    }
}

function concatHeureMin($heure, $min)
{
    if ($min == "0")
        $min = "00";
    return $heure . "h" . $min;
}


/*************************************************************************************/
/*                         Enregistre les données                                    */
/*************************************************************************************/

$champ = [
    'jourCours',
    'moisCours',
    'anneeCours',
    'heureDebutCours',
    'minDebutCours',
    'heureFinCours',
    'minFinCours',
    'typeDeCour',
    'matière',
    'enseignant',
    'salle',
    'groupeDuCours'
];
function formulaireRemplit($liste)
{
    for ($i = 0; $i < count($liste); $i++) {
        if (!array_key_exists($liste[$i], $_POST))
            return false;
    }
    return true;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (formulaireRemplit($champ)) {
        $jourCours = $_POST["jourCours"];
        $moisCours = $_POST["moisCours"];
        $anneeCours = $_POST["anneeCours"];
        $heureDebutCours = $_POST["heureDebutCours"];
        $minDebutCours = $_POST["minDebutCours"];
        $heureFinCours = $_POST["heureFinCours"];
        $minFinCours = $_POST["minFinCours"];
        $typeDeCour = $_POST["typeDeCour"];
        $matière = $_POST["matière"];
        $enseignant = $_POST["enseignant"];
        $salle = $_POST["salle"];
        $groupeDuCours = $_POST["groupeDuCours"];
        print($jourCours);
        print($moisCours);
        print($anneeCours);
        print($heureDebutCours);
        print($minDebutCours);
        print($heureFinCours);
        print($minFinCours);
        print($typeDeCour);
        print($matière);
        print($enseignant);
        print($salle);
        print($groupeDuCours);
        //print(json_encode(array("enseignant" => $enseignant)));
        $semaine = "semaine" . calculSemaineActuel($anneeCours . $moisCours . $jourCours);
        $date = $anneeCours . $moisCours . $jourCours;
        $debutCours = concatHeureMin($heureDebutCours, $minDebutCours);
        $finCours = concatHeureMin($heureFinCours, $minFinCours);
        $newData = array(
            "date" => $date,
            "groupeDuCours" => $groupeDuCours,
            "debutCours" => $debutCours,
            "finCours" => $finCours,
            "typeDeCour" => $typeDeCour,
            "matière" => $matière,
            "enseignant" => $enseignant,
            "salle" => $salle
        );
        //$semaine => $newData;
        $jsonFile = file_get_contents('data.json');
        $data = json_decode($jsonFile, true);
        $data[$semaine][] = $newData;
        $json_data = $json_encde($data);
        file_put_contents("data.json", $json_data);
    }
    $newData = json_encode(
        array(
            "date" => "f",
            "groupeDuCours" => "r",
        )
    );
    file_put_contents("data.json", $newData);
    echo $newData;
}



$jourCours = $_POST["jourCours"];
$moisCours = $_POST["moisCours"];
$anneeCours = $_POST["anneeCours"];

$heureDebutCours = $_POST["heureDebutCours"];
$minDebutCours = $_POST["minDebutCours"];

$heureFinCours = $_POST["heureFinCours"];
$minFinCours = $_POST["minFinCours"];

$typeDeCour = $_POST["typeDeCour"];
$matière = $_POST["matière"];
$enseignant = $_POST["enseignant"];
$salle = $_POST["salle"];
$groupeDuCours = $_POST["groupeDuCours"];

$data = array('jour' => $jourCours, "mois" => $moisCours);
echo json_encode($data);


?>