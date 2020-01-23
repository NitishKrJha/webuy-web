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
                            <h4 class="m-t-0 m-b-30"><?php echo $action; ?></h4>
                            <?php //echo validation_errors(); ?>
      						<ul class="parsley-errors-list filled error text-left" ><li class="parsley-required"><?php echo $errmsg; ?></li></ul>
							<form id="form1" name="form1" class="form-horizontal" action="<?php echo $do_addedit_link;?>" method="post" enctype="multipart/form-data" autocomplete="off">
								<span class="section"></span>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">First Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										   <input class="validate[required] form-control" type="text" id="first_name" name="first_name" value="<?php echo htmlentities($first_name);?>" autocomplete="off">
										   
										</div>
									</div>
									<div class="form-group">
									<input type="hidden" name="hidData" id="hidData" value="">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Last Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										   <input class="validate[required] form-control" type="text" id="last_name" name="last_name" value="<?php echo htmlentities($last_name);?>" autocomplete="off">  
										   
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Username<span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $username;?>" autocomplete="off">
										   
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Email <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										   <input class="validate[required] form-control" type="text" id="email" name="email" value="<?php echo $email;?>" autocomplete="off">
										   
										</div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12"> Phone Number <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										   <input class="validate[required] form-control" type="text" id="phone" name="phone" value="<?php echo $phone;?>" autocomplete="off">
										   
										</div>
									</div>
									<?php if($action=='Add'){
										?>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12"> Password <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="password" id="password" name="password" autocomplete="off">
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12"> Confirm Password <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="password" id="confirm_password" name="confirm_password" autocomplete="off">
											   
											</div>
										</div>
										<?php
									} ?>
									
									<div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Address *</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <textarea name="address" id="address"  class="form-control"><?php echo isset($address)?$address:'';?></textarea>
					                    </div>
					                </div>
					                <div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Address2 *</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <textarea name="address2" id="address2"  class="form-control"><?php echo isset($address2)?$address2:'';?></textarea>
					                    </div>
					                </div>
									<div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Country *</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <select name="country" id="country" class="form-control" required="required" onchange="getState(this.value)">
					                      <option value="">Select Country</option>
					                      <?php
					                                    if(count($allCountry) > 0){
					                                      foreach ($allCountry as $key => $value) {
					                                        $selected='';
					                                        if($value['id']==$country_id){
					                                          $selected="selected='selected'";
					                                        }
					                                        echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['name'].'</option>';
					                                      }
					                                    }
					                                  ?>
					                    </select>
					                    </div>
					                </div>
					                <div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">State *</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <select class="form-control" name="state" id="state" onchange="getCity(this.value)" required="required">
					                          <option value="">Select State</option>
					                          
					                      </select>
					                    </div>
					                </div>
					                <div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">City *</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <select class="form-control" name="city" id="city" required="required">
					                          <option value="">Select City</option>
					                      </select>
					                    </div>
					                </div>
					                <div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Zipcode</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <input type="text" class="form-control" name="zipcode" id="zipcode" value="<?php echo isset($zipcode)?$zipcode:'';?>">
					                    </div>
					                </div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12"> Profile Pic <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										   <input class="form-control" type="file" id="picture" name="picture" accept="image/*">
										   <?php
										   	if($picture!=''){
										   		if($oauth_provider==''){
										   			$picture=file_upload_base_url().'profile_pic/customer/'.$picture;
										   		}
										   		
										   		?>
										   		<img src="<?php echo $picture; ?>" style="width: 50px; height: :50px;">
										   		<?php
										   	}
										   ?>
										</div>
									</div>
									<div class="ln_solid"></div>
									<div class="form-group">
										<div class="col-md-6 col-md-offset-3">
										  <a class="btn btn-primary" href="javascript:window.history.back();">Cancel</a>
										  <button class="btn btn-success" type="submit" id="send">Submit</button>
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


<script type="text/javascript">
var country='<?php echo isset($country_id)?$country_id:''; ?>';
if(country !=0){
  getState(country);
}


  function getState(val){
    var state='<?php echo isset($state_id)?$state_id:0; ?>';
   // alert(state);
    if(val!=''){
      var url='<?php echo base_url(); ?>customer/getState/'+val+'/'+state;
      $.ajax({
        type: 'POST',
        url: url,
        success: function(result){
          if(result!=''){
            $('#state').html(result);
            if(state !=0){
              getCity(state);
            }
          }
        },
        error: function(){
           
        }
      });
    }
  }

  function getCity(val){
    var city='<?php echo isset($city_id)?$city_id:0; ?>';
    if(val!=''){
      var url='<?php echo base_url(); ?>customer/getCity/'+val+'/'+city;
      $.ajax({
        type: 'POST',
        url: url,
        success: function(result){
          if(result!=''){
            $('#city').html(result);
          }
        },
        error: function(){
           
        }
      });
    }
  }
$(document).ready(function(){
	jQuery.validator.addMethod("specialChars", function( value, element ) {
        var regex = new RegExp("^[0-9a-zA-Z \b]+$");
        var key = value;

        if (!regex.test(key)) {
           return false;
        }
        return true;
    }, "Special Character does not allow");
	$("form[name='form1']").validate({
    
      // Specify validation rules
      rules: {
        first_name:{
          required: true,
          specialChars: true
        },
        last_name:{
          required: true,
          specialChars: true
        },
        username:{
          required: true,
          remote: {
	            url: "<?php echo base_url(); ?>customer/checkuser/username/<?php echo $id; ?>",
	            type: "post"
	      }
        },
        email:{
          required: true,
          email: true,
	      remote: {
	            url: "<?php echo base_url(); ?>customer/checkuser/email/<?php echo $id; ?>",
	            type: "post"
	      }
        },
        phone:{
          required: true,
          number: true,
          remote: {
	            url: "<?php echo base_url(); ?>customer/checkuser/phone/<?php echo $id; ?>",
	            type: "post"
	      }
        },
        <?php if($action=='Add'){ ?>
        password:{
          required: true,
          minlength : 6
        },
        confirm_password:{
          required: true,
          minlength : 6,
          equalTo : "#password"
        },
        <?php } ?>
        address:{
          required: true
        },
        country:{
          required: true
        },
        state:{
          required: true
        },
        city:{
          required: true
        },
        zipcode:{
          required: true
        }
      },
      // Specify validation error messages
      messages: {
      	email:{
      		remote: "This email id is already exist"
      	},
      	username:{
      		remote: "This username is already exist"
      	},
      	phone:{
      		remote: "This phone number is already exist"
      	}
      },
      submitHandler: function(form) {
      	$('#hidData').val('JSENABLE');
        form.submit();
      }
    });
});
</script>	