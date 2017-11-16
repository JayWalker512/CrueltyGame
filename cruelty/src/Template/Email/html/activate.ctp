<h1>Cruelty</h1>

<p>Your account with username <b><?= h($user->username) ?></b> has been created.</p>

<p>Please <a href=<?= '"' . $gameDomain . '/users/activate/' . $user->activation_string . '"' ?>>Click Here To Activate Your Account</a> and start playing Cruelty!</p>
