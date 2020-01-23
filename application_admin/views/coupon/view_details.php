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
            <h4 class="page-title">Coupo</h4></div>
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
										   <input class="validate[required] form-control" type="text" id="coupon_name" name="coupon_name" value="<?php echo htmlentities($coupon_name);?>" autocomplete="off" readonly >
                       <?php echo form_error('coupon_name'); ?>
										   
										</div>
									</div>



                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Coupon Code<span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                       <input class="validate[required] form-control" type="text" id="coupon_code" name="coupon_code" value="<?php echo $coupon_code;?>" autocomplete="off" readonly>
                       
                    </div>
                  </div>
									
									


					                <div class="form-group">
					                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Discount Type*</label>
					                    <div class="col-md-6 col-sm-6 col-xs-12">
					                      <select name="discount_type" id="offer_type" class="form-control"  onchange="getState(this.value)" readonly>

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
                       <input class="validate[required] form-control" type="text" id="discount_value" name="discount_value" value="<?php echo $discount_value;?>" autocomplete="off" readonly>
                       
                    </div>
                  </div>

                           <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Discount For*</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="discount_for" id="discount_for" class="form-control" readonly onchange="getState(this.value)">
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
                               <select name="discount_select[]" multiple="multiple" id="discount_select" class="form-control" readonly>
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
                               <select name="discount_select1[]" multiple="multiple" id="discount_select1" class="form-control" readonly>
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
										   <input class="validate[required] form-control" type="text" id="datepicker" name="start_date" value="<?php echo $start_date;?>" autocomplete="off" readonly >
										   
										</div>
									</div>

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">End Date <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                       <input class="validate[required] form-control" type="text" id="datepicker1" name="end_date" value="<?php echo $end_date;?>" autocomplete="off" readonly>

                       
                    </div>
                  </div>

                  
                  

									<div class="ln_solid"></div>
									<div class="form-group">
										<div class="col-md-6 col-md-offset-3">
										  <a class="btn btn-primary" href="javascript:window.history.back();">Cancel</a>
										  
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


