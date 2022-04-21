<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

$prdId = $_GET['prd_id'];
if (empty($_SESSION['prdRelated'])) {
    $_SESSION['prdRelated'] = $prdId;
} else {
    $arrPr = explode(',', $_SESSION['prdRelated']);
    if (!in_array($prdId, $arrPr)) {
        $_SESSION['prdRelated'] = implode(',', $arrPr) . ',' . $prdId;
    }
}

$pro = mysqli_fetch_array($conn->query("SELECT * FROM product WHERE prd_id = $prdId"));

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$row_per_page = 3;
$per_row = $page * $row_per_page - $row_per_page;
$toltal_row = mysqli_num_rows($conn->query("SELECT * FROM prd_comments WHERE comm_call = 0 AND prd_id = $prdId"));
$total_page = ceil($toltal_row / $row_per_page);

$list_page = '';

$prev_page = $page - 1;
if ($prev_page <= 1) {
    $prev_page = 1;
}

if ($page > 1) {
    $list_page .= '<li class="page-item"><a class="page-link" href="index.php?page_layout=prd_details&prd_id=' . $prdId . '&page=' . $prev_page . '#comm-details">&laquo;</a></li>';
}

for ($i = 1; $i <= $total_page; $i++) {
    if ($i == $page) {
        $active = 'active';
    } else {
        $active = '';
    }
    $list_page .= '<li class="page-item ' . $active . '"><a class="page-link" href="index.php?page_layout=prd_details&prd_id=' . $prdId . '&page=' . $i . '#comm-details">' . $i . '</a></li>';
}

$next_page = $page + 1;
if ($next_page >= $total_page) {
    $next_page = $total_page;
}

