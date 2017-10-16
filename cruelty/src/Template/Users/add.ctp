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
    ?>
</fieldset>
<?= $this->Form->button(__('Create')) ?>
<?= $this->Form->end() ?>
