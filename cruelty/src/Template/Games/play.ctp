
    <?php if (empty($loggedUser)): ?>
    <p>To play, <?= $this->Html->link('make an account', [
        'controller' => 'Users',
        'action' => 'add'
    ]) ?> or <?= $this->Html->link('log in', [
        'controller' => 'Users',
        'action' => 'login'
    ]) ?>.</p>
    <?php else: ?>

    <?php if ($bCanPlay == true): ?>
    <h2>How To Play</h2>
    <p>If <b>less</b> than 50% of players check the box, those players who checked it win 10 points.</p>
    <p>If <b>more</b> than 50% of players check the box, those players who checked the box lose 10 points!</p>
    <p><?= "Your current score is " . $loggedUser->score ?></p>
    <?= $this->Form->create() ?>
    <?= $this->Form->input('checked_box', [
        'type' => 'checkbox',
        'label' => 'Check the box?'
    ]) ?>
    <?= $this->Form->button('Play') ?>
    <?= $this->Form->end() ?>
    <?php else: ?>
    <p><?= "You chose to <b>" . ($usersPlay->checked_box ? "CHECK" : "NOT check") . "</b> the box." ?></p>

    <?php
    endif;
    endif;
    ?>
    <br/>
    <h4>Past Games</h4>
    <table class="table">
        <thead>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Total Plays</th>
            <th>Total Checked</th>
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
                <td><?= $game->end_time ?></td>
                <td><?= $game->total_plays ?></td>
                <td><?= ($game->complete == true ? $game->total_checked : "???") ?></td>
                <td><?= ($game->complete == true ? $game->ratio : "???") ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
