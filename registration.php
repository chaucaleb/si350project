<?php
// Registration form using the same field names as the supplied lab.
require 'utilFunctions.php';
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);
include 'header.php';
?>
<section class="info narrow">
    <h1>Register</h1>
    <p>New accounts are plebes. An upperclass member assigns upperclass access.</p>
    <?php if (isset($_GET['error'])): ?>
        <p class="error"><?= safe($_GET['error']) ?></p>
    <?php endif; ?>
    <form action="submit.php" method="post" id="registrationForm">
        <?php csrfInput(); ?>
        <label for="name">Last Name</label>
        <input id="name" name="name" maxlength="80" value="<?= safe($form_data['name'] ?? '') ?>" required>
        <label for="username">Username (m######)</label>
        <input id="username" name="username" pattern="m[0-9]{6}" minlength="7" maxlength="7" title="Enter lowercase m followed by exactly six numbers." placeholder="m301234" value="<?= safe($form_data['username'] ?? '') ?>" autocomplete="username" required>
        <small>Lowercase <code>m</code> followed by your alpha, like m301234.</small>
        <label for="email">Email</label>
        <input id="email" name="email" type="email" maxlength="100" value="<?= safe($form_data['email'] ?? '') ?>" required>
        <label for="passwordexample">Password</label>
        <input id="passwordexample" name="passwordexample" type="password" minlength="12" maxlength="200" autocomplete="new-password" required>
        <small>Use at least 12 characters.</small>
        <label for="confirmation">Confirm password</label>
        <input id="confirmation" name="confirmation" type="password" minlength="12" maxlength="200" autocomplete="new-password" required>
        <p id="formError" class="error" role="alert"></p>
        <button>Register</button>
    </form>
</section>
<?php include 'footer.php'; ?>
