<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
} 
?>
<div class="cart-box items-center" data-toggle="collapse" data-target="#cart-box" data-window='true' class="active">
    <div>
        <span class="cart-qty">
            <?php
            if (isset($_SESSION['cart'])) {
                $total = 0;
                if (isset($_POST['qty'])) {
                    $cart = $_POST['qty'];
                } else {
                    $cart = $_SESSION['cart'];
                }
                foreach ($cart as $prd_id => $qty) {
                    $total += $qty;
                }
                echo $total;
            } else {
                echo 0;
            }
            ?>
        </span><span class="cart-icon"><i class="fa fa-shopping-bag"></i></span> Yours Bag $20
    </div>
    <!-- Cart List Area -->
    <ul id="cart-box" class="cart-list collapse">
        <?php
        $totalCart = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($cart as $prd_id => $qty) {
                if ($prd_id != '') {
                $prdCar = mysqli_fetch_array($conn->query("SELECT * FROM product WHERE prd_id = $prd_id"));
                $totalCart += $prdCar['prd_price'] * $qty;
        ?>
                <li class="cart-item">
                    <a href="#" class="item-image">
                        <img src="admin/images/product/<?= $prdCar['prd_image'] ?>" alt="<?= $prdCar['prd_name'] ?>" />
                    </a>
                    <div class="item-content">
                        <a href="#"><?= $prdCar['prd_name'] ?></a>
                        <p><?= $qty ?>x - <span>$<?= number_format($prdCar['prd_price']) ?></span></p>
                    </div>
                </li>
        <?php } }
        } else {
            echo '<li class="cart-item"><span class="alert-danger">No product on the cart!</span></li>';
        } ?>
        <li class="flex-between items-center">
            <div class="cart-button flex-start">
                <a href="?page_layout=cart" class="btn">Cart</a>
                <a href="?page_layout=checkout" class="btn">Checkout</a>
            </div>
            <span class="total">Tol: $<?= number_format($totalCart) ?></span>
        </li>
    </ul>
</div>