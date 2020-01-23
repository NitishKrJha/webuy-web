<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2><?php echo ucwords($module);?> </h2>
      <div class="clearfix"></div>
    </div>

    <div class="x_content">
      <div>
        <ul class="nav navbar-right panel_toolbox">
          <li style="width:50px;">&nbsp;
          </li>
          <li><a title="Add Amenities" href="<?php echo $add_link; ?>"><i class="fa fa-plus"></i></a>
          </li>
        </ul>
      </div>
      <div class="clearfix"></div>
      <div class="table-responsive">
        <table class="table table-striped jambo_table bulk_action">
          <thead>
            <tr class="headings">
			        <th class="column-title">Order </th>
              <th class="column-title">Name </th>
              <th class="column-title no-link last"><span class="nobr">Action</span>
              </th>
            </tr>
          </thead>

          <tbody>
          <?php if(!empty($recordset)){ $i=2; ?>
            <?php $i=1;
			foreach($recordset as $singleRecord){ 
              $ediLink = str_replace('{{ID}}', $singleRecord['id'], $edit_link);
              $activeLink = str_replace('{{ID}}',$singleRecord['id'],$activated_link);
			        $inacttivedLink = str_replace('{{ID}}',$singleRecord['id'],$inacttived_Link);
			        $deleteLink = str_replace("{{ID}}",$singleRecord['id'],$delete_link);
            ?>
            <tr class="<?php echo $i%2==0?'even':'odd'; ?> pointer">
			  <td>
              <select id="option_<?php echo $singleRecord['id']?>" onchange="changeOrder(this.value,<?php echo $singleRecord['id'];?>)">
                <?php
                for ($o=1; $o <=count($recordset) ; $o++) {
                  $selected='';
                  if($singleRecord['sort_num']==$o){
                    $selected='selected="selected"';
                  }
                  ?>
                  <option value="<?php echo $o; ?>" <?php echo $selected; ?>><?php echo $o; ?></option>
                  <?php
                }
               ?>
              </select>
               
             </td>
			   <td class=""><?php echo $singleRecord['name']?></td>
            
              <td class=" last"><a href="<?php echo $ediLink; ?>" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a> <?php if(count($recordset)!=0) {?>| <a href="javascript:Delete('<?php echo $deleteLink;?>');" title=" Delete Content "><i aria-hidden="true" class="fa fa-trash"></i></a><?php } ?></td>
            </tr>
          <?php $i++; } 
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

<script type="text/javascript">
  function changeOrder(val,id){
    //alert(val+"--"+id);
    $.ajax({
      type:'POST',
      url:'<?php echo base_url('amenities/changeorder')?>',
      data:{val:val,id:id},
      dataType:'JSON',
      success:function(result){
        //alert(result.status);
        if(result.status=='true'){
          $('#option_'+result.id).val(result.val);
        }
      }
    });
  }
</script>