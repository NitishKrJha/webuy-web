<div class="topbar-left">
  <div class="text-center">
          <a href="<?php echo base_url(); ?>" class="logo" ><img src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/images/admin_logo.png" style="width:30% !important;"></a>
          <a href="<?php echo base_url(); ?>" class="logo-sm"><img src="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/images/logo_sm.png"></a>
      </div>  
</div>
  <div class="navbar navbar-default" role="navigation">
      <div class="container">
          <div class="">
              <div class="pull-left">
                  <button type="button" class="button-menu-mobile open-left waves-effect waves-light"> <i class="ion-navicon"></i> </button> <span class="clearfix"></span></div>
              <form class="navbar-form pull-left" role="search" style="display: none;">
                  <div class="form-group">
                      <input type="text" class="form-control search-bar" placeholder="Search...">
                  </div>
                  <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
              </form>
              <ul class="nav navbar-nav navbar-right pull-right">
                  <li class="dropdown hidden-xs">
                      <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light notification-icon-box" data-toggle="dropdown" aria-expanded="true"> <i class="fa fa-bell"></i> <span class="badge badge-xs badge-danger"></span> </a>
                      <ul class="dropdown-menu dropdown-menu-lg noti-list-box">
                          <li class="text-center notifi-title">Notification <span class="badge badge-xs badge-success">3</span></li>
                          <li class="list-group">
                              <a href="javascript:void(0);" class="list-group-item">
                                  <div class="media">
                                      <div class="media-heading">Your order is placed</div>
                                      <p class="m-0"> <small>Dummy text of the printing and typesetting industry.</small></p>
                                  </div>
                              </a>
                              <a href="javascript:void(0);" class="list-group-item">
                                  <div class="media">
                                      <div class="media-body clearfix">
                                          <div class="media-heading">New Message received</div>
                                          <p class="m-0"> <small>You have 87 unread messages</small></p>
                                      </div>
                                  </div>
                              </a>
                              <a href="javascript:void(0);" class="list-group-item">
                                  <div class="media">
                                      <div class="media-body clearfix">
                                          <div class="media-heading">Your item is shipped.</div>
                                          <p class="m-0"> <small>It is a long established fact that a reader will</small></p>
                                      </div>
                                  </div>
                              </a>
                              <a href="javascript:void(0);" class="list-group-item"> <small class="text-primary">See all notifications</small> </a>
                          </li>
                      </ul>
                  </li>
                  <li class="hidden-xs"> <a href="#" id="btn-fullscreen" class="waves-effect waves-light notification-icon-box"><i class="mdi mdi-fullscreen"></i></a></li>
                  <li class="dropdown">
                       <?php
                        $img=CSS_IMAGES_JS_BASE_URL.'assets/images/users/avatar-1.png';
                        if($this->nsession->userdata('admin_session_img_path')){
                          if($this->nsession->userdata('admin_session_img_path')!=''){
                            $img=file_upload_base_url().'profile_pic/staff/'.$this->nsession->userdata('admin_session_img_path');
                          }
                        }
                      ?>
                      <a href="#" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"> <img src="<?php echo $img;?>" alt="user-img" class="img-circle"> </a>
                      <ul class="dropdown-menu">
                          <li><a href="<?php echo base_url('user/myprofile'); ?>"> Profile</a></li>
                          <li><a href="<?php echo base_url('user/changepass'); ?>"> Change Password</a></li>
                          <li><a href="<?php echo base_url('setting');?>"> Settings </a></li>
                          <li class="divider"></li>
                          <li><a href="<?php echo base_url('logout/');?>"> Logout</a></li>
                      </ul>
                  </li>
              </ul>
          </div>
      </div>
  </div>