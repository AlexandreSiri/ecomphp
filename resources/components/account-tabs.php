<div class="tabs">
    <a class="tab <?= isset($active) && $active === "me" ? "active" : "" ?>" href="<?= route("account.me") ?>">
        <div class="tab-icon"><?= svg("user") ?></div>
        <div class="tab-name">Personal informations</div>
    </a>
    <a class="tab <?= isset($active) && $active === "security" ? "active" : "" ?>" href="<?= route("account.security") ?>">
        <div class="tab-icon"><?= svg("lock") ?></div>
        <div class="tab-name">Security</div>
    </a>
    <a class="tab <?= isset($active) && $active === "addresses" ? "active" : "" ?>" href="<?= route("account.addresses") ?>">
        <div class="tab-icon"><?= svg("location") ?></div>
        <div class="tab-name">Addresses</div>
    </a>
    <hr class="separator" />
    <a class="tab <?= isset($active) && $active === "orders" ? "active" : "" ?>" href="<?= route("account.orders") ?>">
        <div class="tab-icon"><?= svg("cart") ?></div>
        <div class="tab-name">Orders</div>
    </a>
    <a class="tab <?= isset($active) && $active === "reviews" ? "active" : "" ?>" href="<?= route("account.reviews") ?>">
        <div class="tab-icon"><?= svg("star") ?></div>
        <div class="tab-name">Reviews</div>
    </a>
    <hr class="separator" />
    <a class="tab" href="<?= route("auth.logout") ?>">
        <div class="tab-icon"><?= svg("logout") ?></div>
        <div class="tab-name">Logout</div>
    </a>
</div>