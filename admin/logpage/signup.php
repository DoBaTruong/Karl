<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
if (isset($_SESSION['time'])) {
    $_SESSION['timeout'] = 30 - time() + $_SESSION['time'];
    $timesub = time() - $_SESSION['time'];
    if ($_SESSION['timeout'] <= 0) {
        unset($_SESSION['time']);
        unset($_SESSION['timeout']);
        unset($_SESSION['disable']);
        if (isset($_SESSION['logfront'])) {
            mysqli_query($conn, "UPDATE customers SET cus_locked = 0 WHERE cus_mail = '" . $_SESSION['mail'] . "'");
        } else {
            mysqli_query($conn, "UPDATE staffs SET user_locked = 0 WHERE user_mail = '" . $_SESSION['mail'] . "'");
        }
    }
}

if (isset($_POST['email-log']) && isset($_POST['pass-log'])) {
    $mail = $_POST['email-log'];
    $pass = md5($_POST['pass-log']);
    if (isset($_GET['logfront'])) {
        $num = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM customers WHERE cus_mail='$mail'"));
    } else {
        $num = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail='$mail'"));
    }
    if ($num > 0) {
        if (isset($_GET['logfront'])) {
            $count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM customers WHERE cus_mail='$mail' AND cus_pass='$pass'"));
        } else {
            $count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail='$mail' AND user_pass='$pass'"));
        }
        $_SESSION['mail'] = $mail;
        if ($count > 0) {
            $_SESSION['pass'] = $pass;
            if (isset($_GET['logfront'])) {
                mysqli_query($conn, "UPDATE customers SET cus_locked = 0 WHERE cus_mail = '$mail'");
            } else {
                mysqli_query($conn, "UPDATE staffs SET user_locked = 0 WHERE user_mail = '$mail'");
            };
            if (isset($_POST['remember'])) {
                $cookie_name = 'karlFashion';
                $cookie_time = 3600 * 24 * 30;
                setcookie($cookie_name, $mail, time() + $cookie_time);
            }
            header('location: index.php' . $subPage);
        } else {
            if (isset($_GET['logfront'])) {
                $locked = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM customers WHERE cus_mail = '$mail'"))['cus_locked'];
            } else {
                $locked = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '$mail'"))['user_locked'];
            }

            if ($locked > 0) {
                $incree = $locked + 1;
            } else {
                $incree = 1;
            }

            switch ($incree) {
                case '1':
                case '2':
                case '3':
                    if (isset($_GET['logfront'])) {
                        mysqli_query($conn, "UPDATE customers SET cus_locked = $locked + 1 WHERE cus_mail = '$mail'");
                    } else {
                        mysqli_query($conn, "UPDATE staffs SET user_locked = $locked + 1 WHERE user_mail = '$mail'");
                    };
                    break;
                case '4':
                    $_SESSION['time'] = time();
                    $_SESSION['disable'] = 'disabled';
                    $_SESSION['timeout'] = 30;
                    break;
            }

            if ($incree > 0 && $incree < 4) {
                $error = '<div class="alert-danger">Password is incorrect, please re-enter password !</div>';
            }
        }
    } else {
        $error = '<div class="alert-danger">Invalid email, please re-enter email !</div>';
    }
}

if (isset($_POST['fullname-regis']) && isset($_POST['email-regis']) && isset($_POST['pass-regis'])) {
    $fullname = $_POST['fullname-regis'];
    $regmail = $_POST['email-regis'];
    $original = $_POST['pass-regis'];
    $regpass = md5($original);
    $date = date('Y-m-d');
    if (isset($_GET['logfront'])) {
        $regis = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM customers WHERE cus_mail = '$regmail'"));
    } else {
        $regis = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '$regmail'"));
    }
    if (isset($regis)) {
        $_SESSION['reg_error'] = '<div class="alert-danger">Email already exists, please use another email or login !</div>';
        $error = $_SESSION['reg_error'];
    } else {
        unset($_SESSION['reg_error']);
        if (isset($_GET['logfront'])) {
            mysqli_query($conn, "INSERT INTO customers (cus_name, cus_mail, cus_pass, cus_original, cus_level, cus_locked, cus_date) VALUES ('$fullname', '$regmail', '$regpass', '$original', 0, 0, '$date')");
        } else {
            mysqli_query($conn, "INSERT INTO staffs (user_full, user_mail, user_pass, user_original, user_level, user_locked, user_create) VALUES ('$fullname', '$regmail', '$regpass', '$original', 2, 0, '$date')");
        }
        $_SESSION['mail'] = $regmail;
        $_SESSION['pass'] = $regpass;
        header('location: index.php' . $subPage);
    }
}
?>

