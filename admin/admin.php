<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
ob_start();
date_default_timezone_set('Asia/Bangkok');
include "PHPMailer-master/src/PHPMailer.php";
include "PHPMailer-master/src/Exception.php";
include "PHPMailer-master/src/OAuth.php";
include "PHPMailer-master/src/POP3.php";
include "PHPMailer-master/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_GET['mess_infor'])) {
    $_SESSION['messID'] = explode('%', $_GET['mess_infor'])[0];
    $_SESSION['messBoxID'] = explode('%', $_GET['mess_infor'])[1];
    $conn->query("UPDATE messengers SET mess_read = 1 WHERE mess_id = '" . $_SESSION['messID'] . "'");
}

if (isset($_POST['replyInquiry'])) {
    $replyfor = $_POST['reply_id'];
    $inqui = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM supports WHERE supp_id = $replyfor"));
    $content = $_POST['supp_detail'];
    $datecomm = date('Y-m-d H:i:s');
    mysqli_query($conn, "UPDATE supports SET supp_reply = '$content', reply_date = '$datecomm'");

    $mail = new PHPMailer(true);                              // Passing 'true' enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'developer.karlfashion@gmail.com';                 // SMTP username
        $mail->Password = 'karlfashionTt11041998';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, 'ssl' also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        //Recipients
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('developer.karlfashion@gmail.com', 'Karl Fashion Shop');                // Gửi mail tới Mail Server
        $mail->addAddress($inqui['supp_mail']);               // Gửi mail tới mail người nhận
        $mail->addCC('developer.karlfashion@gmail.com');

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Reply to:  ' . $inqui['supp_type'];
        $mail->Body    = $content;
        $mail->AltBody = 'Reply to:  ' . $inqui['supp_type'];

        $mail->send();

        header('location: index.php?page_layout=supports');
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}

$mailSS = $_SESSION['mail'];
$staffSS = mysqli_fetch_array($conn->query("SELECT * FROM staffs WHERE user_mail = '$mailSS'"));

function CvSizeClothes($size, $type)
{
    switch ($type) {
        case 'US':
            for ($i = 0; $i < 12; $i++) {
                if ($i < 9) {
                    $compare = 2 * ($i + 1);
                } else {
                    $compare = 2 * ($i + 2);
                }
                if ($size === $compare) {
                    return (34 + 2 * $i);
                }
            };
        case 'UK':
            for ($i = 0; $i < 12; $i++) {
                if ($size === 6 + 2 * $i) {
                    return (34 + 2 * $i);
                }
            };
        case '':
            switch ($size) {
                case 'XS':
                    return 34;
                case 'S':
                    return '34,38';
                case 'M':
                    return '40,42';
                case 'L':
                    return '44,46';
                case 'XL':
                    return '48,50';
                case 'XXL':
                    return '50,52';
                case 'XXXL':
                    return '54,56';
            };
    }
}
function CvSizeShoes($size, $type, $sex)
{
    switch ($type) {
        case 'US':
            for ($i = 0; $i < 30; $i++) {
                if ($size == 4 + (0.5 * $i)) {
                    if ($sex === 'w') {
                        if ($i % 2 == 0) {
                            return (35 + $i / 2 - 1) . ',' . (35 + $i / 2);
                        } else {
                            return (35 + intdiv($i, 2));
                        }
                    } else {
                        if ($i % 2 == 0) {
                            return (37 + $i / 2);
                        } else {
                            return (37 + intdiv($i, 2)) . ',' . (38 + intdiv($i, 2));
                        }
                    }
                }
            };
            break;
        case 'UK':
            for ($i = 0; $i < 30; $i++) {
                if ($size === 2 + $i / 2) {
                    if ($sex === 'w') {
                        if ($i % 2 == 0) {
                            return (35 + $i / 2 - 1) . ',' . (35 + $i / 2);
                        } else {
                            return (35 + intdiv($i, 2));
                        }
                    } else {
                        if ($i % 2 == 0) {
                            return (35 + intdiv($i, 2)) . ',' . (36 + intdiv($i, 2));
                        } else {
                            return (36 + intdiv($i, 2));
                        }
                    }
                }
            };
    }
}
function convert_name($str)
{
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);
    $str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", '-', $str);
    $str = preg_replace("/( )/", '-', $str);
    return $str;
}

