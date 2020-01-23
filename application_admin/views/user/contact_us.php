<?php
//pr($category);
?>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Contact Us</h2>
      <div class="clearfix"></div>
    </div>

    <div class="x_content">
          <form id="frmSearch" class="form-horizontal form-label-left input_mask" name="frmSearch" action="<?php echo $search_link; ?>" method="post" >
			<div class="col-md-4 form-group">
				<select class="form-control" aria-controls="example" name="searchField">
					<option value="">-Select-</option>
					<option value="contact_us.full_name" <?php if(isset($params['searchField']) && $params['searchField']=='contact_us.full_name') echo 'selected';?>>Name</option>
					<option value="contact_us.email" <?php if(isset($params['searchField']) && $params['searchField']=='contact_us.email') echo 'selected';?>>Email</option>
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
          </li>
        </ul>
      </div>
      <div class="clearfix"></div>
      <div class="table-responsive">
        <table class="table table-striped jambo_table bulk_action">
          <thead>
            <tr class="headings">
				<th class="column-title">SL</th>
				<th class="column-title">Full Name</th>
				<th class="column-title">Email</th>
				<th class="column-title">Phone</th>
				<th class="column-title">Message</th>
        <th class="column-title">Date</th>
				<th class="column-title no-link last" style="display: none;"><span class="nobr">Action</span></th>
            </tr>
          </thead>
          <tbody>
          <?php if(!empty($recordset)){ $i=2; ?>
            <?php $i=1; foreach($recordset as $singleRecord){ 
              $ediLink = str_replace('{{ID}}', $singleRecord['id'], $edit_link);
              $activeLink = str_replace('{{ID}}',$singleRecord['pid'],$activated_link);
			  $inacttivedLink = str_replace('{{ID}}',$singleRecord['pid'],$inacttived_Link);
            ?>
        <tr class="<?php echo $i%2==0?'even':'odd'; ?> pointer">
				<td class=" "><?php echo $i+$startRecord; ?></td>
				<td class=" "><?php echo $singleRecord['full_name']; ?></td>
				<td class=" "><?php echo $singleRecord['email']; ?></td>
				<td class=" "><?php echo $singleRecord['phone']; ?></td>
        <td class=" "><?php echo $singleRecord['message']; ?></td>
				<td class=" "><?php echo date('m-d-Y',strtotime($singleRecord['created_date'])); ?></td>
				
				<td  style="display: none;" class=" last"><a href="<?php echo base_url('propertyposted/viewdetails/'.$singleRecord['id']) ?>" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
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