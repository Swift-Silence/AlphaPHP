<?= $this->HTML->Form->start('files', '/home', 'post', true); ?>
    <?= $this->HTML->Form->file('myfile'); ?>
    <?= $this->HTML->Form->submit('yay', 'Upload!'); ?>
<?= $this->HTML->Form->end(); ?>