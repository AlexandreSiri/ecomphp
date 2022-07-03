<?= head("Checkout") ?>
<?= component("navbar", [
    "user" => $user,
    "primary" => false,
    "cartCount" => $cartCount
]) ?>

<div class="checkout">
    <div class="cart">
        <div class="cart-products">
            <div class="illustration <?= (!count($products)) ? "visible" : "" ?>">
                <?= svg("cart_illustration") ?>
                <h4>No products in your cart.</h4>
            </div>
            <?php foreach ($products as $row) : ?>
                <div class="cart-product" id="<?= $row["product"]["id"] ?>">
                    <div class="image" style="background-image: url('<?= $row["product"]["image"] ?>')"></div>
                    <div class="content">
                        <h4>
                            <a href="<?= route("products.detail", ["id" => $row["product"]["productId"], "slug" => $row["product"]["slug"]]) ?>">
                                <?= $row["product"]["name"] ?>
                            </a>
                        </h4>
                        <p><?= $row["product"]["color"] ?> - <?= $row["product"]["size"] ?></p>
                        <h4 class="price" data-price="<?= $row["product"]["promo"] ?? $row["product"]["price"] ?>">$<?= price($row["product"]) ?></h4>
                    </div>
                    <div class="count">
                        <div class="panel">
                            <button class="less">
                                <?= svg("less") ?>
                            </button>
                            <span><?= $row["count"] ?></span>
                            <button class="more">
                                <?= svg("more") ?>
                            </button>
                        </div>
                        <button class="delete">
                            <?= svg("trash") ?>
                        </button>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <div class="payment">
        <div class="payment-head">
            <h3>Payment Details</h3>
            <p>Complete your purchase by providing your payment details.</p>
        </div>
        <form method="POST" action="<?= route("cart.checkout") ?>">
            <div class="payment-information">
                <div class="form-group">
                    <div class="form-label">
                        <label for="email">Email</label>
                    </div>
                    <input type="text" name="email" id="email" value="<?= isset($user) ? $user->email : "" ?>" />
                </div>
            </div>
            <div class="delivery-information">
                <input type="hidden" name="addressId" value="<?= !count($addresses) ? "0" : $addresses[0]["id"] ?>"/>
                <?php if (isset($user)): ?>
                    <div class="delivery-addresses">
                        <div class="information-label">Delivery</div>
                        <div class="addresses">
                            <?php foreach ($addresses as $key => $address): ?>
                                <div class="address <?= $key === 0 ? "active" : "" ?>" id="<?= $address["id"] ?>">
                                    <?= svg("location") ?>
                                    <?= $address["name"] ?> - <?= $address["street"] ?>, <?= $address["postal"] ?> <?= $address["city"] ?>, <?= $address["country"] ?>
                                </div>
                            <?php endforeach ?>
                            <div class="add <?= !count($addresses) ? "active" : "" ?>" id="add">
                                <?= svg("more") ?>
                                Add addresses
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <div class="delivery-address <?= !count($addresses) ? "active" : "" ?>">
                    <div class="information-label">Address</div>
                    <div class="delivery-form">
                        <div class="form-group">
                            <input type="text" name="street" placeholder="Street" />
                        </div>
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" name="zip" placeholder="ZIP"/>
                            </div>
                            <div class="form-group">
                                <input type="text" name="city" placeholder="City" />
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" name="country" placeholder="Country" />
                            </div>
                            <div class="form-group">
                                <input type="text" name="name" placeholder="Full name" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="payment-foot">
                <ul class="payment-detail">
                    <li class="duty-free">
                        <span>Subtotal</span>
                        <span>$<?= $price ?></span>
                    </li>
                    <li class="vat">
                        <span>VAT (20%)</span>
                        <span>$<?= $price * 0.2 ?></span>
                    </li>
                    <li class="bold total">
                        <span>Total</span>
                        <span>$<?= $price * 1.2 ?></span>
                    </li>
                </ul>
                <button type="submit" class="btn btn-primary" <?= !count($products) ? "disabled" : "" ?>>Pay $<?= $price * 1.2 ?></button>
            </div>
        </form>
    </div>
</div>

<?= foot() ?>