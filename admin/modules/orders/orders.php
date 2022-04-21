<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$row_per_page = 9;
$per_row = $page * $row_per_page - $row_per_page;
$toltal_row = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders"));
$total_page = ceil($toltal_row / $row_per_page);

$list_page = '';

$prev_page = $page - 1;
if ($prev_page <= 1) {
    $prev_page = 1;
}

if ($page > 1) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=orders&page=' . $prev_page . '"><i class="fa fa-angle-double-left"></i></a></li>';
}

for ($i = 1; $i <= $total_page; $i++) {
    if ($i == $page) {
        $active = 'active';
    } else {
        $active = '';
    }
    $list_page .= '<li class="page-link ' . $active . '"><a href="index.php?page_layout=orders&page=' . $i . '">' . $i . '</a></li>';
}

$next_page = $page + 1;
if ($next_page >= $total_page) {
    $next_page = $total_page;
}

if ($page < $total_page) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=orders&page=' . $next_page . '"><i class="fa fa-angle-double-right"></i></a></li>';
}

if (isset($_POST['id'])) {
    $arrID = $_POST['id'];
    $strID = implode(', ', $arrID);
    if (isset($_POST['cancel'])) {
        mysqli_query($conn, "UPDATE orders SET order_status = 3 WHERE order_id IN ($strID)");
    }
    if (isset($_POST['shipping'])) {
        mysqli_query($conn, "UPDATE orders SET order_status = 1 WHERE order_id IN ($strID)");
    }
    if (isset($_POST['delivered'])) {
        mysqli_query($conn, "UPDATE orders SET order_status = 2 WHERE order_id IN ($strID)");
        for ($j = 0; $j < count($arrID); $j++) {
            $idSal = $arrID[$j];
            $orderSal = mysqli_fetch_array($conn->query("SELECT * FROM orders WHERE order_id = $idSal"));
            $monthSal = date('M', strtotime($orderSal['order_date']));
            $yearSal = date('Y', strtotime($orderSal['order_date']));
            $queSal = $conn->query("SELECT * FROM salesmonths WHERE sal_month = '$monthSal' AND sal_year = '$yearSal'");
            if ($queSal->num_rows > 0) {
                $saleSal = mysqli_fetch_array($queSal);
                if ($saleSal['sal_orders'] === '') {
                    $arrOrderSal = [];
                } else {
                    $arrOrderSal = explode(',', $saleSal['sal_orders']);
                }
                $salCatOld = $saleSal['sal_categories'];
                $salEyeOld = $saleSal['sal_eye'];
                $salFootOld = $saleSal['sal_shoes'];
                $salCloOld = $saleSal['sal_clothes'];
                $salTolOld = $saleSal['sal_total'];
            } else {
                $salCatOld = $salEyeOld = $salFootOld = $salCloOld = $salTolOld = 0;
                $arrOrderSal = [];
                $conn->query("INSERT INTO salesmonths (sal_year, sal_month) VALUES ('$yearSal', '$monthSal')");
            }
            $orderProdQ = $conn->query("SELECT * FROM bills WHERE order_id = $idSal");
            while ($orderPro = mysqli_fetch_array($orderProdQ)) {
                $idSalPro = $orderPro['prd_id'];
                $proBill = mysqli_fetch_array($conn->query("SELECT * FROM product INNER JOIN categories ON categories.cat_id = product.cat_id WHERE prd_id = $idSalPro"));
                $catProBillName = $proBill['cat_name'];
                if (strpos(mb_strtolower($catProBillName), 'catego') !== false) {
                    $catSaltmp = $orderPro['bill_total'];
                    $eyeSaltmp = $footSaltmp = $cloSaltmp = 0;
                } elseif (strpos(mb_strtolower($catProBillName), 'foot') !== false) {
                    $footSaltmp = $orderPro['bill_total'];
                    $eyeSaltmp = $catSaltmp = $cloSaltmp = 0;
                } elseif (strpos(mb_strtolower($catProBillName), 'eye') !== false) {
                    $eyeSaltmp = $orderPro['bill_total'];
                    $catSaltmp = $footSaltmp = $cloSaltmp = 0;
                } else {
                    $cloSaltmp = $orderPro['bill_total'];
                    $eyeSaltmp = $footSaltmp = $catSaltmp = 0;
                }
                $tolSaltmp = $orderPro['bill_total'];
            }
            if (in_array($idSal, $arrOrderSal)) {
                $catSal = $salCatOld - $catSaltmp;
                $eyeSal = $salEyeOld - $eyeSaltmp;
                $footSal = $salFootOld - $footSaltmp;
                $cloSal = $salCloOld - $cloSaltmp;
                $tolSal = $salTolOld - $tolSaltmp;
                $orderSal = implode(',', array_diff($arrOrderSal, [$idSal]));
            } else {
                $catSal = $salCatOld + $catSaltmp;
                $eyeSal = $salEyeOld + $eyeSaltmp;
                $footSal = $salFootOld + $footSaltmp;
                $cloSal = $salCloOld + $cloSaltmp;
                $tolSal = $salTolOld + $tolSaltmp;
                array_push($arrOrderSal, $idSal);
                $orderSal = implode(',', $arrOrderSal);
            }
            $conn->query("UPDATE salesmonths SET sal_categories = $catSal, sal_eye = $eyeSal, sal_shoes = $footSal, sal_clothes= $cloSal, sal_total = $tolSal, sal_orders = '$orderSal' WHERE sal_month = '$monthSal' AND sal_year = '$yearSal'");
        }
    }
    if (isset($_POST['delete'])) {
        mysqli_query($conn, "DELETE FROM orders WHERE order_id IN ($strID)");
    }
}

