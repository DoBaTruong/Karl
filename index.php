<?php
session_start();
ob_start();
define('SECURITY', True);
include_once('admin/config/connect.php');
date_default_timezone_set('Asia/Bangkok');

include "PHPMailer-master/src/PHPMailer.php";
include "PHPMailer-master/src/Exception.php";
include "PHPMailer-master/src/OAuth.php";
include "PHPMailer-master/src/POP3.php";
include "PHPMailer-master/src/SMTP.php";
if (isset($_SESSION['mail'])) {
    $mailSS = $_SESSION['mail'];
    $ssUser = mysqli_fetch_array($conn->query("SELECT * FROM customers WHERE cus_mail = '$mailSS'"));
    $cusId = $ssUser['cus_id'];
    $ip = $_SESSION['mail'];
} else {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
}
$checkIPQue = $conn->query("SELECT * FROM visitors WHERE vis_ip = '$ip'");
if ($checkIPQue->num_rows == 0) {
    $dateVis = date('Y-m-d H:i:s');
    $conn->query("INSERT INTO visitors (vis_ip, vis_date) VALUES ('$ip', '$dateVis')");
}

function checkColor($hsl)
{
    if ($hsl[2] === 100) {
        return 'white';
    } elseif ($hsl[2] <= 20) {
        return 'dark';
    } else {
        if ($hsl[1] <= 20) {
            return 'gray';
        } else {
            if ($hsl[0] < 30 || $hsl[0] > 340) {
                return 'red';
            } elseif ($hsl[0] >= 30 && $hsl[0] <= 70) {
                return 'yellow';
            } elseif ($hsl[0] > 70 && $hsl[0] <= 160) {
                return 'green';
            } elseif ($hsl[0] > 160 && $hsl[0] <= 270) {
                return 'blue';
            } elseif ($hsl[0] > 270 && $hsl[0] <= 340) {
                return 'purple';
            }
        }
    }
}
function HexToHSL($hex)
{
    list($r, $g, $b) = sscanf($hex, '#%02x%02x%02x');
    $red = $r / 255;
    $green = $g / 255;
    $blue = $b / 255;
    $cmin = min($red, $green, $blue);
    $cmax = max($red, $green, $blue);
    $delta = $cmax - $cmin;
    $lightness = ($cmax + $cmin) / 2;

    if ($delta == 0) {
        $hue = 0;
        $saturation = 0;
    } elseif ($delta !== 0) {
        $saturation = $lightness >= 2 ? $delta / 2 * $lightness : $delta / (2 - 2 * $lightness);
        if ($cmax === $red) {
            $hue = (($green - $blue) / $delta) % 6;
        } elseif ($cmax === $green) {
            $hue = ($blue - $red) / $delta + 2;
        } else {
            $hue = ($red - $green) / $delta + 4;
        }
    }

    $hue = round($hue * 60);
    if ($hue < 0) {
        $hue += 360;
    }

    $lightness = round($lightness * 100);
    $saturation = round($saturation * 100);

    return [$hue, $saturation, $lightness];
}
function GetIDBlog($id, $connect)
{
    $checkFuncQue = $connect->query("SELECT * FROM blog_comments WHERE comm_id = $id");
    while ($checkFuncQue->num_rows > 0) {
        $checkFunc = mysqli_fetch_array($checkFuncQue);
        if ($checkFunc['comm_call'] == 0) {
            return $checkFunc['comm_id'];
            break;
        }
        $idTmp = $checkFunc['comm_repfor'];
        $checkFuncQue = $connect->query("SELECT * FROM blog_comments WHERE comm_id = $idTmp");
    }
};
function GetIDprd($id, $connect)
{
    $checkFuncQue = $connect->query("SELECT * FROM prd_comments WHERE comm_id = $id");
    while ($checkFuncQue->num_rows > 0) {
        $checkFunc = mysqli_fetch_array($checkFuncQue);
        if ($checkFunc['comm_call'] == 0) {
            return $checkFunc['comm_id'];
            break;
        }
        $idTmp = $checkFunc['comm_repfor'];
        $checkFuncQue = $connect->query("SELECT * FROM prd_comments WHERE comm_id = $idTmp");
    }
};

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

