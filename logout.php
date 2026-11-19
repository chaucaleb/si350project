<?php
// Invalidate the session. POST/token required by the assignment.
require 'utilFunctions.php';
checkCsrf();
$_SESSION = [];
setcookie(session_name(), '', [
    'expires' => time() - 3600,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'
]);
session_destroy();
header('Location: index.php');
