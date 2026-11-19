<?php
require 'utilFunctions.php';

$users = readRows('users.txt');
$user = requireLogin($users);
if (!isUpperclass($user)) { stopPage('Upperclass access required.', 403); }
$scores = readRows('scores.txt');
$editing = isUpperclass($user) && ($_GET['edit'] ?? '') === '1';

$search = inputText($_GET['search'] ?? '', 80);
$stats = trackerStats($users, $scores, $chapters);
$results = [];
foreach ($users as $member) {
    if ($member[4] === 'plebe' && ($search === '' || stripos($member[0] . ' ' . $member[1] . ' ' . ($member[6] ?? ''), $search) !== false)) {
        $results[] = $member;
    }
}
if (count($results) === 0) {
    echo '<p>No matches. Try another name/alpha or leave the search blank.</p>';
    exit;
}
?>
<?php if ($editing): ?>
<form id="bulkScores" action="saveScore.php" method="post" onsubmit="return prepareScores(this)">
<?php csrfInput(); ?>
<input type="hidden" name="changes" id="scoreChanges">
<button>Save all changes</button>
<?php endif; ?>
<table class="tracker">
    <caption>16th Company ProKnow Tracker — saved scores</caption>
    <thead><tr><th>Name</th><th>Alpha</th><?php foreach ($chapters as $chapter): ?><th><?= safe($chapter) ?></th><?php endforeach; ?><th>Score</th><th>Overall rank</th><th># of failures</th></tr></thead>
    <tbody>
    <?php foreach ($results as $member): ?>
        <tr>
            <th scope="row"><?= safe($member[1]) ?><small><?= safe($member[0]) ?></small></th>
            <td><?= safe($member[6] ?? '') ?></td>
            <?php foreach ($chapters as $chapter): ?>
                <?php $value = findScore($scores, $member[0], $chapter); $auto = hasAutofail($scores, $member[0], $chapter); ?>
                <td>
                    <?php if ($editing): ?>
                    <input data-user="<?= safe($member[0]) ?>" data-chapter="<?= safe($chapter) ?>" data-original="<?= safe($value) ?>" data-auto="<?= $auto ? 'yes' : 'no' ?>" value="<?= safe($value) ?>" type="number" min="0" max="105" step="0.01" aria-label="<?= safe($member[1] . ' ' . $chapter) ?>" class="<?= $auto || ($value !== '' && $value < 75) ? 'failed' : '' ?>">
                    <label class="auto"><input  type="checkbox" value="yes" <?= $auto ? 'checked' : '' ?>> Autofail</label>
                    <?php else: ?>
                        <span class="<?= $auto || ($value !== '' && $value < 75) ? 'failed' : '' ?>"><?= $value === '' ? '—' : safe($value) ?></span>
                        <?php if ($auto): ?><small>Autofail</small><?php endif; ?>
                    <?php endif; ?>
                </td>
            <?php endforeach; ?>
            <td><?= $stats[$member[0]]['average'] === null ? '—' : safe(number_format($stats[$member[0]]['average'], 2)) ?></td>
            <td><?= safe($stats[$member[0]]['rank'] ?? '—') ?></td>
            <td><?= safe($stats[$member[0]]['failures']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <?php foreach (['Average', 'Failures', 'Average without zeros', 'Zeros'] as $summary): ?>
            <tr><th colspan="2"><?= safe($summary) ?></th>
            <?php foreach ($chapters as $chapter): ?>
                <?php
                $values = []; $nonzero = []; $failures = 0; $zeros = 0;
                // Summary and ranks use ALL plebes, regardless of search filter.
                foreach ($users as $member) {
                    if ($member[4] !== 'plebe') { continue; }
                    $value = findScore($scores, $member[0], $chapter);
                    if ($value !== '') {
                        $values[] = (float) $value;
                        if ((float) $value == 0) { $zeros++; } else { $nonzero[] = (float) $value; }
                    }
                    if (hasAutofail($scores, $member[0], $chapter) || ($value !== '' && $value < 75)) { $failures++; }
                }
                $answer = '—';
                if ($summary === 'Failures') { $answer = $failures; }
                if ($summary === 'Zeros') { $answer = $zeros; }
                if ($summary === 'Average' && count($values) > 0) { $answer = number_format(array_sum($values) / count($values), 2); }
                if ($summary === 'Average without zeros' && count($nonzero) > 0) { $answer = number_format(array_sum($nonzero) / count($nonzero), 2); }
                ?>
                <td><?= safe($answer) ?></td>
            <?php endforeach; ?>
            <td colspan="3">All plebes</td></tr>
        <?php endforeach; ?>
    </tfoot>
</table>

<?php if ($editing): ?></form><?php endif; ?>
