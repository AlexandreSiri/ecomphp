<?= head("Me") ?>
<?= component("navbar", [
    "user" => $user,
    "cartCount" => $cartCount,
    "active" => "auth"
]) ?>

<div class="account">
    <?= component("account-tabs", ["active" => "me"]) ?>
    <div class="account-information">
        <form action="">
            <div class="form-inline">
                <div class="form-group">
                    <div class="form-label">
                        <label for="firstname">Firstname</label>
                    </div>
                    <input type="text" name="firstname" id="firstname" value="<?= $user->firstname ?>"/>
                </div>
                <div class="form-group">
                    <div class="form-label">
                        <label for="lastname">Lastname</label>
                    </div>
                    <input type="text" name="lastname" id="lastname" value="<?= $user->lastname ?>"/>
                </div>
            </div>
            <div class="form-inline">
                <div class="form-group">
                    <div class="form-label">
                        <label for="username">Username</label>
                    </div>
                    <input type="text" name="username" id="username" value="<?= $user->username ?>"/>
                </div>
                <div class="form-group">
                    <div class="form-label">
                        <label for="email">Email</label>
                    </div>
                    <input type="text" name="email" id="email" value="<?= $user->email ?>"/>
                </div>
            </div>
            <div class="form-inline">
                <div class="form-group">
                    <div class="form-label">
                        <label for="birthday">Birthday</label>
                    </div>
                    <input type="text" name="birthday" id="birthday" value="<?= $user->birthAt ? formatDate($user->birthAt, false) : "" ?>"/>
                </div>
                <div class="form-info">
                    <div class="form-label">Fidelity points</div>
                    <div class="form-value"><?= $user->fidelity->points ?></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update profile</button>
        </form>
    </div>
</div>


<?= foot() ?>