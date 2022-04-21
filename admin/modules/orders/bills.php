<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

$id = $_GET['id'];
$order = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM orders INNER JOIN customers ON orders.cus_id = customers.cus_id WHERE order_id = $id"));
?>
<section class="breadcumb-area">
    <h3>Administration Orders</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item"><a href="index.php?page_layout=orders">Orders</a></li>
        <li class="breadcumb-item"><?= ucwords(substr(md5($id), 0, 15)); ?></li>
    </ul>
</section>
<section class="new-orders">
    <table class="table">
        <div class="notify-group">
            <div class="tab-notify"><span>Buyer</span>: <?= $order['cus_name']; ?></div>
            <div class="tab-notify"><span>Mobile</span>: <?= $order['order_phone']; ?></div>
            <div class="tab-notify"><span>Email</span>: <?= $order['cus_mail']; ?></div>
            <div class="tab-notify"><span>Address</span>: <?= $order['cus_add']; ?></div>
        </div>
        <thead class="teal">
            <tr>
                <th class="tab-id">Order Date</th>
                <th class="tab-name">Product</th>
                <th class="tab-center">Quantity</th>
                <th class="tab-center">Unit Price</th>
                <th class="tab-center">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM bills INNER JOIN product ON bills.prd_id = product.prd_id WHERE order_id = $id ORDER BY bill_id DESC";
            $query = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($query);
            while ($row = mysqli_fetch_array($query)) {
            ?>
                <tr>
                    <td class="tab-name"><?= $order['order_date']; ?></td>
                    <td class="tab-name"><?= $row['prd_name']; ?></td>
                    <td class="tab-center"><?= $row['bill_qty']; ?></td>
                    <td class="tab-center"><?= number_format($row['bill_price'], 2, ',','.'); ?>$</td>
                    <td class="tab-center text-danger"><?= number_format($row['bill_qty'] * $row['bill_price'], 2, ',', '.'); ?>$</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="tab-group flex-end">
        <a href="index.php?page_layout=print&id=<?= $id; ?>" class="btn blue"><span><i class="fa fa-print"></i></span> Print Bill</a>
    </div>
</section>