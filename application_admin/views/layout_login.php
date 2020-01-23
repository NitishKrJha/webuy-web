<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo base_url().'public/assets/images/logo_sm.png'; ?>">
    <title><? echo constant("GLOBAL_SITE_NAME");?></title>
	  <link href="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/css/style.css" rel="stylesheet" type="text/css">
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
  </head>

  <body>
    <div class="accountbg"></div>
    <div class="wrapper-page">
        <div class="panel panel-color panel-primary panel-pages">
            <div class="panel-body">
                <?php echo $content_for_layout;?>
            </div>
        </div>
    </div>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/js/modernizr.min.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/js/detect.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/js/fastclick.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/js/jquery.slimscroll.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/js/jquery.blockUI.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/js/waves.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/js/wow.min.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/js/jquery.nicescroll.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/js/jquery.scrollTo.min.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/js/app.js"></script>
</body>

</html>

 
