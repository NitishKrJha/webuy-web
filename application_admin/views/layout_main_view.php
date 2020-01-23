<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=yes">
    <link rel="icon" href="<?php echo base_url().'public/assets/images/logo_sm.png'; ?>">
    <title><? echo constant("GLOBAL_SITE_NAME");?></title>

    <!-- Bootstrap -->
    <link href="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/plugins/morris/morris.css">
    <link href="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">
	<link href="<?php echo CSS_IMAGES_JS_BASE_URL; ?>pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="<?php echo CSS_IMAGES_JS_BASE_URL; ?>pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="<?php echo CSS_IMAGES_JS_BASE_URL; ?>pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
	<script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/jquery.min.js"></script>  
  </head>
  <body class="fixed-left">
    <div id="wrapper">
        <div class="topbar">
            <?php echo $content_for_layout_topmenu;?>
        </div>
        <div class="left side-menu">
            <?php echo $content_for_layout_menu;?>
        </div>
        <div class="content-page">
            <?php echo $content_for_layout_main;?>
            <footer class="footer"> <?php echo date('Y');?> © <? echo constant("GLOBAL_SITE_NAME");?> </footer>
        </div>
    </div>
    <input type="hidden" id="baseurl" value="<?php echo base_url(); ?>">
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/jquery.validate.min.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/additional-methods.js"></script>
    <script src="<?php echo front_base_url();?>public/tinymce/tinymce.min.js"></script>
    <script src="<?php echo front_base_url();?>public/js/tinymc.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/modernizr.min.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/detect.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/fastclick.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/jquery.slimscroll.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/jquery.blockUI.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/waves.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/wow.min.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/jquery.nicescroll.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/jquery.scrollTo.min.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/plugins/morris/morris.min.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/plugins/raphael/raphael-min.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/pages/dashborad.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL;?>assets/js/app.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>pnotify/dist/pnotify.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>pnotify/dist/pnotify.buttons.js"></script>
    <script src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>pnotify/dist/pnotify.nonblock.js"></script>
    <script>
      $(document).ready(function() {
        <?php if($succmsg!='' || $errmsg!=''){ ?>
        new PNotify({
           title: '<?php echo $succmsg!=""?"Success":"Error";?>',
          text: '<?php echo $succmsg!=""?$succmsg:$errmsg;?>',
          type: '<?php echo $succmsg!=""?"success":"error";?>',
          styling: 'bootstrap3'
        });
        <?php } ?>
      });
    </script>
</body>

</html>
  
