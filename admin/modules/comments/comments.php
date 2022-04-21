<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}
?>
<section class="breadcumb-area">
    <h3>Administration Comments</h3>
    <ul class="admin-breadcumb flex-start">
        <li class="breadcumb-item"><a href="index.php"><i class="fa fa-home"></i></a></li>
        <li class="breadcumb-item">Comments</li>
    </ul>
</section>
<section class="new-orders">
    <table class="table">
        <thead class="teal">
            <tr>
                <th class="tab-id">ID</th>
                <th class="tab-name">Comments On</th>
                <th class="tab-total">Quantity</th>
                <th class="tab-action">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tab-id">1</td>
                <td class="tab-level">Products</td>
                <td class="tab-total"><?= mysqli_query($conn, "SELECT * FROM prd_comments")->num_rows; ?></td>
                <td class="tab-action flex-center"><a class="btn cyan flex-between" href="index.php?page_layout=comm_prd">Go <span><i class="fa fa-long-arrow-alt-right"></i></span></a></td>
            </tr>
            <tr>
                <td class="tab-id">2</td>
                <td class="tab-level">Blogs</td>
                <td class="tab-total"><?= mysqli_query($conn, "SELECT * FROM blog_comments")->num_rows; ?></td>
                <td class="tab-action flex-center"><a class="btn cyan flex-between" href="index.php?page_layout=comm_blog">Go <span><i class="fa fa-long-arrow-alt-right"></i></span></a></td>
            </tr>
        </tbody>
    </table>
</section>