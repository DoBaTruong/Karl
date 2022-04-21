<div class="main-menu-area items-center" data-toggle="collapse" data-target="#shop-navbar">
    <button type="button"><i class="fa fa-bars"></i></span></button>
    <ul class="collapse" id="shop-navbar">
        <li class="<?php if (!isset($_GET['page_layout'])) {
                        echo 'active';
                    } ?>"><a class="nav-link" href="index.php">Home</a></li>
        <li class="<?php if ($_GET['page_layout'] == 'shop' || $_GET['page_layout'] =='wishlist' || $_GET['page_layout'] == 'compare'|| $_GET['page_layout'] == 'prd_details') {
                        echo 'active';
                    } ?>"><a class="nav-link" href="?page_layout=shop">Shop</a></li>
        <li class="<?php if ($_GET['page_layout'] == 'blog' || $_GET['page_layout'] == 'blog_details') {
                        echo 'active';
                    } ?>"><a class="nav-link" href="?page_layout=blog">Blog</a></li>
        <li class="<?php if ($_GET['page_layout'] == 'contact') {
                        echo 'active';
                    } ?>"><a class="nav-link" href="?page_layout=contact">Contact</a></li>
    </ul>
</div>