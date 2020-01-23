<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_content">
	  <?php //echo validation_errors(); ?>
      <ul class="parsley-errors-list filled error text-left" ><li class="parsley-required"><?php echo $errmsg; ?></li></ul>
		<form id="form1" name="form1" class="form-horizontal" action="<?php echo $do_addedit_link;?>" method="post" enctype="multipart/form-data">
			<span class="section">Ad Category</span>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"> Level <span class="required">*</span></label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<select class="form-control validate[required]" name="level" id="level">
							<?php for($lno = 0; $lno < $level_nos; $lno++){ ?>
								<option value="<?php echo $lno; ?>"<?php if($lno == $level){ ?> selected<?php } ?>><?php echo $lno; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12"> Parent <span class="required">*</span></label>
					<div class="col-md-6 col-sm-6 col-xs-12" id="service_parent">
						<select id="parent" name="parent" class="form-control">
                        	<option value="0">-- None --</option>
                        </select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Title(EN) <span class="required">*</span></label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					   <input class="form-control validate[required]" type="text" id="title_en" name="title_en" value="<?php echo $title_en;?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Icon(Max width:150 and Height:80) <span class="required">*</span></label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					   <input class="form-control <?php if($action=='Add'){ ?>validate[required]<?php } ?>" type="file" id="icon" name="icon" />
					   <br>
                        <div id="r_pic" style="<?php if(trim($icon) == ''){ ?>display:none;<?php }else{ ?>display:block;<?php } ?>"><img src="<?php echo file_upload_base_url().'category/'.$icon; ?>" width="100" height="100" /></div>
                     	<input type="hidden" name="hdFileID_icon" id="hdFileID_icon" value="<?php echo $icon; ?>" />
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
<script type="text/javascript">
$(document).ready(function(){
	$("#form1").validationEngine();
	<?php if($action=='Add'){ ?>
		//$(".add_more").trigger('click');
	<?php }else{ ?>
		<?php if($is_paid==0 && $level==1){?>
//			$(".add_more").hide();
//			$(".is_paid_div").hide();
//			$(".more_rang_data").empty();
		<?php }else if($is_paid==0 && $level==0){?>
//			$(".add_more").hide();
//			$(".is_paid_div").show();
		<?php }else{ ?>
//			$(".add_more").show();
//			$(".is_paid_div").show();
		<?php } } ?>
});
$("#level").on('change',function(){
	var lv = $(this).val();
	console.log(lv);
	if(lv==1){
		$(".is_paid_div").hide();
		$(".add_more").hide();
		$(".more_rang_data").empty();
	}else{
		$(".is_paid_div").show();
		$(".add_more").show();
		$(".add_more").trigger('click');
	}
	$.ajax({
		url: "<?php echo base_url($this->controller."/setParent/"); ?>"+lv, 
		success: function(result){
		$("#service_parent").html(result);
	}});
});
<?php if($action=='Edit'){?>
$.ajax({
	url: "<?php echo base_url($this->controller."/setParent/"); ?>"+<?php echo $level; ?>+"/"+<?php echo $parent; ?>, 
	success: function(result){
		$("#service_parent").html(result);
	}
});
<?php } ?>
//$(".add_more").on('click',function(){
//	$(".more_rang_data").append($("#newEliments").html());
//});
//$(document).on("click",".delelem",function(){
//	$(this).parent().parent().remove();
//});
//$("#is_paid").on('change',function(){
//	console.log($(this).val());
//	if($(this).val()==0){
//		$(".add_more").hide();
//		$(".more_rang_data").empty();
//	}else{
//		$(".add_more").show();
//		$(".add_more").trigger('click');
//	}
//
//});
function isNumberKey(evt)
{
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode != 46 && charCode > 31 
	&& (charCode < 48 || charCode > 57))
	 return false;

  return true;
}
</script>	
<script type="html/text" id="newEliments">
	<div class="form-group col-md-12 newelem">
		<label class="control-label col-md-4 col-sm-4 col-xs-12"> Price/Posting</label>
		<div class="col-md-2"><input class="form-control" onkeypress="return isNumberKey(event)" name="price[]" type="text" placeholder="Price" required ></div>
		<div class="col-md-2"><input class="form-control" onkeypress="return isNumberKey(event)" name="number_posting[]" type="text" placeholder="Posting" required></div>
		<div class="col-md-2 text-left"><i class="fa fa-trash delelem"></i></div>
	</div>
</script>