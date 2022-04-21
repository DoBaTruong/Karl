<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
?>
<section class="top-discount-area row">
    <!-- Single Discount Area -->
    <?php
    $disQue = $conn->query("SELECT * FROM promotions");
    while ($discount = mysqli_fetch_array($disQue)) {
    ?>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <h5><?= mb_strtoupper($discount['prm_name']) ?></h5>
            <h6>USE CODE: <?= $discount['prm_code'] ?></h6>
        </div>
    <?php } ?>
</section>