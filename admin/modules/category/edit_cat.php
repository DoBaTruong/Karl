<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
$edit = $_GET['edit_id'];
$cat = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM categories WHERE cat_id = $edit"));
if (isset($_POST['cat_name'])) {
    $name = $_POST['cat_name'];
    if (isset($_GET['brand_name'])) {
        $brand = $_GET['brand_name'];
        $check = mysqli_query($conn, "SELECT * FROM categories WHERE cat_name = '$name' AND cat_call = $brand");
    } else {
        $check = mysqli_query($conn, "SELECT * FROM categories WHERE cat_name = '$name'");
    }
    if ($check->num_rows >= 1 && $cat['cat_name'] !== $name) {
        $error = '<div class="alert-danger">Category already exists ! </div>';
    } else {
        if (isset($_GET['brand_name'])) {
            $call = $_POST['cat_call'];
            $status = mysqli_query($conn, "UPDATE categories SET cat_name = '$name', cat_call = $call, cat_type = 0 WHERE cat_id = $edit");
        } else {
            $type = $_POST['cat_type'];
            $status = mysqli_query($conn, "UPDATE categories SET cat_name = '$name', cat_call = 0, cat_type = $type WHERE cat_id = $edit");
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
        <li class="breadcumb-item"><?= $cat['cat_name']; ?></li>
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
                <input type="text" name="cat_name" id="cat_name" value="<?= $cat['cat_name']; ?>" />
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <?php
                if (isset($_GET['brand_name'])) { ?>
                    <label for="cat_call">Categories:</label>
                    <select name="cat_call" id="cat_call">
                        <?php
                        $catSQL = "SELECT * FROM categories WHERE cat_type > 0";
                        $catQUERY = mysqli_query($conn, $catSQL);
                        while ($category = mysqli_fetch_array($catQUERY)) {
                        ?>
                            <option <?php if ($cat['cat_call'] == $category['cat_id']) {
                                        echo 'selected';
                                    } ?> value="<?= $category['cat_id']; ?>"><?= $category['cat_name']; ?></option>
                        <?php } ?>
                    </select>
                    <div class="form-message"></div>
                <?php } else { ?>
                    <label for="cat_type">Categories:</label>
                    <select name="cat_type" id="cat_type">
                        <option value="">----Defaulf Select----</option>
                        <option <?php if ($cat['cat_type'] == 1) {
                                    echo 'selected';
                                } ?> value="1">Product</option>
                        <option <?php if ($cat['cat_type'] == 3) {
                                    echo 'selected';
                                } ?> value="3">Blog</option>
                    </select>
                    <div class="form-message"></div>
                <?php } ?>
            </div>
            <div class="flex-start">
                <button class="btn green" name="sbm" type="submit">UPDATE</button>
                <button class="btn gray" type="reset">Reset</button>
            </div>
        </div>
    </form>
</section>