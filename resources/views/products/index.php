<?= head("Products") ?>
<?= component("navbar", [
    "user" => $user,
    "primary" => false,
    "cartCount" => $cartCount,
    "active" => "product"
]) ?>

<div class="products">
    <div class="products-head">
        <div class="filters">
            <div class="filter" id="category">
                <div class="label">
                    <span>Category</span>
                    <?= svg("arrow") ?>
                </div>
                <ul class="choices">
                    <?php foreach ($categories as $category) : ?>
                        <li>
                            <div>
                                <input type="checkbox" id="<?= $category["id"] ?>" />
                                <label for="<?= $category["id"] ?>"><?= $category["name"] ?></label>
                            </div>
                            <?php if (count($category["sub"])) : ?>
                                <ul class="sub-choices">
                                    <?php foreach ($category["sub"] as $sub) : ?>
                                        <li>
                                            <div>
                                                <input type="checkbox" id="<?= $sub["id"] ?>" />
                                                <label for="<?= $sub["id"] ?>"><?= $sub["name"] ?></label>
                                            </div>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            <?php endif ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <div class="filter" id="size">
                <div class="label">
                    <span>Size</span>
                    <?= svg("arrow") ?>
                </div>
                <ul class="choices">
                    <?php foreach ($sizes as $size) : ?>
                        <li>
                            <div>
                                <input type="checkbox" id="<?= $size ?>" />
                                <label for="<?= $size ?>"><?= $size ?></label>
                            </div>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <div class="filter" id="color">
                <div class="label">
                    <span>Color</span>
                    <?= svg("arrow") ?>
                </div>
                <ul class="choices">
                    <?php foreach ($colors as $color) : ?>
                        <li>
                            <div>
                                <input type="checkbox" id="<?= strtolower($color) ?>" />
                                <label for="<?= strtolower($color) ?>"><?= $color ?></label>
                            </div>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
        <div class="sort">
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
                            <input type="checkbox" id="newest" checked/>
                            <label for="newest">Newest</label>
                        </div>
                    </li>
                    <li>
                        <div>
                            <input type="checkbox" id="low" />
                            <label for="low">Price -</label>
                        </div>
                    </li>
                    <li>
                        <div>
                            <input type="checkbox" id="high" />
                            <label for="high">Price +</label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="products-list">
        <?php foreach ($products as $p): ?>
            <a class="product" href="<?= route("products.detail", ["id" => $p["id"], "slug" => $p["slug"]]) ?>">
                <div class="image" style="background-image: url('<?= $p["image"] ?>')"></div>
                <div class="information">
                    <h6><?= $p["name"] ?></h6>
                    <?= component("rating", ["rating" => $p["rating"]]) ?>
                    <p>$<?= price($p) ?></p>
                </div>
            </a>
        <?php endforeach ?>
    </div>
</div>


<?= foot() ?>