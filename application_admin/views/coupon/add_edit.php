<head>
 
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
  </script>
</head>

<div class="content">

    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Coupon</h4></div>
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
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Coupon Name <span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										   <input class="validate[required] form-control" type="text" id="coupon_name" name="coupon_name" value="<?php echo htmlentities($coupon_name);?>" autocomplete="off" required >
                       <?php echo form_error('coupon_name'); ?>
										   
										</div>
									</div>



                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Coupon Code<span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                       <input class="validate[required] form-control" type="text" id="coupon_code" name="coupon_code" value="<?php echo $coupon_code;?>" autocomplete="off" required>
                       
                    </div>
                  </div>
									
									


					                <div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Discount Type*</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <select name="discount_type" id="offer_type" class="form-control"  onchange="getState(this.value)" required>

			<!--<option value="percent">Percent</option>-->
<option <?php if(isset($discount_type) && $discount_type=="percent") {?> selected="selected"<?php } ?> value="percent" >Percent</option>
<option <?php if(isset($discount_type) && $discount_type=="value") {?> selected="selected"<?php } ?> value="value" >value</option>
			<!--<option value="value">Value</option>-->
					                      
					                    </select>
					                    </div>
					                </div>


                          <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Discount Value<span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                       <input class="validate[required] form-control" type="text" id="discount_value" name="discount_value" value="<?php echo $discount_value;?>" autocomplete="off" required>
                       
                    </div>
                  </div>

                           <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Discount For*</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="discount_for" id="discount_for" class="form-control" required="required" onchange="getState(this.value)">
        <option <?php if(isset($discount_for) && $discount_for=="all") {?> selected="selected"<?php } ?> value="all" >All</option>
        <option <?php if(isset($discount_for) && $discount_for=="category") {?> selected="selected"<?php } ?> value="category" >Category</option>
        <option <?php if(isset($discount_for) && $discount_for=="product") {?> selected="selected"<?php } ?> value="product" >Product</option>
                                <!--<option value="all">All</option>
                                <option value="category">Category</option>
                              
                                <option value="product">Product</option>-->
                                
                              </select>
                              </div>
                          </div>

                          <div id="category" style="display:none;">
                          <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" >Cotegory *</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                               <select name="discount_select[]" multiple="multiple" id="discount_select" class="form-control" required="required">
                                <option value="">Select User Type</option>
                                <?php
                                        if(count($category_level_1) > 0){
                                          foreach ($category_level_1 as $key => $value) {
                                           // $selected='';
                                           // if(isset($co)){
                                            if(isset($discount_select)){ 
                                              $tt=explode(',', $discount_select);
                                              if(in_array($value['id'],$tt)){
                                                $selected="selected";
                                              }else{
                                                $selected='';
                                              }
                                            }
                                            
                                           
                                            
                                            echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['name'].'</option>';
                                          }
                                        }
                                      ?>
                              </select> 
                              </div>
                          </div>
                        </div>


                         <div id="product" style="display:none;">
                          <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12">Product *</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                               <select name="discount_select1[]" multiple="multiple" id="discount_select1" class="form-control" required="required">
                                <option value="">Select User Type</option>
                                <?php
                                        if(count($product) > 0){
                                          foreach ($product as $key => $value) {


                                           // $selected='';

                                             if(isset($discount_select)){ 
                                              $tt1=explode(',', $discount_select);
                                              if(in_array($value['product_id'],$tt1)){
                                                $selected="selected";
                                              }else{
                                                $selected='';
                                              }
                                            }
                                           
                                            
                                            echo '<option value="'.$value['product_id'].'" '.$selected.'>'.$value['title'].'</option>';
                                          }
                                        }
                                      ?>
                              </select>
                              </div>
                          </div>
                        </div>




									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12">Start Date<span class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-12">
										   <input class="validate[required] form-control" type="text" id="datepicker" name="start_date" value="<?php echo $start_date;?>" autocomplete="off" required >
										   
										</div>
									</div>

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">End Date <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                       <input class="validate[required] form-control" type="text" id="datepicker1" name="end_date" value="<?php echo $end_date;?>" autocomplete="off" required>

                       
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
  $( function() {
    $( "#datepicker" ).datepicker();
    dateFormat: "yy-mm-dd"
  } );
   $( function() {
    $( "#datepicker1" ).datepicker();
    dateFormat: "yy-mm-dd"
  } );
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

 $(document).ready(function(){ 
        $('#discount_for').trigger('change');
         
    });

$("#discount_for").on('change',function(){ 
  
    var discount_for = $(this).val();
    if(discount_for =='category'){
        $('#category').show();
         $('#product').hide();
    }
     else if(discount_for =='product'){
        $('#category').hide();
        $('#product').show();
    }
    else{
        $('#category').hide();
        $('#product').hide();
    }
  

  });



$("#coupon_name").on('change',function(){ 
    $.ajax({
    url : '<?php echo base_url("coupon/checkCouponName");?>',
    type : 'POST',
    data : 'coupon_name='+$(this).val(),
    dataType : 'json',
    success : function(data){ 
      if(data[0]['coupon_id']){
       alert('This Coupon already Exist'); 
     $('#coupon_name').val('');
      }
    }
  })
});



$("#coupon_code").on('change',function(){ 
    $.ajax({
    url : '<?php echo base_url("coupon/checkCouponCode");?>',
    type : 'POST',
    data : 'coupon_code='+$(this).val(),
    dataType : 'json',
    success : function(data){ 
      if(data[0]['coupon_id']){
       alert('This Code already Exist'); 
     $('#coupon_code').val('');
      }
    }
  })
});

</script>	