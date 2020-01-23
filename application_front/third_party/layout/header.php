<?php
if(isset($_POST['select_lang'])) {
	$this->nsession->set_userdata('member_session_lang', $_POST['select_lang']);
}
if($this->nsession->userdata('member_session_lang') == "fr") {
	$this->lang->load('static_data', 'french');
} else {
	$this->lang->load('static_data', 'english');
	$this->nsession->set_userdata('member_session_lang', 'en');
}
if(isset($_POST['select_lang'])) {
	redirect('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], '');
}
?>
<div class="container">
    <div class="row">
        <div class="logo">
            <a href="<?php echo base_url(); ?>">
                <img src="<?php echo CSS_IMAGES_JS_BASE_URL;?>images/logo.png" alt="Logo" title="Dapi groupe finance inc">
            </a>
        </div>
    </div>
</div>
<nav class="navbar navbar-default">
    <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
        </button>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('menu1'); ?></a></li>
          <li><a href="<?php echo base_url('page/index/about'); ?>"><?php echo $this->lang->line('menu2'); ?></a></li>
          <li><a href="<?php echo base_url('page/contact'); ?>"><?php echo $this->lang->line('menu3'); ?></a></li>
          <!--<li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true">Dropdown <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
            </ul>
          </li>-->
        </ul>
        
        <ul class="nav navbar-nav navbar-right">
        <?php if($this->nsession->userdata('member_session_id') == ""){ ?>
          <li><a href="<?php echo base_url('registration'); ?>"><?php echo $this->lang->line('signup'); ?></a></li>
          <li><a href="<?php echo base_url('login'); ?>"><?php echo $this->lang->line('signin'); ?></a></li>
        <?php } else { ?>
          <li><a href="<?php echo base_url('user'); ?>"><?php echo $this->lang->line('home_hi'); ?> <?php echo $this->nsession->userdata('member_session_fname'); ?></a></li>
          <li><a href="<?php echo base_url('logout'); ?>"><?php echo $this->lang->line('signout'); ?></a></li>
        <?php } ?>
        </ul>
      </div>
    </div>
</nav>
<div class="language-selector">
    <form id="lang_change_frm" name="lang_change_frm" action="" method="post">
        <select name="select_lang" onChange="lang_change();">
            <option value="en"<?php if($this->nsession->userdata('member_session_lang') != "fr") {?> selected<?php } ?>>English</option>
            <option value="fr"<?php if($this->nsession->userdata('member_session_lang') == "fr") {?> selected<?php } ?>>French</option>
        </select>
    </form>
</div>
<script type="text/javascript">
function lang_change()
{
	$('#lang_change_frm').submit();
}
</script>