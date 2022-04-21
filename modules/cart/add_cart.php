<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

$id = $_GET['prd_id'];

if (!empty($_GET['pagecurrent'])) {
    $pagelay = '?page_layout='.$_GET['pagecurrent'].'&prd_id='.$id;
} else {
    $pagelay = '';
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]++;
} else {
    $_SESSION['cart'][$id] =  1;
}
header('location: ../../index.php'.$pagelay);
?>