<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
if (isset($_POST['cat_name'])) {
    $name = $_POST['cat_name'];
    if (isset($_GET['brand_name'])) {
        $check = mysqli_query($conn, "SELECT * FROM categories WHERE cat_name = '$name' AND cat_call = '".$_GET['id']."'");
    } else {
        $check = mysqli_query($conn, "SELECT * FROM categories WHERE cat_name = '$name'");
    }
    if ($check->num_rows >= 1) {
        $error = '<div class="alert-danger">Category already exists ! </div>';
    } else {
        if (isset($_GET['brand_name'])) {
            $brand = $_GET['brand_name'];
            $call = $_GET['id'];
            $status = mysqli_query($conn, "INSERT INTO categories (cat_name, cat_call, cat_type) VALUES ('$name', $call, $brand - 1)");
        } else {
            $type = $_POST['cat_type'];
            $status = mysqli_query($conn, "INSERT INTO categories (cat_name, cat_call, cat_type) VALUES ('$name', 0, $type)");
        }
        if (isset($status)) {
            header('location: index.php?page_layout=catego');
        }
    }
}
?>
<section class="breadcumb-area">
    <h3>Administration Categories</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item"><a href="index.php?page_layout=catego">Categories</a></li>
        <li class="breadcumb-item">Add Category</li>
    </ul>
</section>
<section class="add_page">
    <?php
    if (!empty($error)) {
        echo $error;
    }
    ?>
    <form method="post" id="edit_cat" class="category" enctype="multipart/form-data">
        <div class="form-item">
            <div class="form-group">
                <label for="cat_name">Category name:</label>
                <input type="text" name="cat_name" id="cat_name" />
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <?php
                if (!isset($_GET['brand_name'])) { ?>
                    <label for="cat_type">Type:</label>
                    <select name="cat_type" id="cat_type">
                        <option value="">----Defaulf Select----</option>
                        <option value="1">Product</option>
                        <option value="3">Blog</option>
                    </select>
                    <div class="form-message"></div>
                <?php } ?>
            </div>
            <div class="flex-start">
                <button class="btn green" name="sbm" type="submit">ADD</button>
                <button class="btn gray" type="reset">Reset</button>
            </div>
        </div>
    </form>
</section>