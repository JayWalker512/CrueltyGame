<div>
    <h2>How To Play</h2>
    <p>If less than 50% of players check the box, those players who checked it win 10 points.</p>
    <p>If more than 50% of players check the box, those players who checked the box lose 10 points!</p>

    <?php if ($bCanPlay == true): ?>
    <?= $this->Form->create() ?>
    <?= $this->Form->input('checked_box', [
        'type' => 'checkbox',
        'label' => 'Check the box?'
    ]) ?>
    <?= $this->Form->button('Submit') ?>
    <?= $this->Form->end() ?>
    <?php else: ?>
    <b><?= "You chose to " . ($usersPlay->checked_box ? "CHECK" : "NOT check") . " the box." ?></b>
    <?php endif; ?>

    <table>
        <thead>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Total Plays</th>
            <th>Total Checked</th>
            <th>Ratio</th>
        </thead>
        <tbody>
            <?php foreach ($pastGames as $game): ?>
            <tr>
                <td><?= $game->start_time ?></td>
                <td><?= $game->end_time ?></td>
                <td><?= $game->total_plays ?></td>
                <td><?= ($game->complete == true ? $game->total_checked : "???") ?></td>
                <td><?= ($game->complete == true ? $game->ratio : "???") ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>