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
    $toltal_row = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM staffs"));
    $total_page = ceil($toltal_row/$row_per_page);

    $list_page = '';

    $prev_page = $page - 1;
    if ($prev_page <= 1) {
        $prev_page = 1;
    }

    if ($page > 1) {
        $list_page .= '<li class="page-link"><a href="index.php?page_layout=staff&page='.$prev_page.'"><i class="fa fa-angle-double-left"></i></a></li>';
    }

    for ($i = 1; $i <= $total_page; $i++) {
        if ($i == $page) {
            $active = 'active';
        } else {
            $active = '';
        }
        $list_page .= '<li class="page-link '.$active.'"><a href="index.php?page_layout=staff&page='.$i.'">'.$i.'</a></li>';
    }

    $next_page = $page + 1;
    if ($next_page >= $total_page) {
        $next_page = $total_page;
    }

    if ($page < $total_page) {
        $list_page .= '<li class="page-link"><a href="index.php?page_layout=staff&page='.$next_page.'"><i class="fa fa-angle-double-right"></i></a></li>';
    } 
?>
<section class="breadcumb-area">
    <h3>Administration Staffs</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item">Staffs</li>
    </ul>
</section>
<section class="new-orders">
    <form action="index.php?page_layout=del_user" method="post">
        <table class="table">
            <?php 
                $check = mysqli_query($conn, "SELECT * FROM staffs");
            ?>
            <div class="flex-between">
                <div class="tab-notify teal">Have <?= $check->num_rows ?> employees</div>
                <div class="tab-group flex-end">
                    <button type="submit"class="btn red" name="del_all"><i class="fa fa-trash"></i> Delete All</button>
                    <a class="btn green" href="index.php?page_layout=add_staff"><i class="fa fa-user-plus"></i> Add Staff</a>
                </div>
            </div>
            <thead class="teal">
                <tr>
                    <th><input type="checkbox" name="check" data-toggle="checkall" data-target=".item" /></th>
                    <th class="tab-id">ID</th>
                    <th class="tab-name">Full name</th>
                    <th class="tab-address">Address</th>
                    <th class="tab-tel">Phone</th>
                    <th class="tab-mail">Email</th>
                    <th class="tab-permiss">Permission</th>
                    <th class="tab-action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql = "SELECT * FROM staffs ORDER BY user_id DESC LIMIT ".$per_row.','.$row_per_page;
                    $query = mysqli_query($conn, $sql);
                    $num = mysqli_num_rows($query);
                    if ($num <= 0) {
                        echo "<tr><td colspan='8'><span class='alert-danger'>There are no employee on the database ! </td></tr>";
                    } else {
                        while ($employee = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><input type="checkbox" name="del_id[]" class="item" value="<?= $employee['user_id']; ?>" /></td>
                    <td class="tab-id"><?= $employee['user_id']; ?></td>
                    <td class="tab-name"><?= $employee['user_full']; ?></td>
                    <td class="tab-address"><?= $employee['user_add']; ?></td>
                    <td class="tab-tel"><?= $employee['user_tel']; ?></td>
                    <td class="tab-mail"><?= $employee['user_mail']; ?></td>                   
                    <td class="tab-permiss">
                        <?php 
                            switch ($employee['user_level']) {
                                case '0': echo '<span class="admin">Admin</span>'; break;
                                case '1': echo '<span class="manager">Manager</span>'; break;
                                case '2': echo '<span class="staff">Staff</span>';
                            }
                        ?>
                    </td>                   
                    <td class="tab-action">
                        <div class="flex-center">
                            <a class="btn cyan" href="index.php?page_layout=edit_staff&edit_id=<?= $employee['user_id'];?>"><i class="fa fa-user-edit"></i></a>
                            <a data-toggle="modal" data-target="#confimDelete" data-name="<?= $employee['user_mail']; ?>" data-href="index.php?page_layout=del_user&del_id=<?= $employee['user_id'];?>" class="btn red" href="#"><i class="fa fa-user-times"></i></a>
                        </div>
                    </td>
                </tr>
                <?php }} ?>
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