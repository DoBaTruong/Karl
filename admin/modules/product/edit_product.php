<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

if ($staffSS['user_level'] > 1) {
    die("You don't have authorization to view this page !");
}

$edit = $_GET['edit_id'];
$product = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM product WHERE prd_id = $edit"));
if (isset($_POST['prd_name']) && isset($_POST['prd_price'])) {
    $name = $_POST['prd_name'];
    $price = $_POST['prd_price'];
    $check = mysqli_query($conn, "SELECT * FROM product WHERE prd_name = '$name'");
    if ($check->num_rows >= 1 && $product['prd_name'] !== $name) {
        $error = '<div class="alert-danger">Product already exists ! </div>';
    } else {
        $color = $_POST['prd_color'];
        $size = $_POST['prd_size'];
        $qty = $_POST['prd_quantity'];
        $brand = $_POST['prd_brand'];
        $cat_id = $_POST['cat_id'];
        $arrName = explode('_', preg_replace("/[^A-Za-z0-9\-]/", ' ', $name));
        if ($_FILES['prd_image']['name'] == "") {
            $img = $product['prd_image'];
        } else {
            $unimg = 'images/product/' . $product['prd_image'];
            if (file_exists($unimg)) {
                unlink($unimg);
            }
            $arrEx = explode('/', $_FILES['prd_image']['type']);
            $img = mb_strtolower(convert_name(implode('_', $arrName)) . '.' . end($arrEx));
            $des = 'images/product/' . $img;
            if (file_exists($des)) {
                $increment = 0;
                $info = pathinfo($des);
                while (file_exists($des)) {
                    $increment++;
                    $img = $info['filename'] . '-' . $increment . '.' . $info['extension'];
                    $des = "images/product/" . $img;
                }
            }
            move_uploaded_file($_FILES['prd_image']['tmp_name'], $des);
        }
        if ($_FILES['prd_image']['name'] == "") {
            $prdSub = $product['prd_imgSub'];
        } else {
            $unArr = explode(', ', $product['prd_imgSub']);
            for ($i = 0; $i < count($unArr); $i++) {
                $unImSub = 'images/product/' . $unArr[$i];
                if (file_exists($unImSub)) {
                    unlink($unImSub);
                }
            }
            $type = array();
            $nameImg = array();
            $tmp_name = array();
            $countImg = 1;
            foreach ($_FILES['prd_imgDetail']['type'] as $file) {
                $tmptype = explode('/', $file);
                $tmpname = explode(' ', $name);
                $nameImg[] = mb_strtolower(convert_name(implode('_', $tmpname) . '_sub-' . $countImg . '.' . end($tmptype)));
                $countImg++;
            }
            foreach ($_FILES['prd_imgDetail']['tmp_name'] as $file) {
                $tmp_name[] = $file;
            }
            for ($i = 0; $i < count($nameImg); $i++) {
                $desSub = 'images/product/' . $nameImg[$i];
                if (file_exists($desSub)) {
                    $infopath = pathinfo($nameImg[$i]);
                    $increSub = 0;
                    while (file_exists($desSub[$i])) {
                        $increSub++;
                        $nameImg[$i] = $infopath['filename'] . $increSub . '.' . $infopath['extension'];
                        $desSub = 'images/product/' . $nameImg;
                    }
                }
                move_uploaded_file($tmp_name[$i], $desSub);
            }
            $prdSub = implode(', ', $nameImg);
        }
        $details = $_POST['prd_details'];
        $date = date('Y-m-d H:i:s');

        $status = mysqli_query($conn, "UPDATE product SET cat_id = $cat_id, prd_name = '$name', prd_image = '$img', prd_imgSub = '$prdSub', prd_color = '$color', prd_size = '$size', prd_brand = '$brand', prd_price = $price, prd_quantity = $qty, prd_details = '$details', prd_update = '$date' WHERE prd_id = $edit");
        if (isset($status)) {
            header('location: index.php?page_layout=products');
        }
    }
}
?>
<section class="breadcumb-area">
    <h3>Administration Products</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item"><a href="index.php?page_layout=products">Products</a></li>
        <li class="breadcumb-item"><?= $product['prd_name']; ?></li>
    </ul>
