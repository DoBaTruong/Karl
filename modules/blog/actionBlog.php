<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
$id = $_GET['id'];
$blog = mysqli_fetch_array($conn->query("SELECT * FROM blogs WHERE blog_id = $id"))['blog_like'];
if (!isset($_SESSION['mail'])) {
    header('location: index.php?page_layout=logreg&logInfor=blog_details-' . $id);
} else {
    $mail = $_SESSION['mail'];
    $cusSS = mysqli_fetch_array($conn->query("SELECT * FROM customers WHERE cus_mail = '$mail'"));
    if (!empty($_GET['like_comment'])) {
        $cusId = $cusSS['cus_id'];
        $commId = $_GET['like_comment'];
        $like = mysqli_fetch_array($conn->query("SELECT * FROM blog_comments WHERE comm_id = $commId"))['comm_like'];
        if ($like !== '') {
            $arr = explode(',', $like);
            if (in_array($cusId, $arr)) {
                $new = array_diff($arr, [$cusId]);
                $likenew = implode(',', $new);
                $conn->query("UPDATE blog_comments SET comm_like = '$likenew'  WHERE comm_id = $commId");
            } else {
                $likenew = $like.','.$cusId;
                $conn->query("UPDATE blog_comments SET comm_like = '$likenew'  WHERE comm_id = $commId");
            }
        } else {
            $conn->query("UPDATE blog_comments SET comm_like = '$cusId' WHERE comm_id = $commId");
        }
        header('location: index.php?page_layout=blog_details&blog_id=' . $id.'#comm'.$commId);
    } else {
        $like = mysqli_fetch_array($conn->query("SELECT * FROM customers WHERE cus_mail = '$mail'"))['blog_like'];
        if ($like !== '') {
            $arr = explode(',', $like);
            if (in_array($id, $arr)) {
                $new = array_diff($arr, [$id]);
                $conn->query("UPDATE blogs SET blog_like = $blog - 1 WHERE blog_id = $id");
            } else {
                $new = array_push($arr, $id);
                $conn->query("UPDATE blogs SET blog_like = $blog + 1 WHERE blog_id = $id");
            }
            $likbl = implode(',', $new);

            $conn->query("UPDATE customers SET blog_like = '$likbl' WHERE cus_mail = '$mail'");
        } else {
            $conn->query("UPDATE blogs SET blog_like = $blog + 1 WHERE blog_id = $id");
            $conn->query("UPDATE customers SET blog_like = '$id' WHERE cus_mail = '$mail'");
        }
        header('location: index.php?page_layout=blog_details&blog_id=' . $id);
    }
}
