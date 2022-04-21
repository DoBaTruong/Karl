<?php 
    if(!defined('SECURITY')) {
        die("You don't have authorization to view this page !");
    }

    if (isset($_GET['del_id'])) {
        $del = $_GET['del_id'];
        $user = mysqli_fetch_array(mysqli_query($conn, "SELECT user_image FROM staffs WHERE user_id = $del"));
        if (!empty($user)) {
            $url = "images/avata/".$user['user_image'];
            if (file_exists($url)) {
                unlink($url);
            }
        }        
        mysqli_query($conn, "DELETE FROM staffs WHERE user_id = $del");	
        header('location: index.php?page_layout=staff');
    }

    if (isset($_POST['del_id'])) {
        $arrID = $_POST['del_id'];
        $strID = implode(', ', $arrID);
        $sql = "SELECT user_image FROM staffs WHERE user_id IN ($strID)";
        $query = mysqli_query($conn, $sql);
        if ($query->num_rows > 0) {
            while ($row = mysqli_fetch_array($query)) {
                $url = 'images/avata/'.$row['user_image'];
                if (file_exists($url)) {
                    unlink($url);
                }
            }
        }
        mysqli_query($conn, "DELETE FROM staffs WHERE user_id IN ($strID)");
        header('location: index.php?page_layout=staff');
    }
?>