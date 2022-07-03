<?= head("Addresses") ?>
<?= component("navbar", [
    "user" => $user,
    "cartCount" => $cartCount,
    "active" => "auth"
]) ?>

<div class="account">
    <?= component("account-tabs", ["active" => "addresses"]) ?>
    <div class="account-information">
        <div class="illustration <?= !count($addresses) ? "visible" : "" ?>">
            <?= svg("address_illustration") ?>
            <h4>No addresses in your account.</h4>
        </div>
        <div class="addresses-list">
            <?php foreach ($addresses as $address): ?>
                <div class="address" id="<?= $address["id"] ?>">
                    <span><?= $address["name"] ?> - <?= $address["street"] ?>, <?= $address["postal"] ?> <?= $address["city"] ?>, <?= $address["country"] ?></span>
                    <div class="address-icon">
                        <?= svg("trash") ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>


<?= foot() ?>