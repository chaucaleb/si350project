<?php
require 'utilFunctions.php';
checkCsrf();
$name = inputText($_POST['name'] ?? '', 80);
$username = inputText($_POST['username'] ?? '', 30);
$email = inputText($_POST['email'] ?? '', 100);
$password = inputText($_POST['passwordexample'] ?? '', 200);
$confirmation = inputText($_POST['confirmation'] ?? '', 200);
$error = '';
if (!preg_match('/^m[0-9]{6}$/D', $username)) {
    $error = 'Username must be lowercase m followed by exactly six numbers, like m301234.';
} elseif ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Enter your last name and a valid email address.';
} elseif (strlen($password) < 12 || $password !== $confirmation) {
    $error = 'Passwords must match and contain at least 12 characters.';
}

$users = readRows('users.txt');
foreach ($users as $user) {
    if ($user[0] === $username) {
        $error = 'Username already taken.';
    }
}
if ($error !== '') {

    // Keep ordinary fields, but never save plaintext passwords in session data.
    $_SESSION['form_data'] = ['name' => $name, 'username' => $username, 'email' => $email];
    header('Location: registration.php?error=' . urlencode($error));
    exit;
}
$users[] = [$username, $name, $email, password_hash($password, PASSWORD_DEFAULT), 'plebe', 'yes', substr($username, 1)];
writeRows('users.txt', "username\tname\temail\tpassword_hash\trole\tactive\talpha", $users);

session_regenerate_id(true);
$_SESSION = ['username' => $username];
header('Location: profile.php');
