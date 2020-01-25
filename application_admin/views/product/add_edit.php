<link href="<?php echo front_base_url().'public/';?>css/easy-responsive-tabs.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo front_base_url().'public/'; ?>css/loader.css" rel="stylesheet">
<link href="<?php echo front_base_url().'public/';?>css/bootstrap-tagsinput.css" rel="stylesheet">
<script src="<?php echo front_base_url().'public/';?>js/bootstrap-tagsinput.js"></script>
<script src="<?php echo front_base_url().'public/';?>js/bootstrap3-typeahead.js"></script>
<script src="<?php echo front_base_url().'public/';?>tinymce/tinymce.min.js"></script>
<script src="<?php echo front_base_url().'public/';?>js/tinymc.js"></script>
<div class="loading" id="load-txt" style="display: none;">Loading&#8230;</div>
<style>
    .bootstrap-tagsinput{
        width: 100%;
    }
    li .active{
        background-color: #2b323c !important;
    }
    .resp-tabs-container{
        background-color: #2b323c !important;
    }
    .black-btn2{
        background: #1a1a1a;
        padding: 7px 20px;
        color: #fff;
        font-size: 16px;
        display: inline-block;
        margin: 20px 0 0 0;
        border-radius: 5px;
        text-transform: uppercase;
        float: right;
    }
    .business-dtl-frm {
    background: #fff;
    padding: 40px 20px 20px 20px;
    border-radius: 6px;
    width: 70%;
    margin: 0 auto;
    }

    .file-input {
        background: #fff;
        text-align: center;
        width: auto;
        height: 32px;
        overflow: hidden !important;
        cursor: pointer;
        border: 1px solid #ccc;
        border-radius: 5px;
        color: #769f3b;
        font-size: 13px;
        line-height: 30px;
        float: right;
        display: inline-block;
        position: relative;
        padding: 0 20px;
    }

    .file-input input[type="file"] {
        opacity: 0;
        cursor: pointer;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 999;
    }

    .extra-info {
        padding-top: 15px;
    }

    .extra-info ul li {
        float: left;
        width: 50%;
    }

    .extra-info ul li p {
        color: #6a6a6a;
        font-size: 12px;
        margin: 0;
    }

    .extra-info .check-box {
        color: #9d482d;
        font-size: 12px;
    }

    .business-dtl-frm .form-horizontal .control-label {
        font-weight: 400;
        color: #656565;
        font-size: 14px;
    }

    .business-dtl-frm .form-horizontal abbr {
        color: #5aaf35;
        font-size: 11px;
        font-weight: 500;
    }

    .business-dtl-frm .form-horizontal .control-label bdo {
        display: block;
        font-size: 11px;
        line-height: 15px;
        padding-top: 5px;
    }

    .business-dtl-frm .form-horizontal .control-label bdo u {
        color: #799e49;
    }

    .heading2 {
        text-align: center;
        padding-bottom: 25px;
    }

    .heading-icon {
        background: #000;
        width: 75px;
        height: 75px;
        display: inline-block;
        border-radius: 100px;
        padding: 15px;
    }

    .heading2 h2 {
        color: #404040;
        font-size: 22px;
        line-height: 35px;
        font-weight: 400;
        display: inline-block;
        position: relative;
        top: -32px;
        padding-left: 15px;
    }

    .seller-dashboard-dtl-wrap em {
        color: red;
        font-style: normal;
    }

    .business-dtl-frm .orange-btn,
    .business-dtl-frm .black-btn {
        display: inline-block;
        font-size: 16px;
        text-transform: capitalize;
        padding: 10px 25px;
    }

    .extra-info .outline-btn {
        float: right;
    }

    .extra-info .hd3 {
        color: #5b5b5b;
        font-size: 20px;
        font-weight: 500;
        padding-bottom: 0;
        padding-top: 5px;
    }

    .business-dtl-frm .form-control {
        box-shadow: none;
    }

    .para5 {
        color: #e4372a;
        padding: 0 5%;
        text-align: center;
    }

    .para6 {
        color: #6f6f6f;
        text-align: center;
        padding-top: 10px;
    }

    .para6 a {
        color: #e4372a;
    }

    ul, ol{
        margin-bottom: 0px;
        margin-top: 0;
        list-style-type: none;
    }
</style>
<?php
    $variation_name_sel='';
    if(isset($product['vara_list'][0]['variation_name'])){
        $variation_name_sel=$product['vara_list'][0]['variation_name'];
    }
