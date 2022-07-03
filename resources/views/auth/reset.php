<?= head("Reset") ?>
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
        <h4>Reset password</h4>
        <p>Remembered ? <a href="<?= route("auth.login") ?>">Login</a></p>
        <form method="POST" action="" id="reset">
            <div class="form-group">
                <div class="form-label">
                    <label for="password">Password</label>
                </div>
                <input type="password" name="password" id="password"/>
            </div>
            <div class="form-group">
                <div class="form-label">
                    <label for="confirm_password">Password confirmation</label>
                </div>
                <input type="password" name="confirm_password" id="confirm_password"/>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</div>


<?= foot() ?>