?>
<section class="breadcumb-area">
    <h3>Administration Orders</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item">Orders</li>
    </ul>
</section>
<section class="new-orders">
    <form method="post">
        <table class="table">
            <?php
            $check = mysqli_query($conn, "SELECT * FROM orders");
            ?>
            <div class="flex-between">
                <div class="tab-notify teal">Have <?= $check->num_rows ?> orders need to processed </div>
                <div class="tab-group flex-end">
                    <button type="submit" class="btn red" name="delete"><i class="fa fa-trash"></i> Delete All</button>
                    <button type="submit" class="btn orange" name="cancel"><i class="fa fa-ban"></i> Cancel</button>
                    <button type="submit" class="btn blue" name="shipping"><i class="fa fa-shipping-fast"></i> Shipping</button>
                    <button type="submit" class="btn green" name="delivered"><i class="fa fa-truck-loading"></i> Delivered</button>
                </div>
            </div>
            <thead class="teal">
                <tr>
                    <th><input type="checkbox" name="check" data-toggle="checkall" data-target=".item" /></th>
                    <th class="tab-id">Order Date</th>
                    <th class="tab-name">Buyer</th>
                    <th class="tab-center">Phone</th>
                    <th class="tab-center">Email</th>
                    <th class="tab-center">Address</th>
                    <th class="tab-center">Status</th>
                    <th class="tab-action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM orders INNER JOIN customers ON orders.cus_id = customers.cus_id ORDER BY order_id DESC LIMIT " . $per_row . ',' . $row_per_page;
                $query = mysqli_query($conn, $sql);
                $num = mysqli_num_rows($query);
                if ($num <= 0) {
                    echo "<tr><td colspan='8'><span class='alert-danger'>There are no order on the database ! </td></tr>";
                } else {
                    while ($row = mysqli_fetch_array($query)) {
                ?>
                        <tr>
                            <td><input type="checkbox" name="id[]" class="item" value="<?= $row['order_id']; ?>" /></td>
                            <td class="tab-id"><?= $row['order_date']; ?></td>
                            <td class="tab-name"><?= $row['cus_name']; ?></td>
                            <td class="tab-center"><?= $row['order_phone']; ?></td>
                            <td class="tab-center"><?= $row['cus_mail']; ?></td>
                            <td class="tab-center"><?= $row['order_add']; ?></td>
                            <td class="tab-center">
                                <?php
                                switch ($row['order_status']) {
                                    case '0':
                                        echo '<span class="text-danger">Processing</span>';
                                        break;
                                    case '1':
                                        echo '<span class="text-success">Shipping</span>';
                                        break;
                                    case '2':
                                        echo '<span class="text-default">Delivered</span>';
                                        break;
                                    case '3':
                                        echo '<span class="text-warning">Cenceled</span>';
                                        break;
                                }
                                ?>
                            </td>
                            <td class="tab-action">
                                <div class="flex-center">
                                    <a class="btn cyan" href="index.php?page_layout=bills&id=<?= $row['order_id']; ?>">Details</a>
                                </div>
                            </td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>
    </form>
    <?php
    if ($toltal_row > $row_per_page) {
    ?>
        <ul class="pagination flex-end">
            <?php echo $list_page; ?>
        </ul>
    <?php } ?>
</section>