<!doctype html>

<html>
    <head>
        <title>AlphaPHP | A PHP Framework</title>
        <?= $this->_('Alpha.Headers'); ?>
    </head>

    <body style="background: #111; color: #ddd;">

        <?= $this->HTML->Form->start('files', '/home', 'post', true); ?>
            <?= $this->HTML->Form->file('myfile'); ?>
            <?= $this->HTML->Form->submit('yay', 'Upload!'); ?>
        <?= $this->HTML->Form->end(); ?>

        <?= $this->_('Alpha.Flash.HTML'); ?>
    </body>
</hmtl> 