if (isset($_POST['addcart'])) {
    $prd_id = $_POST['prd_id'];
    if (!empty($_POST['quantity'])) {
        $qtt = $_POST['quantity'];
    } else {
        $qtt = 1;
    }
    if (isset($_SESSION['cart'][$prd_id])) {
        $_SESSION['cart'][$prd_id] += $qtt;
    } else {
        $_SESSION['cart'][$prd_id] = $qtt;
    }
    if (!empty($_POST['prd_size'])) {
        $sizeCart = implode(',', $_POST['prd_size']);
    } else {
        $sizeCart = '';
    }
    if (!empty($_POST['prd_color'])) {
        $colorCart = implode(',', $_POST['prd_color']);
    } else {
        $colorCart = '';
    }
    if (!empty($_SESSION['cartInfor'][$prd_id])) {
        $_SESSION['cartInfor'][$prd_id] = $sizeCart . ';' . $colorCart;
    } else {
        $_SESSION['cartInfor'][$prd_id] = $sizeCart . ';' . $colorCart;
    }
}
if (isset($_GET['page_layout']) && $_GET['page_layout'] !== 'compare') {
    unset($_SESSION['add_compare_success']);
}

if (isset($_GET['page_layout']) && $_GET['page_layout'] !== 'wishlist') {
    unset($_SESSION['add_success']);
}

