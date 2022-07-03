<?= head("Forgot") ?>
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
        <h4>Forgot password</h4>
        <p>Remembered ? <a href="<?= route("auth.login") ?>">Login</a></p>
        <form method="POST" action="<?= route("auth.forgot") ?>" id="forgot">
            <div class="form-group">
                <div class="form-label">
                    <label for="email">Email</label>
                </div>
                <input type="text" name="email" id="email"/>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</div>


<?= foot() ?>