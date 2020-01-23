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
<div class="container-fluid">
                    <div class="row">
                        <div class="top-nav notification-row">   
							<div class="col-md-8">
								<a href="<?php echo base_url(); ?>" class="logo_inner"><img src="<?php echo CSS_IMAGES_JS_BASE_URL;?>images/logo-dash.png" alt="logo"></a>
							</div>
							<div class="col-md-4">
								<div class="inner-lang">
									<div class="language-selector">
										<form id="lang_change_frm" name="lang_change_frm" action="" method="post">
											<select name="select_lang" onChange="lang_change();">
												<option value="en"<?php if($this->nsession->userdata('member_session_lang') != "fr") {?> selected<?php } ?>>English</option>
												<option value="fr"<?php if($this->nsession->userdata('member_session_lang') == "fr") {?> selected<?php } ?>>French</option>
											</select>
										</form>
									</div>
								</div>
								
								<ul class="nav pull-right top-menu">
								   <!-- <li class="nor-link"><a href="#">Post a job </a></li>
									<li class="nor-link"><a href="#">Find a job </a></li>
									<li class="nor-link"><a href="#">Dashboard </a></li>
									<li id="mail_notificatoin_bar" class="dropdown">-->
										<!--<a href="message.html">
											<i class="fa fa-envelope-o"></i>
											<span class="badge bg-important">5</span>
										</a>-->
									<!--<li id="alert_notificatoin_bar" class="dropdown">
										<a data-toggle="dropdown" class="dropdown-toggle" href="#">
											<i class="fa fa-bell-o"></i>
											<span class="badge bg-important">7</span>
										</a>
										<ul class="dropdown-menu extended notification">
											<div class="notify-arrow notify-arrow-blue"></div>
											<li>
												<p class="blue">You have 4 new notifications</p>
											</li>
											<li>
												<a href="#">
													<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
													<span class="small italic pull-right">5 mins</span>
												</a>
											</li>
											<li>
												<a href="#">
													<p>John location.</p>
													<span class="small italic pull-right">50 mins</span>
												</a>
											</li>
											<li>
												<a href="#">
													<p>Project 3 Completed.</p>
													<span class="small italic pull-right">1 hr</span>
												</a>
											</li>
											<li>
												<a href="#">
													<p>Mick appreciated your work.</p>
													<span class="small italic pull-right"> Today</span>
												</a>
											</li>                            
											<li>
												<a href="#">See all notifications</a>
											</li>
										</ul>
									</li>-->
									<!-- alert notification end-->
									<!-- user login dropdown start-->
									<li class="dropdown">
										<a data-toggle="dropdown" class="dropdown-toggle" href="#">
											<span class="profile-ava">
												<?php if($this->nsession->userdata('member_session_membertype')==1) {?>
													<?php $member_img =  $this->ModelHome->userimage($this->nsession->userdata('member_session_id'));
														if(count($member_img)!=''){
													?>
														<img style="height:29px;width:29px" alt="" src="<?php echo $member_img[0]['profile_picture'];?>">
														<?php }else{ ?>
														<img style="height:29px;width:29px" alt="" src="<?php echo CSS_IMAGES_JS_BASE_URL;?>images/avatar.jpg">
														<?php } ?>
													
												<?php }elseif($this->nsession->userdata('member_session_membertype')==2) {?>
													<?php $employer_img =  $this->ModelHome->employer_image($this->nsession->userdata('member_session_id'));
														if(count($employer_img)!=''){?>
													<img style="height:29px;width:29px" alt="" src="<?php echo $employer_img[0]['profile_picture'];?>">
														<?php }else{ ?>
														<img style="height:29px;width:29px" alt="" src="<?php echo CSS_IMAGES_JS_BASE_URL;?>images/avatar.jpg">
														<?php } ?>
												<?php } ?>
											</span>
											<span class="username"><?php echo $this->nsession->userdata('member_session_fname'); ?></span>
											<b class="caret"></b>
										</a>
										<ul class="dropdown-menu extended logout">
											<div class="log-arrow-up"></div>
											<li class="eborder-top">
											<?php if($this->nsession->userdata('member_session_membertype')==1) {?>
												<a href="<?php echo base_url('user/employee_profile'); ?>"><i class="fa fa-user"></i> <?php echo $this->lang->line('my_profile'); ?></a>
											<?php }else{ ?>
												 <a href="<?php echo base_url('user/employer_profile'); ?>"><i class="fa fa-user"></i> <?php echo $this->lang->line('my_profile'); ?></a>
											<?php } ?>
											</li>
											<li>
												<a href="<?php echo base_url('logout'); ?>"><i class="fa fa-key"></i> <?php echo $this->lang->line('signout'); ?></a>
											</li>
										</ul>
									</li>
								</ul>
							</div>
                        </div>
                    </div>
                </div>
<script type="text/javascript">
function lang_change()
{
	$('#lang_change_frm').submit();
}
</script>