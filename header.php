<?php
if (!isset($user)) { $user = findUser(readRows('users.txt')); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>16th Company ProKnow</title>
    <link rel="stylesheet" href="styles.css">
    <script src="functions.js" defer></script>
    <style>
        /* Full-width bands, with page contents kept centered. */
        body.proknow-layout { margin: 0; padding: 0; min-height: 100vh; display: flex; flex-direction: column; }
        .proknow-layout .masthead { width: 100%; background: #180b10; border-bottom: 1px solid #663044; }
        .proknow-layout .site-header { border: 0; border-radius: 0; margin: 0 auto; padding: 12px 20px; }
        .proknow-layout .navigation-band { width: 100%; background: #700d2b; border-bottom: 1px solid #a4415c; }
        .proknow-layout nav.container { margin: 0 auto; padding: 14px 20px; border: 0; border-radius: 0; }
        .proknow-layout .page-content { flex: 1; padding: 20px; }
        .proknow-layout .site-footer { width: 100%; margin: 24px 0 0; padding: 0; border-radius: 0; background: #230b15; }
        .proknow-layout .site-footer .container { padding: 18px 20px; text-align: left; }
        @media (max-width: 700px) {
            .proknow-layout .site-header, .proknow-layout nav.container { padding: 12px 16px; }
            .proknow-layout .page-content { padding: 12px; }
        }
    </style>
</head>
<body class="proknow-layout">
<header class="masthead">
    <div class="container site-header">
        <a class="brand" href="index.php">
            <img src="../images/stallions.jpg" alt="16th Company Stallions logo" width="60" height="80">
            <span>16th Company<small>Stallions</small></span>
        </a>
        <?php if ($user !== null): ?><p class="signed-in"><?= safe($user[1]) ?> · <?= safe($user[0]) ?></p><?php endif; ?>
    </div>
</header>
<div class="navigation-band">
    <nav class="container" aria-label="Main navigation">
        <a href="index.php">Home</a>
        <?php if ($user !== null): ?>
            <a href="<?= isUpperclass($user) ? 'requestReport.php' : 'profile.php' ?>"><?= isUpperclass($user) ? 'All scores' : 'My scores' ?></a>
        <?php endif; ?>
        <a href="policy.php">Policy</a>
        <?php if ($user !== null): ?>
            <a href="resources.php">Resources</a>
            <a href="corrections.php">Corrections</a>
            <?php if (isUpperclass($user)): ?><a href="accounts.php">Accounts</a><?php endif; ?>
        <?php endif; ?>
        <a href="about.php">About</a>
        <?php if ($user !== null): ?>
            <form action="logout.php" method="post" class="nav-account">
                <?php csrfInput(); ?><button>Logout</button>
            </form>
        <?php else: ?>
            <a class="nav-account" href="login.php">Login</a>
            <a href="registration.php">Register</a>
        <?php endif; ?>
    </nav>
</div>
<main class="container page-content">
    <?php if (isset($_GET['saved'])): ?><p class="message" role="status">Saved successfully.</p><?php endif; ?>