?>
<div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title"><?php echo $action; ?> Product</h4></div>
    </div>
    <div class="page-content-wrapper ">
        <div class="container">   
            <div class="col-md-12">
                <!-- <div class="heading2">
                    <span class="heading-icon">
                        <img src="<?php echo front_base_url().'public/'; ?>images/seller-icon4.png" alt="" class="img-responsive"/>
                    </span>
                    <h2><?php echo $action;
                        $id = isset($product['product_id'])?$product['product_id']:'';
                    ?> Product</h2>
                </div> -->
                <form name="add-product-form" id="add-product-form" action="<?php echo base_url('product/add_product/'.$id); ?>" method="post" enctype="multipart/form-data">
                    <div class="description-sec">
                        <div id="horizontalTab">
                            <ul class="resp-tabs-list" id="tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab" id="tab1_click">Product Details</a></li>
                                <li><a href="#tab2" data-toggle="tab" id="tab2_click">Business Details</a></li>
                                <!-- <li><a href="#tab3" data-toggle="tab" id="tab3_click">Variants</a></li> -->
                            </ul>
                            <div class="resp-tabs-container">
                                <div class="tab-pane" id="tab1">                              
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <p class="para5"><!--* Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.--></p>
                                            </div>                            
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Product Title  <em>*</em></label>
                                            <div class="col-sm-9">
                                                <input type="text" name="title" id="title" list="productTitleDataList" class="form-control" placeholder="" required="required" autocomplete="off" value="<?php echo !empty($product['title'])?$product['title']:''; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Description  <em>*</em></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="description" id="description" required="required"><?php echo !empty($product['description'])?$product['description']:''; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Specification  <em>*</em></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control mytextarea" name="specification" id="specification"><?php echo !empty($product['specification'])?$product['specification']:''; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Category 1  <em>*</em></label>
                                            <div class="col-sm-9">
                                               <select name="level1" id="level1" class="form-control" onchange="getCategory(this.value,'level2')">
                                                    <option value="">Select</option>
                                                    <?php
                                                        if(!empty($category_level_1) && count($category_level_1) > 0){
                                                            foreach ($category_level_1 as $key => $value) {
                                                                $selected='';
                                                                if(!empty($all_cat_level_name['level1_id']) && $all_cat_level_name['level1_id']==$value['id']){
                                                                    $selected='selected="selected"';
                                                                }
                                                                echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['name'].'</option>';
                                                            }
                                                        }
                                                    ?>
                                               </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Category 2  <em>*</em></label>
                                            <div class="col-sm-9">
                                               <select name="level2" id="level2" class="form-control" onchange="getCategory(this.value,'level3')">
                                                    <option value="">Select</option>
                                                    
                                               </select>
                                            </div>
                                        </div>

                                        <div class="form-group" style="display: none;">
                                            <label class="control-label col-sm-3">Category 3  <em>*</em></label>
                                            <div class="col-sm-9">
                                               <select name="level3" id="level3" class="form-control" onchange="getCategory(this.value,'level4')">
                                                    <option value="">Select</option>
                                                    
                                               </select>
                                            </div>
                                        </div>

                                        <!-- <div class="form-group">
                                            <label class="control-label col-sm-3">Category 4  <em>*</em></label>
                                            <div class="col-sm-9">
                                               <select name="level4" id="level4" class="form-control">
                                                    <option value="">Select</option>
                                                    
                                               </select>
                                            </div>
                                        </div> -->
                                        
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Tags</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="tag" id="tag" class="form-control tagsinput-typeahead" placeholder=""  value="<?php echo !empty($product['tag'])?$product['tag']:''; ?>" /> 
                                                <p><small>Press enter after type tags</small></p>                           
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Images</label>
                                            <div class="col-sm-9">
                                                <ul>
                                                    <li>
                                                        <p>Accepted file formats: image only </p>                                       
                                                    </li>
                                                    <li><div class="file-input" id="addmmoreimg">Add Image</div></li>
                                                </ul>
                                                
                                            </div>
                                        </div>

                                        <div id="addimgdata">
                                            <?php
                                                if(!empty($product['img_list']) && count($product['img_list']) > 0){
                                                    $j=1;
                                                    foreach($product['img_list'] as $key => $value) {
                                                        $img=front_base_url().'public/'.'images/no_pr_img.jpg';
                                                        if($value['path']!=''){
                                                            $img=file_upload_base_url().'product/'.$value['path'];
                                                        }
                                                        ?>
                                                        <div class="form-group btm_border">
                                                            <label for="demo-hor-12" class="col-sm-3 control-label"></label>
                                                            <div class="col-sm-6">
                                                                
                                                                <span id="previewImg_1">
                                                                    
                                                                    <span id="previewImg_<?php echo $j; ?>"><div style="float:left;border:4px solid #303641;padding:5px;margin:5px;"><img height="80" src="<?php echo $img; ?>"></div></span>
                                                                </span>
                                                            </div>
                                                            <div class="col-sm-3"><span class="pull-right btn btn-default btn-file delmainimg" img-id="<?php echo $value['image_id']; ?>" data-loading-text="Deleting...">Delete</span>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    $j++;
                                                    }
                                                }
                                            ?>
                                            
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Brand</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="brand" id="brand">
                                                    <option value="">Select Brand</option>
                                                    <?php foreach ($brands as $brand) { ?>
                                                        <option value="<?php echo $brand['id'] ?>" <?php if(!empty($product['brand']) && $brand['id']==$product['brand']){ echo "selected"; }?>><?php echo $brand['name'] ?></option>
                                                    <?php } ?>
                                                </select>                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Is Combo</label>
                                            <div class="col-sm-1">
                                                <input type="checkbox" name="is_combo" id="is_combo" value="1" <?php if(!empty($product['is_combo'])){ echo'checked' ;} ?> class="form-control" placeholder="" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Documents</label>
                                            <div class="col-sm-9">
                                                <input type="file"   class="form-control" name="attachfiles[]" multiple />

                                            </div>
                                            <p></p>
                                            <?php
                                            if(!empty($product['doc_list']) && count($product['doc_list']) > 0){
                                                $k=1;
                                                foreach($product['doc_list'] as $key => $value) {
                                                    $doc_path=front_base_url().'public/'.'images/no_pr_img.jpg';
                                                    if($value['doc_name']!=''){
                                                        $doc_path=file_upload_base_url().'product_doc/'.$value['doc_name'];
                                                    }
                                                    ?>
                                                    <div class="form-group btm_border">
                                                        <label for="demo-hor-12" class="col-sm-3 control-label"></label>
                                                        <div class="col-sm-6">
                                                          <a href="<?php echo $doc_path; ?>" download=""><?php echo $value['doc_name']; ?></a>
                                                        </div>
                                                        <div class="col-sm-3"><span class="pull-right btn btn-default btn-file deldoc" doc-id="<?php echo $value['attach_id']; ?>" data-loading-text="Deleting...">Delete</span>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    $k++;
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="form-group">                            
                                            <div class="col-sm-12 text-right">
                                                <a href="javascript:void(0)" class="black-btn2 next-tab" data-next="tab2">NEXT</a>
                                            </div>
                                        </div>
                                    </div>                                
                                </div>
                                <div class="tab-pane" id="tab2">
                                  <div class="form-horizontal">        
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Sale Price  <em>*</em></label>
                                            <div class="col-sm-9">
                                                <input type="number" step="0.01" class="form-control" placeholder="" name="sale_price" id="sale_price" required="required" value="<?php echo !empty($product['sale_price'])?$product['sale_price']:''; ?>" />                            
                                            </div>
                                        </div>                        
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Purchase Price<em>*</em></label>
                                            <div class="col-sm-9">
                                                <input type="number" step="0.01" name="purchase_price" id="purchase_price" class="form-control" placeholder="" required="required" value="<?php echo !empty($product['purchase_price'])?$product['purchase_price']:''; ?>" />  
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">GST (%)<em></em></label>
                                            <div class="col-sm-9">
                                                <input type="number" step="0.01" name="gst" id="gst" class="form-control" value="<?php echo !empty($product['gst'])?$product['gst']:'18'; ?>" placeholder="" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Quantity<em>*</em></label>
                                            <div class="col-sm-9">
                                                <input type="number" step="0.01" name="quantity" id="quantity" class="form-control" placeholder="" required="required" value="<?php echo !empty($product['quantity'])?$product['quantity']:''; ?>" />                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Shipping Cost</label>
                                            <div class="col-sm-9">
                                                <input type="number" step="0.01" name="shipping_cost" id="shipping_cost" class="form-control" placeholder="" value="<?php echo !empty($product['shipping_cost'])?$product['shipping_cost']:''; ?>" />                            
                                            </div>
                                        </div>                       
                                        <!-- <div class="form-group">
                                            <label class="control-label col-sm-3">SKU</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="sku" id="sku" class="form-control" placeholder="" value="<?php echo !empty($product['sku'])?$product['sku']:''; ?>" />    
                                            </div>
                                        </div>                      -->
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">UPC </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="upc" id="upc" class="form-control" placeholder="" value="<?php echo !empty($product['upc'])?$product['upc']:''; ?>" />    
                                            </div>
                                        </div>                   
                                        <!-- <div class="form-group">
                                            <label class="control-label col-sm-3">Product Discount  <em>*</em></label>
                                            <div class="col-sm-9">
                                                <input type="number" step="0.01" name="discount" id="discount" class="form-control" placeholder="" required="required" value="<?php echo $product['discount']; ?>"/>    
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3">Discount Type <em>*</em></label>
                                            <div class="col-sm-9">
                                                <select name="discount_type" id="discount_type" class="form-control" required="required">
                                                    <option value="value" <?php echo ($product['sale_price']=='value')?'selected="selected"':''; ?>>Value</option>
                                                    <option value="percent" <?php echo ($product['sale_price']=='percent')?'selected="selected"':''; ?>>Percent</option>
                                                </select>   
                                            </div>
                                        </div>  -->
                                        <!-- <div class="form-group">                            
                                            <div class="col-sm-12 text-right">
                                                <a href="javascript:void(0)" class="black-btn2 next-tab" data-next="tab3">NEXT</a>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab3">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Select variation <em>*</em></label>
                                        <div class="col-sm-9">
                                            
                                            <select name="selected_variation" id="selected_variation"  multiple="multiple">
                                                
                                            </select>
                                        </div>
                                    </div>
                                  <div class="table-responsive1">
                                        <table class="table-bordered" width="100%" border="1" cellspacing="1" cellpadding="2">
                                          
                                            <tr class="blue-bg">
                                              <th scope="col">#</th>  
                                              <th scope="col">SKU</th>
                                              <th scope="col">StartPrice</th>
                                              <th scope="col">Quantity</th>
                                              <th scope="col">Name Value List</th>
                                              <th scope="col">UPC</th>
                                              <th scope="col">Picture</th>
                                            </tr>
                                            <tbody id="tbody_content">
                                            <?php
                                                if(!empty($product['vara_list']) && count($product['vara_list']) > 0){
                                                    $i=1;
                                                    foreach($product['vara_list'] as $key => $value) {
                                                        $variation_name=(array)json_decode($value['variation_name'],true);
                                                        $variation_value=(array)json_decode($value['variation_value'],true);
                                                        ?>
                                                        <tr>
                                                            <td><a href="javascript:void(0)" class="orange-btn2 del-vara">Delete</a></td>
                                                            <td><input type="text" name="vara_sku[]" class="form-control" placeholder="" value="<?php echo $value['sku']; ?>" readonly="readonly"></td>
                                                            <td><input type="text" name="vara_start_price[]" class="form-control" placeholder="" value="<?php echo $value['start_price']; ?>"></td>
                                                            <td><input type="text" name="vara_qty[]" class="form-control" placeholder="" value="<?php echo $value['quantity']; ?>"></td>
                                                            <td>
                                                                <?php
                                                                    $j=0;
                                                                    if(count($variation_name) > 0){
                                                                        foreach ($variation_name as $key2 => $value2) {
                                                                            ?>
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <input type="text" name="var_name[<?php echo $i; ?>][]" class="form-control" readonly="readonly" value="<?php echo $value2; ?>">
                                                                                </div>
                                                                                <div class="col-sm-4">
                                                                                    <input type="text" class="form-control" name="var_val[<?php echo $i; ?>][]" placeholder="" value="<?php echo $variation_value[$key2]; ?>">
                                                                                </div>
                                                                                
                                                                                
                                                                            </div>            
                                                                            <?php
                                                                        $j++;
                                                                        }
                                                                    }
                                                                ?>
                                                                
                                                            </td>
                                                            <td>
                                                                <input type="text" name="vara_upc[]" class="form-control" placeholder="" value="<?php echo $value['upc']; ?>">
                                                            </td>
                                                            <td>
                                                            <?php $iley=((int)$key + 1); ?>
                                                            <a href="javascript:void(0)" class="btn btn-file showpicmodal" id="<?php echo $iley; ?>">Set picture</a></td>
                                                        </tr>
                                                        <?php
                                                    $i++;
                                                    }
                                                }
                                            ?>
                                            
                                          </tbody>
                                        </table>
                                    </div> 
                                    <a href="javascript:void(0)" class="orange-btn2" id="add_new">ADD NEW</a>
                                    
                                    </div>
                                </div>
                                <button type="submit" name="submit"  class="userEditSubmit black-btn2">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

<script src="<?php echo front_base_url().'public/';?>js/vendor/easy-responsive-tabs.js" type="text/javascript"></script>

<datalist id="productTitleDataList"></datalist>
<template id="productTitleTemplate">
    <?php
     if(!empty($all_product_title) && count($all_product_title)>0){
        foreach ($all_product_title as $value) {          
    ?>
    <option><?php echo $value['title']; ?></option>
    <?php
    }}
    ?>
</template>

<input type="hidden" id="hidmg" value="<?php echo !empty($product['img_list'])?count($product['img_list']):0; ?>">
<input type="hidden" id="hidvara" value="<?php echo !empty($product['vara_list'])?count($product['vara_list']):0; ?>">
<script>
    $(document).ready(function() {
        if ($('#horizontalTab').length) {
            $('#horizontalTab').easyResponsiveTabs({
                type: 'default', //Types: default, vertical, accordion           
                width: 'auto', //auto or any width like 600px
                fit: true, // 100% fit in a container
                closed: 'accordion', // Start closed if in accordion view
                activate: function(event) { // Callback function if tab is switched
                    var $tab = $(this);
                    var $info = $('#tabInfo');
                    var $name = $('span', $info);
                    $name.text($tab.text());
                    $info.show();
                }
            });
        }


    });
    var search = document.querySelector('#title');
    var results = document.querySelector('#productTitleDataList');
    var templateContent = document.querySelector('#productTitleTemplate').content;
    search.addEventListener('keyup', function handler(event) {
        while (results.children.length) results.removeChild(results.firstChild);
        var inputVal = new RegExp(search.value.trim(), 'i');
        var set = Array.prototype.reduce.call(templateContent.cloneNode(true).children, function searchFilter(frag, item, i) {
            if (inputVal.test(item.textContent) && frag.children.length < 6) frag.appendChild(item);
            return frag;
        }, document.createDocumentFragment());
        results.appendChild(set);
    });

    window.variationimg=[];
    $(document).ready(function(){
          
    <?php if(!empty($all_cat_level_name['level1_id'])){ ?>
        getCategory('<?php echo $all_cat_level_name['level1_id']; ?>','level2');
    <?php } ?>
      $(document).on('click','#addmmoreimg',function(){
            var pval=$('#hidmg').val();
            var cval =parseInt(parseInt(pval) + 1);
            $('#hidmg').val(cval);
            var html='';
                html +='<div class="form-group btm_border">';
                html +='<label class="col-sm-3 control-label" for="demo-hor-12"></label>';
                html +='<div class="col-sm-6">';
                html +='<span class="pull-left btn btn-default btn-file"> choose_file';
                html +='<input type="file" name="images[]" onchange="preview(this,'+pval+');" id="demo-hor-12" class="form-control required" accept="image/*">';
                html +='</span>';
                html +='<br><br>';
                html +='<span id="previewImg_'+pval+'" ></span>';
                html +='</div>';
                html +='<div class="col-sm-3">';
                html +='<span class="pull-right btn btn-default btn-file delmg">Delete';
                html +='</span>';
                
                html +='</div>';
                html +='</div>';
            $('#addimgdata').append(html);
        });

        $(document).on('click','.delmg',function(){
            $(this).parent().parent().remove();
        });

        $(document).on('click','.deldoc',function () {
            $(this).parent().parent().remove();
            var $this=$(this);
            var id=$this.attr('doc-id');
            $this.button('loading');

            var url='<?php echo base_url('product/deldoc'); ?>'
            $.ajax({
                type:'POST',
                url:url,
                data:{'id':id},
                beforeSend:function(){},
                success:function(msg){
                    var response=$.parseJSON(msg);
                    if(response.error==0){
                        $this.parent().parent().remove();
                    }else{
                        $this.button('reset');
                        messagealert('Error','Invalid Request','error');
                    }
                },
                complete:function(){
                }
            })
        });

        $(document).on('click','.delmainimg',function(){
            var $this=$(this);
            var id=$this.attr('img-id');
            $this.button('loading');
            //alert(id);
            
            var url='<?php echo base_url('product/delimg'); ?>'
            $.ajax({
                type:'POST',
                url:url,
                data:{'id':id},
                beforeSend:function(){},
                success:function(msg){
                var response=$.parseJSON(msg);
                 if(response.error==0){
                    $this.parent().parent().remove();
                 }else{
                    $this.button('reset');
                    messagealert('Error','Invalid Request','error');
                 }
                },
                complete:function(){

                }
              })
        });
    });

    $(document).on('click','.showpicmodal',function(){
        var $this=$(this);
        var id=$this.attr('id');
        $('#var_image_hid_id').val(id);
        $('#pictureModal').modal('show');
        getuploadedFile(id);
    });

    function getuploadedFile(var_image_hid_id){
        $.ajax({
            url: "<?php echo base_url();?>dashboard/getuploadedpic/"+var_image_hid_id,
            data : {},
            //dataType : "json",
            type : "post",
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                var allimg=$.parseJSON(data);
                var html='';
                if(allimg.length > 0){
                    $.each( allimg, function( key_sub, value_sub ) {
                      var imgurl='<?php echo file_upload_base_url(); ?>product/'+value_sub;  
                      html +='<span class="thumbParent"><img class="thumb img-thumbnail" src="'+imgurl+'"><button picname="'+value_sub+'" imgsource="0" itemid="0" class="fa fa-times delimg remove_thumb" type="button"></button></span>'; 
                        
                    });
                }
                $('#list').html('');
                $('#list').html(html);
            }
        });
    }

    //upload image
    $(document).on('change',"#files",function(){
        $('#btnspan').button({loadingText: 'Uploading...'});
        $('#btnspan').button('loading');
        var formdata = new FormData();
        // get the no of files added to detaermine the remaining no to allow upload//
        var addedPicLen = 0;
        var remainingPics = parseInt($('#files')[0].files.length); //get the remaining pictures
        
        var var_image_hid_id=$('#var_image_hid_id').val();



            $.each($('#files')[0].files, function(i, file) {
                if(file.type.match('image.*')){ // check file extaintion
                
                formdata.append('myfile', file);
                $.ajax({
                    url: "<?php echo base_url();?>dashboard/uploadFiles/"+var_image_hid_id,
                    data : formdata,
                    //dataType : "json",
                    type : "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                     beforeSend : function(){
                      $(".loaderOverley").show();
                    },
                    success: function(data){
                        $('#btnspan').button('reset');
                        $("#max_upload").val(100);
                            var upfiles = $("#pictures").val(); //get already added pictures
                            if(upfiles==''){ // add new file name
                                $("#pictures").val(data);
                            }else{
                                $("#pictures").val(upfiles+','+data);
                            };
                            $("#list").append('<span class="thumbParent"><img src="<?php echo file_upload_base_url();?>product/'+data+'"  class="thumb img-thumbnail"><button type="button" class="fa fa-times delimg remove_thumb" itemid="'+i+'" imgsource="0" picname="'+data+'" ></button></span>'); // create preview section
                            $('#files').val('');
                            
                        },
                        failure: function(){
                            $('#btnspan').button('reset');
                            $(this).addClass("error");
                        },
                        complete:function(){
                            $('#btnspan').button('reset');
                             $(".loaderOverley").hide();
                        }
                    });
                
                }else{ // if not an image
                    alert('<?php echo $this->lang->line('no_img');?>'); 
                }
                
            }); 
       
        
    });

    $(document).on('click','#add_new',function(){
        //$('#selected_variation')
        var items = [];
        $('#selected_variation option:selected').each(function(){ items.push($(this).val()); });
        //alert(items.length);
        
        if(items.length <= 0){
            messagealert('Error','Please select atleast one variation','error');
            return false;
        }
        console.log(items);
        var p_hidvara=$('#hidvara').val();
        var n_hidvara = parseInt(p_hidvara) + 1;
        $('#hidvara').val(n_hidvara);
        var parse_variation_attribute=window.variationimg;
        console.log(parse_variation_attribute);
        //var parse_variation_attribute=$.parseJSON(variation_attribute);
        var html='';
        html +='<tr>';
        html +='<td><a href="javascript:void(0)" class="orange-btn2 del-vara">Delete</a></td>';
        html +='<td><input type="text" name="vara_sku[]" readonly="readonly" class="form-control" placeholder=""/></td>';
        html +='<td><input type="text" name="vara_start_price[]" class="form-control" placeholder=""/></td>';
        html +='<td><input type="text" name="vara_qty[]" class="form-control" placeholder=""/></td>';
        html +='<td>';
        $.each( items, function( key, value ) {
            console.log(parse_variation_attribute[value]);
            html +='<div class="row">';
            html +='<div class="col-sm-6">';
            html +='<input type="text" readonly="readonly" name="var_name['+n_hidvara+'][]" class="form-control" value="'+value+'">';
            html +='</div>';
            html +='<div class="col-sm-6">';

            html +='<select class="form-control" name="var_val['+n_hidvara+'][]">';
            $.each(parse_variation_attribute[value], function( key_sub, value_sub ) {
              html +='<option value="'+value_sub+'">'+value_sub+'</option>'; 
            });
            html +='</select>';
            html +='</div>';

            html +='</div>';
        });




        html +='</td>';

        html +='<td><input type="text" name="vara_upc[]" class="form-control" placeholder=""/></td>';
        html +='<td><a href="javascript:void(0)" class="btn btn-file showpicmodal" id="'+n_hidvara+'">Set picture</a></td>';
        html +='</tr>';
        $('#tbody_content').append(html);
    });

    $(document).on('click','.del-vara',function(){
        var $this=$(this);
        $this.closest("tr").remove();
    });

    $(document).on('click','.add_new_vara',function(){
        var variation_attribute='<?php echo json_encode($variation_attribute); ?>';
        var parse_variation_attribute=$.parseJSON(variation_attribute);
        var $this=$(this);
        var n_hidvara=$this.data('id');
        var html='';
        html +='<br/>';
        html +='<div class="row">';
        html +='<div class="col-sm-6">';
        html +='<select class="form-control" name="var_name['+n_hidvara+'][]">';
        $.each( parse_variation_attribute, function( key, value ) {
          html +='<option value="'+value.id+'">'+value.name+'</option>'; 
        });
        html +='</select>';
        html +='</div>';
        html +='<div class="col-sm-4"><input type="text" class="form-control" name="var_val['+n_hidvara+'][]" placeholder=""/></div>';
        html +='<div class="col-sm-2"><a href="javascript:void(0)" class="fa fa-times del_new_vara"></a></div>';
        html +='</div>';
        $this.parent().parent().parent().append(html);
    });

    $(document).on('click','.del_new_vara',function(){
        var $this=$(this);
        $this.parent().parent().remove();
    });

    $(document).ready(function($) {
        $.validator.addMethod('le', function (value, element, param) {
            return this.optional(element) || parseInt(value) <= parseInt($(param).val());
        }, 'Invalid value');

       $('#add-product-form').validate({
            ignore: ".ignore",
            invalidHandler: function(e, validator){
                if(validator.errorList.length){
                   var tabclick='#' + $(validator.errorList[0].element).closest(".tab-pane").attr('id');
                   $(tabclick+'_click').trigger('click');
                }
                
            }
        });
        
        
    });

    $(document).on('click','.next-tab',function(){
        var n_tab=$(this).data('next');
        $('#'+n_tab+'_click').trigger('click');
    });

    window.preview = function (input,nums) {
        if (input.files && input.files[0]) {
            $("#previewImg_"+nums).html('');
            $(input.files).each(function () {
                var reader = new FileReader();
                reader.readAsDataURL(this);
                reader.onload = function (e) {
                    $("#previewImg_"+nums).append("<div style='float:left;border:4px solid #303641;padding:5px;margin:5px;'><img height='80' src='" + e.target.result + "'></div>");
                }
            });
        }
    }

    $(function(){
        var places = [
          {name: "New York"}, 
          {name: "Los Angeles"},
          {name: "Copenhagen"},
          {name: "Albertslund"},
          {name: "Skjern"}  
        ];

        $('.tagsinput-typeahead').tagsinput({
          typeahead: {
            source: places.map(function(item) { return item.name }),
            afterSelect: function() {
                this.$element[0].value = '';
            }
          }
        })
    })

    function getCategory(id,type){
        var url='<?php echo base_url('product/getCategory'); ?>'
        $.ajax({
            type:'POST',
            url:url,
            data:{'id':id,'type':type},
            beforeSend:function(){},
            success:function(msg){
            var response=$.parseJSON(msg);
             if(response.error==0){
                $('#'+type).html(response.data);
                if(type=='level2'){
                    $('#'+type).val('<?php echo !empty($all_cat_level_name['level2_id'])?$all_cat_level_name['level2_id']:0; ?>');
                    $('#level3').html('<option value="">Select Category2</option>');
                    $('#level4').html('<option value="">Select Category3</option>');
                    getCategory('<?php echo !empty($all_cat_level_name['level2_id'])?$all_cat_level_name['level2_id']:0; ?>','level3');
                }else if(type=='level3'){
                    $('#'+type).val('<?php echo !empty($all_cat_level_name['level3_id'])?$all_cat_level_name['level3_id']:0; ?>');
                    getCategory('<?php echo !empty($all_cat_level_name['level3_id'])?$all_cat_level_name['level3_id']:0; ?>','level4');
                }else if(type=='level4'){
                    $('#'+type).val('<?php echo !empty($all_cat_level_name['level4_id'])?$all_cat_level_name['level4_id']:0; ?>');
                    getVariationAllCategory();
                }else{
                    $('#'+type).val('<?php echo !empty($all_cat_level_name['level1_id'])?$all_cat_level_name['level1_id']:0; ?>');
                }
                
             }else{
                //messagealert('Error','Please select all level category correctly!','error');
                $('#'+type).html(response.data);
             }
            },
            complete:function(){

            }
          })
    }

    
   

    function getVariationAllCategory(){
        var level1=$('#level1').val();
        var level2=$('#level2').val();
        var level3=$('#level3').val();
        if(level1==''){
            messagealert('Error','Category level1 is missing','error');
            return false;
        }
        if(level2==''){
            messagealert('Error','Category level2 is missing','error');
            return false;
        }
        if(level3==''){
            messagealert('Error','Category level3 is missing','error');
            return false;
        }
        var url='<?php echo base_url('product/getAllVariationCategoryWise'); ?>'
        $.ajax({
            type:'POST',
            url:url,
            data:{'level1':level1,'level2':level2,'level3':level3},
            beforeSend:function(){},
            success:function(msg){
            var response=$.parseJSON(msg);
             if(response.error==0){
                var html='';
                window.variationimg=response.data;
                var selected_variation='<?php echo ($variation_name_sel!="")?$variation_name_sel:"{}"; ?>';
                var parse_selected_variation=$.parseJSON(selected_variation); 
                 $.each( response.data, function( key, value ) {
                   var selecteddata=''; 
                  if(jQuery.inArray(key, parse_selected_variation) != -1) {
                        var selecteddata='selected="selected"';
                        console.log("is in array");
                    }  
                  html +='<option value="'+key+'" '+selecteddata+'>'+key+'</option>'; 
                 });
                 $('#selected_variation').html(html);
                 $("#selected_variation").multipleSelect({
                    filter: true,
                    width: '100%'
                });
             }else{
                messagealert('Error','Please select all level category correctly!','error');
             }
            },
            complete:function(){

            }
          })
    }

    //remove image
    $(document).on('click', '.remove_thumb', function () {
        $('#btnspan').button({loadingText: 'Deleting...'});
        $('#btnspan').button('loading');
        var filename = $(this).attr('picname'); // file name to be deleted
        var imgsource = $(this).attr('imgsource');
        //alert(filename);
        //return false;
        var removespan=$(this).parent('span.thumbParent'); // div to be removed upon delete
        //var upfiles = $("#pictures").val(); // get already added files
        var var_image_hid_id=$('#var_image_hid_id').val();
        var cnfm = confirm("<?php echo "Do yo want to delete this image ?";?>");
        if(cnfm){
            if(imgsource=="0"){
                $.ajax({
                    url: '<?php echo base_url();?>dashboard/uploadFiles/'+var_image_hid_id+'?del=delete&filename='+filename,
                    type : "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data){
                        removespan.remove();
                       
                           
                        $('#btnspan').button('reset');
                    },
                    failure: function(){
                        $('#btnspan').button('reset');
                        $(this).addClass("error");
                    }
                });
            }else{
                removespan.remove();
                
                
                $('#btnspan').button('reset');
            }
         }else{
             return false;
         }
        
    });

$('#sale_price, #purchase_price').blur(function(){    
     var sale_price = parseFloat($('#sale_price').val());
     var purchase_price = parseFloat($('#purchase_price').val());
     if(sale_price>0 && purchase_price>0 && sale_price < purchase_price){
       messagealert('Error','The sale price: Should not be greater than purchase price.','error');
       $('#sale_price').val('');
     }
});

function messagealert(title, text, type) {
    PNotify.removeAll();
    new PNotify({
        title: title,
        text: text,
        type: type,
        styling: 'bootstrap3'
    });
    }
    
</script>