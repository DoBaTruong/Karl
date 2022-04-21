<section class="breadcumb-area">
    <h3>Dashboard</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item">Dashboard</li>
    </ul>
</section>
<section class="increase-notify-area">
    <?php
    $current = date('m');
    function GetMonth($date)
    {
        $yeMoDa = explode(' ', $date)[0];
        $month = explode('-', $yeMoDa)[1];
        return $month;
    }
    ?>
    <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="increase-item">
                <div class="flex-center">
                    <div class="item-icon cyan"><i class="fa fa-user"></i></div>
                </div>
                <div class="item-number">+ <?php $count = 0;
                                            $sqlUser = "SELECT * FROM customers";
                                            $queryUser = mysqli_query($conn, $sqlUser);
                                            while ($user = mysqli_fetch_array($queryUser)) {
                                                if ($current == GetMonth($user['cus_date'])) {
                                                    ++$count;
                                                }
                                            };
                                            $userIncre = $count;
                                            echo $userIncre; ?></div>
                <div class="item-name">New Customers</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="increase-item">
                <div class="flex-center">
                    <div class="item-icon purple"><i class="fa fa-eye"></i></div>
                </div>
                <div class="item-number">+ <?php $count = 0;
                                            $sqlVis = "SELECT * FROM visitors";
                                            $queryVis = mysqli_query($conn, $sqlVis);
                                            while ($visit = mysqli_fetch_array($queryVis)) {
                                                if ($current == GetMonth($visit['vis_date'])) {
                                                    ++$count;
                                                }
                                            };
                                            $visIncre = $count;
                                            echo $visIncre; ?></div>
                <div class="item-name">Unique Visitors</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="increase-item">
                <div class="flex-center">
                    <div class="item-icon red"><i class="fa fa-tags"></i></div>
                </div>
                <div class="item-number">+ <?php $count = 0;
                                            $totalSales = 0;
                                            $sqlOrder = "SELECT * FROM orders";
                                            $queryOrder = mysqli_query($conn, $sqlOrder);
                                            while ($Order = mysqli_fetch_array($queryOrder)) {
                                                $sqlBill = "SELECT * FROM bills WHERE order_id = '" . $Order['order_id'] . "'";
                                                $queryBill = mysqli_query($conn, $sqlBill);
                                                while ($bill = mysqli_fetch_array($queryBill)) {
                                                    $totalSales += $bill['bill_total'];
                                                    if ($current == GetMonth($Order['order_date'])) {
                                                        $count += $bill['bill_total'];
                                                    }
                                                }
                                            };
                                            $saleIncre = $count;
                                            echo number_format($saleIncre, 2, ',', '.'); ?></div>
                <div class="item-name">Sales</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="increase-item">
                <div class="flex-center">
                    <div class="item-icon teal"><i class="fa fa-shopping-cart"></i></div>
                </div>
                <div class="item-number">+ <?php $count = 0;
                                            $sqlOrder = "SELECT * FROM orders";
                                            $queryOrder = mysqli_query($conn, $sqlOrder);
                                            while ($order = mysqli_fetch_array($queryOrder)) {
                                                if ($current == GetMonth($order['order_date'])) {
                                                    ++$count;
                                                }
                                            };
                                            $orderIncre = $count;
                                            echo $orderIncre; ?>
                </div>
                <div class="item-name">New Orders</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="increase-item">
                <div class="flex-center">
                    <div class="item-icon gray"><i class="fa fa-comments"></i></div>
                </div>
                <div class="item-number">+ <?php $count = 0;
                                            $sqlCom = "SELECT * FROM prd_comments";
                                            $queryCom = mysqli_query($conn, $sqlCom);
                                            while ($comm = mysqli_fetch_array($queryCom)) {
                                                if ($current == GetMonth($comm['comm_date'])) {
                                                    ++$count;
                                                }
                                            };
                                            $commIncre = $count;
                                            echo $commIncre; ?></div>
                <div class="item-name">Comments</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="increase-item">
                <div class="flex-center">
                    <div class="item-icon blue"><i class="fa fa-newspaper"></i></div>
                </div>
                <div class="item-number">+ <?php $count = 0;
                                            $sqlBlog = "SELECT * FROM blogs";
                                            $queryBlog = mysqli_query($conn, $sqlBlog);
                                            while ($bl = mysqli_fetch_array($queryBlog)) {
                                                if ($current == GetMonth($bl['blog_post'])) {
                                                    ++$count;
                                                }
                                            };
                                            $blIncree = $count;
                                            echo $blIncree; ?></div>
                <div class="item-name">New Posts</div>
            </div>
        </div>
    </div>
