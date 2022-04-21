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
$toltal_row = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM product"));
$total_page = ceil($toltal_row / $row_per_page);

$list_page = '';

$prev_page = $page - 1;
if ($prev_page <= 1) {
    $prev_page = 1;
}

if ($page > 1) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=products&page=' . $prev_page . '"><i class="fa fa-angle-double-left"></i></a></li>';
}

for ($i = 1; $i <= $total_page; $i++) {
    if ($i == $page) {
        $active = 'active';
    } else {
        $active = '';
    }
    $list_page .= '<li class="page-link ' . $active . '"><a href="index.php?page_layout=products&page=' . $i . '">' . $i . '</a></li>';
}

$next_page = $page + 1;
if ($next_page >= $total_page) {
    $next_page = $total_page;
}

if ($page < $total_page) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=products&page=' . $next_page . '"><i class="fa fa-angle-double-right"></i></a></li>';
}
?>
<section class="breadcumb-area">
    <h3>Administration Products</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item">Products</li>
    </ul>
</section>
<section class="new-orders">
    <form action="index.php?page_layout=del_prd" method="post">
        <table class="table">
            <?php
            $check = mysqli_query($conn, "SELECT * FROM product");
            ?>
            <div class="flex-between">
                <div class="tab-notify teal">Have <?= $check->num_rows ?> products </div>
                <div class="tab-group flex-end">
                    <button type="submit" class="btn red" name="del_all"><i class="fa fa-trash"></i> Delete All</button>
                    <a class="btn green" href="index.php?page_layout=add_prd"><i class="fa fa-plus"></i> Add Product</a>
                </div>
            </div>
            <thead class="teal">
                <tr>
                    <th><input type="checkbox" name="check" data-toggle="checkall" data-target=".item" /></th>
                    <th class="tab-id">ID</th>
                    <th class="tab-name">Product</th>
                    <th class="tab-center">Image</th>
                    <th class="tab-center">Price</th>
                    <th class="tab-center">Quantity</th>
                    <th class="tab-center">Sell</th>
                    <th class="tab-center">Comments</th>
                    <th class="tab-center">Category</th>
                    <th class="tab-action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM product INNER JOIN categories ON product.cat_id = categories.cat_id  ORDER BY prd_id DESC LIMIT " . $per_row . ',' . $row_per_page;
                $query = mysqli_query($conn, $sql);
                $num = mysqli_num_rows($query);
                if ($num <= 0) {
                    echo "<tr><td colspan='10'><span class='alert-danger'>There are no customer on the database ! </td></tr>";
                } else {
                    while ($row = mysqli_fetch_array($query)) {
                ?>
                        <tr>
                            <td><input type="checkbox" name="del_id[]" class="item" value="<?= $row['prd_id']; ?>" /></td>
                            <td class="tab-id"><?= $row['prd_id']; ?></td>
                            <td class="tab-name"><?= $row['prd_name']; ?></td>
                            <td class="tab-img"><img src="images/product/<?= $row['prd_image']; ?>" alt="" /></td>
                            <td class="tab-center"><?= $row['prd_price']; ?> $</td>
                            <td class="tab-center"><?= $row['prd_quantity'] - $row['prd_sell']; ?></td>
                            <td class="tab-center"><?= $row['prd_sell']; ?></td>
                            <td class="tab-center">
                                <a href="index.php?page_layout=comm_details&comm_type=product&id=<?= $row['prd_id']; ?>"><?= $row['prd_comments']; ?></a>
                            </td>
                            <td class="tab-center"><?= $row['cat_name']; ?></td>
                            <td class="tab-action">
                                <div class="flex-center">
                                    <a class="btn cyan" href="index.php?page_layout=edit_prd&edit_id=<?= $row['prd_id']; ?>"><i class="fa fa-edit"></i></a>
                                    <a data-toggle="modal" data-target="#confimDelete" data-name="<?= $row['prd_name']; ?>" data-href="index.php?page_layout=del_prd&del_id=<?= $row['prd_id']; ?>" class="btn red" href="#"><i class="fa fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                <?php }
                } ?>
            </tbody>
            <div id="confimDelete" class="modal flex-center" role="dialog">
                <div class="modal-dialog" role="document">
                    <p class="modal-header">Are you sure to delete <span data-type="show-name"></span> ?</p>
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