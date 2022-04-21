<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
?>
<div class="shop-sidebar-menu">
    <div class="widget-catagory">
        <!-- Side Navigation -->
        <div class="nav-side-menu">
            <h6>Categories</h6>
            <ul class="menu-list">
                <!-- Single Item -->
                <?php
                $sql = "SELECT * FROM categories WHERE cat_type = 1 ORDER BY cat_name ASC";
                $query = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_array($query)) {
                ?>
                    <li data-toggle="collapse" data-target="#shop-<?= strtolower(implode('_', explode(' ', preg_replace("/[^A-Za-z0-9\-]/", ' ', $row['cat_name'])))) ?>">
                        <a href="#"><?= $row['cat_name'] ?> <span class="fa fa-caret-down"></span></a>
                        <ul class="sub-menu collapse" id="shop-<?= strtolower(implode('_', explode(' ', preg_replace("/[^A-Za-z0-9\-]/", ' ', $row['cat_name'])))) ?>">
                            <?php
                            $subSql = "SELECT * FROM categories WHERE cat_call = '" . $row['cat_id'] . "' ORDER BY cat_name ASC";
                            $subQue = mysqli_query($conn, $subSql);
                            while ($subMenu = mysqli_fetch_array($subQue)) {
                            ?>
                                <li><a href="?page_layout=shop&brand_id=<?= $subMenu['cat_id'] ?>"><?= $subMenu['cat_name'] ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="widget-price">
        <h6>Filter by Price</h6>
        <div class="filter-price" id="filter-price">
            <div id="range-price">
                <div class="range-slider">
                    <div class="range-handle handle-left"></div>
                    <div class="range-handle handle-right"></div>
                </div>
            </div>
            <input type="range" value="0" max="3000" min="0" step="1" />
            <input type="range" value="0" max="3000" min="0" step="1" />
            <div class="show-value">Price: $<span class="price-min"></span> - $<span class="price-max"></span></div>
            <form method="post">
                <input hidden type="text" name="price_filter" />
            </form>
        </div>
    </div>

    <div class="widget-color">
        <h6>Filter by Color</h6>
        <ul class="flex-start filter-color">
            <?php
            $quCount = $conn->query("SELECT * FROM product");
            $grayArr = $redArr = $greenArr = $yellowArr = $blueArr = $darkArr = $whiteArr = $purpleArr = [];
            while ($countRow = mysqli_fetch_array($quCount)) {
                $colorTmp = explode(',', $countRow['prd_color']);
                $arrTmp = [];
                for ($i = 0; $i < count($colorTmp); $i++) {
                    $tmp = trim($colorTmp[$i]);
                    $arrTmp[] = mb_strtolower(checkColor(HexToHSL($tmp)));
                }

                if (in_array('gray', $arrTmp)) {
                    $grayArr[] = $countRow['prd_id'];
                }
                if (in_array('red', $arrTmp)) {
                    $redArr[] = $countRow['prd_id'];
                }
                if (in_array('green', $arrTmp)) {
                    $greenArr[] = $countRow['prd_id'];
                }
                if (in_array('yellow', $arrTmp)) {
                    $yellowArr[] = $countRow['prd_id'];
                }
                if (in_array('blue', $arrTmp)) {
                    $blueArr[] = $countRow['prd_id'];
                }
                if (in_array('dark', $arrTmp)) {
                    $darkArr[] = $countRow['prd_id'];
                }
                if (in_array('white', $arrTmp)) {
                    $whiteArr[] = $countRow['prd_id'];
                }
                if (in_array('purple', $arrTmp)) {
                    $purpleArr[] = $countRow['prd_id'];
                }
            }
            ?>
            <li class="dark">
                <div><a href="?page_layout=shop&filcolor=dark"></a></div><span>(<?= count($darkArr) ?>)</span>
            </li>
            <li class="gray">
                <div><a href="?page_layout=shop&filcolor=gray"></a></div><span>(<?= count($grayArr) ?>)</span>
            </li>
            <li class="red">
                <div><a href="?page_layout=shop&filcolor=red"></a></div><span>(<?= count($redArr) ?>)</span>
            </li>
            <li class="purple">
                <div><a href="?page_layout=shop&filcolor=purple"></a></div><span>(<?= count($purpleArr) ?>)</span>
            </li>
            <li class="yellow">
                <div><a href="?page_layout=shop&filcolor=yellow"></a></div><span>(<?= count($yellowArr) ?>)</span>
            </li>
            <li class="green">
                <div><a href="?page_layout=shop&filcolor=green"></a></div><span>(<?= count($greenArr) ?>)</span>
            </li>
            <li class="blue">
                <div><a href="?page_layout=shop&filcolor=blue"></a></div><span>(<?= count($blueArr) ?>)</span>
            </li>
            <li class="white">
                <div><a href="?page_layout=shop&filcolor=white"></a></div><span>(<?= count($whiteArr) ?>)</span>
            </li>
        </ul>
    </div>

    <div class="widget-size">
        <h6>Filter by Size</h6>
        <ul class="flex-between filter-size">
            <li><a href="?page_layout=shop&filsize=0%34">34</a></li>
            <li><a href="?page_layout=shop&filsize=34%38">38</a></li>
            <li><a href="?page_layout=shop&filsize=38%42">42</a></li>
            <li><a href="?page_layout=shop&filsize=42%46">46</a></li>
            <li><a href="?page_layout=shop&filsize=46%50">50</a></li>
            <li><a href="?page_layout=shop&filsize=50%56">56</a></li>
        </ul>
    </div>

    <div class="widget-recommended">
        <h6>Recommended</h6>
        <div class="sidebar-recommended">
            <!-- Single Recommended Product -->
            <?php
            $recommQ = $conn->query("SELECT * FROM prd_comments ORDER BY comm_date DESC");
            if ($recommQ->num_rows > 0) {
                $id = [];
                while ($recommPr = mysqli_fetch_array($recommQ)) {
                    if (!in_array($recommPr['prd_id'], $id)) {
                        $id[] = $recommPr['prd_id'];
                    }
                }
                if (count($id) > 3) {
                    $limit = 3;
                } else {
                    $limit = count($id);
                }
                for ($i = 0; $i < $limit; $i++) {
                    $recommPr = mysqli_fetch_array($conn->query("SELECT * FROM product WHERE prd_id = '" . $id[$i] . "'"));
            ?>
                    <div class="flex-between">
                        <a class="recommended-thumb" href="?page_layout=prd_details&prd_id=<?= $id[$i] ?>">
                            <img src="admin/images/product/<?= $recommPr['prd_image'] ?>" alt="" />
                        </a>
                        <div class="recommended-desc">
                            <h6><?= $recommPr['prd_name'] ?></h6>
                            <p>$ <?= number_format($recommPr['prd_price'], 2, ',', '.') ?></p>
                        </div>
                    </div>
            <?php }
            } else {
                echo 'No product recommended !';
            } ?>
        </div>
    </div>
</div>