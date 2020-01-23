  <div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Setting</h4>
        </div>
    </div>
   <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Customer</h2>
                  <div class="clearfix"></div>
                </div>

                <div class="x_content">
                      <form id="frmSearch" class="form-horizontal form-label-left input_mask" name="frmSearch" action="<?php echo $search_link; ?>" method="post" >
                      <div class="col-md-4 form-group">
                        <select class="form-control" aria-controls="example" name="searchField">
                              <option value="">-Select-</option>
                               <option value="member.first_name" <?php if(isset($params['searchField']) && $params['searchField']=='member.first_name') echo 'selected';?>>Name</option>
                               <option value="member.email" <?php if(isset($params['searchField']) && $params['searchField']=='member.email') echo 'selected';?>>Email</option>
                           </select>
                      </div>
                      <div class="col-md-4 form-group">
                           <input type="text" class="form-control" value="<?php if(isset($params['searchString'])){echo $params['searchString']; }?>" name="searchString">
                       </div>
                       <div class="col-md-4 form-group">
                          <input type="hidden" name="sortType" id="sortType" value="<?php echo $params['sortType'];?>" />
                          <input type="hidden" name="sortField" id="sortField" value="<?php echo $params['sortField'];?>" />
                          <input type="submit" id="addData" class="btn btn-primary marg-5" name="search" value="Search">
                          <a href="<?php echo $showall_link;?>" class="btn btn-success marg-5">Show All</a>
                      </div>
                      </form>
                  <div>
                    <ul class="nav navbar-right panel_toolbox">
                      <li style="width:50px;">&nbsp;
                      </li>
                      <li><a title="add member" href="<?php echo $add_link; ?>"><i class="fa fa-plus"></i></a>
                      </li>
                    </ul>
                  </div>
                  <div class="clearfix"></div>
                  <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action">
                      <thead>
                        <tr class="headings">
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
                          $ediLink = str_replace('{{ID}}', $singleRecord['id'], $edit_link);
                          $pwd_link= str_replace('{{ID}}', $singleRecord['id'], $pwd_link);
                          $activeLink = str_replace('{{ID}}',$singleRecord['id'],$activated_link);
                    $inacttivedLink = str_replace('{{ID}}',$singleRecord['id'],$inacttived_Link);
                        ?>
                        <tr class="<?php echo $i%2==0?'even':'odd'; ?> pointer">
                  <td class=" "><?php echo $i+$startRecord; ?></td>
                  <td class=" "><?php echo $singleRecord['first_name']; ?></td>
                          <td class=" "><?php echo $singleRecord['last_name']; ?></td>
                          <td class=" "><?php echo $singleRecord['email']; ?></td>
                          <td class=" "><?php echo date('m-d-Y',strtotime($singleRecord['created'])); ?></td>
                          <td><a href="javascript:ChangeStatus('<?php echo $singleRecord['is_active']==1?$inacttivedLink:$activeLink;?>');"><button class="btn btn-<?php echo $singleRecord['is_active']=='1'?'info':'danger';?> btn-xs" type="button"><?php echo $singleRecord['is_active']==1?'Active':'InActive';?></button></a></td>
                          <td class=" last"><a href="<?php echo $ediLink; ?>" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>||
                         <a href="<?php echo base_url('customer/viewdetails/'.$singleRecord['id']) ?>" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>||
                         <a href="javascript:void(0)" onclick="deleteMember('<?php echo base_url('customer/delete/'.$singleRecord['id']); ?>')" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>||
                          <a href="<?php echo $pwd_link; ?>" title="Change Password"><i class="fa fa-key" aria-hidden="true"></i></a></td>
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

  

<script>
function deleteMember(url) {
    if (confirm("Are you sure want to delete?")) {
        window.location.href=url;
    }
    return false;
}
</script>