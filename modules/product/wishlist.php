<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

if (!empty($_GET['prd_id'])) {
    $idPrd = $_GET['prd_id'];
    if (!empty($_SESSION['wishlist'])) {
        $arrWi = explode(',', $_SESSION['wishlist']);
        if (!in_array($idPrd, $arrWi)) {
            array_push($arrWi, $idPrd);
            $_SESSION['wishlist'] = implode(',', $arrWi);
        }
    } else {
        $_SESSION['wishlist'] = $idPrd;
    }
}

if (isset($_POST['addcart'])) {
    $prd_id = $_POST['prd_id'];
    if (!empty($_SESSION['add_success'])) {
        $arrSucc = explode(',', $_SESSION['add_success']);
        if (!in_array($prd_id, $arrSucc)) {
            $_SESSION['add_success'] = implode(',', $arrSucc) . ',' . $prd_id;
        }
    } else {
        $_SESSION['add_success'] = $prd_id;
    }
}
?>
<div class="cart-area mb-100">
    <div class="container">
        <div class="cart-table">
            <h2>Wishlist</h2>
            <p>My wishlist on Karl Fashion</p>
            <table id="wishlist" class="table table-responsive">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Add</th>
                        <th>remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($_SESSION['wishlist'])) {
                        if (!empty($_SESSION['add_success'])) {
                            $arrSucc = explode(',', $_SESSION['add_success']);
                        }
                        $arrList = explode(',', $_SESSION['wishlist']);
                        for ($i = 0; $i < count($arrList); $i++) {
                            if (trim($arrList[$i]) === '') {
                                $arrList = array_diff($arrList, [$arrList[$i]]);
                            }
                        }
                        $strList = implode(',', $arrList);
                        $quWi = $conn->query("SELECT * FROM product WHERE prd_id IN ($strList)");
                        while ($wishlist = mysqli_fetch_array($quWi)) {
                    ?>
                            <tr>
                                <form method="post">
                                    <input hidden type="text" name="prd_id" value="<?= $wishlist['prd_id'] ?>" />
                                    <td class="cart-prd-img">
                                        <a href="?page_layout=prd_details&prd_id=<?= $wishlist['prd_id'] ?>"><img src="admin/images/product/<?= $wishlist['prd_image'] ?>" alt="" /></a>
                                        <div>
                                            <span class="prd-name"><?= $wishlist['prd_name'] ?></span>
                                            <div class="cart-select-prd">
                                                <?php
                                                if (!empty($_SESSION['cartInfor'][$wishlist['prd_id']])) {
                                                    $saveTmp = explode(';', $_SESSION['cartInfor'][$wishlist['prd_id']]);
                                                    $colorInfor = explode(',', $saveTmp[1]);
                                                    $sizeInfor = explode(',', $saveTmp[0]);
                                                }
                                                $sizePr = explode(',', $wishlist['prd_size']);
                                                if (count($sizePr) > 0) {
                                                ?>
                                                    <ul class="flex-start">
                                                        <?php
                                                        for ($i = 0; $i < count($sizePr); $i++) {
                                                        ?>
                                                            <div class="custom-checkbox">
                                                                <div class="form-group">
                                                                    <input <?php if (!empty($sizeInfor)) {
                                                                                for ($j = 0; $j < count($sizeInfor); $j++) {
                                                                                    if (trim($sizeInfor[$j]) == trim($sizePr[$i])) {
                                                                                        echo 'checked';
                                                                                    }
                                                                                }
                                                                            } ?> type="checkbox" name="prd_size[]" id="prd_size_<?= $sizePr[$i] . $wishlist['prd_id'] ?>" value="<?= $sizePr[$i] ?>" />
                                                                    <label for="prd_size_<?= $sizePr[$i] . $wishlist['prd_id'] ?>"><span><?= $sizePr[$i] ?></span></label>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                                <?php
                                                $colorPrs = explode(',', $wishlist['prd_color']);
                                                if (count($sizePr) > 0) {
                                                ?>
                                                    <ul class="flex-start widget-color">
                                                        <?php
                                                        for ($i = 0; $i < count($colorPrs); $i++) {
                                                        ?>
                                                            <div class="custom-checkbox">
                                                                <div class="form-group">
                                                                    <input <?php if (!empty($colorInfor)) {
                                                                                for ($j = 0; $j < count($colorInfor); $j++) {
                                                                                    if (trim($colorInfor[$j]) == trim($colorPrs[$i])) {
                                                                                        echo 'checked';
                                                                                    }
                                                                                }
                                                                            } ?> type="checkbox" name="prd_color[]" id="prd_color_<?= $colorPrs[$i] . $wishlist['prd_id'] ?>" value="<?= $colorPrs[$i] ?>" />
                                                                    <label for="prd_color_<?= $colorPrs[$i] . $wishlist['prd_id'] ?>"><span style="background-color: <?= $colorPrs[$i]  ?>;"></span></label>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="prd-price">$<?= number_format($wishlist['prd_price'], 2, ',', '.') ?></td>
                                    <td class="table-qty">
                                        <div class="cart-qty">
                                            <div class="quantity">
                                                <span onclick="var effect = document.getElementById('qty<?= $wishlist['prd_id'] ?>'); var qty = effect.value; if( !isNaN( qty ) && qty > 1) effect.value--;return false;">
                                                    <i class="fa fa-minus"></i>
                                                </span>
                                                <input type="number" id="qty<?= $wishlist['prd_id'] ?>" step="1" min="1" max="<?= $wishlist['prd_quantity'] - $wishlist['prd_sell'] ?>" name="quantity" value="1" />
                                                <span onclick="var effect = document.getElementById('qty<?= $wishlist['prd_id'] ?>'); var qty = effect.value; if( !isNaN( qty )) effect.value++;return false;">
                                                    <i class="fa fa-plus"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="add-to-cart">
                                        <div>
                                            <button class="<?php if (isset($arrSucc) && in_array($wishlist['prd_id'], $arrSucc)) {
                                                                echo 'success';
                                                            } ?>" type="submit" name="addcart" id="alert-cart"><?php if (isset($arrSucc) && in_array($wishlist['prd_id'], $arrSucc)) {
                                                                                                                    echo 'Success';
                                                                                                                } else {
                                                                                                                    echo 'Add to cart';
                                                                                                                } ?></button>
                                            <?php if (isset($arrSucc) && in_array($wishlist['prd_id'], $arrSucc)) { ?>
                                                <span class="add-success"><i class="fa fa-check"></i></span>
                                            <?php } else { ?>
                                                <span class="add-start"><i class="fa fa-cart-plus"></i></span>
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td class="remove-cart">
                                        <div data-toggle="modal" data-target="#confimDelete" data-name="<?= $wishlist['prd_name']; ?>" data-href="index.php?page_layout=acPrd&actype=delete-wishlist&prd_id=<?= $wishlist['prd_id']; ?>&pagecurr=wishlist%wishlist">
                                            <span><i class="fa fa-times"></i></span>
                                        </div>
                                    </td>
                                </form>
                            </tr>
                    <?php }
                    } else {
                        echo '<tr><td colspan="5" style="text-align: justify;"><span class="alert-danger">No product on the wishlist !</span></td><tr>';
                    } ?>
                </tbody>
                <div id="confimDelete" class="modal flex-center items-center" role="dialog">
                    <div class="modal-dialog" role="document">
                        <p class="modal-header">Are you sure to delete "<span data-type="show-name"></span>" from Wishlish ?</p>
                        <a class="btn" data-dismiss="modal">Cancel</a>
                        <a class="btn text-danger" data-submit="modal">Sure</a>
                    </div>
                </div>
            </table>
            <div class="cart-table-footer"></div>
        </div>
    </div>
</div>