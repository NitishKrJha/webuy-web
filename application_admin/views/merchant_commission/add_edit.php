<link href="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/css/loader.css" rel="stylesheet">
<div class="loading" id="load-txt" style="display: none;">Loading&#8230;</div>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Merchant Commission</h4></div>
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
							<form id="form1" name="form1" class="form-horizontal" method="post" action="<?php echo $do_addedit_link;?>" enctype="multipart/form-data">
				            	<span class="section">Commission</span>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Category Level 1<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <select onchange="getCatLevelTwo(this.value);" name="cat_level1" id="cat_level1" class="form-control">
                                            <option value="">Please Select</option>
                                            <?php
                                            if(count($cat_level_one) > 0){
                                                foreach ($cat_level_one as $catl) {
                                                    if(isset($cat_level1) && $cat_level1==$catl['id']){
                                                        echo '<option value="'.$catl['id'].'" selected>'.$catl['name'].'</option>';
                                                    }else{
                                                        echo '<option value="'.$catl['id'].'">'.$catl['name'].'</option>';
                                                    }

                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Category Level 2<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="cat_level2" id="cat_level2" class="form-control">

                                        </select>
                                    </div>
                                </div>
                                <p><h4>New Customer Web(%)</h4></p>
				                <div class="form-group">
				                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="new_upto_350_web">Upto 350<span class="required">*</span></label>
				                    <div class="col-sm-3">
										<input type="text" name="new_upto_350_web" class="form-control" value="<?php echo $new_upto_350_web; ?>">
									</div>
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="new_above_350_web">Above 350<span class="required">*</span></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="new_above_350_web" class="form-control" value="<?php echo $new_above_350_web; ?>">
                                    </div>
				                </div>
                                <p><h4>Existing Customer Web(%)</h4></p>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ex_upto_2500_web">Upto 2500<span class="required">*</span></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="ex_upto_2500_web" class="form-control" value="<?php echo $ex_upto_2500_web; ?>">
                                    </div>
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ex_above_2500_web">Above 2500<span class="required">*</span></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="ex_above_2500_web" class="form-control" value="<?php echo $ex_upto_2500_web; ?>">
                                    </div>
                                </div>
                                <p><h4>New Customer App(%)</h4></p>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="new_upto_350_app">Upto 350<span class="required">*</span></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="new_upto_350_app" class="form-control" value="<?php echo $new_upto_350_app; ?>">
                                    </div>
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="new_above_350_app">Above 350<span class="required">*</span></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="new_above_350_app" class="form-control" value="<?php echo $new_above_350_app; ?>">
                                    </div>
                                </div>
                                <p><h4>Existing Customer App(%)</h4></p>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ex_upto_2500_app">Upto 2500<span class="required">*</span></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="ex_upto_2500_app" class="form-control" value="<?php echo $ex_upto_2500_app; ?>">
                                    </div>
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ex_above_2500_app">Above 2500<span class="required">*</span></label>
                                    <div class="col-sm-3">
                                        <input type="text" name="ex_above_2500_app" class="form-control" value="<?php echo $ex_above_2500_app; ?>">
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
<?php
if(isset($cat_level1)) {
    ?>
<script>
   $(document).ready(function () {
       getCatLevelTwo(<?php echo $cat_level1 ?>,<?php echo $cat_level2 ?>);
   });
</script>
    <?php
}
?>


<script type="text/javascript">
function getCatLevelTwo(cat_level1,cat_level2='') {
    $.ajax({
        type: 'POST',
        url: "<?php echo base_url('commission/get_cat_level_two'); ?>",
        data: {'cat_level_one':cat_level1,'cat_level_two':cat_level2},
        success: function(resultData) {
           $('#cat_level2').html(resultData);
      }
    });
}
$(function() {

  $("form[name='form1']").validate({
    
    // Specify validation rules
    rules: {
        cat_level1:{
        required: true
      },
        cat_level2:{
        required: true
      },
        new_upto_350_web:{
        required:true
      },
        new_above_350_web:{
            required:true
        },
        ex_upto_2500_web:{
            required:true
        },
        ex_above_2500_web:{
            required:true
        },
        new_upto_350_app:{
            required:true
        },
        new_above_350_app:{
            required:true
        },
        ex_upto_2500_app:{
            required:true
        },
        ex_above_2500_app:{
            required:true
        }
    },
    errorPlacement: function(error, element) {},
    // Specify validation error messages
    messages: {
    	name:"This field is required"
    },
    submitHandler: function(form) {
      $('#errorImgLength').html('');
      $('#load-txt').show();
      //form.submit();
       alert(form.serialize());
    }
  });
});
</script>