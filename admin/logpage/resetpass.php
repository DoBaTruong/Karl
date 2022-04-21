<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

if (isset($_POST['pass-reset'])) {
    $pass = md5($_POST['pass-reset']);
    $original = $_POST['pass-reset'];
    $email = $_SESSION['forgot_mail'];
    echo $email;
    if (isset($_GET['logfront'])) {
        $status = mysqli_query($conn, "UPDATE customers SET cus_original = '$original', cus_pass = '$pass' WHERE cus_mail = '$email'");
    } else {
        $status = mysqli_query($conn, "UPDATE staffs SET user_original = '$original', user_pass = '$pass' WHERE user_mail = '$email'");
    }
    if ($status) {
        unset($_SESSION['forgot_mail']);
        $_SESSION['pass'] = $pass;
        $_SESSION['mail'] = $email;
        header('location: index.php' . $subPage);
    }
}
?>

<div class="flex-center forgot-box">
    <div class="col-lg-6 col-md-10 col-sm-12">
        <div class="forgot-item">
            <h2>Reset Password ?</h2>
            <form id="reset-password" method="post">
                <div class="form-group">
                    <label for="pass-reset">Password *</label>
                    <div class="pass-eye">
                        <input type="password" name="pass-reset" id="pass-reset" />
                        <span class="eye-show"><i class="fa fa-eye-slash"></i></span>
                    </div>
                    <div class="form-message"></div>
                </div>
                <div class="form-group">
                    <label for="re-pass-reset">Re-Password *</label>
                    <div class="pass-eye">
                        <input type="password" name="re-pass-reset" id="re-pass-reset" />
                        <span class="eye-show"><i class="fa fa-eye-slash"></i></span>
                    </div>
                    <div class="form-message"></div>
                </div>
                <button name="sbm-reset-pass" type="submit" class="btn">RESET FORGOT</button>
            </form>
        </div>
    </div>
</div>