if (isset($_POST['sendchatcontent'])) {
    $contentChat = $_POST['contentchat'];
    $dateChat = date("Y-m-d H:i:s");
    $conn->query("INSERT INTO messengers (mess_infor, mess_content, mess_repfor, mess_date) VALUES ('$ip', '$contentChat', '$ip', '$dateChat')");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karl Fashion | Home</title>
    <link rel="shortcut icon" href="images/logo/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="admin/css/font-awesome.min.css" />
    <link rel="stylesheet" href="admin/css/animate.css" />
    <link rel="stylesheet" href="css/core.css" />
    <link rel="stylesheet" href="css/responsive.css" />
    <script src="admin/js/jquery-3.5.0.min.js"></script>
    <script src="admin/ckeditor/ckeditor.js"></script>
    <script src="admin/ckeditor/ckfinder/ckfinder.js"></script>
    <script>
        function format(n) {
            n = n.toString()
            while (true) {
                var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1.$2$3')
                if (n == n2) break
                n = n2
            }
            return n
        }
    </script>
</head>

<body>
    <!-- ****** Side Menu Right Area Start ****** -->
    <div id="side-menu-area" class="collapse">
        <!-- Button Close Side Menu -->
        <div id="sideMenuClose"><i class="fa fa-times"></i></div>
        <!-- Side Navigation -->
        <?php include_once('modules/sidebars/categories.php') ?>
    </div>
    <!-- ****** Side Menu Right Area End ****** -->

    <div id="wrapper">
        <!-- ****** Header Area Start ****** -->
        <header>
            <div class="container">
                <!-- Top Header Area -->
                <div class="top-header-area items-center">
                    <div class="row flex-end">
                        <div class="col-lg-7 col-sm-12 col-md-12 flex-between">
                            <!-- Top Header Logo Area -->
                            <div class="top-logo">
                                <a href="#"><img width="170" src="images/logo/logo.png" alt="Logo Image" /></a>
                            </div>
                            <!-- Cart & Menu Area -->
                            <div class="top-cart-menu flex-between items-center">
                                <!-- Cart Area -->
                                <?php include_once('modules/cart/cart_notify.php') ?>
                                <div id="sideMenuOpen"><i class="fa fa-bars"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Main Header Area -->
                <div class="main-header-area">
                    <div class="row flex-between items-center">
                        <!-- Header Social Area -->
                        <?php include_once('modules/socials/share_socials.php') ?>

                        <!-- Menu Area -->
                        <?php include_once('modules/menu/headmenu.php') ?>
                        <!-- Help Line -->
                        <div class="help-line">
                            <a href="tel:+346573556778"><i class="fa fa-headset"></i> +34 657 3556 778</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- ****** Header Area End ****** -->

        <!-- ****** Top Discount Area Start ****** -->
        <?php
        if (!isset($_GET['page_layout']) || $_GET['page_layout'] != 'policy' && $_GET['page_layout'] != 'term' && $_GET['page_layout'] != 'faqs' && $_GET['page_layout'] != 'contact' && $_GET['page_layout'] != 'about' && $_GET['page_layout'] != 'loggis') {
            include_once('modules/banner/top_discount.php');
        }
        ?>
        <!-- ****** Top Discount Area End ****** -->
        <?php
        if (isset($_GET['page_layout'])) {
            switch ($_GET['page_layout']) {
                case 'prd_details':
                    include_once('modules/product/prd_details.php');
                    break;
                case 'acPrd':
                    include_once('modules/product/action.php');
                    break;
                case 'shop':
                    include_once('modules/product/shop.php');
                    break;
                case 'wishlist':
                    include_once('modules/product/wishlist.php');
                    break;
                case 'compare':
                    include_once('modules/product/compare.php');
                    break;
                case 'promotion':
                    include_once('modules/products/promotion.php');
                    break;
                case 'blog':
                    include_once('modules/blog/blog.php');
                    break;
                case 'blog_details':
                    include_once('modules/blog/blog_details.php');
                    break;
                case 'acBl':
                    include_once('modules/blog/actionBlog.php');
                    break;
                case 'cart':
                    include_once('modules/cart/cart.php');
                    break;
                case 'addcart':
                    include_once('modules/cart/add_cart.php');
                    break;
                case 'checkout':
                    include_once('modules/cart/checkout.php');
                    break;
                case 'myaccount':
                    include_once('modules/account/myaccount.php');
                    break;
                case 'faqs':
                    include_once('modules/about/faq.php');
                    break;
                case 'contact':
                    include_once('modules/about/contact.php');
                    break;
                case 'about':
                    include_once('modules/about/aboutus.php');
                    break;
                case 'term':
                    include_once('modules/policyTerm/term.php');
                    break;
                case 'policy':
                    include_once('modules/policyTerm/policy.php');
                    break;
                case 'logreg':
                    $infor = $_GET['logInfor'];
                    header('location: admin/index.php?logfront=' . $infor);
            }
        } else {
        ?>
            <!-- ****** Welcome Slides Area Start ****** -->
            <?php include_once('modules/slider/welcome-slider.php') ?>
            <!-- ****** Welcome Slides Area End ****** -->

            <!-- ****** Top Catagory Area Start ****** -->
            <?php include_once('modules/banner/top_categories.php') ?>
            <!-- ****** Top Catagory Area End ****** -->

            <!-- ****** New Arrivals Area Start ****** -->
            <section id="new-arrivals" class="new-arrivals-area">
                <div class="container">
                    <h2>New Arrivals</h2>
                    <div class="shop-menu">
                        <?php include_once('modules/menu/arrival-menu.php') ?>
                    </div>
                    <!-- ****** Quick View Modal Area Start ****** -->
                    <?php
                    include_once('modules/product/new_arrials.php');
                    include_once('modules/product/quickview.php');
                    ?>
                    <!-- ****** Quick View Modal Area End ****** -->
                </div>
            </section>
            <!-- ****** New Arrivals Area End ****** -->
            <!-- ****** Offer Area Start ****** -->
            <div class="offer-area">
                <div class="container">
                    <div class="row flex-end">
                        <?php include_once('modules/product/offer_product.php') ?>
                    </div>
                </div>
            </div>
            <!-- ****** Offer Area End ****** -->

            <!-- ****** Popular Brands Area Start ****** -->
            <?php include_once('modules/slider/testimonial-slider.php') ?>
            <!-- ****** Popular Brands Area End ****** -->
        <?php } ?>
        <!-- ****** Footer Area Start ****** -->
        <footer class="footer-area">
            <div class="container">
                <div class="row">
                    <!-- Single Footer Area Start -->
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="single-footer-area">
                            <div class="footer-logo flex-center">
                                <img src="images/logo/logo.png" alt="" />
                            </div>
                            <div class="copywrite-text">
                                <p class="flex-between">
                                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                    Copyright &copy;<script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    All rights reserved | Made with BaTruong &amp; distributed by BaTruong
                                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Single Footer Area Start -->
                    <?php include_once('modules/menu/footer_menu.php') ?>
                    <!-- Single Footer Area Start -->
                    <div class="col-md-12 col-sm-12 col-lg-5">
                        <div class="single-footer-area">
                            <div class="footer-heading">
                                <h6>Subscribe to our newsletter</h6>
                            </div>
                            <div class="subscribtion-form">
                                <form action="#" method="post" class="flex-between">
                                    <input type="email" name="mail" class="mail" placeholder="Your email here">
                                    <button type="submit" class="submit">Subscribe</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer Bottom Area Start -->
                <?php include_once('modules/socials/footer_social.php') ?>
            </div>
        </footer>
        <!-- ****** Footer Area End ****** -->
    </div>

    <!-- Messenger -->
    <section id="messenger-box">
        <div class="messenger-area">
            <span id="block-button"><i class="fa fa-angle-left"></i></span>
            <span class="notify-mess">CHAT WITH SHOP</span>
            <form method="post" class="inbox-area">
                <div class="inbox-header">
                    <span><i class="fa fa-headset"></i> KARL FASHION: Greetings to you! </span>
                </div>
                <div class="inbox-show">
                    <?php
                    $chatque = $conn->query("SELECT * FROM messengers WHERE mess_repfor = '$ip' ORDER BY mess_date ASC LIMIT 0, 6");
                    while ($chatR = mysqli_fetch_array($chatque)) {
                        if ($chatR['mess_infor'] === 'karlfashion.com') {
                            $imageChat = 'karl-logo.png';
                            $typeClass = 'shopadmin';
                        } else {
                            $imageChat = 'avatar-default.png';
                            $typeClass = 'customers';
                        }
                    ?>
                        <div class="inbox-item <?= $typeClass ?> flex-between items-center">
                            <div class="item-image"><img src="admin/images/avata/<?= $imageChat ?>" alt="" /></div>
                            <div class="item-infor">
                                <p><?= $chatR['mess_content'] ?></p>
                                <span class="collapse"><?= date('h:i a d-M', strtotime($chatR['mess_date'])) ?></span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="inbox-add">
                    <textarea name="contentchat" cols="30" rows="10"></textarea>
                    <button type="submit" name="sendchatcontent">SEND</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Back Home -->
    <div class="items-center home-back">
        <div id="home-back"><i class="fa fa-angle-up"></i></div>
    </div>
    <script src="admin/js/jquery-3.5.0.min.js"></script>
    <script src="admin/js/active.js"></script>
    <script src="js/plugin.js"></script>
    <script>
        Validator({
            form: '#contactQuest',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            rules: [
                Validator.isRequired('#name-ques', 'Please enter your name !'),
                Validator.isEmail('#mail-ques', 'Invaid email !'),
                Validator.isRequired('#type-ques', 'Please enter type inquiry !'),
                Validator.isRequired('#details-ques', 'Please enter content inquiry !')
            ]
        });

        let formElCus = document.getElementById('checkoutInfor');
        if (formElCus) {
            let grPasFormCus = formElCus.querySelector('#creatElPass');
            if (grPasFormCus) {
                grPasFormCus.onmouseover = function() {
                    let inputEls = grPasFormCus.querySelectorAll('input[name]');
                    if (inputEls) {
                        Array.from(inputEls).forEach(function(inpEl) {
                            inpEl.onblur = function() {
                                if (inpEl.id === 'pass-order') {
                                    if (inpEl.value.length < 6) {
                                        getParent(inpEl, '.form-group').classList.add('invalid');
                                        getParent(inpEl, '.form-group').querySelector('.form-message').innerHTML = 'Please enter at least 6 characters !';
                                    }
                                }
                                if (inpEl.id === 'rePass-order') {
                                    if (inpEl.value !== grPasFormCus.querySelector('#pass-order').value) {
                                        getParent(inpEl, '.form-group').classList.add('invalid');
                                        getParent(inpEl, '.form-group').querySelector('.form-message').innerHTML = 'Entered password is incorrect !';
                                    }
                                }
                                if (inpEl.id === 'pass-oldest') {
                                    if (MD5(inpEl.value) !== grPasFormCus.getAttribute('data-pass')) {
                                        getParent(inpEl, '.form-group').classList.add('invalid');
                                        getParent(inpEl, '.form-group').querySelector('.form-message').innerHTML = 'Entered password is incorrect !';
                                    } else {
                                        Array.from(grPasFormCus.querySelectorAll('input[name]:not(#pass-oldest)')).forEach(function(inP) {
                                            inP.disabled = false;
                                        })
                                    }
                                }
                            }
                            inpEl.oninput = function() {
                                getParent(inpEl, '.form-group').classList.remove('invalid');
                                getParent(inpEl, '.form-group').querySelector('.form-message').innerHTML = ''
                            }
                            inpEl.onfocus = function() {
                                getParent(inpEl, '.form-group').classList.remove('invalid');
                                getParent(inpEl, '.form-group').querySelector('.form-message').innerHTML = ''
                            }
                        })
                    }
                    var eyeEls = grPasFormCus.querySelectorAll('.eye-show');
                    if (eyeEls) {
                        Array.from(eyeEls).forEach(function(eye) {
                            eye.onclick = function() {
                                var inputEl = eye.parentElement.querySelector('input');
                                if (inputEl) {
                                    if (inputEl.type.toLowerCase() === 'password') {
                                        inputEl.type = 'text';
                                        eye.querySelector('i').classList.remove('fa-eye-slash');
                                        eye.querySelector('i').classList.add('fa-eye');
                                    } else {
                                        inputEl.type = 'password';
                                        eye.querySelector('i').classList.add('fa-eye-slash');
                                        eye.querySelector('i').classList.remove('fa-eye');
                                    }
                                }
                            }
                        })
                    }
                }
            }
        }
        Validator({
            form: '#checkoutInfor',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            rules: [
                Validator.isRequired('#full_name', 'Please enter your full name !'),
                Validator.isEmail('#email_address', 'Invaid email !'),
                Validator.isRequired('#cus_address', 'Please enter your address !'),
                Validator.isRequired('#phone_number', 'Please enter your phone !'),
                Validator.isRequired('input[name=payMethod]', 'Please select your method payment !'),
                Validator.minLength('#pass-order', 6, 'Please enter at least 6 characters !'),
                Validator.compareValues('#rePass-order', function getCompareValue() {
                    return (document.querySelector('#checkoutInfor #pass-order').value);
                }, 'Re-entered password is incorrect !'),
                Validator.compareValues('#pass-oldest', function getCompareValue() {
                    return (document.querySelector('#checkoutInfor #creatElPass').getAttribute('data-pass'));
                }, 'Entered password is incorrect !')
            ],
            onSubmit: function(data) {
                var formCusEl = document.getElementById('checkoutInfor');
                if (data.payMethod == 1) {
                    if (formCusEl) {
                        var agreeTermEl = formCusEl.querySelector('input[name=agreeTerm]:checked');
                        if (agreeTermEl) {
                            document.getElementById('checkoutInfor').submit();
                        } else {
                            var labelTermEl = formCusEl.querySelector('input[name=agreeTerm]').parentElement.querySelector('label');
                            labelTermEl.style.color = 'red';
                            labelTermEl.querySelector('span').style.borderWidth = '5px';
                        }
                    }
                }
            }
        })
        Validator({
            form: '#profileofform',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            rules: [
                Validator.minLength('#pass-new', 6, 'Please enter at least 6 characters !'),
                Validator.compareValues('#re-pass-new', function getCompareValue() {
                    return (document.querySelector('#profileofform #pass-new').value);
                }, 'Re-entered password is incorrect !'),
                Validator.compareValues('#pass-oldest', function getCompareValue() {
                    return ('<?= $ssUser['cus_pass'] ?>');
                }, 'Entered password is incorrect !')
            ]
        })
    </script>
    </script>
    <?php ob_flush(); ?>
</body>

</html>