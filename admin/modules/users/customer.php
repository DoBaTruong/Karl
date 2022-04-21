<?php
	if(!defined('SECURITY')) {
        die("You don't have authorization to view this page !");
    }

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $row_per_page = 6;
    $per_row = $page * $row_per_page - $row_per_page;
    $toltal_row = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM customers"));
    $total_page = ceil($toltal_row/$row_per_page);

    $list_page = '';

    $prev_page = $page - 1;
    if ($prev_page <= 1) {
        $prev_page = 1;
    }

    if ($page > 1) {
        $list_page .= '<li class="page-link"><a href="index.php?page_layout=customer&page='.$prev_page.'"><i class="fa fa-angle-double-left"></i></a></li>';
    }

    for ($i = 1; $i <= $total_page; $i++) {
        if ($i == $page) {
            $active = 'active';
        } else {
            $active = '';
        }
        $list_page .= '<li class="page-link '.$active.'"><a href="index.php?page_layout=customer&page='.$i.'">'.$i.'</a></li>';
    }

    $next_page = $page + 1;
    if ($next_page >= $total_page) {
        $next_page = $total_page;
    }

    if ($page < $total_page) {
        $list_page .= '<li class="page-link"><a href="index.php?page_layout=customer&page='.$next_page.'"><i class="fa fa-angle-double-right"></i></a></li>';
    } 
?>
<section class="breadcumb-area">
    <h3>Administration Customers</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item">Customers</li>
    </ul>
</section>
<section class="new-orders">
    <form action="index.php?page_layout=member" method="post">
        <table class="table">
            <?php 
                $check = mysqli_query($conn, "SELECT * FROM customers");
                if ($check->num_rows > 0) {
            ?>
            <div class="flex-between">
                <div class="tab-notify teal">Have <?= $check->num_rows ?> customers </div>
            </div>
            <?php } ?>
            <thead class="teal">
                <tr>
                    <th class="tab-id">ID</th>
                    <th class="tab-name">Full name</th>
                    <th class="tab-address">Address</th>
                    <th class="tab-tel">Phone</th>
                    <th class="tab-mail">Email</th>
                    <th class="tab-permiss">Total Amounts</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql = "SELECT * FROM customers ORDER BY cus_id DESC LIMIT ".$per_row.','.$row_per_page;
                    $query = mysqli_query($conn, $sql);
                    $num = mysqli_num_rows($query);
                    if ($num <= 0) {
                        echo "<tr><td colspan='8'><span class='alert-danger'>There are no customer on the database ! </td></tr>";
                    } else {
                        while ($employee = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td class="tab-id"><?= $employee['cus_id']; ?></td>
                    <td class="tab-name"><?= $employee['cus_name']; ?></td>
                    <td class="tab-address"><?= $employee['cus_add']; ?></td>
                    <td class="tab-tel"><?= $employee['cus_phone']; ?></td>
                    <td class="tab-mail"><?= $employee['cus_mail']; ?></td>   
                    <td class="tab-permiss"><?= $employee['cus_amount']; ?>$</td>
                </tr>
                <?php }} ?>
            </tbody>
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