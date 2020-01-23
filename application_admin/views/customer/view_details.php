<?php 
//pr($data);
?>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Customer</h4></div>
    </div>
    <div class="page-content-wrapper ">
        <div class="container">

           <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <h4 class="m-t-0 m-b-30">View</h4>
                            <?php //echo validation_errors(); ?>
						      <ul class="parsley-errors-list filled error text-left" ><li class="parsley-required"><?php echo $errmsg; ?></li></ul>
									<form id="form1" class="form-horizontal" action="<?php echo $do_addedit_link;?>" method="post" enctype="multipart/form-data">
										
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Picture </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											<?php
												if($oauth_provider!=''){
													if($picture==''){
														$picture=file_upload_base_url().'no-img.png';
													}else{
														$picture=$picture;
													}
						                        }else{

						                            if($picture==''){
														$picture=file_upload_base_url().'no-img.png';
													}else{
														$picture=file_upload_base_url().'profile_pic/customer/'.$picture;
													}
												}
											?>
											<img src="<?php echo $picture;?>" style="width: 100px;height: 100px;">
											
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">First Name </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="first_name" name="first_name" value="<?php echo $first_name;?>" readonly>
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Last Name </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="last_name" name="last_name" value="<?php echo $last_name;?>" readonly>  
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Email </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="email" name="email" value="<?php echo $email;?>" readonly>
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12"> Phone Number </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="phone_number" name="phone_number" value="<?php echo $phone;?>" readonly>
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">username </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $username;?>" readonly>
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Address </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $address;?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Address2 </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $address2;?>" readonly>
											   
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Country </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $country_name;?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">State </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $state_name;?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">City </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $city_name;?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Zipcode </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $zipcode;?>" readonly>
											   
											</div>
										</div>
										
										
										
										
						                


										
										<div class="form-group">
											<div class="col-md-6 col-md-offset-3">
											  <a class="btn btn-primary" href="javascript:window.history.back();">Back</a>
											  <!--<button class="btn btn-success" type="submit" id="send">Submit</button>-->
											</div>
										</div>
									</form> 
							
                        </div>
                    </div>
                </div>
            </div>

           
        </div>
    </div>
</div>