</section>
<section class="add_page">
    <?php
    if (!empty($error)) {
        echo $error;
    }
    ?>
    <form method="post" id="edit_prd" class="flex-between" enctype="multipart/form-data">
        <div class="form-item">
            <div class="form-group">
                <label for="prd_name">Product name:</label>
                <input type="text" name="prd_name" id="prd_name" value="<?= $product['prd_name']; ?>" />
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <label for="prd_price">Price:</label>
                <input type="number" name="prd_price" id="prd_price" value="<?= $product['prd_price']; ?>" />
                <div class="form-message"></div>
            </div>
            <div class="form-color">
                <div class="form-group">
                    <input data-toggle="select" data-target="#prd_color" type="color" name="select_color" id="select_color" />
                </div>
                <div class="form-group">
                    <label for="prd_color">Color:</label>
                    <input type="text" name="prd_color" id="prd_color" value="<?= $product['prd_color']; ?>" />
                    <div class="form-message"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="prd_size">Size:</label>
                <input type="text" name="prd_size" id="prd_size" value="<?= $product['prd_size']; ?>" />
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <label for="prd_quantity">Quantity:</label>
                <input type="number" name="prd_quantity" min="0" step="1" id="prd_quantity" value="<?= $product['prd_quantity']; ?>" />
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <label for="cat_id">Categories:</label>
                <?php
                $sqlCat = "SELECT * FROM categories WHERE cat_type = 0";
                $queryCat = mysqli_query($conn, $sqlCat);
                $dataCat = array();
                while ($catTest = mysqli_fetch_array($queryCat)) {
                    $dataCat[] = $catTest['cat_name'] . ':' . $catTest['cat_call'] . '-' . $catTest['cat_id'];
                }
                $stringData = implode('; ', $dataCat);
                ?>
                <select data-toggle="movedata" data-select="<?= $product['prd_brand']; ?>" data-parent=".form-item" data-target="#prd_brand" name="cat_id" id="cat_id" data-catego="<?php echo $stringData; ?>">
                    <option value="">----Defaulf Select----</option>
                    <?php
                    $catSQL = "SELECT * FROM categories WHERE cat_type = 1";
                    $catQUERY = mysqli_query($conn, $catSQL);
                    while ($category = mysqli_fetch_array($catQUERY)) {
                    ?>
                        <option <?php if ($category['cat_id'] == $product['cat_id']) {
                                    echo 'selected';
                                } ?> value="<?= $category['cat_id']; ?>"><?= $category['cat_name']; ?></option>
                    <?php } ?>
                </select>
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <label for="prd_brand">Type:</label>
                <select disabled name="prd_brand" id="prd_brand">
                    <option value="">----Default Select----</option>
                </select>
                <div class="form-message"></div>
            </div>
        </div>
        <div class="form-item">
            <div class="form-image">
                <div class="image-item flex-between">
                    <div class="form-group">
                        <label>Image:</label>
                        <div class="items-center">
                            <input hidden type="file" name="prd_image" id="prd_image" />
                            <input class="green" type="button" onclick="this.parentElement.querySelector('input[type=file]').click();" value="Chose image" />
                        </div>
                        <div class="form-message"></div>
                        <div class="file_preview">
                            <?php if (!empty($product['prd_image'])) {
                                echo '<img src="images/product/' . $product['prd_image'] . '" alt="" />';
                            } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Image Details:</label>
                        <div class="items-center">
                            <input hidden multiple type="file" name="prd_imgDetail[]" id="prd_imgDetail" />
                            <input class="green" type="button" onclick="this.parentElement.querySelector('input[type=file]').click();" value="Chose image" />
                        </div>
                        <div class="form-message"></div>
                        <div class="file_preview flex-between">
                            <?php if (!empty($product['prd_imgSub'])) {
                                $arrImg = explode(', ', $product['prd_imgSub']);
                                for ($i = 0; $i < count($arrImg); $i++) {
                                    echo '<img src="images/product/' . $arrImg[$i] . '" alt="" />';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="prd_details">Details:</label>
                <textarea name="prd_details" id="prd_details" cols="30" rows="10"><?= $product['prd_details']; ?></textarea>
                <script>
                    CKEDITOR.replace('prd_details');
                </script>
                <div class="form-message"></div>
            </div>
            <div class="flex-start">
                <button class="btn green" name="sbm" type="submit">UPDATE</button>
                <button class="btn gray" type="reset">Reset</button>
            </div>
        </div>
    </form>
</section>