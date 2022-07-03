<?= head("Register") ?>
<?= component("navbar", [
    "primary" => true,
    "user" => $user,
    "cartCount" => $cartCount,
    "active" => "auth"
]) ?>

<?php if (isset($errors)) : ?>
    <?php dump($errors) ?>
<?php endif; ?>

<div class="auth">
    <div class="illustration">
        <?= svg("auth_illustration") ?>
    </div>
    <div class="form">
        <h4>Register</h4>
        <p>Already have an account ? <a href="<?= route("auth.login") ?>">Login</a></p>
        <form method="POST" action="<?= route("auth.register") ?>" id="register">
            <div class="form-inline">
                <div class="form-group">
                    <div class="form-label">
                        <label for="firstname">Firstname</label>
                    </div>
                    <input type="text" name="firstname" id="firstname" />
                </div>
                <div class="form-group">
                    <div class="form-label">
                        <label for="lastname">Lastname</label>
                    </div>
                    <input type="text" name="lastname" id="lastname" />
                </div>
            </div>
            <div class="form-inline">
                <div class="form-group">
                    <div class="form-label">
                        <label for="username">Username</label>
                    </div>
                    <input type="text" name="username" id="username"/>
                </div>
                <div class="form-group">
                    <div class="form-label">
                        <label for="email">Email</label>
                    </div>
                    <input type="text" name="email" id="email" />
                </div>
            </div>
            <div class="form-inline">
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
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</div>



<?= foot() ?>