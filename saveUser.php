<?php
require 'utilFunctions.php';
checkCsrf();
$username = inputText($_POST['username'] ?? '', 30);
$role = inputText($_POST['role'] ?? '', 20);
$active = inputText($_POST['active'] ?? '', 3);
if (!in_array($role, ['plebe', 'upperclass']) || !in_array($active, ['yes', 'no'])) {
    stopPage('Choose a valid role and active status.');
}

$users = readRows('users.txt');
$user = requireLogin($users);
if (!isUpperclass($user) || $username === $user[0]) { stopPage('This account change is not allowed.', 403); }
$found = false;
foreach ($users as $index => $member) {
    if ($member[0] === $username) {
        $users[$index][4] = $role;
        $users[$index][5] = $active;
        $found = true;
    }
}
if (!$found) { stopPage('Account not found.', 404); }
writeRows('users.txt', "username\tname\temail\tpassword_hash\trole\tactive\talpha", $users);

header('Location: accounts.php?saved=1');
