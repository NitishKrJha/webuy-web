<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Variation</h4></div>
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
				            	<span class="section"></span>
								<div class="form-group">
				                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Name<span class="required">*</span></label>
				                    <div class="col-sm-9">
										<input type="text" name="name" class="validate[required] col-xs-10 col-sm-5 form-control" value="<?php echo isset($name)?$name:''; ?>">
									</div>
				                </div>
				                
				               
								<div id="addData">
									<?php
										if(isset($variation_value)){
											if(count($variation_value) > 0){
												$i=1;
												foreach ($variation_value as $key => $value) {
													?>
													<div class="form-group">
									                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">
									                    <?php 
									                    if($i==1){
									                    	echo 'Value<span class="required">*</span>';
									                    }
									                    ?>
									                    	
									                    </label>
									                    <div class="col-sm-8">
															<input type="text" name="value[]" class="validate[required] col-xs-10 col-sm-5 form-control" value="<?php echo $value['name']; ?>" required="required">
														</div>
														<?php
															if($i>1){
																?>
																<div class="col-sm-1" id="deleteData"><i class="mdi mdi-minus-circle-outline"></i></div>
																<?php
															}else{
																?>
																<div class="col-sm-1"><i id="addmore" class="mdi mdi-plus-circle-outline"></i></div>
																<?php
															}
														?>
														
									                </div>
													<?php
													$i++;
												}
											}else{
												?>
												<div class="form-group">
								                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Value<span class="required">*</span></label>
								                    <div class="col-sm-8">
														<input type="text" name="value[]" class="validate[required] col-xs-10 col-sm-5 form-control" required="required">
													</div>
													<div class="col-sm-1"><i id="addmore" class="mdi mdi-plus-circle-outline"></i></div>
								                </div>
												<?php
											}
										}else{
											?>
											<div class="form-group">
							                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Value<span class="required">*</span></label>
							                    <div class="col-sm-8">
													<input type="text" name="value[]" class="validate[required] col-xs-10 col-sm-5 form-control" required="required">
												</div>
												<div class="col-sm-1"><i id="addmore" class="mdi mdi-plus-circle-outline"></i></div>
							                </div>
											<?php
										}
									?>
								</div>
								
								<div class="form-group">
				                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Category level1<span class="required">*</span></label>
				                    <div class="col-sm-9">
										<select name="level1" id="level1" class="form-control" onchange="getCategory(this.value,'level2')" required="required">
                                                    <option value="">Select</option>
                                                    <?php
                                                        if(count($category_level_1) > 0){
                                                            foreach ($category_level_1 as $key => $value) {
                                                                $selected='';
                                                                if($cat_level1==$value['id']){
                                                                    $selected='selected="selected"';
                                                                }
                                                                echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['name'].'</option>';
                                                            }
                                                        }
                                                    ?>
                                               </select>
									</div>
				                </div>

				                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Category 2  <em>*</em></label>
                                    <div class="col-sm-9">
                                       <select name="level2" id="level2" class="form-control" onchange="getCategory(this.value,'level3')" required="required">
                                            <option value="">Select</option>
                                            
                                       </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Category 3  <em>*</em></label>
                                    <div class="col-sm-9">
                                       <select name="level3" id="level3" class="form-control" onchange="getCategory(this.value,'level4')" required="required">
                                            <option value="">Select</option>
                                            
                                       </select>
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
var cat_level1='<?php echo isset($cat_level1)?$cat_level1:"" ?>';
if(cat_level1!=''){
	getCategory(cat_level1,'level2');
}

function getCategory(id,type){
    var url='<?php echo base_url('variation/getCategory'); ?>'
    $.ajax({
        type:'POST',
        url:url,
        data:{'id':id,'type':type},
        beforeSend:function(){},
        success:function(msg){
        var response=$.parseJSON(msg);
         if(response.error==0){
            $('#'+type).html(response.data);
            var cat_level1='<?php echo isset($cat_level1)?$cat_level1:"" ?>';
            var cat_level2='<?php echo isset($cat_level2)?$cat_level2:"" ?>';
            var cat_level3='<?php echo isset($cat_level3)?$cat_level3:"" ?>';
            if(type=='level2'){
                $('#'+type).val(cat_level2);
                getCategory(cat_level2,'level3');
            }else if(type=='level3'){
                $('#'+type).val(cat_level3);
                getCategory(cat_level3,'level4');
            }else{
                $('#'+type).val(cat_level1);
            }
         }else{
            messagealert('Error','Invalid Request','error');
         }
        },
        complete:function(){

        }
      })
}
$(document).ready(function(){
 


 $(document).on('click','.delmainimg',function(){
        var $this=$(this);
        var id=$this.attr('img-id');
        $this.button('loading');
        //alert(id);
        
        var url='<?php echo base_url('product/delimg'); ?>'
        $.ajax({
            type:'POST',
            url:url,
            data:{'id':id},
            beforeSend:function(){},
            success:function(msg){
            var response=$.parseJSON(msg);
             if(response.error==0){
                $this.parent().parent().remove();
             }else{
                $this.button('reset');
                messagealert('Error','Invalid Request','error');
             }
            },
            complete:function(){

            }
          })
    });
});
$(function(){
	$('#addmore').click(function(){
		var html='';
		html +='<div class="form-group">';
		html +='<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Value<span class="required">*</span></label>';
		html +='<div class="col-sm-8">';
		html +='<input type="text" name="value[]" class="validate[required] col-xs-10 col-sm-5 form-control" required="required">';
		html +='</div>';
		html +='<div class="col-sm-1" id="deleteData"><i class="mdi mdi-minus-circle-outline"></i></div>';
		html +='</div>';
		$('#addData').append(html);
	});
	
});
$(document).on('click','#deleteData',function(){
	$(this).parent('div').remove();
})
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

/*$(document).ready(function(){
	$("#form1").validationEngine();
});*/
</script>