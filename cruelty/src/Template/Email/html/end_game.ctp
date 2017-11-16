<h1>Cruelty</h1>

<p>The last Cruelty game you played ended with a ratio of <?= (string)((float)($ratio) * 100.0) . "%" ?>.</p>

<?php if ($checked): ?>
<p><b>You have <?= ($ratio > 0.5 ? "lost" : "been awarded") ?> 10 points!</b></p>
<?php else: ?>
<p>You did not check the box last game, so your score has not changed.</p>
<?php endif; ?>

<p>Would you like to <a href="<?= $gameDomain ?>">play again?</a></p>