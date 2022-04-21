<?php
    if(!defined('SECURITY')) {
        die("You don't have authorization to view this page !");
    }
    $conn = mysqli_connect('localhost', 'root', '', 'karl_fashion');
    $sql = "SET NAMEs 'utf8'";
    if (isset($conn)) {
        mysqli_query($conn, $sql);
    } else {
        die('Database connection failed ');
    }
?>