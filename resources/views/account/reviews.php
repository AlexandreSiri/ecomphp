<?= head("Reviews") ?>
<?= component("navbar", [
    "user" => $user,
    "cartCount" => $cartCount,
    "active" => "auth"
]) ?>

<div class="account">
    <?= component("account-tabs", ["active" => "reviews"]) ?>
    <div class="account-information">
        <div class="illustration <?= !count($reviews) ? "visible" : "" ?>">
            <?= svg("review_illustration") ?>
            <h4>No reviews in your account.</h4>
        </div>
        <div class="reviews-list">
            <?php foreach ($reviews as $review): ?>
                <div class="review-row" data-id="<?= $review["id"] ?>">
                    <div class="image" style="background-image: url('<?= $review["product"]["image"] ?>')"></div>
                    <div class="content">
                        <div class="detail">
                            <h6>
                                <a href="<?= route("products.detail", [
                                    "id" => $review["product"]["id"], 
                                    "slug" => $review["product"]["slug"]
                                ]) ?>"><?= $review["product"]["name"] ?></a>
                            </h6>
                            <p><?= $review["product"]["color"] ?></p>
                        </div>
                        <div class="review">
                            <div class="note">
                                <?= component("rating", [
                                    "rating" => $review["note"],
                                ]) ?>
                            </div>
                        </div>
                        <div class="review-content">
                            <?= $review["content"] ?>
                        </div>
                    </div>
                    <div class="trash" data-id="<?= $review["id"] ?>">
                        <?= svg("trash") ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>


<?= foot() ?>