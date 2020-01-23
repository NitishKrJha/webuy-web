<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Setting</h4></div>
    </div>
    <div class="page-content-wrapper ">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                         <div class="panel-body">
                            <ul class="parsley-errors-list filled error text-left" ><li class="parsley-required"><?php echo $errmsg; ?></li></ul>
							<form role="form" id="myform" class="form-horizontal form-label-left" id="form1" action="<?php echo $submit_link;?>" method="post" enctype="multipart/form-data" onsubmit="return validate_data()">
									<span class="section">CMS
									<small>
										<i class="ace-icon fa fa-angle-double-right"></i>
										<?php echo $action."&nbsp;".$content->title?> Content
									</small>
									</span>
									<div class="form-group">
										<label for="form-field-1" class="control-label col-md-2 col-sm-3 col-xs-12">Content<span class="required">*</span></label>
										<div class="col-sm-10">
											<textarea name="content" id="content" class="content mytextarea"><?php echo $content->content;?></textarea>
										</div>
									</div>
									<input type="hidden" name="cms_hid" value="<?php echo $pages;?>" />
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-9">
											<input type="submit" value="Update" class="btn btn-shadow btn-success" id="send"/>
										
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
$(document).ready(function(){
	$("#myform").validationEngine();
});
function validate_data(){
	var content = tinyMCE.get('content').getContent();
	if (content == '')
	{
		alert('Content cannot be blank.!');
		return false;
	}
	else
	{
		return true;
	}
}
</script>