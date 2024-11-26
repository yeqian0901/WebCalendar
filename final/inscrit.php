<?php
$username = $_POST['username'];
$password = $_POST['password'];
$id = $_POST['id'];

$json = file_get_contents("code.json");
$data = json_decode($json);

//foreach($data as $obj){
//    if($obj['username'] == $username){
//       header('location:login.html');
//       exit();
//   }
//}

$data[] = array(
    "username" => $username,
    "password" => $password,
    "identifient" => intval($id)
);


$json_data = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('code.json', $json_data);

header('location:login.html');
exit();
?>