<?php
// 获取提交的用户名和密码
$username = $_POST['username'];
$password = $_POST['password'];
$id = 0;
// 从JSON文件中读取保存的用户名和密码
$data = json_decode(file_get_contents('code.json'), true);

foreach ($data as $user) {
    if ($username === $user['username'] && $password === $user['password']) {
        $id = $user['identifient'];
        //header('location:etudiant.php');
        //exit;
    }
}

if ($id === 1) {
    header('location:etudiant.html');
    exit;
} else if ($id === 2) {
    header('location:coordinateurPedagogique.php');
    exit;
} else if ($id == 3) {
    header('location:responsable.php');
} else {
    echo 'Invalid username or password.';
}

?>