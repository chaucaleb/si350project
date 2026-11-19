<?php
require 'utilFunctions.php';

$user = requireLogin(readRows('users.txt'));
if (!isUpperclass($user)) {
    stopPage('Upperclass access required.', 403);
}

$editing = ($_GET['edit'] ?? '') === '1';
include 'header.php';
?>
<section class="info">
    <h1>Score Tracker</h1>

    <?php if ($editing): ?>
        <p>Edit any scores, then select <strong>Save all changes</strong>.</p>
        <p>
            <a href="requestReport.php"
               onclick="return confirm('Discard unsaved edits and return to viewing scores?')">
                Cancel editing
            </a>
        </p>
    <?php endif; ?>

    <p>
        Blank = not recorded.<br>
        Zero = a recorded zero.<br>
        Autofail is a separate policy failure, even if the numeric score is passing.
    </p>

    <form id="searchForm">
        <input type="hidden" id="editMode"
               value="<?= $editing ? '1' : '0' ?>">

        <label for="search">Search by name, username, or alpha</label>
        <input id="search" name="search" maxlength="80"
               placeholder="Leave blank to show all plebes">

        <button>Search / refresh</button>
    </form>

    <p id="searchStatus" role="status" aria-live="polite"></p>
    <div id="reportResults" class="table-wrap"></div>

    <p>
        Ranks and summary rows always use the full roster.
        Score is the unweighted mean of recorded assessments.
        Averages and ranks are calculated from saved scores.
    </p>

    <?php if (!$editing): ?>
        <p>
            <a class="button" href="requestReport.php?edit=1">
                Edit scores
            </a>
        </p>
    <?php endif; ?>
</section>
<?php include 'footer.php'; ?>