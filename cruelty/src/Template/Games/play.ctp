
<?php if (empty($loggedUser)): ?>
<h2>What's this?</h2>
<p>Cruelty is a game about anticipating the actions of other players. Each round, every player has the option
    to <i>check</i> a box, or to <i>not check</i> a box. At the end of the round, the ratio of players who
    checked versus the total number of players who participated is calculated.</p>
<p>If up to 50% of the players
    checked the box, those players who checked the box are awarded 10 points. However, if <i>more</i> than
    50% of the players checked the box, those players who checked the box <i>lose</i> 10 points. Every player
    can see the outcome of past rounds, but has to make a guess about what to do in the current round. It is
    similar in spirit to the <a href="https://en.wikipedia.org/wiki/Prisoner%27s_dilemma">prisoners dilemma</a>,
    or trying to predict the stock market.</p>
<p>Humans can play this of course, but the real fun comes from creating a strategy and
    <?= $this->Html->link('writing a bot to play for you', [
        'controller' => 'games', 'action' => 'api'
        ]) ?>. That way you can check in from time to time and see if you <i>really can</i>
    predict the future!</p>
<h2>To play, <?= $this->Html->link('make an account', [
    'controller' => 'Users',
    'action' => 'add'
]) ?> or <?= $this->Html->link('log in', [
    'controller' => 'Users',
    'action' => 'login'
]) ?>.</h2>
<?php else: ?>

<?php if ($bCanPlay == true): ?>
<h2>How To Play</h2>
<p>If <b>50% or less</b> of players check the box, those players who checked it win 10 points.</p>
<p>If <b>strictly more than 50%</b> of players check the box, those players who checked the box lose 10 points!</p>
<br/>
<p><?= "You are logged in as <b>" . h($loggedUser->username) . "</b> and your current score is <b>" . $loggedUser->score ?></b>.</p>
<?= $this->Form->create() ?>

<?= $this->Form->button("Check the box") ?>
<?= $this->Form->button("DON'T check the box", [
    'name' => 'checked_box',
    'value' => '0'
]) ?>
<?= $this->Form->end() ?>
<?php else: ?>
<p><?= "You chose to <b>" . ($userPlays[$currentGame['id']] ? "CHECK" : "NOT check") . "</b> the box. Wait until the game ends to play again!" ?></p>

<?php
endif;
endif;
?>
<br/>
<h4>Past Rounds</h4>
<table class="table">
    <thead>
        <th>Start Time (GMT)</th>
        <th>End Time (GMT)</th>
        <th>Total Players</th>
        <th>You</th>
        <th>Ratio</th>
    </thead>
    <tbody>
        <?php foreach ($pastGames as $game): ?>
        <?php if ($game->complete): ?>
        <tr class="<?= ($game->ratio > 0.5 ? "danger" : "success") ?>">
        <?php else: ?>
        <tr>
        <?php endif; ?>
            <td><?= $game->start_time ?></td>
            <td><?php
                if ($currentGame['id'] == $game->id) {
                    $now = new DateTime();
                    $interval = $now->diff(new DateTime($game->end_time));
                    echo $game->end_time . ' (' . ($game->end_time > $now ? $interval->format('%i') : 0) . ' minutes)';
                } else {
                    echo $game->end_time;
                }
            ?></td>
            <td><?= $game->total_plays ?></td>
            <td><?php
                if (isset($userPlays[$game->id])) {
                    if ($userPlays[$game->id]) {
                        echo $this->Html->icon('check');
                    } else {
                        echo $this->Html->icon('unchecked');
                    }
                }
            ?></td>
            <td><?= ($game->complete == true ? (string)((float)($game->ratio) * 100.0) . "%" : "???") ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h4>Leader Board</h4>
<table class="table">
    <thead>
        <th>User</th>
        <th>Score</th>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php
                if (isset($loggedUser)) {
                    echo ($user->username == $loggedUser->username) ? '<b>' . h($user->username) . '</b>' : h($user->username);
                }  else {
                    echo h($user->username);
                }
            ?></td>
            <td><?= h($user->score) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
