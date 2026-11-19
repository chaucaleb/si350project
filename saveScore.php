<?php
require 'utilFunctions.php';
checkCsrf();
$users = readRows('users.txt');
$user = requireLogin($users);
if (!isUpperclass($user)) { stopPage('Upperclass access required to edit scores.', 403); }
$changes = json_decode($_POST['changes'] ?? '', true);
if (!is_array($changes) || count($changes) < 1 || count($changes) > 10000) { stopPage('Submit edited scores using the tracker.'); }
$scores = readRows('scores.txt');
$seen = [];
foreach ($changes as $change) {
    if (!is_array($change)) { stopPage('Invalid score change.'); }
    $username = inputText($change['username'] ?? '', 30);
    $chapter = inputText($change['chapter'] ?? '', 30);
    $found = false;
    foreach ($users as $member) { if ($member[0] === $username && $member[4] === 'plebe') { $found = true; } }
    if (!$found || !in_array($chapter, $chapters, true)) { stopPage('Invalid plebe or assessment.'); }
    $key = $username . ':' . $chapter;
    if (isset($seen[$key])) { stopPage('Duplicate assessment.'); }
    $seen[$key] = true;
    $value = checkScore($change['score'] ?? null);
    $auto = $change['autofail'] ?? null;
    if (!in_array($auto, ['yes', 'no'], true)) { stopPage('Invalid autofail.'); }
    if (($change['original'] ?? null) !== findScore($scores, $username, $chapter) || ($change['originalAuto'] ?? null) !== (hasAutofail($scores, $username, $chapter) ? 'yes' : 'no')) {
        stopPage('An edited score changed since you loaded it. No changes saved. Refresh and try again.', 409);
    }
    $found = false;
    foreach ($scores as $i => $score) {
        if ($score[0] === $username && $score[1] === $chapter) {
            $scores[$i] = [$username, $chapter, $value, $auto]; $found = true;
        }
    }
    if (!$found) { $scores[] = [$username, $chapter, $value, $auto]; }
}
// Validate the entire submission first, then write once into the existing PHP-owned file.
writeRows('scores.txt', "username\tchapter\tscore\tautofail", $scores);
header('Location: requestReport.php?saved=1');
