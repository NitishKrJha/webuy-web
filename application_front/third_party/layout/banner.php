<?php
$global_playstore = $this->functions->getGlobalInfo('global_playstore_url');
$global_appstore = $this->functions->getGlobalInfo('global_appstore_url');
?>
<div class="main-banner">
    <ul class="main-slider">
    	<?php foreach($allBanner as $singleBanner) { ?>
        <li><img src="<?php echo $singleBanner['icon']; ?>" alt="<?php echo $singleBanner['title_'.$this->nsession->userdata('member_session_lang')]; ?>"></li>
        <?php } ?>
    </ul>
<!--    <img src="<?php echo CSS_IMAGES_JS_BASE_URL;?>images/big-banner.jpg" alt="">-->
    <div class="banner-content">
        <?php echo htmlspecialchars_decode($bannercontent); ?>
        <a href="#" class="btn btn-big"><?php echo $this->lang->line('banner_links'); ?></a><br>
        <a href="<?php echo $global_appstore; ?>" target="_blank"><img src="<?php echo CSS_IMAGES_JS_BASE_URL;?>images/app-store.png" alt=""></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="<?php echo $global_playstore; ?>" target="_blank"><img src="<?php echo CSS_IMAGES_JS_BASE_URL;?>images/play-store.png" alt=""></a>  
    </div>
</div>