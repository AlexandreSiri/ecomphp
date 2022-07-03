<?= head($product["name"]) ?>
<?= component("navbar", [
    "user" => $user,
    "primary" => false,
    "cartCount" => $cartCount,
    "active" => "product"
]) ?>

<div class="products-detail">
    <div class="navigation">
        <a href="<?= route("products.list") ?>" class="gray">Products</a>
        <a class="gray"><?= $product["category"] ?></a>
        <a><?= $product["name"] ?></a>
    </div>
    <div class="container">
        <div class="images-list">
            <?php foreach ($product["images"] as $key => $image) : ?>
                <div class="image <?= $key === 0 ? "selected" : "" ?>" data-image="<?= $image ?>" style="background-image: url('<?= $image ?>')"></div>
            <?php endforeach ?>
        </div>
        <div class="image-large" style="background-image: url('<?= $product["images"][0] ?>')"></div>
        <div class="content">
            <h4><?= $product["name"] ?></h4>
            <div class="information">
                <span class="price">$<?= price($product) ?></span>
                <?= component("rating", ["rating" => $product["rating"]]) ?>
            </div>
            <div class="choices" id="color">
                <div class="choices-label">
                    <span class="label">Color</span>
                    <span class="value"><?= $product["color"] ?></span>
                </div>
                <div class="choices-list">
                    <?php foreach ($product["colors"] as $color) : ?>
                        <a class="choice <?= $color["name"] === $product["color"] ? "selected" : "" ?>" href="<?= route("products.detail", ["id" => $color["id"], "slug" => $color["slug"]]) ?>" style="background-image: url('<?= $color["image"] ?>')"></a>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="choices" id="size">
                <div class="choices-label">
                    <span class="label">Size</span>
                    <span class="value"><?= $product["size"]["size"] ?></span>
                </div>
                <div class="choices-list">
                    <?php foreach ($product["sizes"] as $size) : ?>
                        <div id="<?= $size["id"] ?>" class="choice <?= $size["id"] === $product["size"]["id"] ? "selected" : "" ?> <?= $size["disabled"] ? "disabled" : "" ?>">
                            <?= $size["size"] ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
            <button id="add-cart" class="btn btn-primary" <?= $product["size"]["disabled"] ? "disabled" : ""?>>
                <?= $product["size"]["disabled"] ? "No stock" : "Add to cart"?>
            </button>
        </div>
    </div>
    <div class="reviews">
        <div class="reviews-title">
            <?= component("rating", ["rating" => $product["rating"], "size" => "large"]) ?>
            <h4><?= $product["reviewsCount"] ?> Review<?= $product["reviewsCount"] > 1 ? "s" : "" ?></h4>
        </div>
        <div class="reviews-head">
            <div class="filter" id="sort">
                <div class="label">
                    <span>
                        <p>Sort by</p>
                        <div>Newest</div>
                    </span>
                    <?= svg("arrow") ?>
                </div>
                <ul class="choices">
                    <li>
                        <div>
                            <input type="checkbox" id="newest" checked />
                            <label for="newest">Newest</label>
                        </div>
                    </li>
                    <li>
                        <div>
                            <input type="checkbox" id="low" />
                            <label for="low">Note -</label>
                        </div>
                    </li>
                    <li>
                        <div>
                            <input type="checkbox" id="high" />
                            <label for="high">Note +</label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="reviews-list">
            <?php foreach ($product["reviews"] as $review): ?>
                <div class="review">
                    <?= component("rating", ["rating" => $review["note"], "size" => "large"]) ?>
                    <div class="review-content">
                        <div class="review-body">
                            <?= $review["content"] ?>
                        </div>
                        <div class="review-footer">
                            <span class="date"><?= $review["createdAt"] ?></span>
                            <span class="author"><?= $review["author"] ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>


<?= foot() ?>