<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
} 
?>

<div class="nav-side-menu">
    <h6>Categories</h6>
    <ul class="menu-list">
        <!-- Single Item -->
        <?php
        $sql = "SELECT * FROM categories WHERE cat_type = 1 ORDER BY cat_name ASC";
        $query = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($query)) {
        ?>
            <li data-toggle="collapse" data-target="#<?= strtolower(implode('_', explode(' ', preg_replace("/[^A-Za-z0-9\-]/", ' ', $row['cat_name'])))) ?>">
                <a href="#"><?= $row['cat_name'] ?> <span class="fa fa-caret-down"></span></a>
                <ul class="sub-menu collapse" id="<?= strtolower(implode('_', explode(' ', preg_replace("/[^A-Za-z0-9\-]/", ' ', $row['cat_name'])))) ?>">
                    <?php
                    $subSql = "SELECT * FROM categories WHERE cat_call = '" . $row['cat_id'] . "' ORDER BY cat_name ASC";
                    $subQue = mysqli_query($conn, $subSql);
                    while ($subMenu = mysqli_fetch_array($subQue)) {
                    ?>
                        <li><a href="?page_layout=shop&brand_id=<?= $subMenu['cat_id'] ?>"><?= $subMenu['cat_name'] ?></a></li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>
    </ul>
</div>