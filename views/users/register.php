<div class="title">
    <h1>REGISTER</h1>
</div>
<?php if(isset($errorMessage) && !empty($errorMessage)): ?>
    <div class="error-messages">
        <?= $errorMessage ?>
    </div>
<?php endif; ?>
<div class="content">
    <form action="/register" method="post">
        <div class="form-line">
            <input type="text" name="login" placeholder="Login" value="<?php echo $_POST['login'] ?? '' ?>">
        </div>
        <div class="form-line">
            <input type="password" name="password" placeholder="Password" >
        </div>
        <div class="form-line">
            <input type="password" name="confirm_password" placeholder="Repeat password" >
        </div>
        <div class="form-submit">
            <input type="submit" value="Register">
        </div>
    </form>
</div>
<div class="content-link">
    Have an account? <a href="/login">Sign In</a>
</div>