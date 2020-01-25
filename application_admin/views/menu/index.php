<?php 
$controller = $this->uri->segment(1);
$action = $this->uri->segment(2);
?>
<div class="sidebar-inner slimscrollleft">
    <div id="sidebar-menu">
        <ul>
            <?php /* ?>
            <li class="menu-title"><?php echo constant("GLOBAL_SITE_NAME");?></li>
            <li> <a href="<?php echo base_url(); ?>" class="waves-effect"><i class="mdi mdi-home"></i><span> Dashboard <span class="badge badge-primary pull-right"></span></span></a></li>
            <li class="has_sub"> <a href="javascript:void(0);" class="waves-effect <?php echo ($controller=='landing')?'active subdrop':''; ?>"><i class="mdi mdi-album"></i> <span> Landing </span> <span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
                <ul class="list-unstyled">
                    <li><a href="<?php echo base_url('landing/left_ad_section'); ?>">Left Ad Section</a></li>
                    <li><a href="<?php echo base_url('landing/right_ad_section'); ?>">Right Ad Section</a></li>
                    <li><a href="<?php echo base_url('landing/category_wise_product'); ?>">Category Wise Product</a></li>
                </ul>
            </li>
            <li> <a href="<?php echo base_url('order/index/'); ?>" class="waves-effect <?php echo ($controller=='order')?'active':''; ?>"><i class="mdi mdi-cart"></i><span> Order <span class="badge badge-primary pull-right"></span></span></a></li>
            <li> <a href="<?php echo base_url('commission/index/'); ?>" class="waves-effect <?php echo ($controller=='commision')?'active':''; ?>"><i class="mdi mdi-tag-text-outline"></i><span> Commission <span class="badge badge-primary pull-right"></span></span></a></li>
            <li class="has_sub"> <a href="javascript:void(0);" class="waves-effect <?php echo ($controller=='reports')?'active subdrop':''; ?>"><i class="mdi mdi-album"></i> <span> Reports </span> <span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
                <ul class="list-unstyled">
                    <li><a href="<?php echo base_url('reports/sales'); ?>">Sales</a></li>
                    <li><a href="<?php echo base_url('reports/customer'); ?>">Customer</a></li>
                    <li><a href="<?php echo base_url('reports/merchants'); ?>">Merchants</a></li>
                    <li><a href="<?php echo base_url('reports/delivery'); ?>">Delivery Status</a></li>
                    <li><a href="<?php echo base_url('reports/stock_report'); ?>">Stock Report</a></li>
                </ul>
            </li>
            <li class="has_sub"> <a href="javascript:void(0);" class="waves-effect <?php echo ($controller=='home')?'active subdrop':''; ?>"><i class="mdi mdi-album"></i> <span> Home Section </span> <span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
                <ul class="list-unstyled">
                    <li><a href="<?php echo base_url('homepage/adsection/0/1'); ?>">Ad Section</a></li>

                </ul>
            </li>
            <?php */ ?>
            <li class="has_sub"> <a href="javascript:void(0);" class="waves-effect <?php echo ($controller=='category')?'active subdrop':''; ?>"><i class="fa fa-list-alt"></i> <span> Category </span> <span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
                <ul class="list-unstyled">
                    <li><a href="<?php echo base_url('category/index/0/1'); ?>">Category 1</a></li>
                    <li><a href="<?php echo base_url('category/level2/0/1'); ?>">Category 2</a></li>
                    <!-- <li><a href="<?php echo base_url('category/level3/0/1'); ?>">Level3</a></li>
                    <li><a href="<?php echo base_url('category/level4/0/1'); ?>">Level4</a></li> -->
                </ul>
            </li>
            <li class="has_sub"> <a href="javascript:void(0);" class="waves-effect <?php echo ($controller=='content')?'active subdrop':''; ?>"><i class="mdi mdi-album"></i> <span> CMS </span> <span class="pull-right"><i class="mdi mdi-plus"></i></span></a>
                <ul class="list-unstyled">
                  <li><a href="<?php echo base_url('content/pages/about_us');?>">About Us</a></li>
                  <li><a href="<?php echo base_url('content/pages/term_of_use');?>">Term of Use</a></li>
                  <li><a href="<?php echo base_url('content/pages/privacy_policy');?>">Privacy Policy</a></li>
                  <li><a href="<?php echo base_url('content/pages/pricing_policy');?>">Privacy Policy</a></li>
                  <li><a href="<?php echo base_url('content/pages/shipping_policy');?>">Shipping Policy</a></li>
                  <li><a href="<?php echo base_url('content/pages/return');?>">Return Policy</a></li>
                  <li><a href="<?php echo base_url('content/pages/license');?>">License</a></li>
                  <li><a href="<?php echo base_url('faq/index');?>">FAQ</a></li>
                
                </ul>
            </li>
            <?php /* ?>
            <li> <a href="<?php echo base_url('brands'); ?>" class="waves-effect <?php echo ($controller=='brands')?'active':''; ?>"><i class="fa fa-bars"></i><span> Brands <span class="badge badge-primary pull-right"></span></span></a></li>
            <li> <a href="<?php echo base_url('variation/index/0/1'); ?>" class="waves-effect <?php echo ($controller=='variation')?'active':''; ?>"><i class="fa fa-bars"></i><span> Variation Attributes <span class="badge badge-primary pull-right"></span></span></a></li>
            <li> <a href="<?php echo base_url('offer/index/0/1'); ?>" class="waves-effect <?php echo ($controller=='offer')?'active':''; ?>"><i class="fa fa-bars"></i><span> Various offer <span class="badge badge-primary pull-right"></span></span></a></li>
            <?php */ ?>
            <li> <a href="<?php echo base_url('banner/index/0/1'); ?>" class="waves-effect <?php echo ($controller=='banner')?'active':''; ?>"><i class="fa fa-picture-o"></i><span> Banner <span class="badge badge-primary pull-right"></span></span></a></li>
            <?php /* ?>
            <li> <a href="<?php echo base_url('staff/index/0/1'); ?>" class="waves-effect <?php echo ($controller=='staff')?'active':''; ?>"><i class="mdi mdi-account-multiple-outline"></i><span> Staff <span class="badge badge-primary pull-right"></span></span></a></li>
            <li> <a href="<?php echo base_url('mail_template'); ?>" class="waves-effect <?php echo ($controller=='mail_template')?'active':''; ?>"><i class="fa fa-envelope"></i><span> Email Templates <span class="badge badge-primary pull-right"></span></span></a></li>
            <?php /* ?>
            <li> <a href="<?php echo base_url('newsletter'); ?>" class="waves-effect <?php echo ($controller=='newsletter')?'active':''; ?>"><i class="mdi mdi-email-outline"></i><span> Subscriber <span class="badge badge-primary pull-right"></span></span></a></li>
            <li> <a href="<?php echo base_url('product_reveiw'); ?>" class="waves-effect <?php echo ($controller=='product_reveiw')?'active':''; ?>"><i class="mdi mdi-account-star-variant"></i><span> Product Review <span class="badge badge-primary pull-right"></span></span></a></li>
            <li> <a href="<?php echo base_url('merchants/index/0/1'); ?>" class="waves-effect <?php echo ($controller=='merchants')?'active':''; ?>"><i class="fa fa-user-secret"></i><span> Merchants <span class="badge badge-primary pull-right"></span></span></a></li>
            <?php */ ?>
            <li> <a href="<?php echo base_url('customer/index/0/1'); ?>" class="waves-effect <?php echo ($controller=='customer')?'active':''; ?>"><i class="fa fa-user"></i><span> Customer <span class="badge badge-primary pull-right"></span></span></a></li> 
            <?php /* ?>
            <li> <a href="<?php echo base_url('contact/index/0/1/'); ?>" class="waves-effect <?php echo ($controller=='contact')?'active':''; ?>"><i class="mdi mdi-contact-mail"></i><span> Contact us <span class="badge badge-primary pull-right"></span></span></a></li>
            <?php */ ?>
            <li> <a href="<?php echo base_url('product/index/0/1/'); ?>" class="waves-effect <?php echo ($controller=='product')?'active':''; ?>"><i class="mdi mdi-shopping"></i><span> Product <span class="badge badge-primary pull-right"></span></span></a></li>
            <?php /* ?>
            <li> <a href="<?php echo base_url('coupon/index/'); ?>" class="waves-effect <?php echo ($controller=='coupon')?'active':''; ?>"><i class="mdi mdi-receipt"></i><span> Coupon <span class="badge badge-primary pull-right"></span></span></a></li>
            <?php */ ?>
            <li> <a href="<?php echo base_url('template/index/'); ?>" class="waves-effect <?php echo ($controller=='template')?'active':''; ?>"><i class="mdi mdi-desktop-mac"></i><span> Template <span class="badge badge-primary pull-right"></span></span></a></li>

            
        </ul>
    </div>
    <div class="clearfix"></div>
</div>