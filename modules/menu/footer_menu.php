<div class="col-sm-6 col-md-3 col-lg-2">
    <div class="single-footer-area">
        <ul class="footer-widget-menu">
            <li><a class="nav-link" href="index.php?page_layout=about">About</a></li>
            <li><a class="nav-link" href="index.php?page_layout=blog">Blog</a></li>
            <li><a class="nav-link" href="index.php?page_layout=faqs">Faq</a></li>
            <li><a class="nav-link" href="index.php?page_layout=faqs#faq-return">Returns</a></li>
            <li><a class="nav-link" href="index.php?page_layout=contact">Contact</a></li>
        </ul>
    </div>
</div>
<!-- Single Footer Area Start -->
<div class="col-sm-6 col-md-3 col-lg-2">
    <div class="single-footer-area">
        <ul class="footer-widget-menu">
            <li><a class="nav-link" href="<?php 
            if (isset($_SESSION['mail'])) {
                echo '?page_layout=myaccount';
            } else {
                echo '?page_layout=logreg&logInfor=myaccount';
            }
            
            ?>">My Account</a></li>
            <li><a class="nav-link" href="index.php?page_layout=faqs#faq-return">Shipping</a></li>
            <li><a class="nav-link" href="index.php?page_layout=policy">Our Policies</a></li>
            <li><a class="nav-link" href="#">Afiliates</a></li>
        </ul>
    </div>
</div>