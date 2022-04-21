<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
if (isset($_POST['code-value'])) {
    $code = $_POST['code-value'];
    if (isset($_SESSION['code_timeout'])) {
        $checkcode = $_SESSION['security_code'];
        if ($checkcode == $code) {
            unset($_SESSION['code_time']);
            unset($_SESSION['code_timeout']);
            unset($_SESSION['security_code']);
            if (isset($_GET['logfront'])) {
                header('location: index.php' . $subPage . '&login_page=reset-pass');
            } else {
                header('location: index.php?login_page=reset-pass');
            }
        } else {
            $error = '<div class="alert-danger">Code is incorrect !</div>';
        }
    }
}
?>
<div class="flex-center forgot-box">
    <div class="col-lg-6 col-md-10 col-sm-12">
        <div class="forgot-item">
            <h2>Check Your Email ?</h2>
            <?php if (isset($error)) {
                echo $error;
            } ?>
            <form id="confirm-code" method="post">
                <div class="form-group">
                    <input type="text" name="code-value" id="code-value" placeholder="Enter your code *" />
                    <div class="form-message"></div>
                </div>
                <div class="flex-start">
                    <button name="confirm-email" type="submit" class="btn">CONFIRM</button>
                    <?php if (isset($_SESSION['code_timeout']) && isset($_SESSION['security_code'])) { ?>
                        <div class="alert-timeout alert-danger">Vetification code <span data-toggle="wait" data-session="<?php echo $_SESSION['code_timeout']; ?>"><?php echo $_SESSION['code_timeout']; ?></span> seconds</div>
                    <?php } ?>
                </div>
            </form>
            <?php
            if (isset($_GET['logfront'])) { ?>
                <a class="re-send-code" href="index.php<?= $subPage ?>&login_page=send-mail">I didn't receive a code ?</a>
            <?php } else { ?>
                <a class="re-send-code" href="index.php?login_page=send-mail">I didn't receive a code ?</a>
            <?php } ?>
        </div>
    </div>
</div>