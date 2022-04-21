<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
$arrWue = $conn->query("SELECT * FROM messengers");
$arrCUsMess = array();
while ($arrRow = mysqli_fetch_array($arrWue)) {
    if (!in_array($arrRow['mess_repfor'], $arrCUsMess)) {
        $arrCUsMess[] = $arrRow['mess_repfor'];
    }
}

if (isset($_GET['mess_id'])) {
    $mesID = $_GET['mess_id'];
    $conn->query("UPDATE messengers SET mess_read = 1 WHERE mess_id = $mesID");
    $userInfor = mysqli_fetch_array($conn->query("SELECT * FROM messengers WHERE mess_id = $mesID"))['mess_repfor'];
    if (isset($_POST['sendchat'])) {
        $contentSend = $_POST['inboxcontent'];
        $dateSend = date('Y-m-d H:i:s');
        $conn->query("INSERT INTO messengers (mess_infor, mess_content, mess_repfor, mess_date, mess_callback) VALUES ('karlfashion.com', '$contentSend', '$userInfor', '$dateSend', $mesID)");
    }
}
?>
<section class="breadcumb-area">
    <h3>Administration Messengers</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item">Messengers</li>
    </ul>
</section>
<section class="new-orders row messengers-area">
    <div class="col-lg-4 col-md-4">
        <div class="message-left">
            <?php
            for ($i = 0; $i < count($arrCUsMess); $i++) {
                $messData = mysqli_fetch_array($conn->query("SELECT * FROM messengers WHERE mess_repfor = '" . $arrCUsMess[$i] . "' ORDER BY mess_date DESC LIMIT 0, 1"));
                $usInforWue = $conn->query("SELECT * FROM customers WHERE cus_mail = '" . $arrCUsMess[$i] . "'");
                if ($usInforWue->num_rows > 0) {
                    $usInfor = mysqli_fetch_array($usInforWue);
                    $nameMess = $usInfor['cus_name'];
                    if ($usInfor['cus_image'] === '') {
                        $imgMess = 'avatar-default.png';
                    } else {
                        $imgMess = $usInfor['cus_image'];
                    }
                } else {
                    $nameMess = $arrCUsMess[$i];
                    $imgMess = 'avatar-default.png';
                }
            ?>
                <a href="?page_layout=messengers&mess_id=<?= $messData['mess_id'] ?>" class="message-item flex-between <?php if (isset($_GET['mess_id']) && $_GET['mess_id'] == $messData['mess_id']) {
                                                                                                                            echo 'active';
                                                                                                                        } ?>">
                    <div class="item-image">
                        <img src="images/avata/<?= $imgMess ?>" alt="">
                    </div>
                    <div class="item-content">
                        <div class="item-name"><?= $nameMess ?></div>
                        <div class="flex-between">
                            <p class="item-mess <?php if ($messData['mess_read'] == 0) {
                                                    echo 'notRead';
                                                } ?>"><span>CUS:</span> <?= $messData['mess_content'] ?></p>
                            <span class="time-mess"><?= date("H:i d/M", strtotime($messData['mess_date'])) ?></span>
                        </div>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="col-lg-8 col-md-8">
        <div class="message-right">
            <div class="message-inbox">
                <?php
                if (isset($_GET['mess_id'])) {
                    $detailMessWue = $conn->query("SELECT * FROM messengers WHERE mess_repfor = '$userInfor' ORDER BY mess_date ASC");
                    while ($detailMess = mysqli_fetch_array($detailMessWue)) {
                        if ($detailMess['mess_infor'] !== 'karlfashion.com') {
                            $userDetail = $conn->query("SELECT * FROM customers WHERE cus_mail = '" . $detailMess['mess_infor'] . "'");
                            if ($userDetail->num_rows > 0) {
                                $imgMessDetaiTmp = mysqli_fetch_array($userDetail)['cus_image'];
                                if ($imgMessDetaiTmp === '') {
                                    $imgMessDetai = 'avatar-default.png';
                                } else {
                                    $imgMessDetai = $imgMessDetaiTmp;
                                }
                            } else {
                                $imgMessDetai = 'avatar-default.png';
                            }
                            $classType = 'shopadmin';
                        } else {
                            $imgMessDetai = 'karl-logo.png';
                            $classType = 'customers';
                        }
                ?>
                        <div class="inbox-item <?= $classType ?>">
                            <div class="item-image"><img src="images/avata/<?= $imgMessDetai ?>" alt="" /></div>
                            <div class="item-infor">
                                <p><?= $detailMess['mess_content'] ?></p>
                                <span><?= date("H:i d/M", strtotime($detailMess['mess_date'])) ?></span>
                            </div>
                        </div>
                <?php }
                } ?>
            </div>
            <form method="post" class="inbox-add">
                <textarea name="inboxcontent"></textarea>
                <button type="submit" name="sendchat">SEND</button>
            </form>
        </div>
    </div>
</section>