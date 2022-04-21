<?php
//Trang này để lấy thông tin đăng nhập từ google, nếu chưa đăng nhập sẽ mở popup để xác thực từ Google.
require_once('Google/autoload.php');

$client_id = '786061073278-5qml5b82ir215adv6bcchjcsd0fc2p6j.apps.googleusercontent.com';
$client_secret = 'qcScDR6jB0FWBPwEuAObh1YT';
$redirect_uri = 'https://karlfashion.com/admin/index.php';

$client = new Google_Client(); //Login Page Google
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri); // if users accept, Gg redirect url
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $client->authenticate(($_GET['code']));
    $_SESSION['access_token'] = $client->getAccessToken();
    $client->setAccessToken($_SESSION['access_token']);

    // get profile info
    $service = new Google_Service_Oauth2($client);
    $googleUser = $service->userinfo->get(); //get user info
    $ggmail =  $googleUser->email;
    $ggname =  $googleUser->name;
    $date = date('Y-m-d');

    if ($client->isAccessTokenExpired()) {
        $authUrl = $client->createAuthUrl();
    }

    if (!empty($googleUser)) {
        $ggmail =  $googleUser->email;
        $ggname =  $googleUser->name;
        $date = date('Y-m-d');
        if (isset($_SESSION['logfront'])) {
            $google = mysqli_query($conn, "SELECT * FROM customers WHERE cus_mail = '$ggmail'");
        } else {
            $google = mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '$ggmail'");
        }
        if ($google->num_rows == 0) {
            if (isset($_SESSION['logfront'])) {
                $google = mysqli_query($conn, "INSERT INTO customers (cus_name, cus_mail, cus_locked, cus_level, cus_date) VALUES ('$ggname', '$ggmail', 0, 0, '$date')");
            } else {
                $google = mysqli_query($conn, "INSERT INTO staffs (user_full, user_mail, user_locked, user_level, user_create) VALUES ('$ggname', '$ggmail', 0, 2, '$date')");
            }
            if (!$google) {
                echo mysqli_error($conn);
                exit;
            }
            if (isset($_SESSION['logfront'])) {
                $google = mysqli_query($conn, "SELECT * FROM customers WHERE cus_mail = '$ggmail'");
            } else {
                $google = mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '$ggmail'");
            }
        }
        if ($google->num_rows > 0) {
            $results = mysqli_fetch_array($google);
            if (isset($_SESSION['logfront'])) {
                $_SESSION['mail'] = $results['cus_mail'];
                $_SESSION['pass'] = $results['cus_pass'];
                header('location: index.php' . $_SESSION['logfront']);
            } else {
                $_SESSION['mail'] = $results['user_mail'];
                $_SESSION['pass'] = $results['user_pass'];
                header('location: index.php');
            }
        }
    }
} else {
    $authUrl = $client->createAuthUrl();
}
