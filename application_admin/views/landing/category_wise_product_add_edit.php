<?php
//pr($data);
?>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Category Wise Product</h4></div>
    </div>
    <div class="page-content-wrapper ">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <h4 class="m-t-0 m-b-30">Edit</h4>                            
                            <ul class="parsley-errors-list filled error text-left" ><li class="parsley-required"><?php echo $errmsg; ?></li></ul>
                            <form id="category_wise_form" name="category_wise_form" class="form-horizontal" method="post" action="<?php echo $do_addedit_link;?>" enctype="multipart/form-data" onsubmit="return validate_data()">
                                <span class="section">Category Wise Product</span>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Title<span class="required">*</span></label>
                                    <div class="col-sm-9">
                                        <input name="title" type="text" id="title" class="form-control" value="<?php echo $title;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Category Level 1<span class="required">*</span></label>
                                   <div class="col-sm-9">
                                      <select name="level1" id="level1" class="form-control valid" onchange="getCategoryLevel2(this.value);">
                                        <option value="">Please Select</option>
                                        <?php
                                          if(count($level1_data) > 0){
                                            foreach ($level1_data as $value) {
                                                ?>
                                              <option <?php if($value['id']==$level1){ echo'selected'; } ?> value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                              <?php
                                            }
                                          }
                                        ?>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cardholder_name">Category Level 2<span class="required">*</span></label>
                                   <div class="col-sm-9">
                                      <select name="level2" id="level2" class="form-control valid">
                                        <option value="">Please Select Category Level1</option>                                        
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

<?php if($level1!=''){ ?>
<script type="text/javascript">
    $.ajax({
          type:'POST',
          url: "<?php echo base_url('landing/getCatLevel2'); ?>",
          data:{'category_level_1':<?php echo $level1; ?>,'category_level_2':<?php echo $level2; ?>},          
          success:function(response){
            if(response.trim()!=''){
              $('#level2').html(response);
            }
          },
          error: function () {            
            messagealert('Error','Technicle issue , Please try later','error');
          }
        });
</script>

<?php } ?>

<script type="text/javascript">
    function getCategoryLevel2(catlevel1){
        $.ajax({
          type:'POST',
          url: "<?php echo base_url('landing/getCatLevel2'); ?>",
          data:{'category_level_1':catlevel1},          
          success:function(response){
            $('#level2').html(response);       
           
          },
          error: function () {            
            messagealert('Error','Technicle issue , Please try later','error');
          }
        });
    }
    
    $(document).ready(function () {
        

        $('#category_wise_form').validate({ // initialize the plugin
            rules: {
                title: {
                    required: true                    
                },
                level1: {
                    required: true                    
                }
            }
        });

    });
   
</script>