</section>
<section class="chart-area">
    <div class="row flex-between">
        <?php
        $chartInforQ = $conn->query("SELECT * FROM salesmonths ORDER BY sal_id ASC");
        $arrYear = $arrVal = [];
        if ($chartInforQ->num_rows > 0) {
            while ($chartInfor = mysqli_fetch_array($chartInforQ)) {
                if (!in_array($chartInfor['sal_year'], $arrYear)) {
                    $arrYear[] = $chartInfor['sal_year'];
                }
            }
        }
        for ($i = 0; $i < count($arrYear); $i++) {
            $arrValtmp = [];
            $chartInforQ = $conn->query("SELECT * FROM salesmonths ORDER BY sal_id ASC");
            while ($chartInfor = mysqli_fetch_array($chartInforQ)) {
                if ($chartInfor['sal_year'] == $arrYear[$i]) {
                    $arrValtmp[] = $chartInfor['sal_total'];
                }
            }
            $arrVal[$i] = implode(',', $arrValtmp);
        }
        $strArrVal = implode('%', $arrVal);
        $chartInforQ = $conn->query("SELECT * FROM salesmonths ORDER BY sal_id DESC LIMIT 0, 1");
        $chartInfor = mysqli_fetch_array($chartInforQ);
        $strValPie = $chartInfor['sal_clothes'].','.$chartInfor['sal_shoes'].','.$chartInfor['sal_eye'].','.$chartInfor['sal_categories'];
        ?>
        <div class="col-lg-7 col-md-12 col-sm-12">
            <h4 class="green"><span><i class="fa fa-chart-line"></i></span> Monthly sales</h4>
            <div class="chart-item" data-value="<?= $strArrVal ?>">
                <canvas id="monthly-chart"></canvas>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <h4 class="red"><span><i class="fa fa-chart-pie"></i></span> Category sales</h4>
            <div class="chart-item" data-value="<?= $strValPie ?>">
                <canvas id="category-chart"></canvas>
            </div>
        </div>
    </div>
    <div id="test-change"></div>
</section>
<section class="total-monthly">
    <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="increase-item cyan">
                <div class="flex-between">
                    <div class="item-icon"><i class="fa fa-users"></i></div>
                    <div class="item-number"><?= $queryUser->num_rows ?></div>
                </div>
                <div class="flex-between">
                    <div class="item-percent">+ <?= $queryUser->num_rows == 0 ? 0 : $userIncre / ($queryUser->num_rows) * 100 ?>%</div>
                    <div class="item-name">New Customers</div>
                </div>
                <div class="progress">
                    <div class="progress-bar green"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="increase-item purple">
                <div class="flex-between">
                    <div class="item-icon"><i class="fa fa-eye"></i></div>
                    <div class="item-number"><?= $queryVis->num_rows ?></div>
                </div>
                <div class="flex-between">
                    <div class="item-percent">+ <?= $queryVis->num_rows == 0 ? 0 : $visIncre / ($queryVis->num_rows) * 100 ?>%</div>
                    <div class="item-name">Unique Visitors</div>
                </div>
                <div class="progress">
                    <div class="progress-bar gray"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="increase-item red">
                <div class="flex-between">
                    <div class="item-icon"><i class="fa fa-tags"></i></div>
                    <div class="item-number"><?= number_format($totalSales, 2, ',', '.') ?>$</div>
                </div>
                <div class="flex-between">
                    <div class="item-percent">+ <?= $saleIncre / $totalSales * 100 ?>%</div>
                    <div class="item-name">Monthly Sales</div>
                </div>
                <div class="progress">
                    <div class="progress-bar cyan"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="increase-item teal">
                <div class="flex-between">
                    <div class="item-icon"><i class="fa fa-shopping-cart"></i></div>
                    <div class="item-number"><?= $queryOrder->num_rows ?></div>
                </div>
                <div class="flex-between">
                    <div class="item-percent">+ <?= $queryOrder->num_rows == 0 ? 0 : $orderIncre / $queryOrder->num_rows * 100 ?>%</div>
                    <div class="item-name">New Orders</div>
                </div>
                <div class="progress">
                    <div class="progress-bar purple"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="increase-item gray">
                <div class="flex-between">
                    <div class="item-icon"><i class="fa fa-comments"></i></div>
                    <div class="item-number"><?= $queryCom->num_rows ?></div>
                </div>
                <div class="flex-between">
                    <div class="item-percent">+ <?= $queryCom->num_rows == 0 ? 0 : $commIncre / $queryCom->num_rows * 100 ?>%</div>
                    <div class="item-name">Comments</div>
                </div>
                <div class="progress">
                    <div class="progress-bar orange"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12">
            <div class="increase-item blue">
                <div class="flex-between">
                    <div class="item-icon"><i class="fa fa-newspaper-o"></i></div>
                    <div class="item-number"><?= $queryBlog->num_rows ?></div>
                </div>
                <div class="flex-between">
                    <div class="item-percent">+ <?= $queryBlog->num_rows == 0 ? 0 : $blIncree / $queryBlog->num_rows * 100 ?>%</div>
                    <div class="item-name">New Posts</div>
                </div>
                <div class="progress">
                    <div class="progress-bar red"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="new-orders">
    <table class="table">
        <thead class="teal">
            <tr>
                <th class="tab-date">Order Date</th>
                <th class="tab-name">Buyer</th>
                <th class="tab-mail">Email</th>
                <th class="tab-phone">Phone</th>
                <th class="tab-address">Address</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM orders WHERE order_status = 0";
            $query = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($query)) {
                $cus = mysqli_fetch_array(mysqli_query($conn, "SELECT *FROM customers WHERE cus_id = '" . $row['cus_id'] . "'"));
            ?>
                <tr>
                    <td class="tab-date"><?= implode('.', explode('-', explode(' ', $row['order_date'])[0])) ?></td>
                    <td class="tab-name"><?= $cus['cus_name'] ?? 'No Information' ?></td>
                    <td class="tab-mail"><?= $cus['cus_mail'] ?? 'No Information' ?></td>
                    <td class="tab-phone"><?= $row['order_phone'] ?></td>
                    <td class="tab-address"><?= $row['order_add'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="tab-button"><a href="index.php?page_layout=orders">Details <span><i class="fa fa-arrow-right"></i></span></a></td>
            </tr>
        </tfoot>
    </table>
</section>