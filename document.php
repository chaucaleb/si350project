<?php
// Serve only known PDFs after checking the user's current permissions.
require 'utilFunctions.php';

$user = requireLogin(readRows('users.txt'));

$document = inputText($_GET['document'] ?? '', 20);
if (!in_array($document, ['probook', 'policy', 'battalion_policy', 'brigade_guidance', 'brigade_failure'])) { stopPage('Document not found.', 404); }
$filename = __DIR__ . '/../pdf/' . $document . '.pdf';
if (!is_file($filename)) { stopPage('The PDF has not been supplied yet.', 404); }
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $document . '.pdf"');
session_write_close();
readfile($filename);
