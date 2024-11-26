<?php

$dateSemaine = $_POST["dateSemaine"];
$dateCours = $_POST["dateCours"];
$groupe = $_POST["groupe"];
$horaireDebut = $_POST["horaireDebut"];

$jsonFile = file_get_contents('data.json');
$data = json_decode($jsonFile, true);



$res = $data[$dateSemaine][$dateCours][$groupe][$horaireDebut];
echo json_encode($res, JSON_PRETTY_PRINT);

// foreach ($data[$dateSemaine][$dateCours] as $cours => $contenu) {
//     if ($groupe == $contenu["groupeDuCours"] && $horaireDebut == $contenu["horaireDebutCours"]) {
//         $res = ["numeroCours" => $cours, "contenu" => $contenu];
//         //$res = $data[$dateSemaine][$dateCours][$cours];
//         echo json_encode($res, JSON_PRETTY_PRINT);
//     }
// }






// $json_data = json_encode($data, JSON_PRETTY_PRINT);
// file_put_contents("data.json", $json_data);











?>