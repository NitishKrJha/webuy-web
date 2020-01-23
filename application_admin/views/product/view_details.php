<?php 
//pr($data);
?>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Product</h4></div>
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
										<h4 class="m-t-0 m-b-30">Product Details</h4>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Picture </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
										<?php if(!empty($imagelist)){
											foreach($imagelist as $imageDetails){
										?>

											<img src="<?php echo front_base_url()."uploads/product/".$imageDetails['path_sm']; ?>" style="width: 100px;height: 100px;">
										<?php } }?>	
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Title </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="first_name" name="first_name" value="<?php echo $title;?>" readonly>
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">SKU </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="last_name" name="last_name" value="<?php echo $sku;?>" readonly>  
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Price </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="email" name="email" value="<?php echo $sale_price;?>" readonly>
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Purchase Price</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="phone_number" name="phone_number" value="<?php echo $purchase_price;?>" readonly>
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Quantity </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $quantity ;?>" readonly>
											   
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Current Stock </label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											   <input class="validate[required] form-control" type="text" id="username" name="username" value="<?php echo $address;?>" readonly>
											   
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
<script>
	function ChangeStatus(url){
		if (confirm("Are you sure want to change status?")) {
	        window.location.href=url;
	    }
	    return false;
	}
</script>