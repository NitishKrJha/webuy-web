<link href="<?php echo CSS_IMAGES_JS_BASE_URL; ?>assets/css/loader.css" rel="stylesheet">
<div class="loading" id="load-txt" style="display: none;">Loading&#8230;</div>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Category Level3</h4></div>
    </div>
    <div class="page-content-wrapper ">
        <div class="container">

           <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <h4 class="m-t-0 m-b-30">Edit</h4>
                            <?php //echo validation_errors(); ?>
      						<ul class="parsley-errors-list filled error text-left" ><li class="parsley-required"><?php echo $errmsg; ?></li></ul>
							     <form id="form1" name="form1" class="form-horizontal" method="post" action="<?php echo $do_addedit_link;?>" enctype="multipart/form-data">
				            	<span class="section">category</span>
								
				                <div class="form-group">
				                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Name<span class="required">*</span></label>
				                    <div class="col-sm-9">
										            <input type="text" name="name" class="form-control" value="<?php echo $name ?>">
									           </div>
				                </div>
				                <div class="form-group">
				                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Description<span class="required">*</span></label>
				                    <div class="col-sm-9">
										          <textarea name="description" class="form-control"><?php echo $description ?></textarea>
									          </div>
				                </div>
                        <div class="form-group">
                           <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Category Level 2<span class="required">*</span></label>
                           <div class="col-sm-9">
                              <select name="level2" id="level2" class="form-control">
                                <option value="">Please Select</option>
                                <?php
                                  if(count($level2_data) > 0){
                                    foreach ($level2_data as $key => $value) {
                                      echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                                    }
                                  }
                                ?>
                              </select>
                            </div>
                        </div>
				                <div class="form-group">
				                	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Icon  <span class="required">*</span></label>
				                    <div class="col-sm-9"> 
				                    	<input accept="image/*" type="file" id="icon" name="icon" class="form-control <?php if(trim($icon) == ''){ ?>validate[required]<?php } ?>" style="height:auto !important;" /> 
				                        <br>
				                        <div id="r_pic" style="<?php if(trim($icon) == ''){ ?>display:none;<?php }else{ ?>display:block;<?php } ?>"><img src="<?php echo file_upload_base_url().'category/level3/'.$icon;?>" width="100" height="100" ></div>
				                     	<input type="hidden" name="hdFileID_icon" id="hdFileID_icon" value="<?php echo $icon; ?>" />
				                     	<span>Image Size shoud be under (width: 500, height:250)</span>
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

function displayPreview(files,category_type) {
    var reader = new FileReader();
    var img = new Image();
    reader.onload = function (e) {
        img.src = e.target.result;
        fileSize = Math.round(files.size / 1024);
        console.log("File size is " + fileSize + " kb");

        img.onload = function () {
			console.log("category Type:" +category_type);
				/* if(parseInt(this.width)>800 || parseInt(this.height)>800){
					alert('Image Size to large !');
					$("#icon").val('');
				} */
				if(fileSize > 500){
          alert('Image Size is to large !');
          $("#icon").val('');
        }
            console.log("width=" + this.width + " height=" + this.height);
        };
    };
    reader.readAsDataURL(files);
}
$("#icon").change(function () {
	var category_type = $("#category_type").val();
	if(category_type!=''){
		var file = this.files[0];
		displayPreview(file,category_type);
	}else{
		alert('First select category type.');
		$(this).val('');
	}
    
});


$(function() {

  $("form[name='form1']").validate({
    
    // Specify validation rules
    rules: {
      name:{
        required: true
      },
      description:{
        required: true
      },
      level2:{
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
      form.submit();
    }
  });
});
</script>