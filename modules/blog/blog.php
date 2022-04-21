<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$row_per_page = 6;
$per_row = $page * $row_per_page - $row_per_page;
if (isset($_POST['keyblog']) || isset($_GET['keyblog'])) {
    if (isset($_POST['keyblog'])) {
        $keyblog = $_POST['keyblog'];
    } else {
        $keyblog = $_GET['keyblog'];
    }
    $arrKey = explode(' ', $keyblog);
    $newkey = '%' . implode('%', $arrKey) . '%';
    $getContent = '&keyblog=' . $keyblog;
    $keySql = 'WHERE blog_title LIKE  "' . $newkey . '"';
} else {
    if (isset($_GET['cat_id'])) {
        $catId = $_GET['cat_id'];
        $getContent = '&cat_id=' . $catId;
        $keySql = 'WHERE blog_topic = "' . $catId . '"';
    } else {
        $getContent = '';
        $keySql = '';
    }
}
$totalSql = "SELECT * FROM blogs  " . $keySql;
$toltal_row = mysqli_num_rows(mysqli_query($conn, $totalSql));
$total_page = ceil($toltal_row / $row_per_page);

$list_page = '';

$prev_page = $page - 1;
if ($prev_page <= 1) {
    $prev_page = 1;
}

if ($page > 1) {
    $list_page .= '<li class="page-item"><a class="page-link" href="index.php?page_layout=blog' . $getContent . '&page=' . $prev_page . '">&laquo;</a></li>';
}

for ($i = 1; $i <= $total_page; $i++) {
    if ($i == $page) {
        $active = 'active';
    } else {
        $active = '';
    }
    $list_page .= '<li class="page-item ' . $active . '"><a class="page-link" href="index.php?page_layout=blog' . $getContent . '&page=' . $i . '">' . $i . '</a></li>';
}

$next_page = $page + 1;
if ($next_page >= $total_page) {
    $next_page = $total_page;
}

if ($page < $total_page) {
    $list_page .= '<li class="page-item"><a class="page-link" href="index.php?page_layout=blog' . $getContent . '&page=' . $next_page . '">&raquo;</a></li>';
}
?>
<section class="new-arrivals-area mb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <?php include_once('modules/sidebars/side-blog.php') ?>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="shop-grid-product-area">
                    <div class="row shop-new-arrivals">
                        <!-- Single gallery Item -->
                        <?php
                        $sqlBl = "SELECT * FROM blogs " . $keySql . " ORDER BY blog_id DESC LIMIT " . $per_row . ',' . $row_per_page;
                        $queBl = mysqli_query($conn, $sqlBl);
                        if ($queBl->num_rows == 0) {
                            echo '<span class="alert-danger">No posts on database !</span>';
                        } else {
                            while ($blog = mysqli_fetch_array($queBl)) {
                        ?>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="blog-item">
                                        <div class="blog-image">
                                            <img src="admin/images/blog/<?= $blog['blog_image'] ?>" alt="" />
                                        </div>
                                        <div class="blog-details">
                                            <div class="blog-time"><?= $blog['blog_like'] ?> like - <?= $blog['blog_view'] ?> Views - <?= date("M d, Y", strtotime($blog['blog_post'])) ?></div>
                                            <div class="blog-title"><?= $blog['blog_title'] ?></div>
                                            <div data-toggle="limit" data-line="5" class="blog-content"><?= $blog['blog_content'] ?></div>
                                            <a href="index.php?page_layout=blog_details&blog_id=<?= $blog['blog_id'] ?>" class="btn read-more">Read More</a>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                    <?php
                    if ($toltal_row > $row_per_page) {
                    ?>
                        <ul class="shop-pagination flex-end">
                            <?php echo $list_page; ?>
                        </ul>
                    <?php }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>