<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

if ($staffSS['user_level'] > 1) {
    die("You don't have authorization to view this page !");
}

if (isset($_GET['del_id'])) {
    $del = $_GET['del_id'];
    $prd = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM product WHERE prd_id = $del"));
    if (!empty($prd)) {
        $tmp = $prd['prd_image'] . ', ' . $prd['prd_imgSub'];
        $arrImg = explode(', ', $tmp);
        for ($i = 0; $i < count($arrImg); $i++) {
            $url = "images/product/" . $arrImg[$i];
            if (file_exists($url)) {
                unlink($url);
            }
        }
    }
    mysqli_query($conn, "DELETE FROM product WHERE prd_id = $del");
    header('location: index.php?page_layout=products');
}

if (isset($_POST['del_id'])) {
    $arrID = $_POST['del_id'];
    $strID = implode(', ', $arrID);
    $sql = "SELECT * FROM product WHERE prd_id IN ($strID)";
    $query = mysqli_query($conn, $sql);
    if ($query->num_rows > 0) {
        while ($row = mysqli_fetch_array($query)) {
            $tmp = $row['prd_image'] . ', ' . $row['prd_imgSub'];
            $arrImg = explode(', ', $tmp);
            for ($i = 0; $i < count($arrImg); $i++) {
                $url = "images/product/" . $arrImg[$i];
                if (file_exists($url)) {
                    unlink($url);
                }
            }
        }
    }
    mysqli_query($conn, "DELETE FROM product WHERE prd_id IN ($strID)");
    header('location: index.php?page_layout=products');
}
