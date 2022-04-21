<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
?>
<section class="breadcumb-area">
    <h3>Administration Notifications</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item">Notifications</li>
    </ul>
</section>
<section class="new-orders notification-area">
    <ul class="notify-page row">
        <?php
        $notiQue = $conn->query("SELECT * FROM notifications ORDER BY ntf_id DESC");
        while ($notiRow = mysqli_fetch_array($notiQue)) {
            switch ($notiRow['ntf_type']) {
                case 'blog':
                    $iconClass = 'fa-comment';
                    $arrInfor = explode('%', $notiRow['ntf_infor']);
                    $contentNoti = 'Have new comment for post "' . $arrInfor[1] . '"';
                    $href = $arrInfor[0].'&ntf_id='.$notiRow['ntf_id'];
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
            if ($notiRow['ntf_read'] == 0) {
                $itemClass = 'notRead';
            } else {
                $itemClass = '';
            }
        ?>
            <li class="menu-sub-item <?= $itemClass ?>">
                <a href="<?= $href ?>" class="items-center flex-between">
                    <span class="icon <?= $colorClass ?>"><i class="fa <?= $iconClass ?>"></i></span>
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
    </ul>
</section>