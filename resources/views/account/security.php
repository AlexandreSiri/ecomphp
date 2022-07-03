<?= head("Security") ?>
<?= component("navbar", [
    "user" => $user,
    "cartCount" => $cartCount,
    "active" => "auth"
]) ?>

<div class="account">
    <?= component("account-tabs", ["active" => "security"]) ?>
    <div class="account-information">
        <form action="">
            <div class="form-inline">
                <div class="form-group">
                    <div class="form-label">
                        <label for="old_password">Old password</label>
                    </div>
                    <input type="password" name="old_password" id="old_password"/>
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
                        <label for="confirm_password">Confirm new password</label>
                    </div>
                    <input type="password" name="confirm_password" id="confirm_password"/>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update password</button>
        </form>
    </div>
</div>


<?= foot() ?>