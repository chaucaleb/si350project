<?php
// Plebes submit requests; upperclass record a review decision.
require 'utilFunctions.php';

$user = requireLogin(readRows('users.txt'));
$requests = readRows('requests.txt');

include 'header.php';
?>
<section class="info">
    <h1>Score corrections</h1>
    <?php if (!isUpperclass($user)): ?>
        <form action="saveCorrection.php" method="post">
            <?php csrfInput(); ?>
            <label for="chapter">Assessment</label>
            <select id="chapter" name="chapter"><?php foreach ($chapters as $chapter): ?><option><?= safe($chapter) ?></option><?php endforeach; ?></select>
            <label for="score">Score you believe is correct</label>
            <input id="score" name="score" type="number" min="0" max="105" step="0.01" required>
            <label for="reason">Reason (one line)</label>
            <input id="reason" name="reason" minlength="5" maxlength="300" required>
            <button>Submit request</button>
        </form>
    <?php else: ?>
        <p>Review each request. If a score change is needed, an upperclass member must update it on All scores before you mark the request resolved.</p>
    <?php endif; ?>
</section>
<?php $visible = 0; ?>
<?php foreach ($requests as $request): ?>
    <?php if (isUpperclass($user) || $request[1] === $user[0]): ?>
        <?php $visible++; ?>
        <section class="info">
            <h2><?= safe($request[1]) ?> — <?= safe($request[2]) ?></h2>
            <p>Requested score: <?= safe($request[3]) ?> | Status: <?= safe($request[5]) ?></p>
            <p><?= safe($request[4]) ?></p>
            <?php if ($request[6] !== ''): ?><p>Review: <?= safe($request[6]) ?></p><?php endif; ?>
            <?php if (isUpperclass($user) && $request[5] === 'pending'): ?>
                <form action="saveCorrection.php" method="post">
                    <?php csrfInput(); ?>
                    <input type="hidden" name="id" value="<?= safe($request[0]) ?>">
                    <label>Decision<select name="decision"><option value="resolved">Resolved (score already checked/updated)</option><option value="rejected">Rejected</option></select></label>
                    <label>Review note<input name="note" minlength="3" maxlength="300" required></label>
                    <button>Save decision</button>
                </form>
            <?php endif; ?>
        </section>
    <?php endif; ?>
<?php endforeach; ?>
<?php if ($visible === 0): ?><p>No correction requests yet.</p><?php endif; ?>
<?php include 'footer.php'; ?>
