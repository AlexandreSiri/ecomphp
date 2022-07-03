<?= head("Orders") ?>
<?= component("navbar", [
    "user" => $user,
    "cartCount" => $cartCount,
    "active" => "auth"
]) ?>

<div class="account">
    <?= component("account-tabs", ["active" => "orders"]) ?>
    <div class="account-information">
        <div class="illustration <?= !count($orders) ? "visible" : "" ?>">
            <?= svg("cart_illustration") ?>
            <h4>No orders in your account.</h4>
        </div>
        <div class="orders-list">
            <?php foreach ($orders as $order) : ?>
                <div class="order">
                    <div class="order-head">
                        <div class="order-head-start">
                            <span class="number"><b>Order : </b><?= $order["number"] ?></span>
                            <a class="download" href="/invoices/<?= $order["number"] ?>.pdf" target="_blank"><?= svg("download") ?></a>
                        </div>
                        <div class="order-head-end">
                            <span class="data"><?= $order["date"] ?></span>
                            <span class="price">$<?= $order["price"] ?></span>
                        </div>
                    </div>
                    <div class="order-products-list">
                        <?php foreach ($order["products"] as $product) : ?>
                            <div class="order-product <?= $product["review"] && !$product["review"]["content"] ? "review" : "" ?>" data-id="<?= $product["id"] ?>">
                                <div class="image" style="background-image: url('<?= $product["image"] ?>')"></div>
                                <div class="content">
                                    <div class="detail">
                                        <h6>
                                            <a href="<?= route("products.detail", ["id" => $product["id"], "slug" => $product["slug"]]) ?>"><?= $product["name"] ?></a>
                                        </h6>
                                        <p><?= $product["color"] ?> - <?= $product["size"] ?></p>
                                        <h6>$<?= $product["price"] ?></h6>
                                    </div>
                                    <div class="review">
                                        <span class="label">Rate it</span>
                                        <div class="note">
                                            <?= component("rating", [
                                                "rating" => $product["review"] ? $product["review"]["note"] : 0,
                                                "ratable" => true,
                                                "id" => $product["id"]
                                            ]) ?>
                                            <span class="write">Write a review</span>
                                            <?php if ($product["review"] && $product["review"]["content"]): ?>
                                                <p><?= $product["review"]["content"] ?></p>
                                            <?php endif ?>
                                        </div>
                                        <div class="comment">
                                            <textarea></textarea>
                                            <div class="comment-footer">
                                                <span class="cancel">Cancel</span>
                                                <span class="submit">Submit</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="count">x<?= $product["quantity"] ?></div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>


<?= foot() ?>