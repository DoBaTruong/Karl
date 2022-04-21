<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

$blogId = $_GET['blog_id'];
$checkV = mysqli_fetch_array($conn->query("SELECT * FROM blogs WHERE blog_id = $blogId"))['blog_view'];
$blog = mysqli_fetch_array($conn->query("SELECT * FROM blogs WHERE blog_id = $blogId"));
$conn->query("UPDATE blogs SET blog_view = $checkV + 1 WHERE blog_id = $blogId");
if (!empty($_POST['comm_details'])) {
    $commDe = $_POST['comm_details'];
    if (!empty($_POST['comm_repfor'])) {
        $repfor = $_POST['comm_repfor'];
        $commCall = 1;
    } else {
        $repfor = 0;
        $commCall = 0;
    }
    $dateComm = date('Y-m-d H:i:s');
    $conn->query("INSERT INTO blog_comments (blog_id, cus_id, comm_details, comm_call, comm_repfor, comm_date) VALUES ($blogId, $cusId, '$commDe', $commCall, $repfor, '$dateComm')");
    $ntfInfor = '?page_layout=comm_details&comm_type=blog&id=' . $blogId . '%' . $blog['blog_title'];
    $conn->query("INSERT INTO notifications (ntf_infor, ntf_type, ntf_date) VALUES ('$ntfInfor','blog', '$dateComm')");
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$row_per_page = 3;
$per_row = $page * $row_per_page - $row_per_page;
$toltal_row = mysqli_num_rows($conn->query("SELECT * FROM blog_comments WHERE comm_call = 0"));
$total_page = ceil($toltal_row / $row_per_page);

$list_page = '';

$prev_page = $page - 1;
if ($prev_page <= 1) {
    $prev_page = 1;
}

if ($page > 1) {
    $list_page .= '<li class="page-item"><a class="page-link" href="index.php?page_layout=blog_details&blog_id=' . $blogId . '&page=' . $prev_page . '#comm-details">&laquo;</a></li>';
}

for ($i = 1; $i <= $total_page; $i++) {
    if ($i == $page) {
        $active = 'active';
    } else {
        $active = '';
    }
    $list_page .= '<li class="page-item ' . $active . '"><a class="page-link" href="index.php?page_layout=blog_details&blog_id=' . $blogId . '&page=' . $i . '#comm-details">' . $i . '</a></li>';
}

$next_page = $page + 1;
if ($next_page >= $total_page) {
    $next_page = $total_page;
}

if ($page < $total_page) {
    $list_page .= '<li class="page-item"><a class="page-link" href="index.php?page_layout=blog_details&blog_id=' . $blogId . '&page=' . $next_page . '#comm-details">&raquo;</a></li>';
}
?>
<section class="blog-details-area mb-100">
    <div class="container">
        <div class="blog-item row">
            <div class="blog-title"><?= $blog['blog_title'] ?></div>
            <div class="blog-time"><?= $blog['blog_like'] ?> like - <?= $blog['blog_view'] ?> Views - <?= date("M d, Y", strtotime($blog['blog_post'])) ?></div>
            <div class="blog-details row"><?= $blog['blog_content'] ?></div>
            <div class="flex-end row" id="blogLike">
                <a data-toggle="comm-parColl" data-target="#writeComm" data-tarblock="#add_comm .comm-block"><i class="fa fa-comments"> <span><?= $conn->query("SELECT * FROM blog_comments WHERE blog_id = $blogId")->num_rows ?></span></i></a>
                <?php
                if (isset($_SESSION['mail'])) {
                    $mail = $_SESSION['mail'];
                    $cus =  mysqli_fetch_array($conn->query("SELECT * FROM customers WHERE cus_mail = '$mail'"));
                    $cusBlLike = explode(',', $cus['blog_like']);
                }
                ?>
                <a class="ml-3 <?php if (isset($_SESSION['mail']) && in_array($blogId, $cusBlLike)) {
                                    echo 'text-danger';
                                } ?>" href="index.php?page_layout=acBl&id=<?= $blog['blog_id'] ?>#blogLike"><i class="fa fa-heart"></i></a>
                <a class="ml-3"><i class="fa fa-share-alt"></i></a>
            </div>
            <div class="blog-author">By: <span><?= $blog['blog_authors'] ?></span></div>
        </div>
        <div id="add_comm" class="comm-area">
            <?php
            if (isset($_SESSION['mail'])) {
            ?>
                <form method="post" class="writeComment" id="writeComm" data-toggle="writeComm">
                    <textarea class="w-100 ckeditor" name="comm_details" placeholder="Write Comment ...."></textarea>
                    <div class="flex-start items-center">
                        <button class="btn" type="submit">Post</button>
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
                <div class="flex-start annouce-sign">Please <a href="index.php?page_layout=logreg&logInfor=blog_details-<?= $blogId ?>">sign-in <span><i class="fa fa-sign-in-alt"></i></span></a> to comments !</div>
            <?php } ?>
            <div id="comm-details" class="comm-block mtb-100">
                <?php
                $commQu = $conn->query("SELECT * FROM blog_comments WHERE blog_id = $blogId AND comm_call = 0 ORDER BY comm_id DESC LIMIT " . $per_row . ',' . $row_per_page);
                while ($commblogs = mysqli_fetch_array($commQu)) {
                    $infoCusBl = mysqli_fetch_array($conn->query("SELECT * FROM customers WHERE cus_id = " . $commblogs['cus_id']));
                    $childCommQue = $conn->query("SELECT * FROM blog_comments WHERE blog_id = $blogId AND comm_call = 1");
                    if ($commblogs['comm_like'] !== "") {
                        $likeCommArr = explode(',', $commblogs['comm_like']);
                    } else {
                        $likeCommArr = [];
                    }
                    $countCommBl = 0;
                    if ($childCommQue->num_rows > 0) {
                        while ($countComm = mysqli_fetch_array($childCommQue)) {
                            $countId = $countComm['comm_repfor'];
                            $lastCountId = GetIDBlog($countId, $conn);
                            if ($lastCountId === $commblogs['comm_id']) {
                                ++$countCommBl;
                            }
                        }
                    }
                ?>
                    <div class="comm-item-group">
                        <div id="comm<?= $commblogs['comm_id'] ?>" class="com-item flex-between">
                            <div class="comm-ava">
                                <img src="admin/images/avata/<?php if ($infoCusBl['cus_image'] !== '') {
                                                                    echo $infoCusBl['cus_image'];
                                                                } else {
                                                                    echo 'avatar-default.png';
                                                                }  ?>" alt="" />
                            </div>
                            <div class="comm-content">
                                <div class="comm-name"><?= $infoCusBl['cus_name'] ?></div>
                                <div class="comm-time"><?= date('M d, Y', strtotime($commblogs['comm_date'])) ?> at <?= date('h:i a', strtotime($commblogs['comm_date'])) ?></div>
                                <p class="comm-detail"><?= $commblogs['comm_details'] ?></p>
                                <div class="comm-but flex-end">
                                    <?php if (isset($_SESSION['mail'])) { ?>
                                        <a data-toggle="reply" data-id="<?= $commblogs['comm_id'] ?>" data-parent=".comm-item-group">Reply</a>
                                    <?php } ?>
                                    <a class="ml-3" data-toggle="comm-collapse" data-target="#chilCom<?= $commblogs['comm_id'] ?>"><i class="fa fa-comments"></i><span><?= $countCommBl ?></span></a>
                                    <a class="ml-3 <?php if (isset($_SESSION['mail']) && in_array($cusId, $likeCommArr)) {
                                                        echo 'text-danger';
                                                    } ?>" href="index.php?page_layout=acBl&id=<?= $blog['blog_id'] ?>&like_comment=<?= $commblogs['comm_id'] ?>#comm<?= $commblogs['comm_id'] ?>"><i class="fa fa-heart"></i><span><?= count($likeCommArr) ?></span></a>
                                </div>
                            </div>
                        </div>
                        <div id="chilCom<?= $commblogs['comm_id'] ?>" class="comm-child collapse">
                            <?php
                            $childCommQue = $conn->query("SELECT * FROM blog_comments WHERE blog_id = $blogId AND comm_call = 1");
                            if ($childCommQue->num_rows > 0) {
                                while ($chilComm = mysqli_fetch_array($childCommQue)) {
                                    $checkId = $chilComm['comm_repfor'];
                                    $lastId = GetIDBlog($checkId, $conn);
                                    if ($lastId === $commblogs['comm_id']) {
                                        $chilInforUser = mysqli_fetch_array($conn->query("SELECT * FROM customers WHERE cus_id = " . $chilComm['cus_id']));
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
                                                        <a data-toggle="reply" data-id="<?= $chilComm['comm_id'] ?>" data-parent=".comm-item-group">Reply</a>
                                                    <?php } ?>
                                                    <a class=" ml-3" href="#"><i class="fa fa-heart"></i><span><?= $chilComm['comm_like'] ?></span></a>
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
                                <form method="post" class="collapse writeComment" data-toggle="writeComm">
                                    <input hidden type="number" name="comm_repfor" />
                                    <div id="textareaEl"></div>
                                    <div class="flex-start items-center">
                                        <button class="btn" type="submit">Post</button>
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
                <?php } ?>
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
    </div>
</section>