<?php
// Save a username, PDF fingerprint, and UTC date in readings.txt.
require 'utilFunctions.php';
checkCsrf();

$user = requireLogin(readRows('users.txt'));
if ($user[4] !== 'plebe') { stopPage('Plebe access required.', 403); }
if (!is_file(__DIR__ . '/../pdf/policy.pdf')) { stopPage('The policy is not available.'); }
$version = hash_file('sha256', __DIR__ . '/../pdf/policy.pdf');
if (($_POST['version'] ?? '') !== $version || ($_POST['read'] ?? '') !== 'yes') {
    stopPage('Reload the page and confirm you have read the current policy.', 409);
}
$readings = readRows('readings.txt');
$updated = [];
foreach ($readings as $reading) {
    if ($reading[0] !== $user[0]) { $updated[] = $reading; }
}
$updated[] = [$user[0], $version, gmdate('Y-m-d H:i:s') . ' UTC'];
writeRows('readings.txt', "username\tpolicy_version\tdate", $updated);

header('Location: policy.php?saved=1#acknowledgement');
