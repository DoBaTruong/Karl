<ul class="flex-center">
    <li class="<?php if (!isset($_GET['cat_id'])) {
                    echo 'active';
                } ?>"><a class="nav-link" href="index.php?#new-arrivals">ALL</a></li>
    <?php
    $quCa = $conn->query("SELECT * FROM categories WHERE cat_call = 0 AND cat_type = 1 ORDER BY cat_name DESC");
    while ($catArr = mysqli_fetch_array($quCa)) {
        $nameTmp = explode('wear', $catArr['cat_name']);
        $name = trim(reset($nameTmp));
        if (mb_strtolower($name) == 'foot') {
            $name = 'shoes';
        }
        if (in_array(mb_strtolower($name), ['man', 'woman', 'shoes', 'accessories'])) {
    ?>
            <li class="<?php if (isset($_GET['cat_id']) && $_GET['cat_id'] === $catArr['cat_id']) {
                            echo 'active';
                        } ?>"><a class="nav-link" href="index.php?cat_id=<?= $catArr['cat_id'] ?>#new-arrivals"><?= $name ?></a></li>
    <?php }
    } ?>
</ul>