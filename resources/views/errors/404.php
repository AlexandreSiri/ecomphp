<?= head("404") ?>
<?= component("navbar", [
    "user" => $user,
    "cartCount" => $cartCount
]) ?>

<div class="page-illustration">
    <?= svg("not_found_illustration") ?>
    <div class="page-illustration-content">
        <h5>Not found !</h5>
        <p>The page you're viewing doesn't exist.</p>
    </div>
</div>


<?= foot() ?>