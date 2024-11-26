<?php

$date = $_POST["date"];

$jsonFile = file_get_contents('data.json');
$data = json_decode($jsonFile, true);

 foreach($data as $key => $value){
     if($key == $date)
      echo json_encode($data[$key]);
 }



//echo $jsonFile;


?> 