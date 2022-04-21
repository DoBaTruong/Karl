<div class="shop-sidebar-menu">
    <div class="widget-category">
        <!-- Side Navigation -->
        <div class="nav-side-menu">
            <h6>Categories</h6>
            <?php include_once('modules/menu/menu-blog.php') ?>
        </div>
    </div>

    <div class="widget-search">
        <h6>Search</h6>
        <div class="search-box">
            <form method="post">
                <input class="form-control" type="search" name="keyblog" placeholder="search for" />
                <button type="submit" name="searchBlog"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>

    <div class="widget-recent">
        <h6>Recent Posts</h6>
        <div class="sidebar-recent">
            <!-- Single Recommended Product -->
            <?php 
            $recent = $conn -> query("SELECT * FROM blogs ORDER BY blog_id DESC LIMIT 0, 4");
            while ($recentBl = mysqli_fetch_array($recent)) {
            ?>
            <div class="flex-between">
                <div class="recent-thumb">
                    <img src="admin/images/blog/<?= $recentBl['blog_image'] ?>" alt="" />
                </div>
                <div class="recent-desc">
                    <h6><?= $recentBl['blog_title'] ?></h6>
                    <p><?= date("M d, Y", strtotime($recentBl['blog_post'])) ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>