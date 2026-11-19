<?php

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'
]);
ini_set('session.use_strict_mode', '1');
session_start();
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

$chapters = ['Chapter 1', 'Chapter 2', 'Chapter 3', 'Chapter 4', 'Chapter 5', 'Chapter 6', 'Chapter 7', 'Chapter 8', 'Chapter 9', 'Chapter 10', 'Chapter 11', 'Chapter 12', 'Final'];

// Escape text before printing it into HTML.
function safe($text) {
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

function stopPage($message, $status = 400) {
    http_response_code($status);
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>ProKnow</title><link rel="stylesheet" href="styles.css"></head><body><div class="container"><header class="site-header"><a class="brand" href="index.php"><img src="../images/stallions.jpg" alt="16th Company Stallions logo" width="60" height="80"><span>16th Company<small>Stallions · ProKnow</small></span></a></header><main class="info"><p>' . safe($message) . '</p><p><a href="index.php">Home</a> | <a href="login.php">Login</a></p></main></div></body></html>';
    exit;
}

function requirePost($field, $message) {
    if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST[$field])) {
        stopPage($message);
    }
}

// Tabs/newlines are not allowed inside fields in a tab-separated file.
function inputText($value, $maximum = 100) {
    if (!is_string($value) || strlen($value) > $maximum || preg_match('/[\t\r\n]/', $value)) {
        stopPage('Invalid input. Check the length and remove tabs or line breaks.');
    }
    return trim($value);
}

// Same pattern as LOG.txt: skip the heading, read each line, split at tabs.
function readRows($filename) {
    $rows = [];
    $file = fopen(__DIR__ . '/' . $filename, 'r');
    if ($file === false) {
        stopPage('Missing data file. Read README.md for setup.', 503);
    }
    fgets($file);
    while (($line = fgets($file)) !== false) {
        $rows[] = explode("\t", rtrim($line, "\r\n"));
    }
    fclose($file);
    return $rows;
}

function writeRows($filename, $heading, $rows) {
    // Direct fopen/fwrite/fclose, like the original lab's LOG.txt handler.
    // Suppress the raw fopen warning so the friendly 503 error can be sent.
    $file = @fopen(__DIR__ . '/' . $filename, 'w');
    if ($file === false) {
        stopPage('PHP could not write to ' . $filename . '. No changes were saved.', 503);
    }
    $text = $heading . PHP_EOL;
    foreach ($rows as $row) {
        $text .= implode("\t", $row) . PHP_EOL;
    }
    if (fwrite($file, $text) !== strlen($text)) {
        fclose($file);
        stopPage('The file could not be saved completely.', 503);
    }
    fclose($file);
}

// Look up the account again on every request. Session role alone is not trusted.
function findUser($users) {
    foreach ($users as $user) {
        if ($user[0] === ($_SESSION['username'] ?? '') && $user[5] === 'yes') {
            return $user;
        }
    }
    return null;
}

function requireLogin($users) {
    $user = findUser($users);
    if ($user === null) {
        stopPage('Please log in with an active account.', 401);
    }
    return $user;
}

function isUpperclass($user) {
    return $user[4] === 'upperclass';
}

// The project requires a random session token on every form that changes data.
function csrfInput() {
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    echo '<input type="hidden" name="csrf" value="' . safe($_SESSION['csrf']) . '">';
}

function checkCsrf() {
    requirePost('csrf', 'Please use the form on the website.');
    if (!is_string($_POST['csrf']) || !isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        stopPage('Invalid form token. Reload the form and try again.', 403);
    }
}

function checkScore($value, $allowBlank = true) {
    $value = inputText($value, 10);
    if ($value === '' && $allowBlank) {
        return '';
    }
    if ($value === '' || !is_numeric($value) || $value < 0 || $value > 105) {
        stopPage('Enter a score from 0 to 105. Blank means not recorded.');
    }
    return (string) round((float) $value, 2);
}

function findScore($scores, $username, $chapter) {
    foreach ($scores as $score) {
        if ($score[0] === $username && $score[1] === $chapter) {
            return $score[2];
        }
    }
    return '';
}

// An autofail counts once, even if that assessment's score is also below 75.
function hasAutofail($scores, $username, $chapter) {
    foreach ($scores as $score) {
        if ($score[0] === $username && $score[1] === $chapter) {
            return ($score[3] ?? 'no') === 'yes';
        }
    }
    return false;
}

// Recalculate from saved scores. Equal unrounded averages share competition rank.
// Blank scores are excluded from averages; recorded zeroes are included.
function trackerStats($users, $scores, $chapters) {
    $stats = [];
    foreach ($users as $member) {
        if ($member[4] !== 'plebe') { continue; }
        $total = 0; $count = 0; $failures = 0;
        foreach ($chapters as $chapter) {
            $value = findScore($scores, $member[0], $chapter);
            if ($value !== '') { $total += (float) $value; $count++; }
            if (hasAutofail($scores, $member[0], $chapter) || ($value !== '' && $value < 75)) { $failures++; }
        }
        $stats[$member[0]] = ['average' => $count > 0 ? $total / $count : null, 'failures' => $failures, 'rank' => null];
    }
    foreach ($stats as $username => $record) {
        if ($record['average'] === null) { continue; }
        $rank = 1;
        foreach ($stats as $other) {
            if ($other['average'] !== null && $other['average'] > $record['average']) { $rank++; }
        }
        $stats[$username]['rank'] = $rank;
    }
    return $stats;
}

