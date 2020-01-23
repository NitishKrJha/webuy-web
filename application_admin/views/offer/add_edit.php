
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">offer</h4></div>
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Offer Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										   <input class="validate[required] form-control" type="text" id="offer_name" name="offer_name" value="<?php echo htmlentities($offer_name);?>" autocomplete="off">
                       <?php echo form_error('offer_name'); ?>
										   
										</div>
									</div>
									
									<div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Cotegory *</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <select name="category_level_1" id="category_level_1" class="form-control" required="required">
					                      <option value="">Select Category</option>
					                      <?php
		                                    if(count($category_level_1_data) > 0){
		                                      foreach ($category_level_1_data as $key => $value) {
		                                        $selected='';
		                                        if(isset($category_level_1)){
		                                        	if($value['id']==$category_level_1){
			                                          $selected='selected="selected"';
			                                        }
		                                        }
		                                        
		                                        echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['name'].'</option>';
		                                      }
		                                    }
		                                  ?>
					                    </select>
					                    </div>
					                </div>


					                <div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Amount type *</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <select name="offer_type" id="offer_type" class="form-control" required="required" onchange="getState(this.value)">
					                      <option value="percent">Percent</option>
					                      <option value="value">Value</option>
					                      
					                    </select>
					                    </div>
					                </div>




									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Actual Amount<span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										   <input class="validate[required] form-control" type="text" id="offer_amount" name="offer_amount" value="<?php echo $offer_amount;?>" autocomplete="off">
										   
										</div>
									</div>

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Amount Limit <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                       <input class="validate[required] form-control" type="text" id="offer_limit" name="offer_limit" value="<?php echo $offer_limit;?>" autocomplete="off">

                       
                    </div>
                  </div>
                  





									<div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Short Description *</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <textarea name="short_description" id="short_description" class="form-control"><?php echo isset($short_description)?$short_description:'';?></textarea>
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
var country='<?php echo isset($country)?$country:''; ?>';
if(country !=0){
  getState(country);
}


  function getState(val){
    var state='<?php echo isset($state)?$state:0; ?>';
   // alert(state);
    if(val!=''){
      var url='<?php echo base_url(); ?>offer/getState/'+val+'/'+state;
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
    var city='<?php echo isset($city)?$city:0; ?>';
    if(val!=''){
      var url='<?php echo base_url(); ?>offer/getCity/'+val+'/'+city;
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
        offer_name:{
          required: true,
          specialChars: true
        },
        category_level_1:{
          required: true,
          specialChars: true
        },
        offer_amount:{
          required: true,
          //specialChars: true
        },
        offer_limit:{
          required: true,
          
	      
        },
        short_description:{
          required: true,
          //specialChars: true
        },
        phone:{
          required: true,
          number: true,
          remote: {
	            url: "<?php echo base_url(); ?>offer/checkuser/phone/<?php echo $id; ?>",
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
        offer_type:{
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
        },
        user_type:{
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