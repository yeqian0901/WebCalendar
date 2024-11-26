<?php

$dateCours = $_POST["dateCours"];
$horaireDebutCours = $_POST["horaireDebutCours"];
$horaireFinCours = $_POST["horaireFinCours"];
$typeDeCour = $_POST["typeDeCour"];
$matiere = $_POST["matiere"];
$enseignant = $_POST["enseignant"];
$salle = $_POST["salle"];
$groupeDuCours = $_POST["groupeDuCours"];
$choixCouleur = $_POST["choixCouleur"];





//prend les données de data.json et convertie en javascript
$jsonFile = file_get_contents('data.json');
$data = json_decode($jsonFile, true);


/*************************************************************************************/
/*                      Calcul le lundi de la date donnée                            */
/*************************************************************************************/

/* Test le nombre de jour du mois de février, si l'année est bissextile */
function nbJourFevrier($annee)
{
    if ($annee % 4 == 0)
        return 29;
    else
        return 28;
}


/**renvoie le nombre de jour total du mois
 * 01 -> janvier ... 12 -> décembre
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

/** Test si c'est un lundi 
 *   1 -> lundi ... 7 -> dimanche
 */
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
    //Récupère les l'année, mois, jour et jour de la semaine de la date
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
        } else if ($mois != 1) {
            $mois = $mois - 1;
            $jour = nbJourDuMois($annee, $mois) + ($jour - ($jourSemaine - 1));
            return $annee . '-' . $mois . '-' . $jour;
        } else {
            $annee = $annee - 1;
            $mois = 12;
            $jour = nbJourDuMois($annee, 12) + ($jour - ($jourSemaine - 1));
            return $annee . '-' . $mois . '-' . $jour;
        }
    }
}


/*************************************************************************************/
/*                               Stokage des données                                 */
/*************************************************************************************/


//la date du cours donnée en format jj/mm/aaaa
$date = new DateTime($dateCours);
//$dateText1 = $date->format('j/m/Y');
$dateText = $date->format('d/m/Y');
;
$jourSemaine = date("N", strtotime($dateCours));


//la date du premier jour de la semaine(lundi) de la date donnée en format jj/mm/aaaa
$dateSemaine = calculSemaineActuel($dateCours);
$dateSemaineText = date("d/m/Y", strtotime($dateSemaine));

$newData = array(
    'typeDeCour' => $typeDeCour,
    'matiere' => $matiere,
    'enseignant' => $enseignant,
    'salle' => $salle,
    'groupeDuCours' => $groupeDuCours,
    'horaireDebutCours' => $horaireDebutCours,
    'horaireFinCours' => $horaireFinCours,
    'date' => $dateText,
    'jourSemaine' => $jourSemaine,
    'choixCouleur' => $choixCouleur,
    'semaine' => $dateSemaineText
);

// //ajoute les nouveaux donnée au data 
// if (isset($data[$dateSemaineText])) {
//     if (isset($data[$dateSemaineText][$dateText])) {
//         $n = count($data[$dateSemaineText][$dateText]);
//         $data[$dateSemaineText][$dateText]["cours" . ($n + 1)] = $newData;
//         //array_push($data[$dateSemaineText][$dateText], $newData);
//     } else {
//         $data[$dateSemaineText][$dateText]["cours1"] = $newData;
//         //$data[$dateSemaineText][$dateText][] = $newData;
//         //array_push($data[$dateSemaine][$dateText],$newData);
//     }
// } else {
//     $data[$dateSemaineText][$dateText]["cours1"] = $newData;
//     //array_push($data[$dateSemaine][$dateText],$newData);
//     //$data[$dateSemaineText][$dateText][] = $newData;
//}

// ajoute les nouveaux donnée au data 
// if (isset($data["semaine"][$dateSemaineText])) {
//     if (isset($data["semaine"][$dateSemaineText]["jours"][$dateText])) {
//         $n = count($data["semaine"][$dateSemaineText]["jours"][$dateText]);
//         $data["semaine"][$dateSemaineText]["jours"][$dateText]["cours" . ($n + 1)] = $newData;
//     } else {
//         $data["semaine"][$dateSemaineText]["jours"][$dateText]["cours1"] = $newData;
//     }
// } else {
//     $data["semaine"][$dateSemaineText]["jours"][$dateText]["cours1"] = $newData;

// }

//ajoute les nouveaux donnée au data 
/*
if (isset($data[$dateSemaineText])) {
    if (isset($data[$dateSemaineText][$dateText])) {
        if(isset($data[$dateSemaineText][$dateText][$groupeDuCours])){
            if(isset($data[$dateSemaineText][$dateText][$groupeDuCours][$horaireDebutCours])){
                
            }
            else{

            }
        }else{

        }
    } 
} else {
    $data[$dateSemaineText][$dateText][$groupeDuCours][$horaireDebutCours] = $newData;
}
*/
//ajoute les nouveaux donnée au data 
if (!isset($data[$dateSemaineText][$dateText][$groupeDuCours][$horaireDebutCours])) {
    $data[$dateSemaineText][$dateText][$groupeDuCours][$horaireDebutCours] = $newData;
}




// if(isset($data["edt"])){
//     if(isset($data["edt"]))
// }

//enregistre dans le fichier data.json
$json_data = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents("data.json", $json_data);




?>