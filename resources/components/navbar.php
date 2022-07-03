<div class="navbar <?= (!isset($primary) || !$primary) ? "" : "primary" ?>">
    <div class="navbar-logo">
        <a href="/">
            <?= svg("shoe") ?>
            Shoes
        </a>
    </div>
    <ul class="navbar-links">
        <li class="<?= (isset($active) && $active === "home" ? "active" : "") ?>">
            <a href="/">Home</a>
        </li>
        <li class="<?= (isset($active) && $active === "product" ? "active" : "") ?>">
            <a href="<?= route("products.list") ?>">Products</a>
        </li>
        <li class="<?= (isset($active) && $active === "contact" ? "active" : "") ?>">
            <a href="#">Contact us</a>
        </li>
    </ul>
    <ul class="navbar-icons">
        <li>
            <a href="<?= route("cart.checkout") ?>">
                <?= svg("cart") ?>
                <div class="badge"><?= isset($cartCount) ? $cartCount : 0 ?></div>
            </a>
        </li>
        <li class="<?= (isset($active) && $active === "auth" ? "active" : "") ?>">
            <?php if (!isset($user) || !$user): ?>
                <a href="<?= route("auth.login") ?>"><?= svg("login") ?></a>
            <?php else: ?>
                <a href="<?= route("account.me") ?>"><?= svg("user") ?></a>
            <?php endif; ?>
        </li>
    </ul>
</div>