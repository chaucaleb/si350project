<?php
require 'utilFunctions.php';
checkCsrf();
$username = strtolower(inputText($_POST['username'] ?? '', 30));
$password = inputText($_POST['passwordexample'] ?? '', 200);
$valid = false;

$users = readRows('users.txt');
foreach ($users as $user) {
    if ($user[0] === $username && $user[5] === 'yes' && password_verify($password, $user[3])) {
        $valid = true;
        break;
    }
}

if ($valid) {
    session_regenerate_id(true);
    $_SESSION = ['username' => $username];
    header('Location: profile.php');
    exit;
}
header('Location: login.php?error=1');
