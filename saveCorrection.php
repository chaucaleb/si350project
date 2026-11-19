<?php
// Persist correction requests/review notes in a tab-separated file.
require 'utilFunctions.php';
checkCsrf();

$user = requireLogin(readRows('users.txt'));
$requests = readRows('requests.txt');
if (isset($_POST['id'])) {
    if (!isUpperclass($user)) { stopPage('Upperclass access required.', 403); }
    $id = inputText($_POST['id'], 40);
    $decision = inputText($_POST['decision'] ?? '', 20);
    $note = inputText($_POST['note'] ?? '', 300);
    if (!in_array($decision, ['resolved', 'rejected']) || strlen($note) < 3) {
        stopPage('Choose a decision and enter a review note.');
    }
    $found = false;
    foreach ($requests as $index => $request) {
        if ($request[0] === $id && $request[5] === 'pending') {
            $requests[$index][5] = $decision;
            $requests[$index][6] = $note;
            $requests[$index][7] = $user[0];
            $found = true;
        }
    }
    if (!$found) { stopPage('Request not found or already reviewed.', 409); }
} else {
    if ($user[4] !== 'plebe') { stopPage('Only plebes submit score correction requests.', 403); }
    $chapter = inputText($_POST['chapter'] ?? '', 30);
    $score = checkScore($_POST['score'] ?? '', false);
    $reason = inputText($_POST['reason'] ?? '', 300);
    if (!in_array($chapter, $chapters) || strlen($reason) < 5) {
        stopPage('Choose an assessment and enter a reason of at least 5 characters.');
    }
    foreach ($requests as $request) {
        if ($request[1] === $user[0] && $request[2] === $chapter && $request[5] === 'pending') {
            stopPage('You already have a pending request for this assessment.', 409);
        }
    }
    $requests[] = [bin2hex(random_bytes(8)), $user[0], $chapter, $score, $reason, 'pending', '', ''];
}
writeRows('requests.txt', "id\tusername\tchapter\trequested_score\treason\tstatus\tnote\treviewer", $requests);

header('Location: corrections.php?saved=1');
