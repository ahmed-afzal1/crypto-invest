<?php
header("Content-type: text/css; charset: UTF-8");
if(isset($_GET['color']))
{
  $color = '#'.$_GET['color'];
}
else {
  $color = '#FF9900';
}
?>

.btn-primary{
    background: <?php echo $color;?>;
}

a, a:hover, a:active, a:focus, .title-head span, body.light .navbar-nav>li .dropdown-menu a:hover, body.light .latest-post .post-title a:hover, .site-navigation ul.nav.nav-tabs li.active a:hover, .site-navigation ul.nav.nav-tabs li.active a, ul.navbar-nav > li:hover > a, body.light ul.navbar-nav > li:hover > a, .navbar-nav .fa-search:hover, .navbar-nav .fa-search:active, .navbar-nav .fa-search:focus, body.light .navbar-nav .fa-search:hover, body.light .navbar-nav .fa-search:active, body.light .navbar-nav .fa-search:focus, ul.navbar-nav > li.active > a, .about-content ul.nav.nav-tabs li.active a, .dropdown-menu>.active>a, .dropdown-menu>.active>a:hover, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>.dropdown-menu>.active>a, .dropdown-menu li a:hover, .dropdown-menu li a:focus, body.light .dropdown-menu>.active>a, .slider-text .slide-title span, #main-slide .carousel-control i, .feature .feature-icon, .button-video, .facts-footer > div h5, .bitcoin-calculator-section h3 span, .team-member-caption .list li a:hover, .team .social-icons ul.social li a:hover:before, blockquote p:before, blockquote p:after, blockquote footer span, .latest-post .post-title a:hover, .footer .top-footer h4, .footer .bottom-footer p span, .breadcrumb>li a:hover, .user-auth > div:nth-child(2) .form-container .form-group a:hover, .sidebar ul.nav-tabs li a:hover, body.light.blog .sidebar ul.nav-tabs li a:hover, .widget.recent-posts .entry-title a:hover, body.light.blog .widget.recent-posts .entry-title a:hover, body.blog article h4:hover, .comments-list .comment-reply, body.blog .meta span i, body.blog .meta a, ul.user li.sign-in a, .slider.btn-primary, .countdown-amount, h4.panel-title a, .contact-page-info .contact-info-box i.big-icon, .facts .facts-content .heading-facts h2 span, .btn-primary.btn-pricing, .shop-cart .table .icon-delete-product:hover, .shop-cart .table .icon-delete-product:focus, .shop-cart .table .icon-delete-product:active, body.light .shop-cart .table .icon-delete-product:hover, body.light .shop-cart .table .icon-delete-product:focus, body.light .shop-cart .table .icon-delete-product:active
.shop-cart .btn.btn-primary.btn-coupon:hover, .shop-cart .btn.btn-primary.btn-coupon:focus, .shop-cart .btn.btn-primary.btn-coupon:active, .shop-cart .btn.btn-update-cart:hover, .shop-cart .btn.btn-update-cart:focus, .shop-cart .btn.btn-update-cart:active, .shop-cart .btn.btn-coupon:hover, .payment .tooltip-text {
    color: <?php echo $color;?>;
}