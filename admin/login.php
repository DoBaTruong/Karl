<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
ob_start();

include "PHPMailer-master/src/PHPMailer.php";
include "PHPMailer-master/src/Exception.php";
include "PHPMailer-master/src/OAuth.php";
include "PHPMailer-master/src/POP3.php";
include "PHPMailer-master/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_SESSION['code_time'])) {
    $_SESSION['code_timeout'] = 60 - time() + $_SESSION['code_time'];
    $timesub = time() - $_SESSION['code_time'];
    if ($_SESSION['code_timeout'] <= 0) {
        unset($_SESSION['code_time']);
        unset($_SESSION['code_timeout']);
        unset($_SESSION['security_code']);
    }
}

if (isset($_GET['logfront'])) {
    $subPage = '?logfront=' . $_GET['logfront'];
    $_SESSION['logfront'] = $subPage;
} else {
    $subPage = '';
}

if (isset($_POST['email-forgot'])) {
    $check = $_POST['email-forgot'];
    if (isset($_GET['logfront'])) {
        $checkMail = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM customers WHERE cus_mail = '$check'"));
        $numMail = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM customers WHERE cus_mail = '$check'"));
    } else {
        $checkMail = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '$check'"));
        $numMail = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '$check'"));
    }

    $str_body = '';

    if ($numMail > 0) {
        $email = $_POST['email-forgot'];
        $_SESSION['forgot_mail'] = $email;
        $md5_hash = md5(rand(0, 999));
        $security_code = substr($md5_hash, 15, 6);
        $_SESSION['security_code'] = $security_code;
        if (isset($_GET['logfront'])) {
            $name = $checkMail['cus_name'];
            $href = 'https://karlfashion.com/admin/index.php?login_page=reset-pass&logfront='.$_GET['logfront'];
        } else {
            $name = $checkMail['user_full'];
            $href = 'https://karlfashion.com/admin/index.php?login_page=reset-pass';
        }

        $str_body .= '<div style="height: max-content !important; position: relative;">
                            <div style="border-bottom: 2px solid black; padding: 7px 0;"><h3 style="color: blue;">KARL FASHION SHOP</h3></div>
                            <div style="margin: 5px 0;">Dear ' . $name . ',' . '</div>
                            <div>We have received your request to reset your login password.</div>
                            <div>Please enter the password reset code:</div>
                            <span style="width: max-content; display: block; margin: 15px 0; padding: 10px 20px; text-align: center; border: 1px solid blue; border-radius: 5px; background-color: #d4d6d9;"> ' . $security_code . '</span>
                            <div style="margin-top: 5px;">Also you can change your password directly.</div><br/>
                            <a style="padding: 10px 30px; text-decoration: none; border-radius: 5px; color: white; background-color: blue; text-align: center;" href="' . $href . '">Reset password</a>
                        </div>';
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
            $mail->addAddress($email);               // Gửi mail tới mail người nhận
            $mail->addCC('developer.karlfashion@gmail.com');

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Request a password reset ';
            $mail->Body    = $str_body;
            $mail->AltBody = 'Request a password reset !';

            $mail->send();
            $_SESSION['code_time'] = time();
            if (isset($_GET['logfront'])) {
                header('location: index.php' . $subPage . '&login_page=check-code');
            } else {
                header('location: index.php?login_page=check-code');
            }
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    } else {
        $error = '<div class="alert-danger">Email does not exist !</div>';
    }
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
</head>

<body>
    <div class="log-res-area">
        <?php
        if (isset($_GET['login_page'])) {
            switch ($_GET['login_page']) {
                case 'send-mail':
                    include_once('logpage/mailcheck.php');
                    break;
                case 'check-code':
                    include_once('logpage/codecheck.php');
                    break;
                case 'reset-pass':
                    include_once('logpage/resetpass.php');
                    break;
            }
        } else {
            include_once('logpage/signup.php');
        }
        ?>
    </div>
    <script src="js/active.js"></script>
    <script>
        Validator({
            form: '#login',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            rules: [
                Validator.isRequired('#email-log', 'Please enter your email !'),
                Validator.isEmail('#email-log', 'Invaid email !'),
                Validator.minLength('#pass-log', 6, 'Please enter at least 6 characters !')
            ]
        })
        Validator({
            form: '#register',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            rules: [
                Validator.isRequired('#fullname-regis', 'Please enter your full name !'),
                Validator.isRequired('#email-regis', 'Please enter your email !'),
                Validator.isEmail('#email-regis', 'Invaid email !'),
                Validator.minLength('#pass-regis', 6, 'Please enter at least 6 characters !'),
                Validator.isRequired('#re-pass-regis', 'Please re-entered your password !'),
                Validator.isRequired('[name=agree]'),
                Validator.compareValues('#re-pass-regis', function getCompareValue() {
                    return (document.querySelector('#register #pass-regis').value);
                }, 'Re-entered password is incorrect !')
            ]
        })
        Validator({
            form: '#forgot-pass',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            rules: [
                Validator.isRequired('#email-forgot', 'Please enter your email !'),
                Validator.isEmail('#email-forgot', 'Invaid email !')
            ]
        })
        Validator({
            form: '#confirm-code',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            rules: [
                Validator.isRequired('#code-value', 'Please enter your code vertification !')
            ]
        })
        Validator({
            form: '#reset-password',
            formGroupSelector: ".form-group",
            errorSelector: ".form-message",
            rules: [
                Validator.minLength('#pass-reset', 6, 'Please enter at least 6 characters !'),
                Validator.isRequired('#re-pass-reset', 'Please re-entered your password !'),
                Validator.compareValues('#re-pass-reset', function getCompareValue() {
                    return (document.querySelector('#reset-password #pass-reset').value);
                }, 'Re-entered password is incorrect !')
            ]
        })
    </script>
</body>

</html>