if (isset($_GET['page_layout'])) {
    $pageLayout = '?page_layout=' . $_GET['page_layout'] . '&';
} else {
    $pageLayout = '?';
}

if (isset($_GET['ntf_id'])) {
    $conn->query("UPDATE notifications SET ntf_read = 1 WHERE ntf_id = '" . $_GET['ntf_id'] . "'");
}

if (isset($_POST['sendchatcontent'])) {
    $iDMess = $_SESSION['messID'];
    $ipMess = mysqli_fetch_array($conn->query("SELECT * FROM messengers WHERE mess_id = $iDMess"))['mess_infor'];
    $contentChat = $_POST['contentchat'];
    $dateChat = date("Y-m-d H:i:s");
    $conn->query("INSERT INTO messengers (mess_infor, mess_content, mess_date, mess_callback, mess_repfor) VALUES ('karlfashion.com', '$contentChat', '$dateChat', $iDMess, '$ipMess')");
    $conn->query("UPDATE messengers SET mess_read = 1 WHERE mess_id = $iDMess");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karl Fashion | Administrator</title>
    <link rel="shortcut icon" href="images/logo/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/animate.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/responsive.css" />
    <script src="js/jquery-3.5.0.min.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
    <script src="ckeditor/ckfinder/ckfinder.js"></script>
</head>

<body>
    <?php
    if (empty($_GET['page_layout']) || (isset($_GET['page_layout'])) && $_GET['page_layout'] != 'print') {
    ?>
        <header>
            <div class="flex-between">
                <div class="flex-between">
                    <div class="flex-start">
                        <div id="nav-sidebar"><i class="fa fa-bars"></i></div>
                        <a class="logo" href="../index.php">KARL<span>FASHION</span></a>
                    </div>
                    <div class="notify-box">
                        <ul class="flex-between">
                            <?php
                            ?>
                            <li class="notify-item items-center" data-toggle="collapse" data-target="#mess-box" data-window="true" data-tip="tip-left">
                                <a href="#" class="notify-icon"><i class="fa fa-envelope"></i><span class="badge teal"><?= $conn->query("SELECT * FROM messengers WHERE mess_callback = 0 AND mess_read = 0")->num_rows ?></span></a>
                                <ul id="mess-box" class="collapse menu-notify">
                                    <div class="notify-top teal">You have <?= $conn->query("SELECT * FROM messengers WHERE mess_callback = 0 AND mess_read = 0")->num_rows ?> new messages</div>
                                    <?php
                                    $notiMessQue = $conn->query("SELECT * FROM messengers WHERE mess_callback = 0 ORDER BY mess_date DESC LIMIT 0, 5");
                                    while ($notiMess = mysqli_fetch_array($notiMessQue)) {
                                    ?>
                                        <li class="menu-sub-item <?php if ($notiMess['mess_read'] == 0) {
                                                                        echo 'bg-light';
                                                                    } ?>">
                                            <a href="index.php<?= $pageLayout ?>mess_infor=<?= $notiMess['mess_id'] ?>%messenger-box#messenger-box" class="items-center">
                                                <?php
                                                $cusQueMess = $conn->query("SELECT * FROM customers WHERE cus_mail = '" . $notiMess['mess_infor'] . "'");
                                                if ($cusQueMess->num_rows > 0) {
                                                    $cusMessInfor =  mysqli_fetch_array($cusQueMess);
                                                    $imgMessTMP = $cusMessInfor['cus_image'];
                                                    if ($imgMessTMP !== '') {
                                                        $imgMess = $imgMessTMP;
                                                    } else {
                                                        $imgMess = 'avatar-default.png';
                                                    }
                                                    $nameMess = $cusMessInfor['cus_name'];
                                                } else {
                                                    $imgMess = 'avatar-default.png';
                                                    $nameMess = $notiMess['mess_infor'];
                                                }
                                                ?>
                                                <span class="photo"><img src="images/avata/<?= $imgMess ?>" alt="" /></span>
                                                <span class="info">
                                                    <span class="user flex-between">
                                                        <span class="from"><?= $nameMess ?></span>
                                                        <span class="time"><?php
                                                                            $timeChat = time() - strtotime($notiMess['mess_date']);
                                                                            if ($timeChat <= 1) {
                                                                                echo 'Just now';
                                                                            } elseif ($timeChat > 1 && $timeChat < 60) {
                                                                                echo ceil($timeChat) . 'secs';
                                                                            } elseif ($timeChat >= 60 && $timeChat < 3600) {
                                                                                echo ceil($timeChat / 60) . 'mins';
                                                                            } elseif ($timeChat >= 3600 && $timeChat < 3600 * 24) {
                                                                                echo ceil($timeChat / 3600) . 'hours';
                                                                            } else {
                                                                                echo ceil($timeChat / 24 / 3600) . 'days';
                                                                            }
                                                                            ?></span>
                                                    </span>
                                                    <span class="message"><?= $notiMess['mess_content'] ?></span>
                                                </span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <a href="index.php?page_layout=messengers">See all messages</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="notify-item items-center" data-toggle="collapse" data-target="#announce-box" data-window="true" data-tip="tip-left">
                                <a href="#" class="notify-icon"><i class="fa fa-bell"></i><span class="badge orange"><?= $conn->query("SELECT * FROM notifications WHERE ntf_read = 0")->num_rows ?></span></a>
                                <ul id="announce-box" class="collapse menu-notify">
                                    <div class="notify-top orange">You have <?= $conn->query("SELECT * FROM notifications WHERE ntf_read = 0")->num_rows ?> new notifications</div>
                                    <?php
                                    $notiQue = $conn->query("SELECT * FROM notifications ORDER BY ntf_date DESC LIMIT 0, 5");
                                    while ($notiRow = mysqli_fetch_array($notiQue)) {
                                        switch ($notiRow['ntf_type']) {
                                            case 'blog':
                                                $iconClass = 'fa-comment';
                                                $arrInfor = explode('%', $notiRow['ntf_infor']);
                                                $contentNoti = 'Have new comment for post "' . $arrInfor[1] . '"';
                                                $href = $arrInfor[0] . '&ntf_id=' . $notiRow['ntf_id'];
                                                $colorClass = 'red';
                                                break;
                                            case 'product':
                                                $iconClass = 'fa-comment';
                                                $arrInfor = explode('%', $notiRow['ntf_infor']);
                                                $contentNoti = 'Have new comment for product "' . $arrInfor[1] . '"';
                                                $href = $arrInfor[0] . '&ntf_id=' . $notiRow['ntf_id'];
                                                $colorClass = 'green';
                                                break;
                                            case 'order':
                                                $iconClass = 'fa-file-invoice-dollar';
                                                $contentNoti = 'Have new order !';
                                                $href = $notiRow['ntf_infor'] . '&ntf_id=' . $notiRow['ntf_id'];
                                                $colorClass = 'blue';
                                                break;
                                            case 'user':
                                                $iconClass = 'fa-user-plus';
                                                $contentNoti = 'Have new customer !';
                                                $href = $notiRow['ntf_infor'] . '&ntf_id=' . $notiRow['ntf_id'];
                                                $colorClass = 'purple';
                                                break;
                                        }
                                    ?>
                                        <li class="menu-sub-item <?php if ($notiRow['ntf_read'] == 0) {
                                                                        echo 'bg-light';
                                                                    } ?>">
                                            <a href="<?= $href ?>" class="items-center">
                                                <span class="icon  <?= $colorClass ?>"><i class="fa <?= $iconClass ?>"></i></span>
                                                <span class="info flex-between">
                                                    <span class="message"><?= $contentNoti ?></span>
                                                    <span class="time"><?php
                                                                        $timeChat = time() - strtotime($notiRow['ntf_date']);
                                                                        if ($timeChat <= 1) {
                                                                            echo 'Just now';
                                                                        } elseif ($timeChat > 1 && $timeChat < 60) {
                                                                            echo ceil($timeChat) . 'secs';
                                                                        } elseif ($timeChat >= 60 && $timeChat < 3600) {
                                                                            echo ceil($timeChat / 60) . 'mins';
                                                                        } elseif ($timeChat >= 3600 && $timeChat < 3600 * 24) {
                                                                            echo ceil($timeChat / 3600) . 'hours';
                                                                        } else {
                                                                            echo ceil($timeChat / 24 / 3600) . 'days';
                                                                        }
                                                                        ?></span>
                                                </span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <a href="?page_layout=notifications">See all notifications</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="flex-between">
                    <div class="search-box" data-toggle="collapse" data-click='false' data-target="#search-form" data-hidden="#btn-rheuseodo" data-window="true">
                        <div id="btn-rheuseodo"><i class="fa fa-search"></i></div>
                        <form id="search-form" class="flex-between collapse" method="post">
                            <input type="search" name="keywords" placeholder="Search" />
                            <button type="submit" name="search"><i class="fa fa-search"></i></button>
                        </form>
                    </div>

                    <div class="profile-area" data-toggle="collapse" data-target="#profile-box" data-window="true" data-tip="tip-right">
                        <div class="flex-between">
                            <?php
                            $mail = $_SESSION['mail'];
                            $user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '$mail'"));
                            $arrname = explode('@', $mail);
                            $name = ucfirst(reset($arrname));
                            ?>
                            <span class="avata"><img src="images/avata/<?php if (!empty($user['user_image'])) {
                                                                            echo $user['user_image'];
                                                                        } else {
                                                                            echo 'avatar-default.png';
                                                                        } ?>" alt="" /></span>
                            <span class="name"><?php if (!empty($user['user_full'])) {
                                                    echo $user['user_full'];
                                                } else {
                                                    echo $name;
                                                } ?></span>
                            <span class="caret"><i class="fa fa-caret-down"></i></span>
                        </div>

                        <ul id="profile-box" class="collapse">
                            <div class="option flex-between">
                                <li><a href="profile.php"><i class="fa fa-medkit"></i><span>Profile</span></a></li>
                                <li><a href="?page_layout=messengers"><i class="fa fa-comments"></i><span>Messengers</span></a></li>
                                <li><a href="?page_layout=notifications"><i class="fa fa-bell"></i><span>Notifications</span></a></li>
                            </div>
                            <li class="logout"><a href="logout.php"><i class="fa fa-key"></i><span>Log out</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
    <?php } ?>
    <main>
        <?php
        if (empty($_GET['page_layout']) || (isset($_GET['page_layout'])) && $_GET['page_layout'] != 'print') {
        ?>
            <aside>
                <ul id="sidebar" class="menu-sidebar">
                    <li class="<?php if (!isset($_GET['page_layout'])) {
                                    echo 'active';
                                } ?>"><a href="index.php"><span><i class="fa fa-tachometer-alt"></i></span>Dashboard</a></li>
                    <li class="<?php if ($_GET['page_layout'] == 'users' || $_GET['page_layout'] == 'staff' || $_GET['page_layout'] == 'edit_staff' || $_GET['page_layout'] == 'add_staff' || $_GET['page_layout'] == 'customer') {
                                    echo 'active';
                                } ?>"><a href="index.php?page_layout=users"><span><i class="fa fa-users"></i></span>Users</a></li>
                    <li class="<?php if ($_GET['page_layout'] == 'products' || $_GET['page_layout'] == 'add_prd' || $_GET['page_layout'] == 'edit_prd') {
                                    echo 'active';
                                } ?>"><a href="index.php?page_layout=products"><span><i class="fa fa-shopping-bag"></i></span>Products</a></li>
                    <li class="<?php if ($_GET['page_layout'] == 'blogs' || $_GET['page_layout'] == 'add_blog' || $_GET['page_layout'] == 'edit_blog') {
                                    echo 'active';
                                } ?>"><a href="index.php?page_layout=blogs"><span><i class="fa fa-newspaper"></i></span>Blogs</a></li>
                    <li class="<?php if ($_GET['page_layout'] == 'catego' || $_GET['page_layout'] == 'add_cat' || $_GET['page_layout'] == 'edit_cat') {
                                    echo 'active';
                                } ?>"><a href="index.php?page_layout=catego"><span><i class="fa fa-folder"></i></span>Categories</a></li>
                    <li class="<?php if ($_GET['page_layout'] == 'oders' || $_GET['page_layout'] == 'bills' || $_GET['page_layout'] == 'print') {
                                    echo 'active';
                                } ?>"><a href="index.php?page_layout=orders"><span><i class="fa fa-file-invoice"></i></span>Orders</a></li>
                    <li class="<?php if ($_GET['page_layout'] == 'comments' || $_GET['page_layout'] == 'comm_prd' || $_GET['page_layout'] == 'comm_blog') {
                                    echo 'active';
                                } ?>"><a href="index.php?page_layout=comments"><span><i class="fa fa-comments"></i></span>Comments</a></li>
                    <li class="<?php if ($_GET['page_layout'] == 'supports') {
                                    echo 'active';
                                } ?>"><a href="index.php?page_layout=supports"><span><i class="fa fa-question-circle"></i></span>Supports</a></li>
                    <li><a href="#"><span><i class="fa fa-cog"></i></span>Configuration</a></li>
                </ul>
            </aside>
        <?php } ?>
        <div id="wrapper-area">
            <?php
            if (isset($_GET['page_layout'])) {
                switch ($_GET['page_layout']) {
                    case 'users':
                        include_once('modules/users/users.php');
                        break;
                    case 'staff':
                        include_once('modules/users/staffs.php');
                        break;
                    case 'add_staff':
                        include_once('modules/users/add_staff.php');
                        break;
                    case 'edit_staff':
                        include_once('modules/users/edit_staff.php');
                        break;
                    case 'del_user':
                        include_once('modules/users/del_user.php');
                        break;
                    case 'customer':
                        include_once('modules/users/customer.php');
                        break;
                    case 'products':
                        include_once('modules/product/products.php');
                        break;
                    case 'add_prd':
                        include_once('modules/product/add_product.php');
                        break;
                    case 'edit_prd':
                        include_once('modules/product/edit_product.php');
                        break;
                    case 'del_prd':
                        include_once('modules/product/del_product.php');
                        break;
                    case 'blogs':
                        include_once('modules/blogs/blogs.php');
                        break;
                    case 'add_blog':
                        include_once('modules/blogs/add_blog.php');
                        break;
                    case 'edit_blog':
                        include_once('modules/blogs/edit_blog.php');
                        break;
                    case 'del_blog':
                        include_once('modules/blogs/del_blog.php');
                        break;
                    case 'catego':
                        include_once('modules/category/categories.php');
                        break;
                    case 'add_cat':
                        include_once('modules/category/add_cat.php');
                        break;
                    case 'edit_cat':
                        include_once('modules/category/edit_cat.php');
                        break;
                    case 'del_cat':
                        include_once('modules/category/del_cat.php');
                        break;
                    case 'orders':
                        include_once('modules/orders/orders.php');
                        break;
                    case 'bills':
                        include_once('modules/orders/bills.php');
                        break;
                    case 'print':
                        include_once('modules/orders/print.php');
                        break;
                    case 'comments':
                        include_once('modules/comments/comments.php');
                        break;
                    case 'comm_prd':
                        include_once('modules/comments/product.php');
                        break;
                    case 'comm_blog':
                        include_once('modules/comments/blog.php');
                        break;
                    case 'comm_details':
                        include_once('modules/comments/details.php');
                        break;
                    case 'supports':
                        include_once('modules/supports/supports.php');
                        break;
                    case 'messengers':
                        include_once('modules/messengers/messengers.php');
                        break;
                    case 'notifications':
                        include_once('modules/messengers/notifications.php');
                        break;
                }
            } else {
                include_once('modules/dashboard/dashboard.php');
            }
            ?>
        </div>
    </main>
    <?php
    if (empty($_GET['page_layout']) || (isset($_GET['page_layout'])) && $_GET['page_layout'] != 'print') {
    ?>
        <footer>
            <p id="footer-area">&copy; Karl Fashion by Ba Truong</p>
        </footer>
    <?php } ?>
    <!-- Messenger -->
    <?php
    if (isset($_SESSION['messID'])) {
        $iDMess = $_SESSION['messID'];
        $ipMess = mysqli_fetch_array($conn->query("SELECT * FROM messengers WHERE mess_id = $iDMess"))['mess_infor'];
        $cusQueMess = $conn->query("SELECT * FROM customers WHERE cus_mail = '$ipMess'");
        if ($cusQueMess->num_rows > 0) {
            $nameMess = mysqli_fetch_array($cusQueMess)['cus_name'];
        } else {
            $nameMess = $ipMess;
        }
    ?>
        <section id="messenger-box" class="<?php if (!isset($_SESSION['messBoxID']) || $_GET['page_layout'] === 'messengers') {
                                                echo 'collapse';
                                            } ?>">
            <div class="messenger-area">
                <span id="block-button"><i class="fa fa-angle-down"></i></span>
                <span class="notify-mess">CHAT WITH CUSTOMER</span>
                <form method="post" class="inbox-area">
                    <div class="inbox-header">
                        <span><i class="fa fa-user mr-10"></i> <?= $nameMess ?></span>
                    </div>
                    <div class="inbox-show">
                        <?php
                        $chatque = $conn->query("SELECT * FROM messengers WHERE mess_repfor = '$ipMess' ORDER BY mess_date ASC LIMIT 0, 6");
                        while ($chatR = mysqli_fetch_array($chatque)) {
                            if ($chatR['mess_infor'] === 'karlfashion.com') {
                                $imgMess = 'karl-logo.png';
                                $typeClass = 'customers';
                            } else {
                                $cusQueMess = $conn->query("SELECT * FROM customers WHERE cus_mail = '$ipMess'");
                                if ($cusQueMess->num_rows > 0) {
                                    $cusMessInfor =  mysqli_fetch_array($cusQueMess);
                                    $imgMessTMP = $cusMessInfor['cus_image'];
                                    if ($imgMessTMP !== '') {
                                        $imgMess = $imgMessTMP;
                                    } else {
                                        $imgMess = 'avatar-default.png';
                                    }
                                    $nameMess = $cusMessInfor['cus_name'];
                                } else {
                                    $imgMess = 'avatar-default.png';
                                    $nameMess = $chatR['mess_infor'];
                                }
                                $typeClass = 'shopadmin';
                            }
                        ?>
                            <div class="inbox-item <?= $typeClass ?> flex-between items-center">
                                <div class="item-image"><img src="images/avata/<?= $imgMess ?>" alt="" /></div>
                                <div class="item-infor">
                                    <p><?= $chatR['mess_content'] ?></p>
                                    <span class="collapse"><?= date('h:i a d-M', strtotime($chatR['mess_date'])) ?></span>
                                </div>
                            </div>
                        <?php
                        } ?>
                    </div>
                    <div class="inbox-add">
                        <textarea name="contentchat"></textarea>
                        <button type="submit" name="sendchatcontent">SEND</button>
                    </div>
                </form>
            </div>
        </section>
    <?php } ?>
    <script src="js/active.js"></script>
    <script>
        Validator({
            form: '#add_staff',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            viewSelector: '#ava-preview',
            rules: [
                Validator.isRequired('#staff_full', 'Please enter your email !'),
                Validator.isEmail('#staff_mail', 'Invaid email !'),
                Validator.isRequired('[name=gender]', 'Please chose your gender !'),
                Validator.isPhone('#staff_tel', 'Incorrect phone your number !'),
                Validator.isRequired('#staff_level', 'Please select permision user !'),
                Validator.isRequired('#staff_birdth', 'Please select your birdth day !'),
                Validator.isRequired('#staff_add', 'Please select your address !'),
                Validator.fileRequired('#staff_avata', 'No choose file !'),
                Validator.fileType('#staff_avata', ['image/png', 'image/jpg', 'image/jpeg'], 'File is incorrect format !'),
                Validator.fileSize('#staff_avata', 10240000, 'File too big !'),
                Validator.minLength('#staff_pass', 6, 'Please enter at least 6 characters !'),
                Validator.compareValues('#staff_re_pass', function getCompareValue() {
                    return (document.querySelector('#add_staff #staff_pass').value);
                }, 'Re-entered password is incorrect !')
            ]
        })
        Validator({
            form: '#edit_staff',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            viewSelector: '#ava-preview',
            rules: [
                Validator.isRequired('#staff_full', 'Please enter your email !'),
                Validator.isEmail('#staff_mail', 'Invaid email !'),
                Validator.isRequired('[name=gender]', 'Please chose your gender !'),
                Validator.isPhone('#staff_tel', 'Incorrect phone your number !'),
                Validator.isRequired('#staff_level', 'Please select permision user !'),
                Validator.isRequired('#staff_birdth', 'Please select your birdth day !'),
                Validator.isRequired('#staff_add', 'Please select your address !'),
                Validator.fileRequired('#staff_avata', 'No choose file !'),
                Validator.fileType('#staff_avata', ['image/png', 'image/jpg', 'image/jpeg'], 'File is incorrect format !'),
                Validator.fileSize('#staff_avata', 10240000, 'File too big !')
            ]
        })
        Validator({
            form: '#add_prd',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            viewSelector: '.file_preview',
            rules: [
                Validator.isRequired('#prd_name', 'Please enter product name !'),
                Validator.isRequired('#prd_price', 'Please enter product price !'),
                Validator.isRequired('#prd_color', 'Please enter product colors !'),
                Validator.isRequired('#prd_size', 'Please enter product sizes !'),
                Validator.isRequired('#prd_quantity', 'Please enter product quantities !'),
                Validator.isRequired('#prd_discount', 'Please enter product discount !'),
                Validator.isRequired('#cat_id', 'Please enter product categories !'),
                Validator.isRequired('#prd_promotion', 'Please enter product promotion !'),
                Validator.fileRequired('input[type=file]', 'No choose file !'),
                Validator.fileType('input[type=file]', ['image/png', 'image/jpg', 'image/jpeg'], 'File is incorrect format !'),
                Validator.fileSize('input[type=file]', 10240000, 'File too big !')
            ]
        })
        Validator({
            form: '#edit_prd',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            viewSelector: '.file_preview',
            rules: [
                Validator.isRequired('#prd_name', 'Please enter product name !'),
                Validator.isRequired('#prd_price', 'Please enter product price !'),
                Validator.isRequired('#prd_color', 'Please enter product colors !'),
                Validator.isRequired('#prd_size', 'Please enter product sizes !'),
                Validator.isRequired('#prd_quantity', 'Please enter product quantities !'),
                Validator.isRequired('#prd_discount', 'Please enter product discount !'),
                Validator.isRequired('#cat_id', 'Please enter product categories !'),
                Validator.isRequired('#prd_promotion', 'Please enter product promotion !'),
                Validator.fileType('input[type=file]', ['image/png', 'image/jpg', 'image/jpeg'], 'File is incorrect format !'),
                Validator.fileSize('input[type=file]', 10240000, 'File too big !')
            ]
        })
        Validator({
            form: '#add_blog',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            viewSelector: '.file_preview',
            rules: [
                Validator.isRequired('#blog_title', 'Please enter blog title !'),
                Validator.isRequired('#blog_topic', 'Please enter blog topic !'),
                Validator.isRequired('#cat_id', 'Please enter product categories !'),
                Validator.isRequired('#blog_authors', 'Please enter blog authors !'),
                Validator.fileRequired('#blog_image', 'No choose file !'),
                Validator.fileType('#blog_image', ['image/png', 'image/jpg', 'image/jpeg'], 'File is incorrect format !'),
                Validator.fileSize('#blog_image', 10240000, 'File too big !')
            ]
        })
        Validator({
            form: '#edit_blog',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            viewSelector: '.file_preview',
            rules: [
                Validator.isRequired('#blog_title', 'Please enter blog title !'),
                Validator.isRequired('#blog_topic', 'Please enter blog topic !'),
                Validator.isRequired('#blog_details', 'Please enter blog content !'),
                Validator.isRequired('#cat_id', 'Please enter product categories !'),
                Validator.isRequired('#blog_authors', 'Please enter blog authors !'),
                Validator.fileType('#blog_image', ['image/png', 'image/jpg', 'image/jpeg'], 'File is incorrect format !'),
                Validator.fileSize('#blog_image', 10240000, 'File too big !')
            ]
        })
        Validator({
            form: '#edit_cat',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            rules: [
                Validator.isRequired('#cat_name', 'Please enter name category !'),
                Validator.isRequired('#cat_call', 'Please choose type category !'),
                Validator.isRequired('#cat_type', 'Please choose type category !')
            ]
        })
        Validator({
            form: '#add_cat',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            rules: [
                Validator.isRequired('#cat_name', 'Please enter name category !'),
                Validator.isRequired('#cat_call', 'Please choose type category !'),
                Validator.isRequired('#cat_type', 'Please choose type category !')
            ]
        })
    </script>
    <?php ob_flush(); ?>
</body>

</html>