
            <div class="content">
                <div class="">
                    <div class="page-header-title">
                        <h4 class="page-title">Product</h4></div>
                </div>
                <div class="page-content-wrapper ">
                    <div class="container">
                        <!--<div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        <h4 class="m-t-0 m-b-30">Search By</h4>
                                        <form id="frmSearch" class="form-inline" name="frmSearch" action="<?php echo $search_link; ?>" method="post" >
                                            <div class="form-group">
                                                <label class="sr-only" for="exampleInputEmail2">Select</label>
                                                <select class="form-control" aria-controls="example" name="searchField">
                                                    <option value="">-Select-</option>
                                                     <option value="merchants.first_name" <?php if(isset($params['searchField']) && $params['searchField']=='merchants.first_name') echo 'selected';?>>Name</option>
                                                     <option value="merchants.email" <?php if(isset($params['searchField']) && $params['searchField']=='merchants.email') echo 'selected';?>>Email</option>
                                                 </select>
                                            </div>
                                            <div class="form-group m-l-10">
                                                <label class="sr-only" for="exampleInputPassword2">SearchString</label>
                                                <input type="text" class="form-control" value="<?php if(isset($params['searchString'])){echo $params['searchString']; }?>" name="searchString">
                                            </div>
                                            <input type="hidden" name="sortType" id="sortType" value="<?php echo $params['sortType'];?>" />
                                            <input type="hidden" name="sortField" id="sortField" value="<?php echo $params['sortField'];?>" />
                                            <input type="submit" id="addData" class="btn btn-primary marg-5" name="search" value="Search">
                                            <a href="<?php echo $showall_link;?>" class="btn btn-success marg-5">Show All</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        <h4 class="m-t-0 m-b-30 pull-left">List</h4>
                                        <!--<div class="pull-right"><a title="add member" href="<?php echo $add_link; ?>"><i class="fa fa-plus"></i></a></div>-->
                                        <div class="table-rep-plugin">
                                            
                                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                                <table id="tech-companies-1" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:80px;">Sl No.</th>
                                                            <th>Image</th>
                                                            <th>Title</th>
                                                            <th>Current Quantity</th>
                                                            <th>Price</th>
                                                            <th>Admin Approval</th>
                                                            <th>Option</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                         if(!empty($recordset)){ $i=1; 
                                                            foreach($recordset as $singleRecord){ 
                                                              $ediLink = str_replace('{{ID}}', $singleRecord['product_id'], $edit_link);
                                                              $activeLink = str_replace('{{ID}}',$singleRecord['product_id'],$activated_link);
                                                              $inacttivedLink = str_replace('{{ID}}',$singleRecord['product_id'],$inacttived_Link);
                                                             ?>
                                                             <tr class="<?php echo $i%2==0?'even':'odd'; ?> pointer">
                                                              <td style="width:80px;"><?php echo $i+$startRecord; ?></td>
                                                              <td style="width:100px; text-align:center">
                                                              <?php
                                                                $pr_image=css_images_js_base_url().'images/no-img.png';
                                                                foreach ($singleRecord['img_list'] as $image) {
                                                                   if($image['path_sm']!='' && $image['type']=='main'){
                                                                  $pr_image=file_upload_base_url().'product/'.$image['path_sm'];
                                                                }
                                                                }
                                                                
                                                              ?>
                                                              <div class="seller-product-img"><img src="<?php echo $pr_image; ?>" alt="" /></div></td>
                                                              <td><?php echo $singleRecord['title']; ?></td>
                                                              <td style="width:150px;"><?php echo $singleRecord['quantity']; ?></td>
                                                              <td><?php echo $singleRecord['sale_price']; ?></td>
                                                              <td><?php if($singleRecord['admin_approval']){ ?> <a href="javascript:void(0)" class="call-btn green-bg">Approved</a> <?php }else{ ?> <a href="javascript:void(0)" title="If your approval is going delay,please contact with admin." class="call-btn red-bg">Pending</a>  <?php } ?></td>
                                                              <td>
                                                                  
                                                                  <a href="<?php echo $ediLink; ?>" class="call-btn gray-bg">Edit</a>
                                                                  <a href="javascript:void(0)" data-id="<?php echo $singleRecord['product_id']; ?>" class="call-btn red-bg delData" data-loading-text="Deleting...">Delete</a>
                                                              </td>
                                                            </tr>
                                                             <?php
                                                             $i++;   
                                                            }
                                                        }
                                                    ?>
                                                    </tbody>
                                                </table>
                                                <?php echo $this->pagination->create_links();?> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div id="merchant_notify_modal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Notify Merchant</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="message">Message:</label>
                                <textarea class="form-control" rows="10" id="message"></textarea>
                                <input type="hidden" id="merchant">
                                <input type="hidden" id="product">
                            </div>
                            <div class="form-group">
                                <label for="status">Status:</label>
                                <label class="radio-inline"><input value="1" type="radio" name="adminstatus">Active</label>
                                <label class="radio-inline"><input value="0" type="radio" name="adminstatus" checked>Pending</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default send-merchant-notification" >Send</button>
                        </div>
                    </div>
                </div>
            </div>
<script>
    function messagealert(title,text,type){
        new PNotify({
            title: title,
            text:  text,
            type:  type,
            styling: 'bootstrap3'
        });
    }
    $('.notify-merchant').on('click', function(ev) {
        $('#merchant_notify_modal').modal({show: 'true'});
        var pid=$(this).attr('product-id');
        var mid=$(this).attr('merchant-id');
        var status = $(this).attr('status');
        $('#product').val(pid);
        $('#merchant').val(mid);
        $("input[name=adminstatus][value="+status+"]").prop("checked",true);
    });

    $('.send-merchant-notification').on('click',function () {
       var product_id = $('#product').val();
       var merchant_id = $('#merchant').val();
       var message = $('#message').val();
       var status = $('input[name=adminstatus]:checked').val();
       if(message.trim()==''){
           messagealert('Error','Please enter message','error');
       }else {
           var url='<?php echo base_url(); ?>product/notifyMerchant';
            $.ajax({
                type:'POST',
                url:url,
                data:{'product_id':product_id,'merchant_id':merchant_id,'message':message,'status':status},
                beforeSend:function(){},
                success:function(response){
                    if(response.status==0){
                        messagealert('Error',response.message,'error');
                    }else{
                        messagealert('Success',response.message,'success');
                        $('#merchant_notify_modal').modal('toggle');
                        $('#message').val('');
                    }
                },
                complete:function(){
                }
            });
       }

    });

    function deletemerchants(url) {
    if (confirm("Are you sure want to delete?")) {
        window.location.href=url;
    }
    return false;
}
function ChangeStatus(url){
     window.location.href=url;
}
</script>
          