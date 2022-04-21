<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$toltal_row = 0;
$sqlComm = "SELECT * FROM product";
$queryComm = mysqli_query($conn, $sqlComm);
while ($rowComm = mysqli_fetch_array($queryComm)) {
    $queryTotal = mysqli_query($conn, "SELECT * FROM prd_comments WHERE prd_id = '" . $rowComm['prd_id'] . "'");
    $countTotal = $queryTotal->num_rows;
    if ($queryTotal->num_rows > 0) {
        mysqli_query($conn, "UPDATE product SET prd_comments = $countTotal WHERE prd_id = '" . $rowComm['prd_id'] . "'");
        ++$toltal_row;
    } else {
        mysqli_query($conn, "UPDATE product SET prd_comments = 0 WHERE prd_id = '" . $rowComm['prd_id'] . "'");
    }
}

$row_per_page = 6;
$per_row = $page * $row_per_page - $row_per_page;
$total_page = ceil($toltal_row / $row_per_page);

$list_page = '';

$prev_page = $page - 1;
if ($prev_page <= 1) {
    $prev_page = 1;
}

if ($page > 1) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=comm_prd&page=' . $prev_page . '"><i class="fa fa-angle-double-left"></i></a></li>';
}

for ($i = 1; $i <= $total_page; $i++) {
    if ($i == $page) {
        $active = 'active';
    } else {
        $active = '';
    }
    $list_page .= '<li class="page-link ' . $active . '"><a href="index.php?page_layout=comm_prd&page=' . $i . '">' . $i . '</a></li>';
}

$next_page = $page + 1;
if ($next_page >= $total_page) {
    $next_page = $total_page;
}

if ($page < $total_page) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=comm_prd&page=' . $next_page . '"><i class="fa fa-angle-double-right"></i></a></li>';
}
?>
<section class="breadcumb-area">
    <h3>Administration Comments</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item"><a href="index.php?page_layout=comments">Comments</a></li>
        <li class="breadcumb-item">Products</li>
    </ul>
</section>
<section class="new-orders">
    <table class="table">
        <?php
        if ($toltal_row > 0) {
        ?>
            <div class="flex-between">
                <div class="tab-notify teal">Have <?= $toltal_row ?> products commented </div>
            </div>
        <?php } ?>
        <thead class="teal">
            <tr>
                <th class="tab-id">ID</th>
                <th class="tab-name">Products</th>
                <th class="tab-center">Total</th>
                <th class="tab-action">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM product WHERE prd_comments > 0 ORDER BY prd_id DESC LIMIT " . $per_row . ',' . $row_per_page;
            $query = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($query);
            if ($num <= 0) {
                echo "<tr><td colspan='4'><span class='alert-danger'>There are no product commented on the database ! </td></tr>";
            } else {
                while ($comm = mysqli_fetch_array($query)) {
            ?>
                    <tr>
                        <td class="tab-id"><?= $comm['prd_id']; ?></td>
                        <td class="tab-name"><?= $comm['prd_name']; ?></td>
                        <td class="tab-center"><?= $comm['prd_comments']; ?></td>
                        <td class="tab-action">
                            <a class="btn cyan" href="index.php?page_layout=comm_details&comm_type=product&id=<?= $comm['prd_id']; ?>">Details</a>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>
    <?php
    if ($toltal_row > $row_per_page) {
    ?>
        <ul class="pagination flex-end">
            <?php echo $list_page; ?>
        </ul>
    <?php } ?>
</section>