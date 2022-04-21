<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
if (!empty($_GET['prd_id'])) {
    $idPrd = $_GET['prd_id'];
    if (!empty($_SESSION['compare'])) {
        $arrWi = explode(',', $_SESSION['compare']);
        if (count($arrWi) >= 3) {
            array_shift($arrWi);
        }
        if (!in_array($idPrd, $arrWi)) {
            array_push($arrWi, $idPrd);
            $_SESSION['compare'] = implode(',', $arrWi);
        }
    } else {
        $_SESSION['compare'] = $idPrd;
    }
}

if (!empty($_SESSION['compare'])) {
    $arrCompare = explode(',', $_SESSION['compare']);
    $arrTmp = $detaTmp = [];
    for ($i = 0; $i < count($arrCompare); $i++) {
        if (trim($arrCompare[$i]) !== '') {
            $arrTmp[] = $arrCompare[$i];
        }
    }
    $strTmp = implode(',', $arrTmp);
    $quePr = $conn->query("SELECT * FROM product WHERE prd_id IN ($strTmp)");
    while ($rowPr = mysqli_fetch_array($quePr)) {
        $textDeta = explode('1. Information:', explode('2. Detail', gettext($rowPr['prd_details']))[0])[1];
        $detaTmp[] = $rowPr['prd_id'] . '%' . $rowPr['prd_name'] . '%' . $rowPr['prd_image'] . '%' . $rowPr['prd_price'] . '%' . ($rowPr['prd_quantity'] - $rowPr['prd_sell']) . '%' . $rowPr['prd_color'] . '%' . $rowPr['prd_ratt'] . '%' . $textDeta;
    }
}
if (isset($_POST['addcart'])) {
    $prd_id = $_POST['prd_id'];
    if (!empty($_SESSION['add_compare_success'])) {
        $arrSucc = explode(',', $_SESSION['add_compare_success']);
        if (!in_array($prd_id, $arrSucc)) {
            $_SESSION['add_compare_success'] = implode(',', $arrSucc) . ',' . $prd_id;
        }
    } else {
        $_SESSION['add_compare_success'] = $prd_id;
    }
}
if (!empty($_SESSION['add_compare_success'])) {
    $arrSucc = explode(',', $_SESSION['add_compare_success']);
}
?>
<div class="cart-area compare-area mb-100">
    <div class="container">
        <div class="cart-table">
            <h2>Compare Products</h2>
            <table class="table">
                <tbody>
                    <?php
                    if (!empty($detaTmp)) {
                    ?>
                        <tr>
                            <td>Name</td>
                            <?php for ($i = 0; $i < count($detaTmp); $i++) { ?>
                                <td><?= explode('%', $detaTmp[$i])[1] ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Image</td>
                            <?php for ($i = 0; $i < count($detaTmp); $i++) { ?>
                                <td>
                                    <a href="?page_layout=prd_details&prd_id=<?= explode('%', $detaTmp[$i])[0] ?>"><img src="admin/images/product/<?= explode('%', $detaTmp[$i])[2] ?>" alt="" /></a>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr class="price-compare">
                            <td>Price</td>
                            <?php
                            for ($i = 0; $i < count($detaTmp); $i++) {
                            ?>
                                <td>
                                    <div class="price-new">$
                                        <?php
                                        $sqlPromotion = $conn->query("SELECT * FROM promotions WHERE prm_type = 0");
                                        while ($promotions = mysqli_fetch_array($sqlPromotion)) {
                                            if (in_array(explode('%', $detaTmp[$i])[0], explode(',', $promotions['prm_apply']))) {
                                                $price = number_format(explode('%', $detaTmp[$i])[3] * (100 - $promotions['prm_percent']) / 100, 2, ',', '.');
                                            } else {
                                                $price = number_format(explode('%', $detaTmp[$i])[3], 2, ',', '.');
                                            }
                                        }
                                        echo $price;
                                        ?>
                                    </div>
                                    <div class="price-old">$<?= number_format(explode('%', $detaTmp[$i])[3], 2, ',', '.') ?></div>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr class="compare-color">
                            <td>Color</td>
                            <?php for ($i = 0; $i < count($detaTmp); $i++) { ?>
                                <td>
                                    <?php
                                    $colorArr = explode(',', explode('%', $detaTmp[$i])[5]);
                                    for ($j = 0; $j < count($colorArr); $j++) {
                                        if (trim($colorArr[$j]) !== '') {
                                    ?>
                                            <span style="background-color: <?= $colorArr[$j] ?>;"></span>
                                    <?php }
                                    }  ?>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Stock</td>
                            <?php for ($i = 0; $i < count($detaTmp); $i++) { ?>
                                <td>
                                    <?php
                                    if (explode('%', $detaTmp[$i])[4] > 0) {
                                        echo 'In Stock';
                                    } else {
                                        echo 'Out of Stock';
                                    }
                                    ?>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Rating</td>
                            <?php for ($i = 0; $i < count($detaTmp); $i++) { ?>
                                <td>
                                    <div class="prd-rating compare-star">
                                        <span class="stars">★★★★★</span>
                                    </div>
                                    <style>
                                        .compare-star .stars::after {
                                            width: <?php echo (explode('%', $detaTmp[$i])[6] / 5 * 100) . '%'; ?>
                                        }
                                    </style>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr class="desc-compare">
                            <td>Description</td>
                            <?php for ($i = 0; $i < count($detaTmp); $i++) { ?>
                                <td>
                                    <div data-toggle="limit" data-line="3" class="hidden">
                                        <?= explode('%', $detaTmp[$i])[7] ?>
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr id="compare-product">
                            <td>Add Cart</td>
                            <?php for ($i = 0; $i < count($detaTmp); $i++) { ?>
                                <td class="add-to-cart">
                                    <form method="post">
                                        <input hidden type="text" name="prd_id" value="<?= explode('%', $detaTmp[$i])[0] ?>" />
                                        <div class="flex-center compare-action">
                                            <div>
                                                <button class="<?php if (isset($arrSucc) && in_array(explode('%', $detaTmp[$i])[0], $arrSucc)) {
                                                                    echo 'success';
                                                                } ?>" name="addcart" type="submit" id="alert-cart"><?php if (isset($arrSucc) && in_array(explode('%', $detaTmp[$i])[0], $arrSucc)) {
                                                                                                                        echo 'Success';
                                                                                                                    } else {
                                                                                                                        echo 'Add to cart';
                                                                                                                    } ?></button>
                                                <?php if (isset($arrSucc) && in_array(explode('%', $detaTmp[$i])[0], $arrSucc)) { ?>
                                                    <span class="add-success"><i class="fa fa-check"></i></span>
                                                <?php } else { ?>
                                                    <span class="add-start"><i class="fa fa-cart-plus"></i></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Remove</td>
                            <?php for ($i = 0; $i < count($detaTmp); $i++) { ?>
                                <td><a data-toggle="modal" data-target="#confimDelete" data-name="<?= explode('%', $detaTmp[$i])[1] ?>" data-href="?page_layout=acPrd&prd_id=<?= explode('%', $detaTmp[$i])[0] ?>&actype=delete-compare&pagecurr=compare%compare-product"><i class="fa fa-times"></i></a></td>
                            <?php } ?>
                        </tr>
                    <?php } else {
                        echo '<div class="alert-danger mb-3">No product to be compare !</div>';
                    } ?>
                    <div id="confimDelete" class="modal flex-center items-center" role="dialog">
                        <div class="modal-dialog" role="document">
                            <p class="modal-header">Are you sure to delete "<span data-type="show-name"></span>" from Compare ?</p>
                            <a class="btn" data-dismiss="modal">Cancel</a>
                            <a class="btn text-danger" data-submit="modal">Sure</a>
                        </div>
                    </div>
                </tbody>
            </table>
            <div class="cart-table-footer"></div>
        </div>
    </div>
</div>