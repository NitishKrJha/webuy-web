<?php 
//pr($data);
?>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">staff</h4></div>
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
              <form id="form1" name="form1" class="form-horizontal" action="<?php echo $do_addedit_link;?>" method="post" enctype="multipart/form-data" autocomplete="off">
                <span class="section"></span>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Title <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                       <input type="text" id="title" name="title" class="form-control" value="<?php echo $title;?>" />
                       
                    </div>
                  </div>
                  <div class="form-group">
                  <input type="hidden" name="hidData" id="hidData" value="">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Content <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                       <textarea name="content" id="content" class="content mytextarea"><?php echo $content;?></textarea> 
                       
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
$('.datepicker').datepicker({
    format: 'dd/mm/yyyy'
});
function validate_data(){
  var editorContent_content_en = tinyMCE.get('content_en').getContent();
  var editorContent_content_ch = tinyMCE.get('content_ch').getContent();
  if (editorContent_content_en == '' || editorContent_content_ch=='')
  {
  // Editor empty
    alert('Content cannot be blank.!');
    return false;
  }
  else
  {
    return true;
  }
}
</script>