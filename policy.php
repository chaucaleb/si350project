<?php
require 'utilFunctions.php';
$users = readRows('users.txt');
$user = findUser($users);
$readings = $user !== null ? readRows('readings.txt') : [];
include 'header.php';
?>
<section class="info policy">
        <p>5 Sep 26</p>
        <h1>16th Company ProKnow Policy</h1>
        <p><strong>MEMORANDUM</strong></p>
        <p><strong>FROM:</strong> MIDN 1/C ALBANESE, USN, 16TH COMPANY PROFESSIONAL DEVELOPMENT ADMIN OFFICER</p>
        <p><strong>TO:</strong> LT MINNIGERODE, USN, 16TH COMPANY OFFICER<br>ETVCS SISTO, USN, 16TH COMPANY SENIOR ENLISTED LEADER</p>
        <p><strong>VIA:</strong> MIDN LT JIN, USN, 16TH COMPANY COMMANDER</p>
        <p><strong>Subj:</strong> 16TH Company ProKnow Policy</p>
        <h2>1. Purpose.</h2>
        <p>To inform Midshipmen of the failure policy for Professional Knowledge quizzes and provide information regarding repercussions for failures and incentives at the company level.</p>
        <h2>2. Failure Policy.</h2>
        <p><strong>a.</strong> A quiz score below 75%, including any extra credit, constitutes a failure. Additionally, failing to adequately complete weekly come-around sheets or to obtain the necessary MQS signatures will result in an automatic failure. The number of failures will reset to zero at the start of the semester and accumulate throughout. All remedial come-around sheets will be conducted in addition to regular weekly come-arounds. Failure to comply with Brigade, Battalion, and Company Failure Policies will result in an automatic failure for the next quiz.</p>
        <p><strong>b.</strong> Fireteam leaders will be responsible for ensuring their Plebe carries out the Brigade, Battalion, and Company Failure Policies.</p>
        <p><strong>i. 1st Failure:</strong> The 4/C will schedule an extra come around with their platoon sergeant for each week of the following chapter. They will also place their phone outside of the Plebe Admin’s room during study period from 1900-2100 if they are on deck.</p>
        <p><strong>ii. 2nd Failure:</strong> The 4/C shall have a come-around with their Professional Development Admin, Plebe Admin, and their chain of command up to and including their Platoon Commander. The Professional Development Admin and chain of command shall identify areas for improvement, and send a write up to the Brigade and Battalion training staffs before the next test.</p>
        <p><strong>iii. 3rd Failure:</strong> The 4/C MIDN will arrange a come-around with the Battalion Training Sergeant in SDBs or Whites (whichever is applicable) along with their Youngster Mentor. They must bring a completed memorandum, signed, and dated by their Squad Leader, summarizing the importance of the ProKnow topic to present to the Battalion Training Sergeant.</p>
        <p><strong>iv. 4th or Subsequent Failure:</strong> The 4/C and their chain of command up to and including their Platoon Commander will attend a come-around with the Brigade Training Sergeant. These come-arounds are to be coordinated and set up by the 4/C, in accordance with the MTP.</p>
        <h2>3. Autofail Procedure.</h2>
        <p><strong>a.</strong> Remediations will follow the same procedure as Brigade failures. In addition, the plebe with an autofail will conduct one company tour.</p>
        <h2>4. Preventative Measures.</h2>
        <p><strong>a. Worst 3 Scores:</strong> The worst 3 scores will be responsible for making a review presentation slideshow to send out to their class by the first Sunday of the next ProKnow topic (the Sunday where a test is not actively being taken).</p>
        <p><strong>b. Score below a 90%:</strong> Plebe will make a handwritten study guide due by Wednesday during the week of the exam and schedule an extra come-around with their Platoon Commander. The Platoon Commander will sign off on the study guide and ensure extra review of the material. Only one-comearound with their Platoon Commander is required during the study period, and Platoon Commanders will send a text to ensure they met with their plebes as needed.</p>
        <p><strong>c. Score below an 85%:</strong> Plebe will follow the same procedure as a score below 90%. In addition, they will attend an evening study session on Thursday at 1900 the week of the test (subject to change) at the discretion of the Platoon Commander that is in charge of NMTs for the week of the test.</p>
        <h2>5. Extra Credit.</h2>
        <p><strong>a.</strong> Extra credit will be offered in the form of five extra credit questions at the end of each exam worth a maximum of five points. The questions will be in reference to any of the material that plebes rate, including plebe summer rates.</p>
        <h2>6. Incentives.</h2>
        <h3>a. Battalion Incentives</h3>
        <p><strong>i.</strong> A company that scores a weekly average greater than 90% shall receive a company-wide sleep-in to be used the following week, at the discretion of the Company Commander.</p>
        <p><strong>ii.</strong> Plebes that receive a 100% or better on a quiz shall receive a sleep-in at the discretion of their Squad Leader.</p>
        <h3>b. Company Incentives</h3>
        <p><strong>i.</strong> The platoon with the best overall score on ProKnow for a given week will be allowed modified carry-on (not allowed to close doors) for one day at discretion of the Company Commander.</p>
        <p>A.R. ALBANESE<br>MIDN USN</p>
        <p>V. JIN<br>16th Company Commander</p>
        <p>ETVCS SISTO, DOMINIC<br>16th Company Senior Enlisted Officer</p>
        <p>LT MINNIGERODE, RYAN<br>16th Company Company Officer</p>
        <hr>
        <p><a href="document.php?document=policy">Open original PDF</a></p>
        <p><a href="resources.php">Browse ProKnow resources</a> </p>

</section>
<?php if ($user !== null): ?>
<?php if (is_file(__DIR__ . '/../pdf/policy.pdf')): ?>
    <?php $version = hash_file('sha256', __DIR__ . '/../pdf/policy.pdf'); ?>
    <section class="info" id="acknowledgement">
        <h2>Policy acknowledgement</h2>
        <?php if ($user[4] === 'plebe'): ?>
            <?php $acknowledged = false; ?>
            <?php foreach ($readings as $reading) { if ($reading[0] === $user[0] && $reading[1] === $version) { $acknowledged = true; } } ?>
            <p><?= $acknowledged ? 'You have acknowledged the current policy.' : 'You have not acknowledged the current policy.' ?></p>
            <?php if (!$acknowledged): ?>
            <p>Confirm that you have read the company policy above.</p>
            <form action="acknowledge.php" method="post">
                <?php csrfInput(); ?>
                <input type="hidden" name="version" value="<?= safe($version) ?>">
                <label ><input type="checkbox" name="read" value="yes" required> I have read the current company policy.</label>
                <button>Record acknowledgement</button>
            </form>
            <?php endif; ?>
        <?php else: ?>
            <table><tr><th>Plebe</th><th>Current policy</th></tr>
                <?php foreach ($users as $member): ?>
                    <?php if ($member[4] === 'plebe'): ?>
                        <?php $date = ''; foreach ($readings as $reading) { if ($reading[0] === $member[0] && $reading[1] === $version) { $date = $reading[2]; } } ?>
                        <tr><td><?= safe($member[1]) ?></td><td><?= $date !== '' ? 'Acknowledged ' . safe($date) : 'Not acknowledged' ?></td></tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </section>
<?php endif; ?>
<?php else: ?>
<section class="info" id="acknowledgement"><h2>Policy acknowledgement</h2><p><a href="login.php">Log in</a> to acknowledge the company policy.</p></section>
<?php endif; ?>
<?php include 'footer.php'; ?>
