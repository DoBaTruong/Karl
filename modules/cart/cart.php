<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
if (isset($_POST['updatecart'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        if ($qty == 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
    $arrSize = $arrColor = [];
    foreach ($_POST['prd_size'] as $id => $value) {
        $arrSize[$id] = implode(',', $value);
    }
    foreach ($_POST['prd_color'] as $id => $value) {
        $arrColor[$id] = implode(',', $value);
    }
    foreach ($arrSize as $id => $val) {
        if (!empty($_SESSION['cartInfor'][$id])) {
            $_SESSION['cartInfor'][$id] =  $arrSize[$id] . ';' . $arrColor[$id];
        } else {
            $_SESSION['cartInfor'][$id] =  $arrSize[$id] . ';' . $arrColor[$id];
        }
    }
}
?>
<div class="cart-area mb-100">
    <div class="container">
        <form method="post">
            <div class="cart-table">
                <a href="index.php?page_layout=shop" class="backToHome"><i class="fa fa-angle-double-left"></i> Continue shopping</a>
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($_SESSION['cart'])) {
                            $totalPrice = 0;
                            foreach ($_SESSION['cart'] as $prdid => $qtt) {
                                if ($prdid != '') {
                                    $prd = mysqli_fetch_array($conn->query("SELECT * FROM product WHERE prd_id = $prdid"));
                                    $totalPrice += $prd['prd_price'] * $qtt;
                        ?>
                                    <tr>
                                        <td class="cart-prd-img">
                                            <a href="#"><img src="admin/images/product/<?= $prd['prd_image'] ?>" alt="<?= $prd['prd_name'] ?>" /></a>
                                            <div>
                                                <span class="prd-name"><?= $prd['prd_name'] ?></span>
                                                <div class="cart-select-prd">
                                                    <?php
                                                    if (!empty($_SESSION['cartInfor'][$prdid])) {
                                                        $saveTmp = explode(';', $_SESSION['cartInfor'][$prdid]);
                                                        $colorInfor = explode(',', $saveTmp[1]);
                                                        $sizeInfor = explode(',', $saveTmp[0]);
                                                    }
                                                    $sizePr = explode(',', $prd['prd_size']);
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
                                                                                } ?> type="checkbox" name="prd_size[<?= $prd['prd_id'] ?>][]" id="prd_size_<?= $sizePr[$i] . $prdid ?>" value="<?= $sizePr[$i] ?>" />
                                                                        <label for="prd_size_<?= $sizePr[$i] . $prdid ?>"><span><?= $sizePr[$i] ?></span></label>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </ul>
                                                    <?php } ?>
                                                    <?php
                                                    $colorPrs = explode(',', $prd['prd_color']);
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
                                                                                } ?> type="checkbox" name="prd_color[<?= $prd['prd_id'] ?>][]" id="prd_color_<?= $colorPrs[$i] . $prdid ?>" value="<?= $colorPrs[$i] ?>" />
                                                                        <label for="prd_color_<?= $colorPrs[$i] . $prdid ?>"><span style="background-color: <?= $colorPrs[$i]  ?>;"></span></label>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </ul>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="prd-price">$<?= number_format($prd['prd_price'], 0, ',', '.') ?></td>
                                        <td class="table-qty">
                                            <div class="cart-qty">
                                                <div class="quantity flex-between">
                                                    <span onclick="var effect = document.getElementById('qty<?= $prdid ?>'); var qty = effect.value; if( !isNaN( qty ) && qty > 1) effect.value--;return false;">
                                                        <i class="fa fa-minus"></i>
                                                    </span>
                                                    <input type="number" id="qty<?= $prdid ?>" step="1" min="0" max="<?= $prd['prd_quantity']  - $prd['prd_sell'] ?>" name="qty[<?= $prdid ?>]" value="<?= $qtt ?>" />
                                                    <span onclick="var effect = document.getElementById('qty<?= $prdid ?>'); var qty = effect.value; if( !isNaN( qty )) effect.value++;return false;">
                                                        <i class="fa fa-plus"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="total-price">$<?= number_format($prd['prd_price'] * $qtt, 2, ',', '.') ?></td>
                                        <td><i class="ti-close"></i></td>
                                    </tr>
                        <?php }
                            }
                        } else {
                            echo '<tr><td colspan=4><span class="alert-danger">No product on the cart !</span></td></tr>';
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="cart-footer flex-end">
                <div class="update-checkout flex-between">
                    <button type="reset">clear cart</button>
                    <button name="updatecart" type="submit">Update cart</button>
                </div>
            </div>
        </form>
        <?php
        if (isset($_POST['sbmcoupon'])) {
            $coup = $_POST['coupon'];

            $queCoup = $conn->query("SELECT * FROM promotions WHERE prm_code = '$coup'");
            if ($queCoup->num_rows > 0) {
                $coupRow = mysqli_fetch_array($queCoup);
                switch ($coupRow['prm_type']) {
                    case '1':
                        $priceSub = 'feeship';
                        break;
                    case '0':
                        $discount = $coupRow['prm_percent'];
                        $arrPro = explode(',', $coupRow['prm_apply']);
                        $priceSub = 0;
                        if (mb_strtolower($coupRow['prm_apply']) === 'all') {
                            foreach ($_SESSION['cart'] as $prd_id => $qty) {
                                $proPric = mysqli_fetch_array($conn->query("SELECT * FROM product WHERE prd_id = $prd_id"));
                                $priceSub += $proPric['prd_price'] * $qty * $discount / 100;
                            }
                        } else {
                            $arrPRO = explode(',', $coupRow['prm_apply']);
                            foreach ($_SESSION['cart'] as $prd_id => $qty) {
                                if (in_array($prd_id, $arrPro)) {
                                    $proPric = mysqli_fetch_array($conn->query("SELECT * FROM product WHERE prd_id = $prd_id"));
                                    $priceSub += $proPric['prd_price'] * $qty * $discount / 100;
                                }
                            }
                        }
                }
                $_SESSION['cupon'] = $coupRow['prm_id'] . '-' . $priceSub;
            } else {
                $error = '<div class="alert-danger mb-3">Coupon invalid !</div>';
            }
        }
        ?>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="coupon-code-area">
                    <div class="cart-page-heading">
                        <h5>Cuponcode</h5>
                        <p>Enter your coupon code</p>
                    </div>
                    <form method="POST">
                        <input type="search" name="coupon" placeholder="#569ab15" value="<?php if (!empty($_SESSION['cupon'])) {
                                                                                                $coupID = explode('-', $_SESSION['cupon'])[0];
                                                                                                echo  mysqli_fetch_array($conn->query("SELECT * FROM promotions WHERE prm_id = $coupID"))['prm_code'];
                                                                                            } ?>" />
                        <button type="submit" name="sbmcoupon">Apply</button>
                    </form>
                    <?php if (!empty($error)) {
                        echo $error;
                    } ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="shipping-method-area">
                    <div class="cart-page-heading">
                        <h5>Shipping method</h5>
                        <p>Select the one you want</p>
                    </div>
                    <div class="custom-radio" id="shipMethod">
                        <div class="form-group">
                            <input type="radio" id="customRadio1" name="shipMethod" value="2">
                            <label class="flex-between" for="customRadio1"><span>Next day delivery</span><span class="fee-ship">$4,99</span></label>
                        </div>
                        <div class="form-group">
                            <input type="radio" id="customRadio2" name="shipMethod" value="1">
                            <label for="customRadio2" class="flex-between"><span>Standard delivery</span><span class="fee-ship">$1,99</span></label>
                        </div>
                        <div class="form-group">
                            <input type="radio" id="customRadio3" name="shipMethod" value="0">
                            <label class="flex-between" for="customRadio3"><span>Personal Pickup</span><span class="fee-ship">Free</span></label>
                        </div>
                        <script>
                            var inpShips = Array.from(document.querySelectorAll('#shipMethod input[name=shipMethod]'));
                            if (inpShips) {
                                inpShips.forEach(function(ship) {
                                    var feeText = ship.parentElement.querySelector('.fee-ship').innerText;
                                    ship.onchange = function(e) {
                                        document.getElementById('fee-ship').innerText = feeText;
                                        sessionStorage.setItem('methodShip', [ship.value, feeText].join('-'));
                                        calcTotalBill();
                                    }
                                    if (sessionStorage['methodShip'] && ship.value === sessionStorage['methodShip'].split('-')[0]) {
                                        window.onload = function() {
                                            ship.checked = true;
                                            document.getElementById('fee-ship').innerText = sessionStorage['methodShip'].split('-')[1];
                                            calcTotalBill();
                                        }
                                    }
                                    window.onload = function() {
                                        SubPrice()
                                    }
                                })

                                function calcTotalBill() {
                                    let amountNoshipTmp = parseFloat(document.getElementById('sub-total-cart').innerText.split('.').join('').split(',').join('.').split('$').pop()).toFixed(2);
                                    amountNoship = parseFloat(amountNoshipTmp);
                                    let feeshipText = document.getElementById('fee-ship').innerText.trim().toLowerCase();
                                    if (feeshipText !== 'free') {
                                        feeshipTmp = parseFloat(feeshipText.split('.').join('').split(',').join('.').split('$').pop()).toFixed(2);
                                        feeship = parseFloat(feeshipTmp);
                                    } else {
                                        feeship = 0;
                                    }
                                    totalFee = parseFloat(amountNoship + feeship).toFixed(2).split('.');
                                    document.getElementById('total-order').innerText = '$' + format(totalFee);
                                    SubPrice()
                                }
                            }

                            function SubPrice() {
                                var dataCoupEl = document.getElementById('sub-total');
                                if (dataCoupEl) {
                                    var dataCoup = dataCoupEl.getAttribute('data-coup');
                                    if (dataCoup) {
                                        switch (dataCoup) {
                                            case 'feeship':
                                                let feeshipText = document.getElementById('fee-ship').innerText.trim().toLowerCase();
                                                if (feeshipText !== 'free') {
                                                    feeshipTmp = parseFloat(feeshipText.split('.').join('').split(',').join('.').split('$').pop()).toFixed(2);
                                                    feeship = parseFloat(feeshipTmp);
                                                } else {
                                                    feeship = 0;
                                                }
                                                dataCoupEl.innerText = format(feeship);
                                                break;
                                            default:
                                                dataCoupEl.innerText = format(dataCoup);
                                        }
                                    }
                                }
                            }
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="cart-total-area">
                    <div class="cart-page-heading">
                        <h5>Cart total</h5>
                        <p>Final info</p>
                    </div>
                    <ul class="cart-total-chart">
                        <li><span>Subtotal</span> <span id="sub-total-cart">$<?php if (!empty($_SESSION['cart'])) {
                                                                                    echo number_format($totalPrice, 2, ',', '.');
                                                                                } else {
                                                                                    echo number_format(0, 2, ',', '.');
                                                                                } ?></span></li>
                        <li><span>Shipping</span> <span id="fee-ship">Free</span></li>
                        <li><span><strong>Total</strong></span> <span><strong id="total-order">$<?php if (!empty($_SESSION['cart'])) {
                                                                                                    echo number_format($totalPrice, 2, ',', '.');
                                                                                                } else {
                                                                                                    echo '0';
                                                                                                } ?></strong></span></li>
                        <li><span></span>
                            <div>(- $<span <?php if (!empty($priceSub)) {
                                                echo 'data-coup="' . implode(',', explode('.', $priceSub)) . '"';
                                            } ?> id="sub-total">0</span>)</div>
                        </li>
                    </ul>
                    <a href="?page_layout=checkout" class="btn">Proceed to checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>