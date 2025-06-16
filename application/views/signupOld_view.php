<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
</head>
<body>
    <h2>Sign Up</h2>
    
    <?php $CI =& get_instance(); ?>

<?php if ($CI->session->flashdata('success')): ?>
    <div class="alert success"><?= $CI->session->flashdata('success') ?></div>
<?php endif; ?>

<?php if ($CI->session->flashdata('error')): ?>
    <div class="alert error"><?= $CI->session->flashdata('error') ?></div>
<?php endif; ?>
    
    <?= form_open('signup') ?>
        
        <div>
            <label>Username:</label>
            <input type="text" name="username" value="<?= set_value('username') ?>" required>
            <?= form_error('username', '<div class="error">', '</div>') ?>
        </div>
        
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
            <?= form_error('password', '<div class="error">', '</div>') ?>
        </div>
        
        <button type="submit">Sign Up</button>
    <?= form_close() ?>
</body>
</html>