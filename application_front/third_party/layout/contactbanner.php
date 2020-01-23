<!-- start banner -->
<div class="main-inner-banner">
    <img src="<?php echo CSS_IMAGES_JS_BASE_URL;?>images/inner-banner.jpg" alt="">
    <?php if($this->nsession->userdata('member_session_lang') == "fr") { ?>
    <h3 class="heading2"><span>Contactez</span> Nous</h3>
    <?php } else { ?>
    <h3 class="heading2"><span>Contact</span> Us</h3>
    <?php } ?>
</div>
<!-- end banner -->