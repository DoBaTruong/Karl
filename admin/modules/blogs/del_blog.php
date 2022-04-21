<?php 
    if(!defined('SECURITY')) {
        die("You don't have authorization to view this page !");
    }

    if (isset($_GET['del_id'])) {
        $del = $_GET['del_id'];
        $blog = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM blogs WHERE blog_id = $del"));
        if (!empty($blog)) {
            $url = "images/blog/".$blog['blog_image'];
            if (file_exists($url)) {
                unlink($url);
            }
        }        
        mysqli_query($conn, "DELETE FROM blogs WHERE blog_id = $del");	
        header('location: index.php?page_layout=blogs');
    }

    if (isset($_POST['del_id'])) {
        $arrID = $_POST['del_id'];
        $strID = implode(', ', $arrID);
        $sql = "SELECT * FROM blogs WHERE blog_id IN ($strID)";
        $query = mysqli_query($conn, $sql);
        if ($query->num_rows > 0) {
            while ($row = mysqli_fetch_array($query)) {
                $url = "images/blog/".$row['blog_image'];
                if (file_exists($url)) {
                    unlink($url);
                }
            }
        }
        mysqli_query($conn, "DELETE FROM blogs WHERE blog_id IN ($strID)");
        header('location: index.php?page_layout=blogs');
    }
?>