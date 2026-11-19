<?php
require 'utilFunctions.php';
include 'header.php';
?>
<style>
.about-heading { text-align: center; }
.about-heading p { max-width: 680px; margin: 12px auto 0; }
.about-team { margin: 28px 0; }
.about-team > h2 { text-align: center; margin-bottom: 20px; }
.team-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; }
.team-member { margin: 0; padding: 20px; background: #290e19; border: 1px solid #a75a70; border-radius: 12px; text-align: center; }
.team-member img { display: block; width: 100%; max-width: 230px; aspect-ratio: 1; object-fit: cover; border: 2px solid #dca1b2; border-radius: 10px; margin: 0 auto 16px; }
.team-member h3 { margin: 0; font-size: 18px; color: #fff5f7; }
.team-member p { margin: 6px 0 0; color: #e2bdc8; font-size: 13px; }
.about-details h2 { margin-top: 0; }
.about-details p:last-child { margin-bottom: 0; }
@media (max-width: 700px) {
    .team-grid { grid-template-columns: 1fr; gap: 16px; }
    .team-member { padding: 20px; }
}
</style>

<section class="info about-heading">
    <h1>About 16th Company ProKnow</h1>
    <p>A company website for checking ProKnow scores, reading policy, and finding study resources.</p>
</section>

<section class="about-team" aria-labelledby="team-heading">
    <h2 id="team-heading">The Team</h2>
    <h2 id="team-heading">"Let Him Cook"</h2>
    <div class="team-grid">
         <article class="team-member">
            <img src="../images/olivia.jpg" alt="Olivia Green" width="230" height="230">
            <h3>Olivia Green</h3>
          
        </article>
        <article class="team-member">
            <img src="../images/caleb.JPG" alt="Caleb Chau" width="230" height="230">
            <h3>Caleb Chau</h3>

        </article>
        <article class="team-member">
            <img src="../images/anita.jpg" alt="Anita Zavyalova" width="230" height="230">
            <h3>Anita Zavyalova</h3>
          
        </article>
    </div>
</section>

<section class="info about-details">
    <h2>Our Project</h2>
    <p>We created this website for our SI350 project to keep ProKnow information in one place. Plebes can view their scores, request corrections, and acknowledge the company policy. Upperclass can manage accounts, review requests, and update the tracker.</p>
</section>

<section class="info about-details">
    <h2>Sources and Development</h2>
    <p>Most of the code is directly sourced from Caleb's SI350 maimai website. Policy documents and the ProBook were acquired via email for this project. Demonstration accounts and scores are fictional.</p>
</section>
<?php include 'footer.php'; ?>
