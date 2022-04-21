<?php   
    if(!defined('SECURITY')) {
        die("You don't have authorization to view this page !");
    }
?>
<div class="flex-center forgot-box">
    <div class="col-lg-6 col-md-10 col-sm-12">
        <div class="forgot-item">
            <h2>Forgotten Password ?</h2>
            <?php if (isset($error)) {echo $error;} ?>
            <form id="forgot-pass" method="post">
                <div class="form-group">
                    <input type="email" name="email-forgot" id="email-forgot" placeholder="Email confirm *" />
                    <div class="form-message"></div>
                </div>
                <button name="send-code" type="submit" class="btn">SEND</button>
            </form>  
        </div>
    </div>
</div>