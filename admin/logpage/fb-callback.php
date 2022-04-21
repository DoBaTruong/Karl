<?php
session_start();
define('SECURITY', True);
include('../config/connect.php');
require_once('Facebook/autoload.php');
$fb = new Facebook\Facebook([
    'app_id' => '500363457864113',
    'app_secret' => '9d418c4201fa65884793c5a6823a9c63',
    'default_graph_version' => 'v2.9',
]);
$helper = $fb->getRedirectLoginHelper();
try {
    $accessToken = $helper->getAccessToken();
    $response = $fb->get('/me?fields=id,name,email', $accessToken);
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
if (!isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}
// Logged in
$userFb = $response->getGraphUser();
if (!empty($userFb)) {
    $fbName = $userFb->getName();
    $fbMail = $userFb->getEmail();
    if (isset($_SESSION['logfront'])) {
        $facebook = mysqli_query($conn, "SELECT * FROM customers WHERE cus_mail = '$fbMail'");
    } else {
        $facebook = mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '$fbMail'");
    }
    if ($facebook->num_rows == 0) {
        $date = date('Y-m-d');
        if (isset($_SESSION['logfront'])) {
            $facebook = mysqli_query($conn, "INSERT INTO customers (cus_name, cus_mail, cus_locked, cus_level, cus_date) VALUES ('$fbName', '$fbMail', 0, 0, '$date')");
        } else {
            $facebook = mysqli_query($conn, "INSERT INTO staffs (user_full, user_mail, user_locked, user_level, user_create) VALUES ('$fbName', '$fbMail', 0, 2, '$date')");
        }
        if (!$facebook) {
            echo mysqli_error($conn);
            exit;
        }
        if (isset($_SESSION['logfront'])) {
            $facebook = mysqli_query($conn, "SELECT * FROM customers WHERE cus_mail = '$fbMail'");
        } else {
            $facebook = mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '$fbMail'");
        }
    }
    if ($facebook->num_rows > 0) {
            $refb = mysqli_fetch_array($facebook);
        if (isset($_SESSION['logfront'])) {
            $_SESSION['mail'] = $refb['cus_mail'];
            $_SESSION['pass'] = $refb['cus_pass'];
            header('location: ../index.php'.$_SESSION['logfront']);
        } else {
            $_SESSION['mail'] = $refb['user_mail'];
            $_SESSION['pass'] = $refb['user_pass'];
            header('location: ../index.php');
        }
    }
}

$_SESSION['fb_access_token'] = (string) $accessToken;

    // Từ đây bạn xử lý kiểm tra thông tin user trong database sau đó xử lý.
