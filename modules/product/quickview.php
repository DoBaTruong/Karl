<?php
if (!defined('SECURITY')) {
    die("You don't have authorization to view this page !");
}?>
<div id="quickview" class="modal items-center" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-body">
            <div class="quickview-body">
                <div class="container">
                    <div class="modal-button" data-dismiss="modal"><i class="fa fa-times"></i></div>
                    <div class="row">
                        <div class="col-lg-5 col-md-12 col-sm-12">
                            <div class="quickview-image"><img alt="" /></div>
                        </div>
                        <div class="col-lg-7 col-md-12 col-sm-12 quickview-content">
                            <div class="quickview-description">
                                <h4></h4>
                                <div class="prd-rating">
                                    <span class="stars">★★★★★</span><span class="stars-show ml-3"></span>
                                </div>
                                <div class="items-center prd-price">
                                    <span class="price-current"></span><span class="price-oldest"></span>
                                </div>
                                <p class="prd-details" data-toggle="limit" data-line="4"></p>
                                <a href="index.php?page_layout=prd_details">View Full Product Details</a>
                            </div>
                            <!-- Add To Cart Form -->
                            <form class="quickview-form items-center" method="post">
                                <input hidden type="number" name="prd_id" />
                                <div class="quantity">
                                    <span onclick="var effect = document.getElementById('qty'); var qty = effect.value; if( !isNaN( qty ) && qty > 1) effect.value--;return false;">
                                        <i class="fa fa-minus"></i>
                                    </span>
                                    <input type="number" id="qty" step="1" min="1" name="quantity" value="1" />
                                    <span onclick="var effect = document.getElementById('qty'); var qty = effect.value; if( !isNaN( qty )) effect.value++;return false;">
                                        <i class="fa fa-plus"></i>
                                    </span>
                                </div>
                                <button type="submit" name="addcart" class="add-cart">
                                    <i class="fa fa-shopping-cart"></i>
                                </button>
                                <!-- Wishlist -->
                                <div class="modal-wishlist">
                                    <a href="index.php?page_layout=wishlist" target="_blank"><i class="fa fa-heart"></i></a>
                                </div>
                                <!-- Compare -->
                                <div class="modal-compare">
                                    <a href="index.php?page_layout=wishlist" target="_blank"><i class="fa fa-chart-line"></i></a>
                                </div>
                            </form>

                            <div class="share-modal">
                                <p>Share With Friend</p>
                                <div class="flex-start">
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-pinterest"></i></a>
                                    <a href="#"><i class="fab fa-google-plus-g"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>