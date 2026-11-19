<?php
require 'utilFunctions.php';
include 'header.php';
?>
<section class="info home-heading">
    <h1>16th Company ProKnow</h1>
    <p>Welcome to the <strong>16th Company ProKnow</strong> Homepage!</p>
</section>

<div class="home-grid">
    <section class="info">
        <h2>Your Profile</h2>
        <?php if ($user !== null): ?>
            <p>Welcome back, <strong><?= safe($user[1]) ?></strong>!</p>
            <?php if (isUpperclass($user)): ?>
                <p>View the company tracker and update plebe scores.</p>
                <p><a class="button" href="requestReport.php">View company scores</a></p>
                <p><a href="corrections.php">Review correction requests</a></p>
            <?php else: ?>
                <p>Check your chapter scores and overall average.</p>
                <p><a class="button" href="profile.php">View your scores</a></p>
                <p><a href="corrections.php">Request a score correction</a></p>
            <?php endif; ?>
        <?php else: ?>
            <p>Log in to view your ProKnow scores.</p>
            <p><a class="button" href="login.php">Login</a></p>
            <p>Need an account? <a href="registration.php">Register here</a>.</p>
        <?php endif; ?>
    </section>

    <section class="info">
        <h2>Documentation</h2>
        <p>Useful links for 16th Company ProKnow.</p>
        <p><a href="policy.php">Company policy</a></p>
        <p><a href="resources.php">ProBook and other resources</a></p>
        <?php if ($user !== null && $user[4] === 'plebe'): ?>
            <p>Remember to acknowledge the company policy after reading it.</p>
        <?php endif; ?>
    </section>
</div>
<?php include 'footer.php'; ?>
