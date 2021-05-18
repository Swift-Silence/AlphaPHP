<!doctype html>

<html>

<head>
    <title>Register</title>
    <?= $this->_('Alpha.Headers'); ?>
</head>

<body style="background: #111; color: #ddd;">
    <?= $this->HTML->Form->start('registration', '/register', 'post'); ?>
    <?= $this->HTML->Form->text('email', '', ['placeholder' => 'E-mail Address']); ?>
    <?= $this->HTML->Form->password('password', '', ['placeholder' => 'Password']); ?>
    <?= $this->HTML->Form->password('password_confirm', '', ['placeholder' => 'Confirm Password']); ?>
    <?= $this->HTML->Form->checkbox('terms', '1'); ?>
    <?= $this->HTML->Form->end(); ?>

    <?= $this->_('Alpha.Flash.HTML'); ?>
</body>

</html>