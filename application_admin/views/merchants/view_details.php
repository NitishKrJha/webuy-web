<?php 
//pr($data);
?>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Merchants</h4></div>
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
										<h4 class="m-t-0 m-b-30">Personal Details</h4>
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
														$picture=file_upload_base_url().'profile_pic/merchants/'.$picture;
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
										
										<?php
											if(count($business_details) > 0){
												?>

										<h4 class="m-t-0 m-b-30">Business Details</h4>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Business name</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $business_details['business_name'];?>" readonly>
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">GSTIN Provisional ID (Sample)  </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $business_details['gstin'];?>" readonly>
											   <?php
			                                    if(isset($business_details['gstin_path'])){
			                                        if($business_details['gstin_path']!=''){
			                                            ?>
			                                            File Uploaded,
			                                            <a class="btn btn-info" href="<?php echo business_details_path().'gstin/'.$business_details['gstin_path']; ?>" download>Download</a>
			                                            <?php
			                                        }
			                                    }
			                                    $activeLink=base_url($this->controller."/gstin_verified/1/".$business_details['merchants_id']);
			                                    $inacttivedLink=base_url($this->controller."/gstin_verified/0/".$business_details['merchants_id']);
			                                    ?>
			                                    <a href="javascript:ChangeStatus('<?php echo $business_details['gstin_verified']==1?$inacttivedLink:$activeLink;?>');"><button class="btn btn-<?php echo $business_details['gstin_verified']=='1'?'info':'danger';?> btn-xs" type="button"><?php echo $business_details['gstin_verified']==1?'Active':'InActive';?></button></a>
											</div>
										</div>
						                <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">PAN </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $business_details['pan_number'];?>" readonly>
											   <?php
			                                        if(isset($business_details['pan_path'])){
			                                            if($business_details['pan_path']!=''){
			                                                ?>
			                                                File Uploaded,
			                                                <a class="btn btn-info" href="<?php echo business_details_path().'pan_card/'.$business_details['pan_path']; ?>" download>Download</a>
			                                                <?php
			                                            }
			                                        }
			                                        $activeLink=base_url($this->controller."/pan_verified/1/".$business_details['merchants_id']);
				                                    $inacttivedLink=base_url($this->controller."/pan_verified/0/".$business_details['merchants_id']);
				                                    ?>
				                                    <a href="javascript:ChangeStatus('<?php echo $business_details['pan_verified']==1?$inacttivedLink:$activeLink;?>');"><button class="btn btn-<?php echo $business_details['pan_verified']=='1'?'info':'danger';?> btn-xs" type="button"><?php echo $business_details['pan_verified']==1?'Active':'InActive';?></button></a>
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Aadhar Card </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $business_details['aadhar_card'];?>" readonly>
											   <?php
		                                        if(isset($business_details['aadhar_card_path'])){
		                                            if($business_details['aadhar_card_path']!=''){
		                                                ?>
		                                                File Uploaded,
		                                                <a class="btn btn-info" href="<?php echo business_details_path().'aadhar_card/'.$business_details['aadhar_card_path']; ?>" download>Download</a>
		                                                <?php
		                                            }
		                                        }
		                                        $activeLink=base_url($this->controller."/aadhar_card_verified/1/".$business_details['merchants_id']);
			                                    $inacttivedLink=base_url($this->controller."/aadhar_card_verified/0/".$business_details['merchants_id']);
			                                    ?>
			                                    <a href="javascript:ChangeStatus('<?php echo $business_details['aadhar_card_verified']==1?$inacttivedLink:$activeLink;?>');"><button class="btn btn-<?php echo $business_details['aadhar_card_verified']=='1'?'info':'danger';?> btn-xs" type="button"><?php echo $business_details['aadhar_card_verified']==1?'Active':'InActive';?></button></a>
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Address line </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $business_details['business_address'];?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Address line 2  </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $business_details['business_address2'];?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Pin Code </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $business_details['business_zipcode'];?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Country </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $business_details['country_name'];?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">State </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $business_details['state_name'];?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">City </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $business_details['city_name'];?>" readonly>
											   
											</div>
										</div>

											<?php
											}
										?>

										<?php
											if(count($bank_details) > 0){
												?>

										<h4 class="m-t-0 m-b-30">Bank Details</h4>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Account Holder Name</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $bank_details['account_holder_name'];?>" readonly>
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Account number  </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $bank_details['account_number'];?>" readonly>
											   
											</div>
										</div>
						                <div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">IFSC Code </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $bank_details['ifsc_code'];?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Bank Name </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $bank_details['bank_name'];?>" readonly>
											   
											</div>
										</div>
										
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Country </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $bank_details['country'];?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">State  </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $bank_details['state'];?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">City </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $bank_details['city'];?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Branch </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $bank_details['branch'];?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Business type </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $bank_details['business_type_name'];?>" readonly>
											   
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Address proof  </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $bank_details['address_proof_name'];?>" readonly>
											   <br>
											   <?php
			                                    if(isset($bank_details['address_proof_path'])){
			                                        if($bank_details['address_proof_path']!=''){
			                                            ?>
			                                            File Uploaded,
			                                            <a class="btn btn-info" href="<?php echo business_details_path().'address_proof/'.$bank_details['address_proof_path']; ?>" download>Download</a>
			                                            <?php
			                                        }
			                                    }
			                                    ?>
											</div>
										</div>

										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Canceled Cheque (Sample) </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <?php
			                                    if(isset($bank_details['address_proof_path'])){
			                                        if($bank_details['address_proof_path']!=''){
			                                            ?>
			                                            <a class="btn btn-info" href="<?php echo business_details_path().'address_proof/'.$bank_details['address_proof_path']; ?>" download>Download</a>
			                                            <?php
			                                        }
			                                    }
			                                    ?>
											</div>
										</div>

											<?php
											}
										?>
										
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
<script>
	function ChangeStatus(url){
		if (confirm("Are you sure want to change status?")) {
	        window.location.href=url;
	    }
	    return false;
	}
</script>