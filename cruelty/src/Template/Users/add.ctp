<?php
/**
 * @var \App\View\AppView $this
 */
?>

<?= $this->Form->create($user) ?>
<fieldset>
    <legend><?= __('Create Account') ?></legend>
    <?php
        echo $this->Form->control('username', [
            'label' => 'Username (this will show on the Leader Board)'
        ]);
        echo $this->Form->control('email');
        echo $this->Form->control('password');
        echo $this->Form->control('receive_email', [
            'label' => 'Email you the results of games that you play?',
            'type' => 'checkbox',
            'default' => true
        ]);
    ?>
</fieldset>
<?= $this->Form->button(__('Create')) ?>
<?= $this->Form->end() ?>
