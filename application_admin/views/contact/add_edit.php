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
                            <h4 class="m-t-0 m-b-30"><?php echo $action; ?></h4>
                            <?php //echo validation_errors(); ?>
                            <ul class="parsley-errors-list filled error text-left" ><li class="parsley-required"><?php echo $errmsg; ?></li></ul>
                            <form id="form1" name="form1" class="form-horizontal" method="post" action="<?php echo $do_addedit_link;?>" enctype="multipart/form-data">
                                <span class="section">Contact  Us</span>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">NAME :  <span class="required"></span></label>
                                    <div class="col-sm-9">  
                                     <?php echo $full_name; ?>   
                                        <!--<select class="form-control validate[required]" name="banner_type" id="banner_type">
                                            <option value="">Select Type</option>
                                                <option value="1" <?php if($type==1){ ?>selected<?php } ?>>Home Page</option>
                                                <option value="2" <?php if($type==2){ ?>selected<?php } ?>>Retailer</option>
                                                <option value="3" <?php if($type==3){ ?>selected<?php } ?>>Supplier</option>
                                        </select> -->
                                    </div>
                                </div>
                                <!--<div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Banner Title <span class="required">*</span></label>
                                    <div class="col-sm-9">    
                                        <input name="title" type="text" id="title" class="textbox form-control validate[required]" value="<?php //echo $title; ?>" />
                                    </div>
                                </div>-->
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Phone No. : <span class="required"></span></label>
                                    <div class="col-sm-9"> 
                                    <?php echo $phone; ?>        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Email : <span class="required"></span></label>
                                    <div class="col-sm-9"> 
                                    <?php echo $email; ?> 
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Reply: <span class="required"></span></label>
                                    <div class="col-sm-9"> 
                                    <textarea name="reply"><?php echo $reply; ?> </textarea>
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
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine();
});
function displayPreview(files,banner_type) {
    var reader = new FileReader();
    var img = new Image();
    reader.onload = function (e) {
        img.src = e.target.result;
        fileSize = Math.round(files.size / 1024);
        console.log("File size is " + fileSize + " kb");

        img.onload = function () {
            console.log("Banner Type:" +banner_type);
            if(banner_type=='1'){
                if(parseInt(this.width)>800 || parseInt(this.height)>800){
                    alert('Image Size to large !');
                    $("#icon").val('');
                }
            }else if(banner_type=='3'){
                if(parseInt(this.width)<1000 || parseInt(this.height)<500){
                    alert('Image Size to small !');
                    $("#icon").val('');
                }
            }
            console.log("width=" + this.width + " height=" + this.height);
        };
    };
    reader.readAsDataURL(files);
}
$("#icon").change(function () {
    var banner_type = $("#banner_type").val();
    if(banner_type!=''){
        var file = this.files[0];
        displayPreview(file,banner_type);
    }else{
        alert('First select banner type.');
        $(this).val('');
    }
    
});
</script>