if ($page < $total_page) {
    $list_page .= '<li class="page-item"><a class="page-link" href="index.php?page_layout=prd_details&prd_id=' . $prdId . '&page=' . $next_page . '#comm-details">&raquo;</a></li>';
}
?>
<div class="container">
    <!-- <<<<<<<<<<<<<<<<<<<< Breadcumb Area Start <<<<<<<<<<<<<<<<<<<< -->
    <div class="breadcumb-area">
        <a href="?page_layout=shop" class="backToHome"><i class="fa fa-angle-double-left"></i> Back to Category</a>
    </div>
    <!-- <<<<<<<<<<<<<<<<<<<< Breadcumb Area End <<<<<<<<<<<<<<<<<<<< -->

    <!-- <<<<<<<<<<<<<<<<<<<< Single Product Details Area Start >>>>>>>>>>>>>>>>>>>>>>>>> -->
    <div class="single-product-details row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div id="product-details" class="slider product-slider">
                <div class="slide-inner">
                    <?php
                    $imgSub = explode(',', $pro['prd_imgSub']);
                    for ($i = 0; $i < count($imgSub); $i++) {
                    ?>
                        <div class="slide-item">
                            <a href="admin/images/product/<?= trim($imgSub[$i]) ?>">
                                <img src="admin/images/product/<?= trim($imgSub[$i]) ?>" alt="" />
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <div class="slide-indicators flex-between">
                    <?php
                    $imgSub = explode(',', $pro['prd_imgSub']);
                    foreach ($imgSub as $index => $img) {
                    ?>
                        <li class="indicator-item" data-id="<?= $index ?>"><img src="admin/images/product/<?= trim($img) ?>" alt="" /></li>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="prd-description">
                <h4 class="prd-name"><?= $pro['prd_name'] ?></h4>
                <p class="price-current">$<?= number_format($pro['prd_price'], 2, ',', '.') ?></p>
                <p class="available">Available: <span>In Stock</span></p>
                <div class="prd-rating">
                    <span data-toggle="showratt" class="stars" data-ratt="<?= $pro['prd_ratt'] ?>">★★★★★</span><span class="stars-show ml-3">(<?= $pro['prd_ratt'] ?>)</span>
                </div>
                <form method="post" action="?page_layout=cart">
                    <input hidden type="text" name="prd_id" value="<?= $pro['prd_id'] ?>" />
                    <?php
                    $sizePr = explode(',', $pro['prd_size']);
                    if (count($sizePr) > 0) {
                    ?>
                        <div class="widget-size">
                            <h6>Size</h6>
                            <ul class="flex-start">
                                <?php
                                for ($i = 0; $i < count($sizePr); $i++) {
                                ?>
                                    <div class="custom-checkbox">
                                        <div class="form-group">
                                            <input type="checkbox" name="prd_size[]" id="prd_size_<?= $sizePr[$i] ?>" value="<?= $sizePr[$i] ?>" />
                                            <label for="prd_size_<?= $sizePr[$i] ?>"><span><?= $sizePr[$i] ?></span></label>
                                        </div>
                                    </div>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <?php
                    $colorPrs = explode(',', $pro['prd_color']);
                    if (count($sizePr) > 0) {
                    ?>
                        <div class="widget-size widget-color">
                            <h6>Color</h6>
                            <ul class="flex-start">
                                <?php
                                for ($i = 0; $i < count($colorPrs); $i++) {
                                ?>
                                    <div class="custom-checkbox">
                                        <div class="form-group">
                                            <input type="checkbox" name="prd_color[]" id="prd_color_<?= $colorPrs[$i] ?>" value="<?= $colorPrs[$i] ?>" />
                                            <label for="prd_color_<?= $colorPrs[$i] ?>"><span style="background-color: <?= $colorPrs[$i]  ?>;"></span></label>
                                        </div>
                                    </div>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <div class="quickview-form items-center">
                        <div class="quantity">
                            <span onclick="var effect = document.getElementById('qty'); var qty = effect.value; if( !isNaN( qty ) && qty > 1) effect.value--;return false;">
                                <i class="fa fa-minus"></i>
                            </span>
                            <input type="number" id="qty" step="1" min="1" max="12" name="quantity" value="1" />
                            <span onclick="var effect = document.getElementById('qty'); var qty = effect.value; if( !isNaN( qty )) effect.value++;return false;">
                                <i class="fa fa-plus"></i>
                            </span>
                        </div>
                        <button type="submit" name="addcart" class="add-cart">Add to cart</button>
                    </div>
                </form>

                <div id="accordition">
                    <div class="card">
                        <?php
                        $detaTmp = html_entity_decode($pro['prd_details']);
                        $arrTmpDeta = explode('2. Details and Care:', $detaTmp);
                        $informa = trim(explode('1. Information:', $arrTmpDeta[0])[1]);
                        $careDeta = trim(explode('3. Shipping and Returns:', $arrTmpDeta[1])[0]);
                        $shipRetur = trim(explode('3. Shipping and Returns:', $arrTmpDeta[1])[1]);
                        ?>
                        <div class="card-header" data-toggle="collapse" data-target="#collapseOne">
                            <h6>Information</h6>
                            <div id="collapseOne" class="collapse">
                                <div class="card-body">
                                    <?= html_entity_decode($informa) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" data-toggle="collapse" data-target="#collapseTwo">
                            <h6>Care And Details</h6>
                            <div id="collapseTwo" class="collapse">
                                <div class="card-body">
                                    <p><?= $careDeta ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" data-toggle="collapse" data-target="#collapseTree">
                            <h6>shipping &amp; Returns</h6>
                            <div id="collapseTree" class="collapse">
                                <div class="card-body">
                                    <?= html_entity_decode($shipRetur) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST['postCommPr']) || isset($_POST['editCommpr'])) {
        if (!empty($_POST['comm_details'])) {
            $detail = $_POST['comm_details'];
            if (!empty($_POST['comm_repfor'])) {
                $repfor = $_POST['comm_repfor'];
                $call = 1;
            } else {
                $repfor = $call = 0;
            }
            $dateCoPr = date('Y-m-d H:i:s');
            if (isset($_POST['postCommPr'])) {
                $testWue = $conn->query("SELECT * FROM prd_comments WHERE prd_id = $prdId AND cus_id = $cusId AND comm_details='' LIMIT 0, 1");
                if ($testWue->num_rows > 0) {
                    $comWueId = mysqli_fetch_array($testWue)['comm_id'];
                    $conn->query("UPDATE prd_comments SET comm_details = '$detail', comm_repfor = $repfor, comm_call = $call, comm_date = '$dateCoPr' WHERE comm_id = $comWueId");
                } else {
                    $conn->query("INSERT INTO prd_comments (prd_id, cus_id, comm_details, comm_repfor, comm_date, comm_call) VALUES ($prdId, $cusId, '$detail', $repfor, '$dateCoPr', $call)");
                }
                $ntfInfor = '?page_layout=comm_details&comm_type=product&id=' . $prdId . '%' . $pro['prd_name'];
                $conn->query("INSERT INTO notifications (ntf_infor, ntf_type, ntf_date) VALUES ('$ntfInfor', 'product', '$dateCoPr')");
            } elseif (isset($_POST['editCommpr'])) {
                $conn->query("UPDATE prd_comments SET comm_details = '$detail' WHERE comm_id = $repfor");
            }
        } else {
            $error = '<div class="alert-danger mb-3">Please enter content comment !</div>';
        }
    } else {
        $error = '';
    }
    ?>
    <div id="add_comm" class="comm-area mtb-100">
        <?php
        if (isset($_SESSION['mail'])) {
            $rattQue = $conn->query("SELECT * FROM rattstars WHERE prd_id = $prdId AND cus_id = $cusId");
            if ($rattQue->num_rows > 0) {
                $rattUs = mysqli_fetch_array($rattQue)['ratt_val'];
            } else {
                $rattUs = 0;
            }
        ?>
            <form method="post" action="index.php?page_layout=acPrd&prd_id=<?= $prdId ?>" id="ratingprd">
                <h2 class="mb-3"><span class="pr-1">Please ratting for this product !</h2>
                <div class="prd-rating-rank flex-end mb-3">
                    <div class="star-group flex-start">
                        <input <?php if ($rattUs == 5) {
                                    echo 'checked';
                                } ?> class="rating-input" type="radio" name="rating" id="rating-5" value="5" />
                        <label class="rating-label" aria-label="5 stars" for="rating-5"><i class="rating-icon fa fa-star"></i></label>
                        <input <?php if ($rattUs == 4) {
                                    echo 'checked';
                                } ?> class="rating-input" type="radio" name="rating" id="rating-4" value="4" />
                        <label class="rating-label" aria-label="4 stars" for="rating-4"><i class="rating-icon fa fa-star"></i></label>
                        <input <?php if ($rattUs == 3) {
                                    echo 'checked';
                                } ?> class="rating-input" type="radio" name="rating" id="rating-3" value="3" />
                        <label class="rating-label" aria-label="3 stars" for="rating-3"><i class="rating-icon fa fa-star"></i></label>
                        <input <?php if ($rattUs == 2) {
                                    echo 'checked';
                                } ?> class="rating-input" type="radio" name="rating" id="rating-2" value="2" />
                        <label class="rating-label" aria-label="2 stars" for="rating-2"><i class="rating-icon fa fa-star"></i></label>
                        <input <?php if ($rattUs == 1) {
                                    echo 'checked';
                                } ?> class="rating-input" type="radio" name="rating" id="rating-1" value="1" />
                        <label class="rating-label" aria-label="1 stars" for="rating-1"><i class="rating-icon fa fa-star"></i></label>
                    </div>
                </div>
            </form>
            <script>
                var formRatEl = document.getElementById('ratingprd');
                if (formRatEl) {
                    var inpuRats = formRatEl.querySelectorAll('[name=rating]');
                    if (inpuRats) {
                        Array.from(inpuRats).forEach(function(ratt) {
                            ratt.oninput = function() {
                                formRatEl.submit();
                            }
                        })
                    }
                }
            </script>
            <h2 class="mb-3" data-toggle="comm-parColl" data-target="#writeComm" data-tarblock="#add_comm .comm-block"><span class="pr-1"><?= $conn->query("SELECT * FROM prd_comments WHERE prd_id = $prdId")->num_rows ?></span>Comments</h2>
            <form method="post" class="writeComment" id="writeComm" data-toggle="writeComm">
                <?php if (!empty($error)) {
                    echo $error;
                } ?>
                <textarea class="w-100 ckeditor" name="comm_details" placeholder="Write Comment ...."></textarea>

                <div class="flex-start items-center">
                    <button class="btn" type="submit" name="postCommPr">Post</button>
                    <div class="flex-start items-center ml-3 cusInfo">
                        <img src="admin/images/avata/<?php if ($ssUser['cus_image'] !== '') {
                                                            echo $ssUser['cus_image'];
                                                        } else {
                                                            echo 'avatar-default.png';
                                                        } ?>" alt="" />
                        <span><?= $ssUser['cus_name'] ?></span>
                    </div>
                </div>
            </form>
        <?php } else { ?>
            <div class="flex-start annouce-sign">Please <a href="index.php?page_layout=logreg&logInfor=prd_details-<?= $prdId ?>">sign-in <span><i class="fa fa-sign-in-alt"></i></span></a> to comments !</div>
        <?php } ?>
        <div id="comm-details" class="comm-block">
            <?php
            $commQu = $conn->query("SELECT * FROM prd_comments WHERE prd_id = $prdId AND comm_call = 0 ORDER BY comm_id DESC LIMIT " . $per_row . ',' . $row_per_page);
            while ($commprd = mysqli_fetch_array($commQu)) {
                if (trim($commprd['comm_details']) !== "") {
                    $infoCusPr = mysqli_fetch_array($conn->query("SELECT * FROM customers WHERE cus_id = " . $commprd['cus_id']));
                    $childCommQue = $conn->query("SELECT * FROM prd_comments WHERE prd_id = $prdId AND comm_call = 1");
                    if ($commprd['comm_like'] !== "") {
                        $likeCommArr = explode(',', $commprd['comm_like']);
                    } else {
                        $likeCommArr = [];
                    }
                    $countCommPr = 0;
                    if ($childCommQue->num_rows > 0) {
                        while ($countComm = mysqli_fetch_array($childCommQue)) {
                            $countId = $countComm['comm_repfor'];
                            $lastCountId = GetIDprd($countId, $conn);
                            if ($lastCountId === $commprd['comm_id']) {
                                ++$countCommPr;
                            }
                        }
                    }
            ?>
                    <div class="comm-item-group">
                        <div id="comm<?= $commprd['comm_id'] ?>" class="com-item flex-between">
                            <div class="comm-ava">
                                <img src="admin/images/avata/<?php if ($infoCusPr['cus_image'] !== '') {
                                                                    echo $infoCusPr['cus_image'];
                                                                } else {
                                                                    echo 'avatar-default.png';
                                                                }  ?>" alt="" />
                            </div>
                            <div class="comm-content">
                                <div class="comm-name"><?= $infoCusPr['cus_name'] ?></div>
                                <div class="comm-time"><?= date('M d, Y', strtotime($commprd['comm_date'])) ?> at <?= date('h:i a', strtotime($commprd['comm_date'])) ?></div>
                                <p class="comm-detail"><?= $commprd['comm_details'] ?></p>
                                <div class="comm-but flex-end">
                                    <?php if (isset($_SESSION['mail'])) { ?>
                                        <a data-toggle="reply" data-setID="<?= $commprd['comm_id'] ?>" data-id="<?= $commprd['comm_id'] ?>" data-parent=".comm-item-group">Reply</a>
                                        <?php if ($cusId == $commprd['cus_id']) { ?>
                                            <a class="ml-3" data-toggle="reply" data-setID="<?= $commprd['comm_id'] ?>" data-togsub="edit" data-id="<?= $commprd['comm_id'] ?>" data-detail="<?= $commprd['comm_details'] ?>" data-parent=".comm-item-group">Edit</a>
                                            <a class="ml-3" data-toggle="modal" data-target="#confimDelete" data-href="index.php?page_layout=acPrd&prd_id=<?= $prdId ?>&comm_id=<?= $commprd['comm_id'] ?>&actype=delete-comments&pagecurr=prd_details%comm-details">Delete</a>
                                    <?php }
                                    } ?>
                                    <a class="ml-3" data-toggle="comm-collapse" data-target="#chilCom<?= $commprd['comm_id'] ?>"><i class="fa fa-comments"></i><span><?= $countCommPr; ?></span></a>
                                    <a class="ml-3 <?php if (isset($_SESSION['mail']) && in_array($cusId, $likeCommArr)) {
                                                        echo 'text-danger';
                                                    } ?>" href="index.php?page_layout=acPrd&prd_id=<?= $prdId ?>&comm_id=<?= $commprd['comm_id'] ?>&actype=like&pagecurr=prd_details%comm-details"><i class="fa fa-heart"></i><span><?= count($likeCommArr) ?></span></a>
                                </div>
                            </div>
                        </div>
                        <div id="chilCom<?= $commprd['comm_id'] ?>" class="comm-child collapse">
                            <?php
                            $childCommQue = $conn->query("SELECT * FROM prd_comments WHERE prd_id = $prdId AND comm_call = 1");
                            if ($childCommQue->num_rows > 0) {
                                while ($chilComm = mysqli_fetch_array($childCommQue)) {
                                    $checkId = $chilComm['comm_repfor'];
                                    $lastId = GetIDprd($checkId, $conn);
                                    if ($lastId === $commprd['comm_id']) {
                                        $chilInforUser = mysqli_fetch_array($conn->query("SELECT * FROM customers WHERE cus_id = " . $chilComm['cus_id']));
                                        if ($chilComm['comm_like'] !== "") {
                                            $likeChil = explode(',', $chilComm['comm_like']);
                                        } else {
                                            $likeChil = [];
                                        }
                            ?>
                                        <div class="com-item flex-between">
                                            <div class="comm-ava">
                                                <img src="admin/images/avata/<?php if ($chilInforUser['cus_image'] !== '') {
                                                                                    echo $chilInforUser['cus_image'];
                                                                                } else {
                                                                                    echo 'avatar-default.png';
                                                                                }  ?>" alt="" />
                                            </div>
                                            <div class="comm-content">
                                                <div class="comm-name"><?= $chilInforUser['cus_name'] ?></div>
                                                <div class="comm-time"><?= date('M d, Y', strtotime($chilComm['comm_date'])) ?> at <?= date('h:i a', strtotime($chilComm['comm_date'])) ?></div>
                                                <p class="comm-detail"><?= $chilComm['comm_details'] ?></p>
                                                <div class="comm-but flex-end">
                                                    <?php if (isset($_SESSION['mail'])) { ?>
                                                        <a data-toggle="reply" data-setID="<?= $commprd['comm_id'] ?>" data-id="<?= $chilComm['comm_id'] ?>" data-parent=".comm-item-group">Reply</a>
                                                        <?php if ($cusId == $commprd['cus_id']) { ?>
                                                            <a class="ml-3" data-toggle="reply" data-togsub="edit" data-setID="<?= $chilComm['comm_id'] ?>" data-id="<?= $chilComm['comm_id'] ?>" data-detail="<?= $chilComm['comm_details'] ?>" data-parent=".comm-item-group">Edit</a>
                                                            <a class="ml-3" data-toggle="modal" data-target="#confimDelete" data-href="index.php?page_layout=acPrd&prd_id=<?= $prdId ?>&comm_id=<?= $chilComm['comm_id'] ?>&actype=delete-comments&pagecurr=prd_details%comm-details">Delete</a>
                                                    <?php }
                                                    } ?>
                                                    <a class="ml-3 <?php if (isset($_SESSION['mail']) && in_array($cusId, $likeCommArr)) {
                                                                        echo 'text-danger';
                                                                    } ?>" href="index.php?page_layout=acPrd&prd_id=<?= $prdId ?>&comm_id=<?= $chilComm['comm_id'] ?>&actype=like&pagecurr=prd_details%comm-details"><i class="fa fa-heart"></i><span><?= count($likeChil) ?></span></a>
                                                </div>
                                            </div>
                                        </div>
                            <?php }
                                }
                            }
                            ?>
                            <?php
                            if (isset($_SESSION['mail'])) {
                            ?>
                                <form method="post" class="collapse writeComment" action="#comm<?= $commprd['comm_id'] ?>" data-toggle="writeComm">
                                    <?php if (!empty($error)) {
                                        echo $error;
                                    } ?>
                                    <input hidden type="number" name="comm_repfor" />
                                    <div id="textareaEl"></div>
                                    <div class="flex-start items-center">
                                        <button class="btn" name="postCommPr" type="submit">Post</button>
                                        <div class="flex-start items-center ml-3 cusInfo">
                                            <img src="admin/images/avata/<?php if ($ssUser['cus_image'] !== '') {
                                                                                echo $ssUser['cus_image'];
                                                                            } else {
                                                                                echo 'avatar-default.png';
                                                                            } ?>" alt="" />
                                            <span><?= $ssUser['cus_name'] ?></span>
                                        </div>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
            <?php }
            } ?>
            <div id="confimDelete" class="modal flex-center items-center" role="dialog">
                <div class="modal-dialog" role="document">
                    <p class="modal-header">Are you sure to delete this comment ?</p>
                    <a class="btn green" data-dismiss="modal">Cancel</a>
                    <a class="btn red" data-submit="modal">Sure</a>
                </div>
            </div>
            <?php
            if ($toltal_row > $row_per_page) {
            ?>
                <ul class="shop-pagination flex-end">
                    <?php echo $list_page; ?>
                </ul>
            <?php }
            ?>
        </div>
    </div>
    <!-- <<<<<<<<<<<<<<<<<<<< Single Product Details Area End >>>>>>>>>>>>>>>>>>>>>>>>> -->

    <section class="new-arrivals-area mb-100">
        <h2>Related Product</h2>
        <div id="relatedProduct" class="slider product-slider">
            <div class="slide-inner">
                <?php
                if (!empty($_SESSION['prdRelated'])) {
                    $arrPrdId = explode(',', $_SESSION['prdRelated']);
                    for ($i = 0; $i < count($arrPrdId); $i++) {
                        if (trim($arrPrdId[$i]) != "") {
                            $prdRelate = mysqli_fetch_array($conn->query("SELECT * FROM product WHERE prd_id = $arrPrdId[$i]"));
                ?>
                            <div class="slide-item">
                                <div class="prd-item">
                                    <a class="prd-image" href="?page_layout=prd_details&prd_id=<?= $prdRelate['prd_id'] ?>">
                                        <img src="admin/images/product/<?= $prdRelate['prd_image'] ?>" alt="" />
                                    </a>
                                    <div class="prd-description">
                                        <h4 class="prd-name" data-toggle="limit" data-line="2"><?= $prdRelate['prd_name'] ?></h4>
                                        <div class="flex-between items-center">
                                            <p class="price-current">$<?= number_format($prdRelate['prd_price'], 2, ',', '.') ?></p>
                                            <a href="?page_layout=addcart&pagecurrent=prd_details&prd_id=<?= $prdRelate['prd_id'] ?>#relatedProduct" class="add-cart">ADD TO CART</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                <?php }
                    }
                } else {
                    echo 'No product related !';
                } ?>
            </div>
        </div>
    </section>
</div>