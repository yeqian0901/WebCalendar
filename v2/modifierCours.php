<?php

//ancien cours a supprimer
$ancienceInformation = $_POST["ancienceInformation"];

$ancienSemaine = $ancienceInformation["semaine"];
$ancienDate = $ancienceInformation["date"];
$anciengroupe = $ancienceInformation["groupeDuCours"];
$ancienHoraireDebutCours = $ancienceInformation["horaireDebutCours"];


//nouveau cours ajouter
$nouveauGroupeDuCours = $_POST["groupeDuCours"];
$nouveauDateCours = $_POST["dateCours"];
$nouveauHoraireDebutCours = $_POST["horaireDebutCours"];
$nouveauHoraireFinCours = $_POST["horaireFinCours"];
$nouveauTypeDeCour = $_POST["typeDeCour"];
$nouveauSalle = $_POST["salle"];
$nouveauEnseignant = $_POST["enseignant"];
$nouveauMatiere = $_POST["matiere"];
$nouveauChoixCouleur = $_POST["choixCouleur"];







$jsonFile = file_get_contents('data.json');
$data = json_decode($jsonFile, true);



/**************************************************************************/
/*                                                                        */
/*                          Supprime le cours                             */
/*                                                                        */
/**************************************************************************/

if (count($data[$ancienSemaine][$ancienDate][$anciengroupe]) == 1) {
    if (count($data[$ancienSemaine][$ancienDate]) == 1) {
        if (count($data[$ancienSemaine]) == 1) {
            if ($data == 1) {
                $data = [];
            } else {
                unset($data[$ancienSemaine]);
            }
        } else {
            unset($data[$ancienSemaine][$ancienDate]);
        }
    } else {
        unset($data[$ancienSemaine][$ancienDate][$anciengroupe]);
    }
} else {
    unset($data[$ancienSemaine][$ancienDate][$anciengroupe][$ancienHoraireDebutCours]);
}




/**************************************************************************/
/*                                                                        */
/*                          Ajoute le nouveau cours                       */
/*                                                                        */
/**************************************************************************/

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
$date = new DateTime($nouveauDateCours);
//$dateText1 = $date->format('j/m/Y');
$dateText = $date->format('d/m/Y');

$jourSemaine = date("N", strtotime($nouveauDateCours));


//la date du premier jour de la semaine(lundi) de la date donnée en format jj/mm/aaaa
$dateSemaine = calculSemaineActuel($nouveauDateCours);
$dateSemaineText = date("d/m/Y", strtotime($dateSemaine));

$newData = array(
    'typeDeCour' => $nouveauTypeDeCour,
    'matiere' => $nouveauMatiere,
    'enseignant' => $nouveauEnseignant,
    'salle' => $nouveauSalle,
    'groupeDuCours' => $nouveauGroupeDuCours,
    'horaireDebutCours' => $nouveauHoraireDebutCours,
    'horaireFinCours' => $nouveauHoraireFinCours,
    'date' => $dateText,
    'jourSemaine' => $jourSemaine,
    'choixCouleur' => $nouveauChoixCouleur,
    'semaine' => $dateSemaineText
);

// //ajoute les nouveaux donnée au data 
// if (isset($data[$dateSemaineText])) {
//     if (isset($data[$dateSemaineText][$dateText])) {
//         $n = count($data[$dateSemaineText][$dateText]);
//         array_push($data[$dateSemaineText][$dateText], $newData);
//     } else {
//         $data[$dateSemaineText][$dateText][] = $newData;
//     }
// } else {
//     $data[$dateSemaineText][$dateText][] = $newData;
// }

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
// }

//ajoute les nouveaux donnée au data 
// if (!isset($data[$dateSemaineText][$dateText][$groupeDuCours][$horaireDebutCours])) {
//     $data[$dateSemaineText][$dateText][$groupeDuCours][$horaireDebutCours] = $newData;
// }

$data[$dateSemaineText][$dateText][$nouveauGroupeDuCours][$nouveauHoraireDebutCours] = $newData;




$json_data = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents("data.json", $json_data);

echo $json_data;

?>