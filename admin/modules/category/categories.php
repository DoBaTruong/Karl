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
$toltal_row = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM categories WHERE cat_call = 0"));
$total_page = ceil($toltal_row / $row_per_page);

$list_page = '';

$prev_page = $page - 1;
if ($prev_page <= 1) {
    $prev_page = 1;
}

if ($page > 1) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=catego&page=' . $prev_page . '"><i class="fa fa-angle-double-left"></i></a></li>';
}

for ($i = 1; $i <= $total_page; $i++) {
    if ($i == $page) {
        $active = 'active';
    } else {
        $active = '';
    }
    $list_page .= '<li class="page-link ' . $active . '"><a href="index.php?page_layout=catego&page=' . $i . '">' . $i . '</a></li>';
}

$next_page = $page + 1;
if ($next_page >= $total_page) {
    $next_page = $total_page;
}

if ($page < $total_page) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=catego&page=' . $next_page . '"><i class="fa fa-angle-double-right"></i></a></li>';
}
?>
<section class="breadcumb-area">
    <h3>Administration Categories</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item">Categories</li>
    </ul>
</section>
<section class="new-orders">
    <table class="table tab-cat tab-border">
        <?php
        $check = mysqli_query($conn, "SELECT * FROM categories WHERE cat_call = 0");
        $check2 = mysqli_query($conn, "SELECT * FROM categories WHERE cat_type = 0");
        ?>
        <div class="flex-between">
            <div class="tab-notify teal">Have <?= $check->num_rows ?> categories (<?= $check2->num_rows; ?> brands/topics) </div>
            <div class="tab-group flex-end">
                <a class="btn green" href="index.php?page_layout=add_cat"><i class="fa fa-plus"></i> Add Category</a>
            </div>
        </div>
        <thead class="teal">
            <tr>
                <th class="tab-id">ID</th>
                <th class="tab-name">Categories</th>
                <th class="tab-name">Brands/Topics</th>
                <th class="tab-center">Type</th>
                <th class="tab-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM categories WHERE cat_call = 0 ORDER BY cat_id DESC LIMIT " . $per_row . ',' . $row_per_page;
            $query = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($query);
            if ($toltal_row <= 0) {
                echo "<tr><td colspan='8'><span class='alert-danger'>There are no category on the database ! </td></tr>";
            } else {
                while ($cat = mysqli_fetch_array($query)) {
            ?>
                    <tr>
                        <td class="tab-id"><?= $cat['cat_id']; ?></td>
                        <td class="tab-name"><?= $cat['cat_name']; ?></td>
                        <td class="tab-name">
                            <?php
                            $brandSql = "SELECT * FROM categories WHERE cat_call = " . $cat['cat_id'];
                            $brandQuery = mysqli_query($conn, $brandSql);
                            if ($brandQuery->num_rows == 0) {
                                echo 'No brand/topic';
                            } else {
                                while ($brand = mysqli_fetch_array($brandQuery)) {
                            ?>
                                    <div class="tab-cat-item flex-between">
                                        <span><?= $brand['cat_name']; ?></span>
                                        <div class="flex-end">
                                            <a href="#" data-toggle="modal" data-target="#confirmCat" data-name="<?= $brand['cat_name']; ?>" data-href="index.php?page_layout=del_cat&del_id=<?= $brand['cat_id']; ?>" class="purple">delete</a>
                                            <a href="index.php?page_layout=edit_cat&edit_id=<?= $brand['cat_id']; ?>&brand_name=<?= $cat['cat_id']; ?>" class="orange">edit</a>
                                        </div>
                                    </div>
                            <?php }
                            } ?>
                        </td>
                        <td class="tab-center">
                            <?php
                            switch ($cat['cat_type']) {
                                case '1':
                                    echo '<span class="manager">Product</span>';
                                    break;
                                case '3':
                                    echo '<span class="admin">Blog</span>';
                                    break;
                            }
                            ?>
                        </td>
                        <td class="tab-action">
                            <div class="flex-center">
                                <a class="btn cyan" href="index.php?page_layout=edit_cat&edit_id=<?= $cat['cat_id']; ?>"><i class="fa fa-edit"></i></a>
                                <a class="btn green" href="index.php?page_layout=add_cat&brand_name=<?= $cat['cat_type']; ?>&id=<?= $cat['cat_id']; ?>"><i class="fa fa-plus"></i></a>
                                <a data-toggle="modal" data-target="#confirmCat" data-name="<?= $cat['cat_name']; ?>" data-href="index.php?page_layout=del_cat&del_id=<?= $cat['cat_id']; ?>" class="btn red" href="#"><i class="fa fa-times"></i></a>
                            </div>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
        <div id="confirmCat" class="modal flex-center" role="dialog">
            <div class="modal-dialog" role="document">
                <p class="modal-header">Are you sure to delete <span data-type="show-name"></span> ?</p>
                <a class="btn cyan" href="#" data-dismiss="modal">Cancel</a>
                <a class="btn red" data-submit="modal">Sure</a>
            </div>
        </div>
    </table>
    <?php
    if ($toltal_row > $row_per_page) {
    ?>
        <ul class="pagination flex-end">
            <?php echo $list_page; ?>
        </ul>
    <?php } ?>
</section>