<?= head() ?>
<?= component("navbar", [
    "user" => $user,
    "primary" => true,
    "cartCount" => $cartCount,
    "active" => "home"
]) ?>

<div class="home">
    <div class="header">
        <div class="content">
            <h2>Find your <br />Favorite and Best Shoes</h2>
            <h5>Get shoes of the highest quality and affordable price. Do not hesitate to shop at our store, we always prioritize service to customers.</h5>
        </div>
        <div class="image">
            <img src="/images/landing-image.png" />
        </div>
    </div>
    <div class="promotion">
        <div class="discount">
            <div class="content">
                <h4>Big Sale, Discount !!</h4>
                <h3>35%</h3>
                <a href="<?= route("products.list") ?>" class="btn btn-outline">More</a>
            </div>
            <div class="image">
                <img src="/images/promo-image.png" />
            </div>
        </div>
        <div class="cart">
            <div class="cart-description">
                <h5>My cart</h5>
                <p>Some of the items that you have selected</p>
            </div>
            <div class="cart-product-list">
                <?php foreach ($carts as $cart) : ?>
                    <a class="cart-product" href="<?= route("products.detail", ["id" => $cart["id"], "slug" => $cart["slug"]]) ?>">
                        <div class="cart-product-image" style="background-image: url('<?= $cart["image"] ?>')"></div>
                        <div class="cart-product-detail">
                            <h6><?= $cart["name"] ?></h6>
                            <p><?= $cart["color"] ?> - <?= $cart["size"] ?></p>
                            <h6>$<?= price($cart) ?></h6>
                        </div>
                    </a>
                <?php endforeach ?>
            </div>
            <a href="<?= route("cart.checkout") ?>" class="btn btn-primary">Checkout</a>
        </div>
    </div>
    <div class="popular">
        <h4>
            <a href="<?= route("products.list") ?>">Most popular <?= svg("arrow") ?></a>
        </h4>
        <div class="products-list">
            <?php foreach ($popular as $product): ?>
                <a class="product" href="<?= route("products.detail", ["id" => $product["id"], "slug" => $product["slug"]]) ?>">
                    <div class="image" style="background-image: url('<?= $product["image"] ?>')"></div>
                    <div class="information">
                        <h6><?= $product["name"] ?></h6>
                        <?= component("rating", ["rating" => $product["score"]]) ?>
                        <p>$<?= price($product) ?></p>
                    </div>
                </a>
            <?php endforeach ?>
        </div>
    </div>
    <div class="collection">
        <div class="image">
            <img src="/images/promo-image.png" />
        </div>
        <div class="content">
            <h4>
                New collection winter <br />
                available until 25/04
            </h4>
            <a href="<?= route("products.list") ?>" class="btn btn-outline">More</a>
        </div>
    </div>
</div>


<?= foot() ?>
