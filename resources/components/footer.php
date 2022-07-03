
<?php if (($alerts = getSession("alerts")) && isset(getSession("alerts")[0])) :?>
    <snackbar type="<?= $alerts[0]["type"] ?>" style="display: none"><?= $alerts[0]["message"] ?></snackbar>
    <?php deleteSession("alerts"); ?>
<?php endif ?>
</body>
</html>