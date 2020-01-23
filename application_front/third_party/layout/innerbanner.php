<!-- start banner -->
<div class="main-inner-banner">
    <img src="<?php echo CSS_IMAGES_JS_BASE_URL;?>images/inner-banner.jpg" alt="">
    <?php
	$titleActual = explode(" ",$title);
	$titleFirstPart = $titleActual[0];
	$titleActualRemaining = explode($titleFirstPart,$title);
	$titleActualRemainingPart = $titleActualRemaining[1];
	?>
    <h3 class="heading2"><span><?php echo $titleFirstPart; ?></span> <?php echo $titleActualRemainingPart; ?></h3>
</div>
<!-- end banner -->