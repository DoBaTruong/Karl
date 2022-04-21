<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}

$id = $_GET['id'];

$type = $_GET['comm_type'];
switch ($type) {
    case 'product':
        $prd = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM product WHERE prd_id = $id"));
        break;
    case 'blog':
        $blog = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM blogs WHERE blog_id = $id"));
}
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

if (isset($_POST['replyComm'])) {
    $replyfor = $_POST['reply_id'];
    $content = $_POST['comment_detail'];
    $datecomm = date('Y-m-d H:i:s');
    $commMail = $_SESSION['mail'];
    $commName = 'Karl Fashion Admin';
    switch ($type) {
        case 'product':
            mysqli_query($conn, "INSERT INTO prd_comments (prd_id, comm_name, comm_mail, comm_details, comm_repfor, comm_date, comm_call) VALUES ($id, '$commName', '$commMail', '$content', $replyfor, '$datecomm', 1)");
            break;
        case 'blog':
            mysqli_query($conn, "INSERT INTO blog_comments (blog_id, comm_name, comm_mail, comm_details, comm_repfor, comm_date, comm_call) VALUES ($id, '$commName', '$commMail', '$content', $replyfor, '$datecomm', 1)");
            break;
    }
}

if (isset($_POST['deleteComm'])) {
    $arrID = $_POST['del_id'];
    $strID = implode(', ', $arrID);
    switch ($type) {
        case 'product';
            mysqli_query($conn, "DELETE FROM prd_comments WHERE comm_id IN ($strID)"); break;
        case 'blog':
            mysqli_query($conn, "DELETE FROM blog_comments WHERE comm_id IN ($strID)");
    }
}

$row_per_page = 6;
$per_row = $page * $row_per_page - $row_per_page;
$toltal_row = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM prd_comments WHERE prd_id = $id"));
$total_page = ceil($toltal_row / $row_per_page);

$list_page = '';

$prev_page = $page - 1;
if ($prev_page <= 1) {
    $prev_page = 1;
}

if ($page > 1) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=comm_details&comm_type=' . $type . '&id=' . $id . '&page=' . $prev_page . '"><i class="fa fa-angle-double-left"></i></a></li>';
}

for ($i = 1; $i <= $total_page; $i++) {
    if ($i == $page) {
        $active = 'active';
    } else {
        $active = '';
    }
    $list_page .= '<li class="page-link ' . $active . '"><a href="index.php?page_layout=comm_details&comm_type=' . $type . '&id=' . $id . '&page=' . $i . '">' . $i . '</a></li>';
}

$next_page = $page + 1;
if ($next_page >= $total_page) {
    $next_page = $total_page;
}

if ($page < $total_page) {
    $list_page .= '<li class="page-link"><a href="index.php?page_layout=comm_details&comm_type=' . $type . '&id=' . $id . '&page=' . $next_page . '"><i class="fa fa-angle-double-right"></i></a></li>';
}
?>
<section class="breadcumb-area">
    <h3>Administration Comments</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item"><a href="index.php?page_layout=<?php if (isset($_GET['comm_type']) && $_GET['comm_type'] == 'product') {
                                                                        echo 'comm_prd';
                                                                    } else {
                                                                        echo 'comm_blog';
                                                                    } ?>">Comments</a></li>
        <li class="breadcumb-item limit-title">
            <?php
            switch ($type) {
                case 'product':
                    echo $prd['prd_name'];
                    break;
                case 'blog':
                    echo $blog['blog_title'];
            }
            ?>
        </li>
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
                    <th class="tab-name">Contents</th>
                    <th class="tab-action">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                switch ($type) {
                    case 'product':
                        $sql = "SELECT * FROM prd_comments INNER JOIN customers ON prd_comments.cus_id = customers.cus_id WHERE prd_id = $id AND comm_call = 0 ORDER BY comm_id DESC LIMIT " . $per_row . ',' . $row_per_page;
                        break;
                    case 'blog':
                        $sql = "SELECT * FROM blog_comments INNER JOIN customers ON blog_comments.cus_id = customers.cus_id WHERE blog_id = $id AND comm_call = 0 ORDER BY comm_id DESC LIMIT " . $per_row . ',' . $row_per_page;
                }

                $query = mysqli_query($conn, $sql);
                $num = mysqli_num_rows($query);
                if ($num <= 0) {
                    echo "<tr><td colspan='6'><span class='alert-danger'>There are no comments on the database ! </td></tr>";
                } else {
                    while ($comments = mysqli_fetch_array($query)) {
                ?>
                        <tr>
                            <td><input type="checkbox" name="del_id[]" class="item" value="<?= $comments['comm_id']; ?>" /></td>
                            <td colspan="2" class="tab-name"><?= $comments['cus_name']; ?></td>
                            <td class="tab-name"><?= $comments['cus_mail']; ?></td>
                            <td class="tab-tel"><?= $comments['comm_details']; ?></td>
                            <td class="tab-action">
                                <div class="flex-center">
                                    <a class="btn cyan" data-toggle="modal" data-target="#replyComment" data-name="<?= $comments['comm_id']; ?>"><i class="fa fa-reply"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php
                        switch ($type) {
                            case 'product':
                                $chilSql = "SELECT * FROM prd_comments INNER JOIN customers ON prd_comments.cus_id = customers.cus_id WHERE prd_id = $id AND comm_call = 1 AND comm_repfor = '" . $comments['comm_id'] . "' ORDER BY comm_id ASC";
                                break;
                            case 'blog':
                                $chilSql = "SELECT * FROM blog_comments INNER JOIN customers ON blog_comments.cus_id = customers.cus_id WHERE blog_id = $id AND comm_call = 1 AND comm_repfor = '" . $comments['comm_id'] . "' ORDER BY comm_id ASC";
                        }


                        $chilQuery = mysqli_query($conn, $chilSql);
                        while ($chilComm = mysqli_fetch_array($chilQuery)) {
                        ?>
                            <tr>
                                <td><input type="checkbox" name="del_id[]" class="item" value="<?= $chilComm['comm_id']; ?>" /></td>
                                <td class="tab-icon tab-right"><i class="fa fa-level-down-alt"></i></td>
                                <td class="tab-name"><?= $chilComm['cus_name']; ?></td>
                                <td class="tab-name"><?= $chilComm['cus_mail']; ?></td>
                                <td class="tab-tel"><?= $chilComm['comm_details']; ?></td>
                                <td class="tab-action">
                                    <div class="flex-center">
                                        <a class="btn cyan" data-toggle="modal" data-target="#replyComment" data-name="<?= $chilComm['comm_id']; ?>"><i class="fa fa-reply"></i></a>
                                    </div>
                                </td>
                            </tr>
                <?php }
                    }
                } ?>
            </tbody>
        </table>
    </form>
    <div id="replyComment" class="modal items-center" role="dialog">
        <div class="modal-dialog" role="document">
            <form method="post">
                <input hidden data-type="show-name" type="number" name="reply_id" id="reply_id" />
                <div class="form-group">
                    <label for="comment_detail">Content:</label>
                    <input required type="text" name="comment_detail" id="comment_detail" value="" />
                    <div class="form-message"></div>
                </div>
                <div class="flex-start">
                    <a class="btn cyan" href="#" data-dismiss="modal">Cancel</a>
                    <button type="submit" name="replyComm" class="btn red">Send</a>
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