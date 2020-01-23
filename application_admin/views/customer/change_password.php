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
                            <h4 class="m-t-0 m-b-30">Change Password</h4>
                            <?php //echo validation_errors(); ?>
						      <ul class="parsley-errors-list filled error text-left" ><li class="parsley-required"><?php echo $errmsg; ?></li></ul>
								<form id="form1" class="form-horizontal" action="<?php echo $do_addedit_link;?>" method="post" enctype="multipart/form-data">
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Password <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="password" id="password" name="password" >
											   <span class="error" id="errorpassword"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Confirm Password <span class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="password" id="confirm_password" name="confirm_password">
											   <span class="error" id="errorupdatepassword"></span>
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
$(document).on('click','#send',function(){
	$('#errorpassword').html('');
	$('#errorupdatepassword').html('');
	var error=0;
	
	  if($('#password').val()==''){
	  	$('#errorpassword').html('This field should not be blank');
	  	var error=1;
	  }
	  if($('#confirm_password').val()==''){
	  	$('#errorupdatepassword').html('This field should not be blank');
	  	var error=1;
	  }
	  if($('#password').val()!=$('#confirm_password').val()){
	  	$('#errorupdatepassword').html('Confirm Password should be same as passowrd');
	  	var error=1;
	  }
	  if(error>0){
	  	return false;
	  }
});
</script>	