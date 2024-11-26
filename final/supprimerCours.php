<?php

$information = $_POST["information"];


$dateSemaine = $information["semaine"];
$dateCours = $information["date"];
$groupeDuCours = $information["groupeDuCours"];
$horaireDebutCours = $information["horaireDebutCours"];



$jsonFile = file_get_contents('data.json');
$data = json_decode($jsonFile, true);


//supprime le cours
if (count($data[$dateSemaine][$dateCours][$groupeDuCours]) == 1) {
    if (count($data[$dateSemaine][$dateCours]) == 1) {
        if (count($data[$dateSemaine]) == 1) {
            if (count($data) == 1) {
                $data = [];
            } else {
                unset($data[$dateSemaine]);
            }
        } else {
            unset($data[$dateSemaine][$dateCours]);
        }
    } else {
        unset($data[$dateSemaine][$dateCours][$groupeDuCours]);
    }
} else {
    unset($data[$dateSemaine][$dateCours][$groupeDuCours][$horaireDebutCours]);
}




// if (count($data[$dateSemaine][$dateCours]) == 1) {
//     if (count($data[$dateSemaine]) == 1) {
//         if (count($data) == 1)
//             $data = [];
//         unset($data[$dateSemaine]);
//     } else
//         unset($data[$dateSemaine][$dateCours]);
// } else {
//     unset($data[$dateSemaine][$dateCours][$numeroCours]);
// }




$json_data = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents("data.json", $json_data);


?>