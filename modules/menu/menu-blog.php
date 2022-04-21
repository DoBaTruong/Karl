<ul class="menu-list">
    <!-- Single Item -->
    <?php
    $sqlCatbl = "SELECT * FROM categories WHERE cat_type = 3";
    $quCatbl = mysqli_query($conn, $sqlCatbl);
    while ($catBl = mysqli_fetch_array($quCatbl)) {
    ?>
        <li data-toggle="collapse" data-target="#<?= strtolower(implode('_', explode(' ', preg_replace("/[^A-Za-z0-9\-]/", ' ', $catBl['cat_name'])))) ?>" class="active">
            <a href="#"><?= $catBl['cat_name'] ?> <span class="fa fa-caret-down""></span></a>
        <ul class=" sub-menu collapse" id="<?= strtolower(implode('_', explode(' ', preg_replace("/[^A-Za-z0-9\-]/", ' ', $catBl['cat_name'])))) ?>">
                    <?php
                    $queSub = $conn->query("SELECT * FROM categories WHERE cat_call = '" . $catBl['cat_id'] . "'");
                    while ($menusub = mysqli_fetch_array($queSub)) {
                    ?>
        <li><a href="index.php?page_layout=blog&cat_id=<?= $menusub['cat_id'] ?>"><?= $menusub['cat_name'] ?></a></li>
    <?php } ?>
</ul>
</li>
<?php } ?>
</ul>