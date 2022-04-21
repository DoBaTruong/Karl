<?php
session_start();
define('SECURITY', True);
include_once('config/connect.php');

if (isset($_GET['logfront'])) {
    $infor = explode('-', $_GET['logfront']);
    $type = explode('_', reset($infor));
    $pageinfor = implode('&'.reset($type).'_id=', $infor);
    if (isset($_SESSION['mail']) && isset($_SESSION['pass'])) {
        header('location: ../index.php?page_layout='.$pageinfor);
        unset($_SESSION['logfront']);
    } else {
        include_once('login.php');
    }
} else {
    if (isset($_SESSION['mail']) && isset($_SESSION['pass'])) {
        include_once('admin.php');
    } else {
        include_once('login.php');
    }
}
