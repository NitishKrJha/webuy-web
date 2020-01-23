<div class="second-menu">
                <div class="container-fluid">
                <div class="row">
                    <nav class="navbar navbar-inverse">
                        <div class="container">
                          <!-- Brand and toggle get grouped for better mobile display -->
                          <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                              <span class="sr-only">Toggle navigation</span>
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                            </button>
                          </div>

                          <!-- Collect the nav links, forms, and other content for toggling -->
                          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                            <ul class="nav navbar-nav">
							
							<?php if($this->nsession->userdata('member_session_membertype')==1){ ?>
								
								<li class="<?php if($this->uri->segment(1)=='map' && $this->uri->segment(2)=='employee_map'){ echo 'active';} ?>"><a href="<?php echo base_url('map/employee_map'); ?>"><?php echo $this->lang->line('user_profile_menu_map'); ?></a></li>
								
								<li class="<?php if($this->uri->segment(1)=='user' && $this->uri->segment(2)=='employee_profile'){ echo 'active';} ?>"><a href="<?php echo base_url('user/employee_profile'); ?>"><?php echo $this->lang->line('user_profile_menu_profile'); ?></a></li>
								
								<li class="<?php if($this->uri->segment(1)=='user' && $this->uri->segment(2)=='employee_service_work_setup'){ echo 'active';} ?>"><a href="<?php echo base_url('user/employee_service_work_setup'); ?>"><?php echo $this->lang->line('user_profile_menu_manage_service'); ?></a></li>
								
								<?php $account_arr = array('employee_account','payment_certificate','employee_changepassword','employee_deleteAcc','employee_portfolio','view_employee_portfolio','edit_employee_portfolio','add_employee_portfolio','employee_video'); ?>
								
								<li class="<?php if($this->uri->segment(1)=='user' && in_array($this->uri->segment(2),$account_arr)){ echo 'active';} ?>"><a href="<?php echo base_url('user/employee_account'); ?>"><?php echo $this->lang->line('user_profile_menu_account'); ?></a></li>
								
								<li class="<?php if($this->uri->segment(1)=='reviewrating' && $this->uri->segment(2)=='employee'){ echo 'active';} ?>"><a href="<?php echo base_url('reviewrating/employee'); ?>"><?php echo $this->lang->line('user_profile_menu_review'); ?></a></li>
								
								<li class="<?php if($this->uri->segment(1)=='missedcall' && $this->uri->segment(2)=='employee_missedcall'){ echo 'active';} ?>"><a href="<?php echo base_url('missedcall/employee_missedcall'); ?>"><?php echo $this->lang->line('user_profile_menu_missedcall'); ?></a></li>	
								
								<?php 
									$job_ctr = array('user','jobs');
									$job_function = array('employee_manage_job','add_invoice','add_tools','postJob','edit_tool');
								?>
								
								<li class="<?php if(in_array($this->uri->segment(1),$job_ctr) && in_array($this->uri->segment(2),$job_function)){ echo 'active';} ?>"><a href="<?php echo base_url('user/employee_manage_job'); ?>"><?php echo $this->lang->line('user_profile_menu_managejob'); ?></a></li>
								
								<li class="<?php if($this->uri->segment(1)=='billing' && $this->uri->segment(2)=='employee_billing'){ echo 'active';} ?>"><a href="<?php echo base_url('billing/employee_billing'); ?>"><?php echo $this->lang->line('user_profile_menu_bilingreport'); ?></a></li>								
								
								<li class="<?php if($this->uri->segment(1)=='notification' && $this->uri->segment(2)=='employee_notification' ){ echo 'active'; }else if($this->uri->segment(1)=='notification' && $this->uri->segment(2)=='employee_jobDetail' ){ echo 'active'; }?>"><a href="<?php echo base_url('notification/employee_notification'); ?>"><?php echo $this->lang->line('user_profile_menu_notification'); ?></a></li>
								
								<li class="<?php if($this->uri->segment(1)=='user' && $this->uri->segment(2)=='employee_deactive_account'){ echo 'active';} ?>"><a href="<?php echo base_url('user/employee_deactive_account'); ?>"><?php echo $this->lang->line('user_profile_menu_setting'); ?></a></li>
							
							<?php }elseif($this->nsession->userdata('member_session_membertype')==2) {?>
								
								<li class="<?php if($this->uri->segment(1)=='map' && $this->uri->segment(2)=='employer_map'){ echo 'active';} ?>"><a href="<?php echo base_url('map/employer_map'); ?>"><?php echo $this->lang->line('user_profile_menu_map'); ?></a></li>
								
								<li class="<?php if($this->uri->segment(1)=='user' && $this->uri->segment(2)=='employer_profile'){ echo 'active';} ?>"><a href="<?php echo base_url('user/employer_profile'); ?>"><?php echo $this->lang->line('user_profile_menu_profile'); ?></a></li>
								<!--<li class=""><a href="<?php echo base_url('user/services'); ?>"><?php echo $this->lang->line('user_profile_menu_postjob'); ?></a></li>-->
								
								<?php 
									$jobs_ctr = array('user','jobs','reviewrating');
									$jobs_function = array('listallJobs','services','getSubServices','postJob','awardJob','reassign_job','EditJob','employer_review');
								?>
								<li class="<?php if(in_array($this->uri->segment(1),$jobs_ctr) && in_array($this->uri->segment(2),$jobs_function)){ echo 'active';} ?>"><a href="<?php echo base_url() ?>user/listallJobs"><?php echo $this->lang->line('my_jobs'); ?></a></li>
									<?php $employeraccount_arr = array('employer_account','employer_payment_certificate','employer_changepassword','employer_deleteAcc'); ?>
								
								<li class="<?php if($this->uri->segment(1)=='user' && in_array($this->uri->segment(2),$employeraccount_arr)){ echo 'active';} ?>"><a href="<?php echo base_url('user/employer_account'); ?>"><?php echo $this->lang->line('user_profile_menu_account'); ?></a></li>
								
								<li class="<?php if($this->uri->segment(1)=='reviewrating' && $this->uri->segment(2)=='employer'){ echo 'active';} ?>"><a href="<?php echo base_url('reviewrating/employer'); ?>"><?php echo $this->lang->line('user_profile_menu_review'); ?></a></li>
								
								<li class="<?php if($this->uri->segment(1)=='missedcall' && $this->uri->segment(2)=='employer_calllist'){ echo 'active';} ?>"><a href="<?php echo base_url('missedcall/employer_calllist'); ?>"><?php echo $this->lang->line('user_profile_menu_calllist'); ?></a></li>
								
								<li class="<?php if($this->uri->segment(1)=='billing' && $this->uri->segment(2)=='employer_billing'){ echo 'active';} ?>"><a href="<?php echo base_url('billing/employer_billing'); ?>"><?php echo $this->lang->line('user_profile_menu_bilingreport'); ?></a></li>	
								
								<li class="<?php if($this->uri->segment(1)=='billing' && $this->uri->segment(2)=='employer_pay'){ echo 'active';} ?>"><a href="<?php echo base_url('billing/employer_pay'); ?>"><?php echo $this->lang->line('user_profile_menu_makepayment'); ?></a></li>								
								
								<li class="<?php if($this->uri->segment(1)=='notification' && $this->uri->segment(2)=='employer_notification' ){ echo 'active'; }else if($this->uri->segment(1)=='notification' && $this->uri->segment(2)=='employer_jobDetail' ){ echo 'active'; }?>"><a href="<?php echo base_url('notification/employer_notification'); ?>"><?php echo $this->lang->line('user_profile_menu_notification'); ?></a></li>
							
							<?php } ?>
								
                              <!--<li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true">Dropdown <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                  <li><a href="#">Action</a></li>
                                  <li><a href="#">Another action</a></li>
                                  <li><a href="#">Something else here</a></li>
                                </ul>
                              </li>-->
                            </ul>
                          </div>
                        </div>
                    </nav>
                </div>
                </div>
            </div>