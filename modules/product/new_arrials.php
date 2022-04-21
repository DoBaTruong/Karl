<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
?>
<div id="newArrivals" class="row shop-new-arrivals mb-100">
    <!-- Single gallery Item -->
    <?php
    if (!empty($_GET['cat_id'])) {
        $catId = $_GET['cat_id'];
        $sql = "SELECT * FROM product WHERE cat_id = $catId ORDER BY prd_update DESC LIMIT 0, 6";
    } else {
        $sql = "SELECT * FROM product ORDER BY prd_id DESC LIMIT 0, 6";
    }
    $query = mysqli_query($conn, $sql);
    if ($query->num_rows > 0) {
        while ($row = mysqli_fetch_array($query)) {
            $prmQue = $conn->query("SELECT * FROM promotions");
            while ($prmRow = mysqli_fetch_array($prmQue)) {
                $arrPRd = explode(',',$prmRow['prm_apply']);
                if (isset($arrPRd) && in_array($row['prd_id'], $arrPRd)) {
                    $discount = $prmRow['prm_percent'];
                } else {
                    $discount = 0;
                }
            }
            $dataInfor = $row['prd_id'] . '**' . $row['prd_name'] . '**' . $row['prd_image'] . '**' . $row['prd_ratt'] . '**' . $row['prd_price'] . '**' . $discount . '**' . ($row['prd_quantity'] - $row['prd_sell']);
    ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="prd-item">
                    <div data-toggle="savetmp" class="prd-item-tmp">
                        <?= $row['prd_details'] ?>
                    </div>
                    <div class="prd-image">
                        <img src="admin/images/product/<?= $row['prd_image'] ?>" alt="<?= $row['prd_name'] ?>" />
                        <div class="prd-quickview">
                            <a data-toggle="modal" data-id="<?= $row['prd_id'] ?>" data-info="<?= $dataInfor ?>" data-target="#quickview"><span><i class="fa fa-plus"></i></span></a>
                        </div>
                    </div>
                    <div class="prd-description">
                        <h4 class="prd-name" data-toggle="limit" data-line="2"><?= $row['prd_name'] ?></h4>
                        <div class="flex-between items-center">
                            <p class="price-current">$<?= number_format($row['prd_price'], 2, ',', '.') ?></p>
                            <a href="?page_layout=addcart&prd_id=<?= $row['prd_id'] ?>#newArrivals" class="add-cart">ADD TO CART</a>
                        </div>
                    </div>
                </div>
            </div>
    <?php }
    } else {
        echo '<span class="alert-danger">No product to be founded !</span>';
    } ?>
</div>