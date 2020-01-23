<?php
//pr($data);
?>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Ad Section</h4></div>
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
                            <form id="form1" name="form1" class="form-horizontal" method="post" action="<?php echo $do_addedit_link;?>" enctype="multipart/form-data" onsubmit="return validate_data()">
                                <span class="section">Ad Section</span>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Title<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="title" class="form-control" value="<?php echo $title ?>" required="required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Page Url<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="page_url" class="form-control" value="<?php echo $page_url ?>" required="required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Web Ad Icon [Width:1300px,Height:605px] <span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <input accept="image/*" type="file" id="icon" name="icon" class="form-control <?php if(trim($icon) == ''){ ?>validate[required]<?php } ?>" style="height:auto !important;" />
                                        <br>
                                        <div id="r_pic" style="<?php if(trim($icon) == ''){ ?>display:none;<?php }else{ ?>display:block;<?php } ?>"><img src="<?php echo file_upload_base_url().'adsection/'.$icon;?>" width="100" height="100" ></div>
                                        <input type="hidden" name="hdFileID_icon" id="hdFileID_icon" value="<?php echo $icon; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">App Ad Icon [Width:1300px,Height:605px] <span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <input accept="image/*" type="file" id="icon_app" name="icon_app" class="form-control <?php if(trim($icon_app) == ''){ ?>validate[required]<?php } ?>" style="height:auto !important;" />
                                        <br>
                                        <div id="r_pic" style="<?php if(trim($icon_app) == ''){ ?>display:none;<?php }else{ ?>display:block;<?php } ?>"><img src="<?php echo file_upload_base_url().'adsection/'.$icon_app;?>" width="100" height="100" ></div>
                                        <input type="hidden" name="hdFileID_icon_app" id="hdFileID_icon_app" value="<?php echo $icon_app; ?>" />
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

    function displayPreview(files,banner_type) {
        var reader = new FileReader();
        var img = new Image();
        reader.onload = function (e) {
            img.src = e.target.result;
            fileSize = Math.round(files.size / 1024);
            console.log("File size is " + fileSize + " kb");

            img.onload = function () {
                console.log("Banner Type:" +banner_type);
                /* if(parseInt(this.width)>800 || parseInt(this.height)>800){
                    alert('Image Size to large !');
                    $("#icon").val('');
                } */
                /*if(parseInt(this.width)<1000 || parseInt(this.height)<500){
                    alert('Image Size to small !');
                    $("#icon").val('');
                }*/
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
        var banner_type = $("#banner_type").val();
        if(banner_type!=''){
            var file = this.files[0];
            displayPreview(file,banner_type);
        }else{
            alert('First select banner type.');
            $(this).val('');
        }

    });
    function validate_data(){
        var editorContent_title_en = tinyMCE.get('title_en').getContent();
        var editorContent_title_ch = tinyMCE.get('title_ch').getContent();
        if (editorContent_title_en == '' || editorContent_title_ch=='')
        {
            // Editor empty
            alert('Banner Title cannot be blank.!');
            return false;
        }
        else
        {
            return true;
        }
    }
</script>