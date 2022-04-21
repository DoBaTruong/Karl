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
$toltal_row = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM blogs"));
$total_page = ceil($toltal_row / $row_per_page);

$list_page = '';

$prev_page = $page - 1;
if ($prev_page <= 1) {
    $prev_page = 1;
}

if ($page > 1) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=blogs&page=' . $prev_page . '"><i class="fa fa-angle-double-left"></i></a></li>';
}

for ($i = 1; $i <= $total_page; $i++) {
    if ($i == $page) {
        $active = 'active';
    } else {
        $active = '';
    }
    $list_page .= '<li class="page-link ' . $active . '"><a href="index.php?page_layout=blogs&page=' . $i . '">' . $i . '</a></li>';
}

$next_page = $page + 1;
if ($next_page >= $total_page) {
    $next_page = $total_page;
}

if ($page < $total_page) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=blogs&page=' . $next_page . '"><i class="fa fa-angle-double-right"></i></a></li>';
}
?>
<section class="breadcumb-area">
    <h3>Administration Blogs</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item">Blogs</li>
    </ul>
</section>
<section class="new-orders">
    <form action="index.php?page_layout=del_blog" method="post">
        <table class="table">
            <?php
            $check = mysqli_query($conn, "SELECT * FROM blogs");
            ?>
            <div class="flex-between">
                <div class="tab-notify teal">Have <?= $check->num_rows ?> posts </div>
                <div class="tab-group flex-end">
                    <button type="submit" class="btn red" name="del_all"><i class="fa fa-times"></i> Delete All</button>
                    <a class="btn green" href="index.php?page_layout=add_blog"><i class="fa fa-plus"></i> Add News</a>
                </div>
            </div>
            <thead class="teal">
                <tr>
                    <th><input type="checkbox" name="check" data-toggle="checkall" data-target=".item" /></th>
                    <th class="tab-id">ID</th>
                    <th class="tab-center">Title</th>
                    <th class="tab-image">Topic</th>
                    <th class="tab-center">Abstract</th>
                    <th class="tab-center">Authors</th>
                    <th class="tab-center">Comments</th>
                    <th class="tab-action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM blogs INNER JOIN categories ON blogs.cat_id = categories.cat_id  ORDER BY blog_id DESC LIMIT " . $per_row . ',' . $row_per_page;
                $query = mysqli_query($conn, $sql);
                $num = mysqli_num_rows($query);
                if ($num <= 0) {
                    echo "<tr><td colspan='8'><span class='alert-danger'>There are no customer on the database ! </td></tr>";
                } else {
                    while ($row = mysqli_fetch_array($query)) {
                ?>
                        <tr>
                            <td><input type="checkbox" name="del_id[]" class="item" value="<?= $row['blog_id']; ?>" /></td>
                            <td class="tab-id"><?= $row['blog_id']; ?></td>
                            <td class="tab-center tab-img">
                                <div class="flex-center">
                                    <img src="images/blog/<?= $row['blog_image']; ?>" alt="" />
                                </div>
                                <p class="content">
                                    <?= $row['blog_title']; ?>
                                </p>
                            </td>
                            <td class="tab-center"><?= mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM categories WHERE cat_id = '" . $row['blog_topic'] . "'"))['cat_name']; ?></td>
                            <td class="tab-name">
                                <div data-toggle="limit" data-line="4" class="limit-content">
                                    <?= $row['blog_content'] ?>
                                </div>
                            </td>
                            <td class="tab-center"><?= $row['blog_authors']; ?></td>
                            <td class="tab-center">
                                <a href="index.php?page_layout=blog_comment&blog_id=<?= $row['blog_id']; ?>">
                                    <?= mysqli_query($conn, "SELECT * FROM blog_comments WHERE comm_id = '" . $row['blog_id'] . "'")->num_rows; ?>
                                </a>
                            </td>
                            <td class="tab-action">
                                <div class="flex-center">
                                    <a class="btn cyan" href="index.php?page_layout=edit_blog&page=<?= $page; ?>&edit_id=<?= $row['blog_id']; ?>"><i class="fa fa-edit"></i></a>
                                    <a data-toggle="modal" data-target="#confimDelete" data-name="<?= $row['blog_title']; ?>" data-href="index.php?page_layout=del_blog&del_id=<?= $row['blog_id']; ?>" class="btn red" href="#"><i class="fa fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                <?php }
                } ?>
            </tbody>
            <div id="confimDelete" class="modal flex-center" role="dialog">
                <div class="modal-dialog" role="document">
                    <p class="modal-header">Are you sure to delete "<span data-type="show-name"></span>" ?</p>
                    <a class="btn cyan" href="#" data-dismiss="modal">Cancel</a>
                    <a class="btn red" data-submit="modal">Sure</a>
                </div>
            </div>
        </table>
    </form>
    <?php
    if ($toltal_row > $row_per_page) {
    ?>
        <ul class="pagination flex-end">
            <?php echo $list_page; ?>
        </ul>
    <?php } ?>
</section>