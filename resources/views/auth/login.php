<?= head("Login") ?>
<?= component("navbar", [
    "primary" => true,
    "user" => $user,
    "cartCount" => $cartCount,
    "active" => "auth"
]) ?>

<?php if (isset($errors)): ?>
    <?php dump($errors) ?>
<?php endif; ?>

<div class="auth">
    <div class="illustration">
        <?= svg("auth_illustration") ?>
    </div>
    <div class="form">
        <h4>Login</h4>
        <p>Don't have an account ? <a href="<?= route("auth.register") ?>">Register</a></p>
        <form method="POST" action="<?= route("auth.login") ?>" id="login">
            <div class="form-group">
                <div class="form-label">
                    <label for="email">Email</label>
                </div>
                <input type="text" name="email" id="email"/>
            </div>
            <div class="form-group">
                <div class="form-label">
                    <label for="password">Password</label>
                    <span><a href="<?= route("auth.forgot") ?>" tabindex="-1">Forgot password ?</a></span>
                </div>
                <input type="password" name="password" id="password"/>
            </div>
            <div class="form-checkbox">
                <input type="checkbox" name="remember" id="remember" tabindex="-1"/>
                <label for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</div>


<?= foot() ?>