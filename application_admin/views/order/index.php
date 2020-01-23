<?php //pr($recordset); ?>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Order</h4></div>
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
                                        <option value="orders.order_id" <?php if(isset($params['searchField']) && $params['searchField']=='orders.order_id') echo 'selected';?>>OrderID </option>
                                        <option value="orders.created_at" <?php if(isset($params['searchField']) && $params['searchField']=='orders.created_at') echo 'selected';?>>Order Date </option>

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

                            <div class="table-rep-plugin">

                                <div class="table-responsive b-0" data-pattern="priority-columns">
                                    <table id="tech-companies-1" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th class="column-title">SL</th>
                                            <th class="column-title">OrderID</th>
                                            <th class="column-title">OrderDt</th>
                                            <th class="column-title">Name</th>
                                            <th class="column-title">Phone</th>
                                            <th class="column-title">Amount</th>
                                            <th class="column-title no-link last"><span class="nobr">Action</span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($recordset)){ $i=2; ?>
                                            <?php $i=1; foreach($recordset as $singleRecord){
                                                $view_link = str_replace('{{ID}}', $singleRecord['id'], $view_link);
                                                ?>
                                                <tr class="<?php echo $i%2==0?'even':'odd'; ?> pointer">
                                                    <td class=" "><?php echo $i+$startRecord; ?></td>
                                                    <td class=" "><?php echo $singleRecord['order_id']; ?></td>
                                                    <td class=" "><?php echo date("F j, Y, g:i a",$singleRecord['created_at']); ?></td>
                                                    <td class=" "><?php echo $singleRecord['name']; ?></td>
                                                    <td class=" "><?php echo $singleRecord['phone']; ?></td>
                                                    <td class=" ">Rs.<?php echo $singleRecord['amount']; ?></td>
                                                    <td class=" last"><a href="<?php echo base_url('order/viewdetails/'.$singleRecord['id']); ?>" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>

                                                    </td>
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
    function deletefaq(url) {
        if (confirm("Are you sure want to delete?")) {
            window.location.href=url;
        }
        return false;
    }

    function ChangeStatus(url){
        window.location.href=url;
    }
</script>
