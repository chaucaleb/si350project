<?php
require 'utilFunctions.php';

$users = readRows('users.txt');
$user = requireLogin($users);
$scores = readRows('scores.txt');
$stats = isUpperclass($user) ? [] : trackerStats($users, $scores, $chapters);

include 'header.php';
?>
<section class="info">
    <h1><?= safe($user[1]) ?>'s profile</h1>
    <p>Username: <?= safe($user[0]) ?> | Role: <?= safe($user[4]) ?></p>
    <?php if (isUpperclass($user)): ?>
        <p><a href="requestReport.php">View all plebe scores</a></p>
    <?php else: ?>
        <h2>My scores</h2>
        <table>
            <tr><th>Assessment</th><th>Score</th><th>Autofail</th></tr>
                <?php foreach ($chapters as $chapter): ?>
                <?php $value = findScore($scores, $user[0], $chapter); ?>
                <tr><td><?= safe($chapter) ?></td><td><?= $value === '' ? 'Not recorded' : safe($value) ?></td><td><?= hasAutofail($scores, $user[0], $chapter) ? 'Yes' : 'No' ?></td></tr>
            <?php endforeach; ?>
        </table>
        <p>Average: <?= $stats[$user[0]]['average'] !== null ? safe(round($stats[$user[0]]['average'], 2)) . '%' : 'Not available' ?></p>
        <p>Overall rank: <?= safe($stats[$user[0]]['rank'] ?? '—') ?> | Failures: <?= safe($stats[$user[0]]['failures']) ?></p>
        <p>Blank scores are excluded. Recorded zeroes count. This is an unweighted average.</p>
    <?php endif; ?>
</section>
<?php include 'footer.php'; ?>
