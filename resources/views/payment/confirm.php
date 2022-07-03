<?= head("Payment confirmation") ?>
<?= component("navbar", [
    "user" => $user,
    "cartCount" => $cartCount
]) ?>

<div class="page-illustration">
    <?= svg("payment_illustration") ?>
    <div class="page-illustration-content">
        <h5>Thanks for your order !</h5>
        <p>Your payment was successful, and your order is complete. <br />A receipt has been sent to your inbox.</p>
    </div>
</div>


<?= foot() ?>