<?php
$global_facebook = $this->functions->getGlobalInfo('global_facebook_url');
$global_twitter = $this->functions->getGlobalInfo('global_twitter_url');
$global_googleplus = $this->functions->getGlobalInfo('global_googleplus_url');
$global_instagram = $this->functions->getGlobalInfo('global_instagram_url');
$global_playstore = $this->functions->getGlobalInfo('global_playstore_url');
$global_appstore = $this->functions->getGlobalInfo('global_appstore_url');

$global_address = $this->functions->getGlobalInfo('global_web_admin_address');
$global_phone = $this->functions->getGlobalInfo('global_phone_number');
$global_email = $this->functions->getGlobalInfo('global_webadmin_email');
?>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-4">
                <h4><?php echo $this->lang->line('contact_heading'); ?></h4>
                <address>
                    <p>
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <?php echo htmlspecialchars_decode($global_address); ?>
                    </p>
                    <br>
                    <p><i class="fa fa-phone" aria-hidden="true"></i> <?php echo htmlspecialchars_decode($global_phone); ?></p>
                    <br>
                    <p><i class="fa fa-location-arrow" aria-hidden="true"></i> <?php echo htmlspecialchars_decode($global_email); ?></p>
                </address>
            </div>
            <div class="col-md-6 col-sm-4">
                <div class="ftr-abt">
                    <h4>Cybskills <?php echo $this->lang->line('social_heading'); ?></h4>
                    <p><?php echo $this->lang->line('social_text'); ?></p>
                </div>
                <div class="social">
                    <ul>
                        <li>
                            <a href="<?php echo $global_facebook; ?>" target="_blank"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li>
                            <a href="<?php echo $global_twitter; ?>" target="_blank"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href="<?php echo $global_googleplus; ?>" target="_blank"><i class="fa fa-google-plus"></i></a>
                        </li>
                        <li>
                            <a href="<?php echo $global_instagram; ?>" target="_blank"><i class="fa fa-instagram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <h4><?php echo $this->lang->line('newsletter_heading'); ?></h4>
                <div class="news-letter">
                <p><?php echo $this->lang->line('newsletter_text'); ?></p>
                <p id="thanks_subscription" style="display:none; color:#59FFB4; font-size:12px; font-weight:bold;">Thanks for subscription...</p>
                <form method="post" onSubmit="return subscribe();">
                    <input type="email" placeholder="Enter Your Email Address" id="email_id" name="email_id">
                    <input type="submit" value="<?php echo $this->lang->line('btn_subscription'); ?>">
                </form>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="footer-links">
        <a href="<?php echo $global_appstore; ?>" target="_blank"><img src="<?php echo CSS_IMAGES_JS_BASE_URL;?>images/app-store-small.png" alt=""></a>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="<?php echo $global_playstore; ?>" target="_blank"><img src="<?php echo CSS_IMAGES_JS_BASE_URL;?>images/play-store-small.png" alt=""></a> 
        <ul>
            <li>
                <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('menu1'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url('page/index/about'); ?>"><?php echo $this->lang->line('menu2'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url('page/contact'); ?>"><?php echo $this->lang->line('menu3'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url('page/faq'); ?>"><?php echo $this->lang->line('menu4'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url('page/index/privacy'); ?>"><?php echo $this->lang->line('menu5'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url('page/index/terms'); ?>"><?php echo $this->lang->line('menu6'); ?></a>
            </li>
        </ul>
    </div>
</footer>
<div class="copy">
    <p>Copyright &COPY; <?php echo date('Y'); ?> <?php echo $this->lang->line('copyright'); ?> <a href="http://www.vtdesignz.com/" target="_blank">VTDesignz</a></p>
</div>
<script type="text/javascript">
function subscribe()
{
	if($('#email_id').val() == '')
	{
		$('#email_id').css('border-color','#FF0004');
		$('#thanks_subscription').hide();
	}
	else
	{
		var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
		if (filter.test($('#email_id').val()))
		{
			$('#thanks_subscription').show();
			$('#email_id').val('');
			$('#email_id').css('border-color','');
		}
		else
		{
			$('#thanks_subscription').hide();
			$('#email_id').css('border-color','#FF0004');
		}
	}
	return false;
}
</script>