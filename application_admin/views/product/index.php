
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
                                        <div class="pull-right"><a title="add product" href="<?php echo $add_link; ?>"><i class="fa fa-plus"></i> Product</a></div>
                                        <div class="table-rep-plugin">
                                            
                                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                                <table id="tech-companies-1" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="column-title">SL</th>
                                                            <th class="column-title">Picture</th>
                                                            <th class="column-title">Title</th>
                                                            <th class="column-title">SKU</th>
                                                            <th class="column-title">Sell Price</th>
                                                            <th class="column-title">purchase price</th>
                                                            <th class="column-title">Quantity</th>
                                                            <th class="column-title">Current Stock</th>
                                                            <th class="column-title no-link last"><span class="nobr">Action</span>
                                                            <th class="column-title no-link last"><span class="nobr">Feaure</span></th>
                                                            <th class="column-title">View Link</th>
                                                            <th class="column-title no-link last"><span class="nobr">Approval</span></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(!empty($recordset)){ $i=2; ?>
                                                        <?php $i=1; foreach($recordset as $singleRecord){ 
                                                          $ediLink = str_replace('{{ID}}', $singleRecord['product_id'], $edit_link);
                                                          $pwd_link= str_replace('{{ID}}', $singleRecord['product_id'], $pwd_link);
                                                          $activeLink = str_replace('{{ID}}',$singleRecord['product_id'],$activated_link);
                                                            $inacttivedLink = str_replace('{{ID}}',$singleRecord['product_id'],$inacttived_Link);

                                                            $featureLink = str_replace('{{ID}}',$singleRecord['product_id'],$feature_link);
                                                            $infeatureLink = str_replace('{{ID}}',$singleRecord['product_id'],$infeature_link);
                                                            $viewLink = str_replace('{{ID}}',$singleRecord['product_id'],$view_link);
                                                            if($singleRecord['smimagepath'] == "")
                                                            {
                                                                $imagepath =  $singleRecord['imagepath'];
                                                            }
                                                            else
                                                            {
                                                                $imagepath =  $singleRecord['smimagepath'];
                                                            }

                                                            if($imagepath==''){
                                                                $imagepath = file_upload_base_url().'images/no_image.png';
                                                            }else{
                                                                $imagepath = front_base_url()."uploads/product/".$imagepath;
                                                            }

                                                        ?>
                                                        <tr class="<?php echo $i%2==0?'even':'odd'; ?> pointer">
                                                            <td class=" "><?php echo $i+$startRecord; ?></td>
                                                            <td class=" "><img src = "<?php echo $imagepath; ?>" style="width: 50px; height: 50px;"></td>
                                                            <td class=" "><?php echo $singleRecord['title']; ?><br>
                                                            <a href="<?php echo $ediLink; ?>" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i>Edit</a>    
                                                        </td>
                                                            <td class=" "><?php echo $singleRecord['sku']; ?></td>
                                                            
                                                            <td class=" "><?php echo $singleRecord['sale_price']; ?></td>
                                                            <td class=" "><?php echo $singleRecord['purchase_price']; ?></td>
                                                            <td class=" "><?php echo $singleRecord['quantity']; ?></td>
                                                            <td class=" "><?php echo $singleRecord['current_stock']; ?></td>
                                                            
                                                            <td><a href="javascript:ChangeStatus('<?php echo $singleRecord['is_active']==1?$inacttivedLink:$activeLink;?>');"><button class="btn btn-<?php echo $singleRecord['is_active']=='1'?'info':'danger';?> btn-xs" type="button"><?php echo $singleRecord['is_active']==1?'Active':'InActive';?></button></a></td>
                                                            <td><a href="javascript:ChangeStatus('<?php echo $singleRecord['featured']==1?$infeatureLink:$featureLink;?>');"><button class="btn btn-<?php echo $singleRecord['featured']=='1'?'info':'danger';?> btn-xs" type="button"><?php echo $singleRecord['featured']==1?'Featured':'NonFeatured';?></button></a></td>
                                                            <td class=" last"><a href="<?php echo $viewLink; ?>" title="View" target=""><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                                                            <td class=" last"><a href="javascript:void(0);" title="Approve" class="notify-merchant" status="<?php echo $singleRecord['admin_approval']; ?>" merchant-id="<?php echo $singleRecord['created_by']; ?>" product-id="<?php echo $singleRecord['product_id']; ?>" ><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                                                            
                                                        </tr>
                                                      <?php  $i++; } 
                                                        }else{ echo '<tr><td colspan="7" align="center">No Record Added Yet.</td></tr>'; }
                                                      ?>
                                                        
                                                    </tbody>
                                                </table>
                                                <?php echo $this->pagination->create_links();?> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
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
          