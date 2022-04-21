<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

if (isset($_POST['deleteComm'])) {
    $arrID = $_POST['del_id'];
    $strID = implode(', ', $arrID);
    mysqli_query($conn, "DELETE FROM supports WHERE supp_id IN ($strID)");
}

$row_per_page = 6;
$per_row = $page * $row_per_page - $row_per_page;
$toltal_row = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM supports"));
$total_page = ceil($toltal_row / $row_per_page);

$list_page = '';

$prev_page = $page - 1;
if ($prev_page <= 1) {
    $prev_page = 1;
}

if ($page > 1) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=supports&page=' . $prev_page . '"><i class="fa fa-angle-double-left"></i></a></li>';
}

for ($i = 1; $i <= $total_page; $i++) {
    if ($i == $page) {
        $active = 'active';
    } else {
        $active = '';
    }
    $list_page .= '<li class="page-link ' . $active . '"><a href="index.php?page_layout=supports&page=' . $i . '">' . $i . '</a></li>';
}

$next_page = $page + 1;
if ($next_page >= $total_page) {
    $next_page = $total_page;
}

if ($page < $total_page) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=supports&page=' . $next_page . '"><i class="fa fa-angle-double-right"></i></a></li>';
}
?>
<section class="breadcumb-area">
    <h3>Administration Supports</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item">Supports</li>
    </ul>
</section>
<section class="new-orders">
    <form method="post">
        <table class="table">
            <div class="flex-end">
                <div class="tab-group flex-end">
                    <button type="submit" class="btn red" name="deleteComm"><i class="fa fa-trash"></i> Delete</button>
                </div>
            </div>
            <thead class="teal">
                <tr>
                    <th><input type="checkbox" name="check" data-toggle="checkall" data-target=".item" /></th>
                    <th colspan="2" class="tab-name">Name</th>
                    <th class="tab-name">Email</th>
                    <th class="tab-name">Inquiry Type</th>
                    <th class="tab-name">Contents</th>
                    <th class="tab-action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM supports ORDER BY supp_id DESC LIMIT " . $per_row . ',' . $row_per_page;

                $query = mysqli_query($conn, $sql);
                $num = mysqli_num_rows($query);
                if ($num <= 0) {
                    echo "<tr><td colspan='5'><span class='alert-danger'>There are no inquiry on the database ! </td></tr>";
                } else {
                    while ($supp = mysqli_fetch_array($query)) {
                ?>
                        <tr>
                            <td><input type="checkbox" name="del_id[]" class="item" value="<?= $supp['supp_id']; ?>" /></td>
                            <td colspan="2" class="tab-name"><?= $supp['supp_name']; ?></td>
                            <td class="tab-name"><?= $supp['supp_mail']; ?></td>
                            <td class="tab-name"><?= $supp['supp_type']; ?></td>
                            <td class="tab-name"><?= $supp['supp_content']; ?></td>
                            <td class="tab-action">
                                <div class="flex-center">
                                    <?php 
                                        if ($supp['supp_reply'] == "") {
                                    ?>
                                    <a class="btn cyan" data-toggle="modal" data-target="#replyComment" data-name="<?= $supp['supp_id']; ?>"><i class="fa fa-reply"></i></a>
                                    <?php 
                                        } else {
                                            echo '<span class="text-success"><i class="fa fa-check"></i></span>';
                                        }
                                    ?>
                                </div>
                            </td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>
    </form>
    <div id="replyComment" class="modal items-center" role="dialog">
        <div class="modal-dialog" role="document">
            <form method="post">
                <input hidden data-type="show-name" type="number" name="reply_id" id="reply_id" />
                <div class="form-group">
                    <label for="supp_detail">Content:</label>
                    <textarea required class="ckeditor" name="supp_detail" id="supp_detail"></textarea>
                    <div class="form-message"></div>
                </div>
                <div class="flex-start">
                    <a class="btn cyan" href="#" data-dismiss="modal">Cancel</a>
                    <button type="submit" name="replyInquiry" class="btn red">Reply</a>
                </div>
            </form>
        </div>
    </div>
    <?php
    if ($toltal_row > $row_per_page) {
    ?>
        <ul class="pagination flex-end">
            <?php echo $list_page; ?>
        </ul>
    <?php } ?>
</section>