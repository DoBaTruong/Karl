<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
if (!empty($_GET['prd_id'])) {
    $prdId = $_GET['prd_id'];
}
if (!empty($_POST['rating'])) {
    $countRat = $conn->query("SELECT * FROM rattstars WHERE prd_id = $prdId");
    if ($countRat->num_rows > 0) {
        $countVal =  0;
        while ($counRatRows = mysqli_fetch_array($countRat)) {
            $countVal += $counRatRows['ratt_val'];
        }
    } else {
        $countVal =  0;
    }
    $prdRatt = $countVal / ($countRat->num_rows);
    $conn->query("UPDATE product SET prd_ratt = $prdRatt WHERE prd_id = $prdId");
    $ratt = $_POST['rating'];
    $queryUs = $conn->query("SELECT * FROM rattstars WHERE prd_id = $prdId AND cus_id = $cusId");
    if ($queryUs->num_rows > 0) {
        $comRatId = mysqli_fetch_array($queryUs)['ratt_id'];
        $conn->query("UPDATE rattstars SET ratt_val = $ratt WHERE ratt_id = $comRatId");
    } else {
        $conn->query("INSERT INTO rattstars (prd_id, cus_id, ratt_val) VALUES ($prdId, $cusId, $ratt)");
    }
    header("location: index.php?page_layout=prd_details&prd_id=" . $prdId . "#ratingprd");
}
if (!empty($_GET['actype'])) {
    switch ($_GET['actype']) {
        case 'delete-comments':
            $cId = $_GET['comm_id'];
            $queryCheckCom = $conn->query("SELECT * FROM prd_comments WHERE prd_id = $prdId AND comm_call = 1");
            if ($queryCheckCom->num_rows > 0) {
                $arrID = [];
                while ($checkComm = mysqli_fetch_array($queryCheckCom)) {
                    $tmpId = $checkComm['comm_id'];
                    $checkID = GetIDprd(GetIDprd($tmpId, $conn), $conn);
                    if ($checkID == $cId) {
                        $arrID[] = $tmpId;
                    }
                }
                $arrIDlast = array_push($arrID, $cId);
                for ($i = 0; $i < count($arrID); $i++) {
                    $delID = $arrID[$i];
                    $conn->query("DELETE FROM prd_comments WHERE prd_id =$prdId AND comm_id = $delID");
                }
            } else {
                $conn->query("DELETE FROM prd_comments WHERE prd_id =$prdId AND comm_id = $cId");
            }
            $current = '?page_layout=' . explode('%', $_GET['pagecurr'])[0] . '&prd_id=' . $prdId . '#' . explode('%', $_GET['pagecurr'])[1];
            header('location: index.php' . $current);
            break;
        case 'delete-wishlist':
            $prdId =$_GET['prd_id'];
            $arrOld = explode(',', $_SESSION['wishlist']);
            $arrNew = array_diff($arrOld, [$prdId]);
            $_SESSION['wishlist'] = implode(',', $arrNew);
            $current = '?page_layout=' . implode('#', explode('%', $_GET['pagecurr']));
            header('location: index.php' . $current);
            break;
        case 'delete-compare':
            $prdId = $_GET['prd_id'];
            $arrOld = explode(',', $_SESSION['compare']);
            $arrNew = array_diff($arrOld, [$prdId]);
            $_SESSION['compare'] = implode(',', $arrNew);
            $current = '?page_layout=' . implode('#', explode('%', $_GET['pagecurr']));
            header('location: index.php' . $current);
            break;
        case 'like':
            $cId = $_GET['comm_id'];
            $CheckLike = mysqli_fetch_array($conn->query("SELECT * FROM prd_comments WHERE prd_id = $prdId AND comm_id = $cId"));
            if (trim($CheckLike['comm_like']) !== "") {
                $arrLike = explode(',', $CheckLike['comm_like']);
                if (in_array($cusId, $arrLike)) {
                    $newArrLike = array_diff($arrLike, $cusId);
                } else {
                    $newArrLike = array_push($arrLike, $cusId);
                }
                $like = implode(',', $newArrLike);
                $conn->query("UPDATE prd_comments SET comm_like = '$like' WHERE prd_id = $prdId AND comm_id = $cId");
            } else {
                $conn->query("UPDATE prd_comments SET comm_like = '$cusId' WHERE prd_id = $prdId AND comm_id = $cId");
            }
            $current = '?page_layout=' . explode('%', $_GET['pagecurr'])[0] . '&prd_id=' . $prdId . '#' . explode('%', $_GET['pagecurr'])[1];
            header('location: index.php' . $current);
    }
}
