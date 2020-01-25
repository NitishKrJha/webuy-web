
            <div class="content">
                <div class="">
                    <div class="page-header-title">
                        <h4 class="page-title">Customer</h4></div>
                </div>
                <div class="page-content-wrapper ">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        <h4 class="m-t-0 m-b-30">Search By</h4>
                                        <form id="frmSearch" class="form-inline" name="frmSearch" action="<?php echo $search_link; ?>" method="post" >
                                            <div class="form-group">
                                                <label class="sr-only" for="exampleInputEmail2">Select</label>
                                                <select class="form-control" aria-controls="example" name="searchField">
                                                    <option value="">-Select-</option>
                                                     <option value="customer.first_name" <?php if(isset($params['searchField']) && $params['searchField']=='customer.first_name') echo 'selected';?>>Name</option>
                                                     <option value="customer.email" <?php if(isset($params['searchField']) && $params['searchField']=='customer.email') echo 'selected';?>>Email</option>
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
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        <h4 class="m-t-0 m-b-30 pull-left">List</h4>
                                        <!-- <div class="pull-right"><a title="add member" href="<?php echo $add_link; ?>"><i class="fa fa-plus"></i></a></div> -->
                                        <div class="table-rep-plugin">
                                            
                                            <div class="table-responsive b-0" data-pattern="priority-columns">
                                                <table id="tech-companies-1" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="column-title">SL</th>
                                                            <th class="column-title">First Name</th>
                                                            <th class="column-title">Last Name</th>
                                                            <th class="column-title">Email ID</th>
                                                            <th class="column-title">Created</th>
                                                            <th class="column-title">Status</th>
                                                            <th class="column-title no-link last"><span class="nobr">Action</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(!empty($recordset)){ $i=2; ?>
                                                        <?php $i=1; foreach($recordset as $singleRecord){ 
                                                          $ediLink = str_replace('{{ID}}', $singleRecord['customer_id'], $edit_link);
                                                          $pwd_link= str_replace('{{ID}}', $singleRecord['customer_id'], $pwd_link);
                                                          $activeLink = str_replace('{{ID}}',$singleRecord['customer_id'],$activated_link);
                                                    $inacttivedLink = str_replace('{{ID}}',$singleRecord['customer_id'],$inacttived_Link);
                                                        ?>
                                                        <tr class="<?php echo $i%2==0?'even':'odd'; ?> pointer">
                                                            <td class=" "><?php echo $i+$startRecord; ?></td>
                                                            <td class=" "><?php echo $singleRecord['first_name']; ?></td>
                                                            <td class=" "><?php echo $singleRecord['last_name']; ?></td>
                                                            <td class=" "><?php echo $singleRecord['email']; ?></td>
                                                            <td class=" "><?php echo date('d-m-Y',strtotime($singleRecord['created_date'])); ?></td>
                                                            <td><a href="javascript:ChangeStatus('<?php echo $singleRecord['status']==1?$inacttivedLink:$activeLink;?>');"><button class="btn btn-<?php echo $singleRecord['status']=='1'?'info':'danger';?> btn-xs" type="button"><?php echo $singleRecord['status']==1?'Active':'InActive';?></button></a></td>
                                                            <td class=" last"><a href="<?php echo $ediLink; ?>" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>||
                                                         <a href="<?php echo base_url('customer/viewdetails/'.$singleRecord['customer_id']) ?>" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                         <!-- <a href="javascript:void(0)" onclick="deletecustomer('<?php echo base_url('customer/delete/'.$singleRecord['customer_id']); ?>')" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>|| -->
                                                          <!-- <a href="<?php echo $pwd_link; ?>" title="Change Password"><i class="fa fa-key" aria-hidden="true"></i></a></td> -->
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<script>
function deletecustomer(url) {
    if (confirm("Are you sure want to delete?")) {
        window.location.href=url;
    }
    return false;
}

function ChangeStatus(url){
     window.location.href=url;
}
</script>
          