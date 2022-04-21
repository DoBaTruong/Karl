<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
$id = $_GET['id'];
$infoCus = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM orders INNER JOIN customers ON orders.cus_id = customers.cus_id WHERE order_id = $id"));
$date = date('Y/m/d H:i:i');
?>
<div class="order-print">
    <div class="shop-info">
        <div class="flex-start items-center">
            <img src="images/logo/logo.png" alt="" />
            <h2 class="store-name">Shop</h2>
        </div>
        <table class="table">
            <tbody>
                <tr class="store-address">
                    <td>Address</td>
                    <td>: Dan Tao - Tan Minh - Soc Son - Ha Noi</td>
                </tr>
                <tr class="store-tel">
                    <td>Tel</td>
                    <td>: 034 256 1450</td>
                </tr>
                <tr class="store-mobile">
                    <td>Mobile</td>
                    <td>: 0988 041 615</td>
                </tr>
                <tr class="store-address">
                    <td>Email</td>
                    <td>: dobatruongbk48@gmail.com</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="invoice-delivery">
        <h1 class="text-center">Invoice Delivery</h1>
        <h4>Customer Information</h4>
        <table class="table cus-info">
            <tbody>
                <tr class="cus-name">
                    <td>Buyer</td>
                    <td>: <?= $infoCus['cus_name']; ?></td>
                </tr>
                <tr class="cus-address">
                    <td>Address</td>
                    <td>: <?= $infoCus['order_add']; ?></td>
                </tr>
                <tr class="cus-mobile">
                    <td>Mobile</td>
                    <td>: <?= $infoCus['order_phone']; ?></td>
                </tr>
                <tr class="cus-mail">
                    <td>Email</td>
                    <td>: <?= $infoCus['cus_mail']; ?></td>
                </tr>
                <tr class="cus-date">
                    <td>Date Of Delivery</td>
                    <td>: <?= $date; ?></td>
                </tr>
                <tr class="cus-method">
                    <td>Payment Method</td>
                    <td>:
                        <?php
                        switch ($infoCus['order_pay']) {
                            case '0':
                                echo 'Paypal';
                                break;
                            case '1':
                                echo 'Cash On Delivery';
                            case '2':
                                echo 'Credit Card';
                                break;
                            case '3':
                                echo 'Direct Banh Transfer';
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <h4>Order Information</h4>
        <table class="table order-infor">
            <thead>
                <tr>
                    <th class="tab-center">Number</th>
                    <th class="tab-name">Product</th>
                    <th class="tab-center">Quantity</th>
                    <th class="tab-center">Unit Price</th>
                    <th class="tab-center">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM bills INNER JOIN product ON bills.prd_id = product.prd_id WHERE order_id = $id";
                $query = mysqli_query($conn, $sql);
                $incree = 1;
                $total = 0;
                while ($row = mysqli_fetch_array($query)) {
                    $incree++;
                    $total += $row['bill_total'];

                ?>
                    <tr>
                        <td class="tab-center"><?= $incree ?></td>
                        <td class="tab-name"><?= $row['prd_name']; ?></td>
                        <td class="tab-center"><?= $row['bill_qty']; ?></td>
                        <td class="tab-center"><?= number_format($row['bill_price']); ?></td>
                        <td class="tab-center"><?= number_format($row['bill_total']); ?></td>
                    </tr>
                <?php } ?>
                <tr class="total-bill">
                    <td colspan="6" class="tab-right">
                        Total Amount: <span class="text-danger"><?= number_format($total); ?>$</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="signature">
        <div class="text-right">29/05/2021</div>
        <div class="flex-end">
            <div class="sign-buyer">
                <div class="text-center text-danger">Buyer</div>
                <span><i>(Signature, Full name)</i></span>
            </div>
            <div class="sign-seller">
                <div class="text-center text-danger">Seller</div>
                <span><i>(Signature, Full name)</i></span>
            </div>
        </div>
    </div>
</div>
<script>
    window.print()
</script>