<div class="flex-center">
    <div class="col-lg-6 col-md-10 col-sm-12">
        <div class="form-area">
            <div id="navTab" class="flex-between" data-play="#contentTab">
                <div class="tabLink <?php if (!isset($_SESSION['reg_error'])) {
                                        echo 'active';
                                    } ?>" data-toggle="tab" data-target="#login">Login</div>
                <div class="tabLink <?php if (isset($_SESSION['reg_error'])) {
                                        echo 'active';
                                    } ?>" data-toggle="tab" data-target="#register">Register</div>
            </div>
            <div id="navContent">
                <div class="form-item <?php if (!isset($_SESSION['reg_error'])) {
                                            echo 'active';
                                        } ?>">
                    <?php
                    if (!empty($error)) {
                        echo $error;
                    }
                    if (isset($_SESSION['timeout'])) {
                    ?>
                        <div class="alert-danger">Please wait <span data-toggle="wait" data-session="<?php echo $_SESSION['timeout']; ?>"><?php echo $_SESSION['timeout']; ?></span> seconds !</div>
                    <?php } ?>
                    <form id="login" method="post">
                        <div class="form-group">
                            <label for="email-log">Email Address *</label>
                            <input <?php if (isset($_SESSION['disable'])) {
                                        echo $_SESSION['disable'];
                                    } ?> type="email" name="email-log" id="email-log" value="<?php if (!empty($_COOKIE['karlFashion'])) {
                                                                                                    echo $_COOKIE['karlFashion'];
                                                                                                } else if (isset($_SESSION['mail'])) {
                                                                                                    echo $_SESSION['mail'];
                                                                                                } ?>" />
                            <div class="form-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="pass-log">Password *</label>
                            <div class="pass-eye">
                                <input <?php if (isset($_SESSION['disable'])) {
                                            echo $_SESSION['disable'];
                                        } ?> value="<?php if (!empty($_COOKIE['karlFashion'])) {
                                                        echo mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '" . $_COOKIE['karlFashion'] . "'"))['user_original'];
                                                    } else if (isset($_SESSION['pass'])) {
                                                        echo mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM staffs WHERE user_mail = '" . $_SESSION['mail'] . "'"))['user_original'];
                                                    };  ?>" type="password" name="pass-log" id="pass-log" />
                                <span class="eye-show"><i class="fa fa-eye-slash"></i></span>
                            </div>
                            <div class="form-message"></div>
                        </div>
                        <div class="flex-between">
                            <?php if (isset($_GET['logfront'])) { ?>
                                <a href="index.php<?= $subPage ?>&login_page=send-mail">Forgot Your Password ?</a>
                            <?php } else { ?>
                                <a href="index.php?login_page=send-mail">Forgot Your Password ?</a>
                            <?php } ?>
                            <div class="custom-checkbox flex-start">
                                <input type="checkbox" name="remember" id="remember" />
                                <label for="remember" class="items-center"><span>Remember Me</span></label>
                            </div>
                            <button class="btn items-center" name="login" type="submit">LOGIN <span><i class="fa fa-long-arrow-alt-right"></i></span></button>
                        </div>
                        <div class="log-social">
                            <p>or sign in with</p>
                            <div class="flex-between">
                                <?php include_once('fb-source.php');
                                include_once('gg-redirect.php'); ?>
                                <a href="<?php if (isset($loginUrl)) {
                                                echo $loginUrl;
                                            } ?>" class="btn items-center"><span><i class="fab fa-facebook-f"></i></span>Login With Facebook</a>
                                <a href="<?php if (isset($authUrl)) {
                                                echo $authUrl;
                                            } ?>" class="btn items-center"><span><i class="fab fa-google-plus-g"></i></span>Login With Google</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="form-item <?php if (isset($_SESSION['reg_error'])) {
                                            echo 'active';
                                        } ?>">
                    <?php if (isset($_SESSION['reg_error'])) {
                        echo $_SESSION['reg_error'];
                    } ?>
                    <form id="register" method="post">
                        <div class="form-group">
                            <label for="fullname-regis">Full Name *</label>
                            <input type="text" name="fullname-regis" id="fullname-regis" />
                            <div class="form-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="email-regis">Email Address *</label>
                            <input type="email" name="email-regis" id="email-regis" />
                            <div class="form-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="pass-regis">Password *</label>
                            <div class="pass-eye">
                                <input type="password" name="pass-regis" id="pass-regis" />
                                <span class="eye-show"><i class="fa fa-eye-slash"></i></span>
                            </div>
                            <div class="form-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="re-pass-regis">Password *</label>
                            <div class="pass-eye">
                                <input type="password" name="re-pass-loregisg" id="re-pass-regis" />
                                <span class="eye-show"><i class="fa fa-eye-slash"></i></span>
                            </div>
                            <div class="form-message"></div>
                        </div>
                        <div class="flex-between">
                            <div class="custom-checkbox flex-start form-group">
                                <input type="checkbox" name="agree" id="agree" />
                                <label for="agree" class="items-center"><span>I agree to the <a href="../index.php?page_layout=policy">privacy policy *</a></span></label>
                            </div>
                            <button class="btn items-center" name="register" type="submit">Register <span><i class="fa fa-long-arrow-alt-right"></i></span></button>
                        </div>
                        <div class="log-social">
                            <p>or sign in with</p>
                            <div class="flex-between">
                                <a href="<?php if (isset($loginUrl)) {
                                                echo $loginUrl;
                                            } ?>" class="btn items-center"><span><i class="fab fa-facebook-f"></i></span>Login With Facebook</a>
                                <a href="<?php if (isset($authUrl)) {
                                                echo $authUrl;
                                            } ?>" class="btn items-center"><span><i class="fab fa-google-plus-g"></i></span>Login With Google</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>