<?php
require 'utilFunctions.php';
$user = requireLogin(readRows('users.txt'));
$resources = [
    ['ProBook', '2025–2026 reference book', 'document.php?document=probook', 'PDF'],
    ['Company policy', '16th Company ProKnow policy', 'document.php?document=policy', 'PDF'],
    ['Battalion policy', '3rd Battalion ProKnow policy', 'document.php?document=battalion_policy', 'PDF'],
    ['Brigade guidance', 'FAY27 Professional Knowledge guidance', 'document.php?document=brigade_guidance', 'PDF'],
    ['Brigade failure policy', 'FAY27 failures, retakes, and incentives', 'document.php?document=brigade_failure', 'PDF'],
    ['ProKnow spreadsheet', 'Open the shared Google Sheet', 'https://docs.google.com/spreadsheets/d/1jexccC5P9p5MtjxMCZIMk4qP40XZ7u7zgJzQMRNtD5w/edit?usp=sharing', 'GOOGLE SHEETS']
];
include 'header.php';
?>
<section class="info">
    <h1>ProKnow resources</h1>
    <p>Select a resource to open it in a new tab.</p>
    <div class="resource-grid">
    <?php foreach ($resources as $resource): ?>
        <a class="resource-card" href="<?= safe($resource[2]) ?>" target="_blank" rel="noopener noreferrer">
            <span class="resource-type"><?= safe($resource[3]) ?></span>
            <strong><?= safe($resource[0]) ?></strong>
            <span><?= safe($resource[1]) ?></span>
        </a>
    <?php endforeach; ?>
    </div>
</section>
<?php include 'footer.php'; ?>
