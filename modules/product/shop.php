<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
if (!empty($_GET['filsize'])) {
    $filtersize = $_GET['filsize'];
    $sizmin = trim(explode('%', $filtersize)[0]);
    $sizmax = trim(explode('%', $filtersize)[1]);
    $sizsql = '(prd_size BETWEEN ' . $sizmin . ' AND ' . $sizmax . ') ';
    $sentURL = '&filsize=' . $filtersize;
} else {
    $sizsql = '';
    $sentURL = '';
}
if (!empty($_GET['filcolor'])) {
    $filtercolor = $_GET['filcolor'];
    $sentURL = '&filcolor=' . $filtercolor;
} else {
    $filtercolor = '';
    $sentURL = '';
}
if (!empty($_GET['cat_id'])) {
    $filtercat = $_GET['cat_id'];
    $catsql = ' cat_id = ' . $filtercat . ' ';
    $sentURL = '&cat_id=' . $filtercat;
} elseif (!empty($_GET['brand_id'])) {
    $filtercat = $_GET['brand_id'];
    $catsql = ' prd_brand = ' . $filtercat . ' ';
    $sentURL = '&brand_id=' . $filtercat;
} else {
    $catsql = '';
    $sentURL = '';
}
if (!empty($_POST['price_filter']) || !empty($_GET['price_filter'])) {
    $filterprice = $_POST['price_filter'] ? $_POST['price_filter'] : $_GET['price_filter'];
    $filmin = trim(explode('%', $filterprice)[0]);
    $filmax = trim(explode('%', $filterprice)[1]);
    $pricesql = '( prd_price BETWEEN ' . $filmin . ' AND ' . $filmax . ') ';
    $sentURL = '&price_filter=' . $filterprice;
} else {
    $pricesql = '';
    $sentURL = '';
}
if ((!empty($_GET['cat_id']) || !empty($_GET['brand_id'])) || !empty($_GET['filsize']) || !empty($_POST['price_filter']) || !empty($_GET['price_filter'])) {
    $where = 'WHERE';
} else {
    $where = '';
}
if ((!empty($_GET['cat_id']) || !empty($_GET['brand_id']))  && ((!empty($_POST['price_filter']) || !empty($_GET['price_filter'])) || !empty($_GET['filsize']))) {
    if (!empty($_GET['filsize'])) {
        $andfirt = ' AND ';
        $andsec = ' ';
    } elseif (!empty($_POST['price_filter']) || !empty($_GET['price_filter'])) {
        $andfirt = ' ';
        $andsec = ' AND ';
    }
} elseif ((!empty($_GET['cat_id']) || !empty($_GET['brand_id'])) && !empty($_GET['filsize']) && (!empty($_POST['price_filter']) || !empty($_GET['price_filter']))) {
    $andfirt = ' AND ';
    $andsec = ' AND ';
} else {
    $andfirt = ' ';
    $andsec = ' ';
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$row_per_page = 9;
$per_row = $page * $row_per_page - $row_per_page;
$sqlFilTmp = $conn->query("SELECT * FROM product " . $where . $catsql . $andfirt . $sizsql  . $andsec . $pricesql);
$idArr = [];
while ($saveIDtmp = mysqli_fetch_array($sqlFilTmp)) {
    if (!empty($_GET['filcolor'])) {
        $colorArrTmp = explode(',', $saveIDtmp['prd_color']);
        for ($i = 0; $i < count($colorArrTmp); $i++) {
            $tmp = trim($colorArrTmp[$i]);
            if (mb_strtolower(checkColor(HexToHSL($tmp))) == mb_strtolower(trim($filtercolor))) {
                $idArr[] = $saveIDtmp['prd_id'];
            }
        }
    } else {
        $idArr[] = $saveIDtmp['prd_id'];
    }
}
$toltal_row = count($idArr);
$total_page = ceil($toltal_row / $row_per_page);

$list_page = '';

$prev_page = $page - 1;
if ($prev_page <= 1) {
    $prev_page = 1;
}

if ($page > 1) {
    $list_page .= '<li class="page-item"><a class="page-link" href="index.php?page_layout=shop' . $sentURL . '&page=' . $prev_page . '">&laquo;</a></li>';
}

for ($i = 1; $i <= $total_page; $i++) {
    if ($i == $page) {
        $active = 'active';
    } else {
        $active = '';
    }
    $list_page .= '<li class="page-item ' . $active . '"><a class="page-link" href="index.php?page_layout=shop' . $sentURL . '&page=' . $i . '">' . $i . '</a></li>';
}

$next_page = $page + 1;
if ($next_page >= $total_page) {
    $next_page = $total_page;
}

if ($page < $total_page) {
    $list_page .= '<li class="page-item"><a class="page-link" href="index.php?page_layout=shop' . $sentURL . '&page=' . $next_page . '">&raquo;</a></li>';
}
?>
<section class="new-arrivals-area mb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <?php include_once('modules/sidebars/side-nav.php') ?>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="shop-grid-product-area">
                    <div class="row shop-new-arrivals">
                        <?php
                        if (count($idArr) > 0) {
                            $strID = implode(', ', $idArr);
                            $sqlFil = $conn->query("SELECT * FROM product WHERE prd_id IN ($strID) ORDER BY prd_id DESC LIMIT " . $per_row . ',' . $row_per_page);
                            while ($prdShop = mysqli_fetch_array($sqlFil)) {
                                $prmQue = $conn->query("SELECT * FROM promotions");
                                while ($prmRow = mysqli_fetch_array($prmQue)) {
                                    $arrPRd = explode(',', $prmRow['prm_apply']);
                                    if (isset($arrPRd) && in_array($prdShop['prd_id'], $arrPRd)) {
                                        $discount = $prmRow['prm_percent'];
                                    } else {
                                        $discount = 0;
                                    }
                                }
                                $dataInfor = $prdShop['prd_id'] . '**' . $prdShop['prd_name'] . '**' . $prdShop['prd_image'] . '**' . $prdShop['prd_ratt'] . '**' . $prdShop['prd_price'] . '**' . $discount . '**' . ($prdShop['prd_quantity'] - $prdShop['prd_sell']);
                        ?>
                                <!-- Single gallery Item -->
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="prd-item">
                                        <div data-toggle="savetmp" class="prd-item-tmp">
                                            <?= $prdShop['prd_details'] ?>
                                        </div>
                                        <div class="prd-image">
                                            <img src="admin/images/product/<?= $prdShop['prd_image'] ?>" alt="" />
                                            <div class="prd-quickview">
                                                <a data-id="<?= $row['prd_id'] ?>" data-info="<?= $dataInfor ?>" data-toggle="modal" data-target="#quickview"><i class="fa fa-plus"></i></a>
                                            </div>
                                        </div>
                                        <div class="prd-description">
                                            <h4 class="prd-name" data-toggle="limit" data-line="2"><?= $prdShop['prd_name'] ?></h4>
                                            <p class="price-current text-center">$<?= number_format($prdShop['prd_price'], 2, ',', '.') ?></p>
                                            <a href="?page_layout=addcart&pagecurrent=shop&prd_id=<?= $prdShop['prd_id'] ?>" class="add-cart">ADD TO CART</a>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } else {
                            echo '<span class="alert-danger">No product to be founded !</span>';
                        } ?>
                    </div>

                    <!-- ****** Quick View Modal Area Start ****** -->
                    <?php include_once('modules/product/quickview.php') ?>
                    <!-- ****** Quick View Modal Area End ****** -->
                </div>

                <?php
                if ($toltal_row > $row_per_page) {
                ?>
                    <ul class="shop-pagination flex-end">
                        <?php echo $list_page; ?>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>
</section>