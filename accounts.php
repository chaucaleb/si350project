<?php
require 'utilFunctions.php';

$users = readRows('users.txt');
$user = requireLogin($users);
if (!isUpperclass($user)) { stopPage('Upperclass access required.', 403); }

include 'header.php';
?>
<section class="info">
    <h1>Manage accounts</h1>
    <p>Verify identity before changing a role. Disabled users cannot log in or continue using protected pages.</p>
    <?php foreach ($users as $member): ?>
        <form action="saveUser.php" method="post" class="account">
            <?php csrfInput(); ?>
            <input type="hidden" name="username" value="<?= safe($member[0]) ?>">
            <p><strong><?= safe($member[1]) ?></strong> (<?= safe($member[0]) ?>) — <?= safe($member[2]) ?></p>
            <?php if ($member[0] === $user[0]): ?>
                <p>Your own account cannot be changed here.</p>
            <?php else: ?>
                <label>Role<select name="role"><?php foreach (['plebe', 'upperclass'] as $role): ?><option <?= $role === $member[4] ? 'selected' : '' ?>><?= safe($role) ?></option><?php endforeach; ?></select></label>
                <label>Active<select name="active"><option value="yes" <?= $member[5] === 'yes' ? 'selected' : '' ?>>Yes</option><option value="no" <?= $member[5] === 'no' ? 'selected' : '' ?>>No (disabled)</option></select></label>
                <button>Save account</button>
            <?php endif; ?>
        </form>
    <?php endforeach; ?>
</section>
<?php include 'footer.php'; ?>
