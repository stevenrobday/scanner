<?php require APPROOT . '/views/inc/header.php'; ?>
<div id="loginHeader">Login</div>
<p id="loginMsg">Please fill in your login credentials.</p>
<form action="<?php echo URLROOT; ?>/users/login" method="post">
    <div>
        <input type="text" name="username" placeholder="Username" class="loginField <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username']; ?>">
        <div class="help-block"><?php echo $data['username_err']; ?></div>
    </div>    
    <div>
        <input type="password" name="password" placeholder="Password" class="loginField <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
        <div class="help-block"><?php echo $data['password_err']; ?></div>
    </div>
    <div>
        <input type="submit" id="loginBtn" value="LOGIN">
    </div>
</form>
<?php require APPROOT . '/views/inc/footer.php'; ?>