
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
                            <h4 class="m-t-0 m-b-30 pull-left">List</h4>
                            <div class="pull-right"><a title="add member" href="<?php echo $add_link; ?>"><i class="fa fa-plus"></i></a></div>
                            <div class="table-rep-plugin">

                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                    <table class="table table-striped jambo_table bulk_action">
                                        <thead>
                                        <tr class="headings">

                                            <th class="column-title">Image </th>
                                            <!--<th class="column-title">Title(EN)</th>
                                      <th class="column-title">Title(Chinese)</th>-->
                                            <th class="column-title">Status</th>
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

                                                    <td class=""><img src="<?php echo file_upload_base_url().'adsection/'.$singleRecord['icon']?>" width="50" height="50" ></td>
                                                    <!-- <td class=" "><?php echo $singleRecord['title_en']; ?></td>
                                              <td class=" "><?php echo $singleRecord['title_ch']; ?></td>-->
                                                    <td><a href="javascript:ChangeStatus('<?php echo $singleRecord['is_active']==1?$inacttivedLink:$activeLink;?>');"><button class="btn btn-<?php echo $singleRecord['is_active']=='1'?'info':'danger';?> btn-xs" type="button"><?php echo $singleRecord['is_active']==1?'Active':'InActive';?></button></a></td>
                                                    <td class=" last"><a href="<?php echo $ediLink; ?>" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a> <?php if(count($recordset)!=1) {?>| <a href="javascript:deleteadsection('<?php echo $deleteLink;?>');" title=" Delete Content "><i aria-hidden="true" class="fa fa-trash"></i></a><?php } ?></td>
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
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function changeOrder(val,id){
        //alert(val+"--"+id);
        $.ajax({
            type:'POST',
            url:'<?php echo base_url('homepage/adsection_changeorder')?>',
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
    function deleteadsection(url) {
        if (confirm("Are you sure want to delete?")) {
            window.location.href=url;
        }
        return false;
    }

    function ChangeStatus(url){
        window.location.href=url;
    }
</script>