<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
if (isset($_POST['blog_title']) && isset($_POST['blog_topic'])) {
    $title = $_POST['blog_title'];
    $topic = $_POST['blog_topic'];
    $check = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM blogs WHERE blog_title = '$title'"));
    if (isset($check)) {
        $error = '<div class="alert-danger">Title already exists ! </div>';
    } else {
        $authors = $_POST['blog_authors'];
        $cat_id = $_POST['cat_id'];
        $arrName = explode(' ', preg_replace("/[^A-Za-z0-9\-]/", ' ', $title));
        $arrEx = explode('/', $_FILES['blog_image']['type']);
        $img = mb_strtolower(convert_name(implode('_', $arrName)) . '.' . end($arrEx));
        $des = 'images/blog/' . $img;
        if (file_exists($des)) {
            $increment = 0;
            $info = pathinfo($des);
            while (file_exists($des)) {
                $increment++;
                $img = $info['filename'] . '-' . $increment . '.' . $info['extension'];
                $des = "images/blog/" . $img;
            }
        }
        move_uploaded_file($_FILES['blog_image']['tmp_name'], $des);

        $details = $_POST['blog_details'];
        $date = date('Y-m-d h:i:s');

        $status = mysqli_query($conn, "INSERT INTO blogs (cat_id, blog_title, blog_topic, blog_image, blog_authors, blog_content, blog_post) VALUES ($cat_id, '$title', '$topic', '$img', '$authors', '$details', '$date')");
        if (isset($status)) {
            header('location: index.php?page_layout=blogs');
        }
    }
}
?>
<section class="breadcumb-area">
    <h3>Administration Blogs</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item"><a href="index.php?page_layout=Blogs">Blogs</a></li>
        <li class="breadcumb-item">Add News</li>
    </ul>
</section>
<section class="add_page">
    <?php
    if (!empty($error)) {
        echo $error;
    }
    ?>
    <form method="post" id="add_blog" class="flex-between" enctype="multipart/form-data">
        <div class="form-item">
            <div class="form-group">
                <label for="blog_title">Title:</label>
                <input type="text" name="blog_title" id="blog_title" />
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <label for="cat_id">Categories:</label>
                <?php
                $sqlCat = "SELECT * FROM categories WHERE cat_type = 2";
                $queryCat = mysqli_query($conn, $sqlCat);
                $dataCat = array();
                while ($catTest = mysqli_fetch_array($queryCat)) {
                    $dataCat[] = $catTest['cat_name'] . ':' . $catTest['cat_call'] . '-' . $catTest['cat_id'];
                }
                $stringData = implode('; ', $dataCat);
                ?>
                <select data-toggle="movedata" data-parent=".form-item" data-target="#blog_topic" name="cat_id" id="cat_id" data-catego="<?php echo $stringData; ?>">
                    <option value="">----Defaulf Select----</option>
                    <?php
                    $catSQL = "SELECT * FROM categories WHERE cat_type = 3";
                    $catQUERY = mysqli_query($conn, $catSQL);
                    while ($category = mysqli_fetch_array($catQUERY)) {
                    ?>
                        <option value="<?= $category['cat_id']; ?>"><?= $category['cat_name']; ?></option>
                    <?php } ?>
                </select>
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <label for="blog_topic">Topic:</label>
                <select disabled name="blog_topic" id="blog_topic">
                    <option value="">----Default Select----</option>
                </select>
                <div class="form-message"></div>
            </div>
            <div class="form-group">
                <label for="blog_details">Details:</label>
                <textarea name="blog_details" id="blog_details" cols="30" rows="10"></textarea>
                <script>
                    CKEDITOR.replace('blog_details');
                </script>
                <div class="form-message"></div>
            </div>
        </div>
        <div class="form-item">
            <div class="form-group blog-page">
                <label for="blog_image">Image:</label>
                <div class="items-center">
                    <input hidden type="file" name="blog_image" id="blog_image" />
                    <input class="green" type="button" onclick="this.parentElement.querySelector('input[type=file]').click();" value="Chose image" />
                </div>
                <div class="form-message"></div>
                <div class="file_preview"></div>
            </div>
            <div class="form-group">
                <label for="blog_authors">Authors:</label>
                <input type="text" name="blog_authors" id="blog_authors" />
                <div class="form-message"></div>
            </div>
            <div class="flex-start">
                <button class="btn green" name="sbm" type="submit">ADD</button>
                <button class="btn gray" type="reset">Reset</button>
            </div>
        </div>
    </form>
</section>