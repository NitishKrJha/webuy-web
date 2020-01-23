<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_content">
	  <?php //echo validation_errors(); ?>
      <ul class="parsley-errors-list filled error text-left" ><li class="parsley-required"><?php echo $errmsg; ?></li></ul>
			<form id="form1" name="form1" class="form-horizontal" method="post" action="<?php echo $do_addedit_link;?>" enctype="multipart/form-data">
            	<span class="section">Amenities</span>
				<div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Name<span class="required">*</span></label>
                    <div class="col-sm-9">
						<input type="text" name="name" class="validate[required] col-xs-10 col-sm-5 form-control" value="<?php echo isset($name)?$name:''; ?>" required>
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
jQuery(document).ready(function(){
	jQuery("#form1").validationEngine();
});
function displayPreview(files,faq_type) {
    var reader = new FileReader();
    var img = new Image();
    reader.onload = function (e) {
        img.src = e.target.result;
        fileSize = Math.round(files.size / 1024);
        console.log("File size is " + fileSize + " kb");

        img.onload = function () {
			console.log("faq Type:" +faq_type);
				/* if(parseInt(this.width)>800 || parseInt(this.height)>800){
					alert('Image Size to large !');
					$("#icon").val('');
				} */
				if(parseInt(this.width)<1000 || parseInt(this.height)<500){
					alert('Image Size to small !');
					$("#icon").val('');
				}
            console.log("width=" + this.width + " height=" + this.height);
        };
    };
    reader.readAsDataURL(files);
}
$("#icon").change(function () {
	var faq_type = $("#faq_type").val();
	if(faq_type!=''){
		var file = this.files[0];
		displayPreview(file,faq_type);
	}else{
		alert('First select faq type.');
		$(this).val('');
	}
    
});

</script>