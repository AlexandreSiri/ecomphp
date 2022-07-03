<div id="<?= isset($id) ? $id : "" ?>" class="rating <?= isset($size) && $size === "large" ? "large" : "" ?> <?= isset($ratable) && $ratable ? "ratable" : "" ?>">
    <?php if ($rating >= 1) : ?><div class="star filled" id="1"><?= svg("star") ?></div>
    <?php else : ?>
        <div class="star" id="1">
            <div class="mask" style="clip-path: polygon(0 0, <?= ($rating - 0) * 100 ?>% 0, <?= ($rating - 0) * 100 ?>% 100%, 0% 100%);">
                <?= svg("star") ?>
            </div>
            <?= svg("star") ?>
        </div>
    <?php endif ?>
    
    <?php if ($rating >= 2) : ?><div class="star filled" id="2"><?= svg("star") ?></div>
    <?php else : ?>
        <div class="star" id="2">
            <div class="mask" style="clip-path: polygon(0 0, <?= ($rating - 1) * 100 ?>% 0, <?= ($rating - 1) * 100 ?>% 100%, 0% 100%);">
                <?= svg("star") ?>
            </div>
            <?= svg("star") ?>
        </div>
    <?php endif ?>

    <?php if ($rating >= 3) : ?><div class="star filled" id="3"><?= svg("star") ?></div>
    <?php else : ?>
        <div class="star" id="3">
            <div class="mask" style="clip-path: polygon(0 0, <?= ($rating - 2) * 100 ?>% 0, <?= ($rating - 2) * 100 ?>% 100%, 0% 100%);">
                <?= svg("star") ?>
            </div>
            <?= svg("star") ?>
        </div>
    <?php endif ?>

    <?php if ($rating >= 4) : ?><div class="star filled" id="4"><?= svg("star") ?></div>
    <?php else : ?>
        <div class="star" id="4">
            <div class="mask" style="clip-path: polygon(0 0, <?= ($rating - 3) * 100 ?>% 0, <?= ($rating - 3) * 100 ?>% 100%, 0% 100%);">
                <?= svg("star") ?>
            </div>
            <?= svg("star") ?>
        </div>
    <?php endif ?>

    <?php if ($rating >= 5) : ?><div class="star filled" id="5"><?= svg("star") ?></div>
    <?php else : ?>
        <div class="star" id="5">
            <div class="mask" style="clip-path: polygon(0 0, <?= ($rating - 4) * 100 ?>% 0, <?= ($rating - 4) * 100 ?>% 100%, 0% 100%);">
                <?= svg("star") ?>
            </div>
            <?= svg("star") ?>
        </div>
    <?php endif ?>
</div>