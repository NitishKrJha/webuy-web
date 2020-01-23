<div class="content">
<div class="page-header-title">
    <h4 class="page-title">Permission</h4></div>
    <!--<input class="read_check" id="selectall" name="check" type="checkbox">-->
    <br>
    <!--<input class="write_check" id="selectallwrite" name="check1" type="checkbox">-->
    <br>
    <!--<input class="del_check" id="selectalldel" name="check1" type="checkbox">-->
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-primary">
        	<div class="panel-body">
            <h4 class="m-t-0 m-b-30 pull-left">List</h4>
                        
                <div class="table-rep-plugin">
                                            
                   	<div class="table-responsive b-0" data-pattern="priority-columns">
                        <form id="form1" name="form1" class="form-horizontal" action="<?php echo $set_permission_link;?>" method="post" enctype="multipart/form-data" autocomplete="off">
                        <table id="tech-companies-1" class="table table-striped">
                        <thead>
                        	<tr>
                            	<th class="column-title"> Title</th>
                                <th class="column-title"> <input class="read_check" id="selectall" name="check" type="checkbox"> &nbsp Read </th>
                                <th class="column-title"> <input class="write_check" id="selectallwrite" name="check1" type="checkbox"> &nbsp Write</th>
                               	<th class="column-title"> <input class="del_check" id="selectalldel" name="check1" type="checkbox"> &nbsp Delete</th>
                               	
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                        	
        						      <td>
                          Category
                          </td>
                          
        						      <td><label class="checkbox-inline">
                      
                          <input type="hidden" name="category[0]" value="0">
                          <input type="checkbox" class="read" name="category[0]" value="1" <?php if ($jsonvalue['category'][0] == 1) { echo 'checked'; } ?> ></label>
                        </td>
        						    <td><label class="checkbox-inline">
                          <input type="hidden"  name="category[1]" value="0">
                          <input type="checkbox" class="write" name="category[1]" value="1" <?php if ($jsonvalue['category'][1] == 1) { echo 'checked'; } ?>></label>
                        </td>
        						    <td><label class="checkbox-inline">
                          <input type="hidden"   name="category[2]" value="0">
                          <input type="checkbox" class="del" name="category[2]" value="1" <?php if ($jsonvalue['category'][2] == 1) { echo 'checked'; } ?>></label>
                        </td>
        						   
        							
        						
      						<tr>
        						<td>CMS</td>
                    <td><label class="checkbox-inline">
                      
                          <input type="hidden" name="cms[0]" value="0">
                          <input type="checkbox" class="read" name="cms[0]" value="1" <?php if ($jsonvalue['cms'][0] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden" name="cms[1]" value="0">
                          <input type="checkbox" class="write" name="cms[1]" value="1" <?php if ($jsonvalue['cms'][1] == 1) { echo 'checked'; } ?> ></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden"  name="cms[2]" value="0">
                          <input type="checkbox" class="del" name="cms[2]" value="1" <?php if ($jsonvalue['cms'][2] == 1) { echo 'checked'; } ?>></label>
                        </td>
       						 	
       							
      						</tr>
      						<tr>
        						<td>Variation Attributes</td>
        						<td><label class="checkbox-inline">
                      
                          <input type="hidden" name="variation_attribute[0]" value="0">
                          <input type="checkbox" class="read" name="variation_attribute[0]" value="1" <?php if ($jsonvalue['variation_attribute'][0] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden" name="variation_attribute[1]" value="0">
                          <input type="checkbox" class="write" name="variation_attribute[1]" value="1" <?php if ($jsonvalue['variation_attribute'][1] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden"  name="variation_attribute[2]" value="0">
                          <input type="checkbox"  class="del" name="variation_attribute[2]" value="1"<?php if ($jsonvalue['variation_attribute'][2] == 1) { echo 'checked'; } ?> ></label>
                        </td>
        					
      						</tr>
      						<tr>
        						<td>Various Offer</td>
        						<td><label class="checkbox-inline">
                      
                          <input type="hidden" name="various_offer[0]" value="0">
                          <input type="checkbox" class="read" name="various_offer[0]" value="1" <?php if ($jsonvalue['various_offer'][0] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden" name="various_offer[1]" value="0">
                          <input type="checkbox" class="write" name="various_offer[1]" value="1" <?php if ($jsonvalue['various_offer'][1] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden"  name="various_offer[2]" value="0">
                          <input type="checkbox" class="del" name="various_offer[2]" value="1" <?php if ($jsonvalue['various_offer'][2] == 1) { echo 'checked'; } ?>></label>
                        </td>
        					
      						</tr>
      						<tr>
        						<td>Banner</td>
        						<td><label class="checkbox-inline">
                      
                          <input type="hidden" name="banner[0]" value="0">
                          <input type="checkbox" class="read" name="banner[0]" value="1" <?php if ($jsonvalue['banner'][0] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden" name="banner[1]" value="0">
                          <input type="checkbox" class="write" name="banner[1]" value="1" <?php if ($jsonvalue['banner'][1] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden"  name="banner[2]" value="0">
                          <input type="checkbox" class="del" name="banner[2]" value="1" <?php if ($jsonvalue['banner'][2] == 1) { echo 'checked'; } ?>></label>
                        </td>
      						</tr>
      						<tr>
        						<td>Contect Us</td>
        						<td><label class="checkbox-inline">
                      
                          <input type="hidden" name="staff[0]" value="0">
                          <input type="checkbox" class="read" name="staff[0]" value="1" <?php if ($jsonvalue['staff'][0] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden" name="staff[1]" value="0">
                          <input type="checkbox" class="write" name="staff[1]" value="1" <?php if ($jsonvalue['staff'][1] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden"  name="staff[2]" value="0">
                          <input type="checkbox" class="del" name="staff[2]" value="1" <?php if ($jsonvalue['staff'][2] == 1) { echo 'checked'; } ?>></label>
                        </td>
      						</tr>
      						<tr>
        						<td>Merchants</td>
        						<td><label class="checkbox-inline">
                      
                          <input type="hidden" name="merchant[0]" value="0">
                          <input type="checkbox" class="read" name="merchant[0]" value="1" <?php if ($jsonvalue['merchant'][0] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden" name="merchant[1]" value="0">
                          <input type="checkbox" class="write" name="merchant[1]" value="1" <?php if ($jsonvalue['merchant'][1] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden"  name="merchant[2]" value="0">
                          <input type="checkbox" class="del" name="merchant[2]" value="1" <?php if ($jsonvalue['merchant'][2] == 1) { echo 'checked'; } ?>></label>
                        </td>
      						</tr>
      						<tr>
        						<td>Customer</td>
        						<td><label class="checkbox-inline">
                      
                          <input type="hidden" name="customer[0]" value="0">
                          <input type="checkbox" class="read" name="customer[0]" value="1" <?php if ($jsonvalue['customer'][0] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden" name="customer[1]" value="0">
                          <input type="checkbox" class="write" name="customer[1]" value="1" <?php if ($jsonvalue['customer'][1] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden"  name="customer[2]" value="0">
                          <input type="checkbox" class="del" name="customer[2]" value="1" <?php if ($jsonvalue['customer'][2] == 1) { echo 'checked'; } ?>></label>
                        </td>
      						</tr>
      						
      						<tr>
        						<td>Product</td>
        						<td><label class="checkbox-inline">
                      
                          <input type="hidden" name="product[0]" value="0">
                          <input type="checkbox" class="read" name="product[0]" value="1" <?php if ($jsonvalue['product'][0] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden" name="product[1]" value="0">
                          <input type="checkbox" class="write" name="product[1]" value="1" <?php if ($jsonvalue['product'][1] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden"  name="product[2]" value="0">
                          <input type="checkbox" class="del" name="product[2]" value="1" <?php if ($jsonvalue['product'][2] == 1) { echo 'checked'; } ?>></label>
                        </td>
      						</tr>
      						<tr>
        						<td>Setting</td>
        						<td><label class="checkbox-inline">
                      
                          <input type="hidden" name="setting[0]" value="0">
                          <input type="checkbox" class="read" name="setting[0]" value="1" <?php if ($jsonvalue['setting'][0] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden" name="setting[1]" value="0">
                          <input type="checkbox" class="write" name="setting[1]" value="1" <?php if ($jsonvalue['setting'][1] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden"  name="setting[2]" value="0">
                          <input type="checkbox" class="del" name="setting[2]" value="1" <?php if ($jsonvalue['setting'][2] == 1) { echo 'checked'; } ?>></label>
                        </td>
      						</tr>
                  <tr>
                    <td>Brands</td>
                    <td><label class="checkbox-inline">
                      
                          <input type="hidden" name="brands[0]" value="0">
                          <input type="checkbox" class="read" name="brands[0]" value="1" <?php if ($jsonvalue['brands'][0] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden" name="brands[1]" value="0">
                          <input type="checkbox" class="write" name="brands[1]" value="1" <?php if ($jsonvalue['brands'][1] == 1) { echo 'checked'; } ?>></label>
                        </td>
                        <td><label class="checkbox-inline">
                          <input type="hidden"  name="brands[2]" value="0">
                          <input type="checkbox" class="del" name="brands[2]" value="1" <?php if ($jsonvalue['brands'][2] == 1) { echo 'checked'; } ?>></label>
                        </td>
                  </tr>

                        </tbody>
                    	</table>

                    </div>
                    <button class="btn btn-success" type="submit" id="send">Submit</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
<script type="text/javascript">
    // when page is ready
    $(document).ready(function() {
         // on form submit
        $("#form1").on('submit', function() {
            // to each unchecked checkbox
            $(this + 'input[type=checkbox]:not(:checked)').each(function () {
                // set value 0 and check it
                $(this).attr('checked', true).val(0);
            });
        })
    })



$("#selectall").click(function() {
$(".read").prop("checked", $("#selectall").prop("checked"))
});

$("#selectallwrite").click(function() {
$(".write").prop("checked", $("#selectallwrite").prop("checked"))
});

$("#selectalldel").click(function() {
$(".del").prop("checked", $("#selectalldel").prop("checked"))
});

$(document).on('click','.read',function(){
  //alert($(this).prop('checked'));
  if ($(this).prop('checked')!=true){ 
      $("#selectall").prop("checked", false);
  }
});

$(document).on('click','.write',function(){
  //alert($(this).prop('checked'));
  if ($(this).prop('checked')!=true){ 
      $("#selectallwrite").prop("checked", false);
  }
});

$(document).on('click','.del',function(){
  //alert($(this).prop('checked'));
  if ($(this).prop('checked')!=true){ 
      $("#selectalldel").prop("checked", false);
  }
});
    
</script>
