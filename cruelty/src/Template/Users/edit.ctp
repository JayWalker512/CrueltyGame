<h2>Account Details</h2>
<table class="table">
    <tr>
        <th>Username</th>
        <td><?= h($user->username) ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= h($user->email) ?></td>
    </tr>
    <tr>
        <th>Score</th>
        <td><?= h($user->score) ?></td>
    </tr>
</table>
<?= $this->Form->create($user) ?>
<fieldset>
    <legend><?= __('Change Preferences') ?></legend>
    <?php
        echo $this->Form->control('receive_emails', [
            'type' => 'checkbox',
            'label' => 'Email you the results of games that you play?'
        ]);
    ?>
</fieldset>
<?= $this->Form->button(__('Save')) ?>
<?= $this->Form->end() ?>
<br/>
<?= $this->Form->create($passwordForm) ?>
<fieldset>
    <legend><?= __('Change Password') ?></legend>
    <?php
        echo $this->Form->control('old_password', ['type' => 'password']);
        echo $this->Form->control('new_password', ['type' => 'password']);
        echo $this->Form->control('confirm_new_password', ['type' => 'password']);
    ?>
</fieldset>
<?= $this->Form->button(__('Save')) ?>
<?= $this->Form->end() ?>
