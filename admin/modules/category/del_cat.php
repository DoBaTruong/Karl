<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

$del = $_GET['del_id'];
mysqli_query($conn, "DELETE FROM categories WHERE cat_id = $del");
mysqli_query($conn, "DELETE FROM categories WHERE cat_call = $del");
header('location: index.php?page_layout=catego');
?>