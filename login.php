<?php
require 'utilFunctions.php';
include 'header.php';
?>
<section class="info narrow">
    <h1>Login</h1>
    <?php if (isset($_GET['error'])): ?>
        <p class="error">Incorrect username or password, or your account is disabled.</p>
    <?php endif; ?>
    <form action="process_login.php" method="post">
        <?php csrfInput(); ?>
        <label for="username">Username (m######)</label>
        <input id="username" name="username" maxlength="30" autocomplete="username" required>
        <label for="passwordexample">Password</label>
        <input id="passwordexample" name="passwordexample" type="password" maxlength="200" autocomplete="current-password" required>
        <button>Login</button>
    </form>
</section>
<?php include 'footer.php'; ?>
