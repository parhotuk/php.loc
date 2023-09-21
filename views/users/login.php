<div class="title">
    <h1>LOGIN</h1>
</div>
<?php if(isset($errorMessage) && !empty($errorMessage)): ?>
    <div class="error-messages">
        <?= $errorMessage ?>
    </div>
<?php endif; ?>
<div class="content">
    <form action="/login" method="post">
        <div class="form-line">
            <input type="text" name="login" placeholder="Login" value="<?php echo $_POST['login'] ?? '' ?>">
        </div>
        <div class="form-line">
            <input type="password" name="password" placeholder="Password" >
        </div>
        <div class="form-submit">
            <input type="submit" value="Login">
        </div>
    </form>
</div>
<div class="content-link">
    Don't have an account? <a href="/register">Sign Up</a>
</div>