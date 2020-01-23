<?php
class ModelApi extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    public function getCount($tbl_name,$select,$where=array(),$where_in=array()){
    	if(count($where) > 0){
    		$this->db->where($where);
    	}
        if(count($where_in) >0){
            $this->db->where_in('product_id',$where_in);
        }
    	$result=$this->db->select($select)->get($tbl_name)->num_rows();
    	return $result;
    }

    function insertData($tbl_name,$data){
        $result=$this->db->insert($tbl_name,$data);
        return $this->db->insert_id();
    }

    function updateData($tbl_name,$data,$check){
        $this->db->where($check);
        $result=$this->db->update($tbl_name,$data);
        return true;
    }

    function getallcountry()
	{
		$this->db->select('*');
		$this->db->from('countries');
		$result = $this->db->get();
		return $result->result_array();		
	}
	
	function getstate($id)
	{		
		$this->db->select('*');
		$this->db->from('states');
		$this->db->where('country_id',$id);
		$result = $this->db->get();
		return $result->result_array();		
	}
	
	function getcity($id)
	{
		$this->db->select('*');
		$this->db->from('cities');
		$this->db->where('state_id',$id);
		$result = $this->db->get();
		return $result->result_array();	
	}

    function delData($tbl_name,$data){
        return $this->db->delete($tbl_name,$data);
    }

    function getSingleData($tbl_name,$where){
        return $this->db->get_where($tbl_name,$where)->row_array();
    }

    public function getAllItemIDforRecent($customer_id){
        $this->db->order_by('created_date','DESC');
        $this->db->limit(10);
        $data=$this->db->select('GROUP_CONCAT(DISTINCT(product_id)) as gr_id')->get_where('product_recent_view',array('customer_id'=>$customer_id))->row_array();
        if(isset($data['gr_id'])){
            return $data['gr_id'];
        }else{
            return false;
        }
    }

    public function getAllWishlist($limit,$num,$where=array(),$where_in=array()){
        if(count($where) > 0){
            $this->db->where($where);
        }
        $this->db->limit($limit,$num);
        $this->db->join('product','product.product_id=wishlist.product_id','Left outer');
        $result=$this->db->select('product.*,wishlist.id as wishlist_id')->get_where('wishlist',array('product.product_id >'=>0))->result_array();
        //pr($result);
        if(count($result) > 0){
            foreach ($result as $key => $value) {
                $product_id=$value['product_id'];
                $result[$key]['image_path']=file_upload_base_url().'product/';
                $result[$key]['img_list']=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$value['product_id'],'type'=>'main'))->result_array();
                
                $result[$key]['vara_list']=$this->db->select('product_more.*')->get_where('product_more',array('product_id'=>$value['product_id']))->result_array();
            }
        }
        return $result;
    }

    public function getAllShippingAddress($limit,$num,$where=array(),$where_in=array()){
        if(count($where) > 0){
            $this->db->where($where);
        }
        $this->db->limit($limit,$num);
        $result=$this->db->select('shipping_address.*')->get_where('shipping_address',$where)->result_array();
        return $result;
    }


    public function getAllProduct($customer_id=0,$limit,$num,$where=array(),$where_in=array()){
    	if(count($where) > 0){
            $this->db->where($where);
        }
        if(count($where_in) >0){
            $this->db->where_in('product_id',$where_in);
        }
    	$this->db->limit($limit,$num);
    	$result=$this->db->select('product.*')->get_where('product',array())->result_array();
    	//pr($result);
    	if(count($result) > 0){
    		foreach ($result as $key => $value) {
    			$product_id=$value['product_id'];
                $wishlist=0;
                if($customer_id > 0){
                    $wishlist_status=$this->db->select('id')->get_where('wishlist',array('customer_id'=>$customer_id,'product_id'=>$value['product_id']))->row_array();
                    if(count($wishlist_status) > 0){
                        $wishlist=1;
                    }
                }
                $result[$key]['wishlist']=$wishlist;
    			$result[$key]['image_path']=file_upload_base_url().'product/';
    			$result[$key]['img_list']=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$value['product_id'],'type'=>'main'))->result_array();
                $result[$key]['doc_path']=file_upload_base_url().'product_doc/';
    			$result[$key]['doc_list']=$this->db->select('product_attach_document.*')->get_where('product_attach_document',array('product_id'=>$value['product_id']))->result_array();
				$result[$key]['vara_list']=$this->db->select('product_more.*')->get_where('product_more',array('product_id'=>$value['product_id']))->result_array();
    		}
    	}
    	return $result;
    }

    public function getAllData($tbl_name,$select,$where=array(),$join=array()){
    	if(count($join) > 0){
    		$this->db->join($join['tbl_name'],$join['where'],'Left outer');
    	}
    	$result=$this->db->select($select)->get_where($tbl_name,$where)->result_array();
    	return $result;
    }

    public function getCategoryData($tbl_name,$select,$where=array(),$join=''){

    	$result=$this->db->select($select)->get_where($tbl_name,$where)->result_array();
    	return $result;
    }

    public function getProductDetail($product_id,$customer_id=0,$ip_address){
        $recent_view_data = array('product_id'=>$product_id,'customer'=>$customer_id,'ip_address'=>$ip_address);
        $this->insertData('product_recent_view',$recent_view_data);
        $result=$this->db->get_where('product',array('product_id'=>$product_id))->row_array();
        if(count($result) > 0){
            $product_id=$result['product_id'];
            $wishlist=0;
            if($customer_id > 0){
                $wishlist_status=$this->db->select('id')->get_where('wishlist',array('customer_id'=>$customer_id,'product_id'=>$result['product_id']))->row_array();
                if(count($wishlist_status) > 0){
                    $wishlist=1;
                }
            }
            $result['wishlist']=$wishlist;
            $ratingData=$this->db->select('AVG(rate) as avg_rate')->get_where('product_rating',array('is_active'=>1,'product_id'=>$result['product_id']))->row_array();
            if(isset($ratingData['avg_rate'])){
                $rating=round($ratingData['avg_rate']);
            }else{
                $rating=0;
            }
            $result['rating']=$rating;
            $result['comments']=$this->db->select('product_rating.*')->get_where('product_rating',array('product_rating.product_id'=>$result['product_id'],'product_rating.is_active'=>1))->result_array();
            $result['image_path']=file_upload_base_url().'product/';
            $result['img_list']=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$result['product_id'],'type'=>'main'))->result_array();
            $result['doc_path']=file_upload_base_url().'product_doc/';
            $result['doc_list']=$this->db->select('product_attach_document.*')->get_where('product_attach_document',array('product_id'=>$result['product_id']))->result_array();
            $result['vara_list']=$this->db->select('product_more.*')->get_where('product_more',array('product_id'=>$result['product_id']))->result_array();
            
            $namevaluelist=array();
            if(count($result['vara_list']) > 0){
                foreach($result['vara_list'] as $key => $value) {
                    $variation_name=(array)json_decode($value['variation_name'],true);
                    $variation_value=(array)json_decode($value['variation_value'],true);
                   
                    $nvara=array();
                    $nvara['variation_id']=$value['id'];
                    $nvara['sku']=$value['sku'];
                    $nvara['start_price']=$value['start_price'];
                    $nvara['quantity']=$value['quantity'];
                    $nvara['upc']=$value['upc'];
                    $nvara['NameValue']=array();
                    $variation_attr=array();
                    
                    if(count($variation_name) > 0){
                        foreach ($variation_name as $key_variation => $value_variation) {
                            $variation_combination=array();
                            $variation_combination['Name']=$value_variation;
                            $variation_combination['Value']=$variation_value[$key_variation];
                            $nvara['NameValue'][]=$variation_combination;
                            /*if(!in_array($variation_value[$key_variation], $namevaluelist[$value_variation][])){
                                $namevaluelist[$value_variation][]=$variation_value[$key_variation];
                            }*/
                            $namevaluelist[$value_variation][]=$variation_value[$key_variation];

                        }
                    }
                    
                    $nvara['pic']=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$value['id'],'type'=>'variation'))->result_array();
                    unset($result['vara_list'][$key]['variation_name']);
                    unset($result['vara_list'][$key]['variation_value']);
                    
                    $result['vara_list'][$key]['variations']=$nvara;
                }
            }
            
            $nnvarvalue=array();
            
            if(count($namevaluelist) > 0){
                foreach ($namevaluelist as $key => $value) {
                    $nnvarvalue[]=array('Name'=>$key,'Value'=>array_values(array_unique($value)));
                    
                }
            }
            
            $result['namevalue']=$nnvarvalue;
            $result['seller']=$this->db->select('*')->get_where('merchants_business_details',array('merchants_id'=>$result['created_by']))->row_array();
        }

        return $result;

    }

    public function getvarientPrice(){
        $response = array();
      $form_data = $_POST;
          $product_id = $form_data['product_id'];
          $price = $form_data['product_price'];
          $product_title = $form_data['product_title'];
          $options = array();
          $varient_name= array();
          $varient_value = array();
          $empty_varient = array();
          foreach ($form_data as $key => $value) {
             if($key!=product_id && $key!='product_price' && $key!='product_title'){
                $options[$key] = $value;
                array_push($varient_name,$key);
                array_push($varient_value,$value);
                if(trim($value)==''){
                  array_push($empty_varient,$key);
                }
             }
          }
          
          $varient_name ="'".json_encode($varient_name)."'";
          $varient_value="'".json_encode($varient_value)."'";
          $checkQuantity = $this->db->query("select * from product_more where variation_name=$varient_name and variation_value=$varient_value and product_id=$product_id");
                  if ($checkQuantity->num_rows() > 0) {
                    $product_varient_result = $checkQuantity->row_array();                    
                    $response['status']=true;
                    $response['data']=array('start_price' => $product_varient_result['start_price']);              
                    
                  }else{
                    $productDetails = $this->db->query("select * from product where product_id=$product_id")->row_array();
                    $discount_price=(((float)$productDetails['purchase_price']-(float)$productDetails['sale_price'])/(float)$productDetails['purchase_price'])*100;                  
                    $response['status']=false;
                    $response['data']=array('purchase_price' => $productDetails['purchase_price'],'sale_price'=>$productDetails['sale_price'],'discount'=>number_format(abs($discount_price),2));
                  }
          return $response;
    }

    public function getSellerProfile($seller_id){
        $seller_details_query = $this->db->query("select mbd.*,cun.name as country,stat.name as state,city.name as city from merchants_business_details as mbd left join countries as cun on cun.id=mbd.business_country left join states as stat on stat.id=business_state left join cities as city on city.id=business_city where merchants_id=$seller_id");
        if($seller_details_query->num_rows()>0){
            $seller_details=$seller_details_query->row_array();
        $seller_details['feedback']=$this->db->query("select prd_r.* from product_rating as prd_r  join product as prod on prd_r.product_id=prod.product_id where prd_r.is_active=1 and prod.created_by=$seller_id and prod.is_active=1")->result_array();
        $seller_details['total_rating'] = $this->db->query("select sum(prd_r.rate) as total_rating from product_rating as prd_r join product as prod on prd_r.product_id=prod.product_id where prd_r.is_active=1 and prod.created_by=$seller_id and prod.is_active=1")->row()->total_rating;
        $seller_details['total_rating_user']=$this->db->query("select prd_r.* from product_rating as prd_r  join product as prod on prd_r.product_id=prod.product_id where prd_r.is_active=1 and prod.created_by=$seller_id and prod.is_active=1")->num_rows();

         $data = array('status' => true, 'message' => 'Seller Available','data'=>$seller_details);
       }else{
         $data = array('status' => false, 'message' => 'Seller Not Available','data'=>array());
       }

       return $data;
       
    }

    public function getAllBanner(){
        $path=file_upload_base_url()."banner";
        $this->db->limit($limit,$num);
        $this->db->select('(IF(icon="","",CONCAT("'.$path.'","/",icon))) as icon_path,(IF(icon_app="","",CONCAT("'.$path.'","/",icon_app))) as icon_app_path,banner.*', FALSE);
        $result=$this->db->get_where('banner',array('is_active'=>1))->result_array();
        return $result;
    }

    function getRecentProductView($customer_id,$ip_address){
        $this->db->select('product.*,product_recent_view.product_id');
        $this->db->from('product_recent_view');
        $this->db->join('product','product.product_id=product_recent_view.product_id');
        $this->db->where('product.admin_approval',1);
        $this->db->where('product_recent_view.ip_address',$ip_address);
        if($customer_id!=''){
            $this->db->or_where('product_recent_view.customer',$customer_id);
        }
        $this->db->group_by('product_recent_view.id');
        $result=$this->db->get()->result_array();
        if(count($result) > 0){
            foreach ($result as $key => $value) {
                $product_id=$value['product_id'];
                $wishlist=0;
                if($customer_id > 0){
                    $wishlist_status=$this->db->select('id')->get_where('wishlist',array('customer_id'=>$customer_id,'product_id'=>$value['product_id']))->row_array();
                    if(count($wishlist_status) > 0){
                        $wishlist=1;
                    }
                }
                $result[$key]['wishlist']=$wishlist;
                $result[$key]['image_path']=file_upload_base_url().'product/';
                $result[$key]['img_list']=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$value['product_id'],'type'=>'main'))->result_array();
                $result[$key]['doc_path']=file_upload_base_url().'product_doc/';
                $result[$key]['doc_list']=$this->db->select('product_attach_document.*')->get_where('product_attach_document',array('product_id'=>$value['product_id']))->result_array();
                $result[$key]['vara_list']=$this->db->select('product_more.*')->get_where('product_more',array('product_id'=>$value['product_id']))->result_array();
            }
        }
        return $result;
    }

    function getTrendsProductList($customer_id){
        $this->db->select('product.*,SUM(order_product_details.qty) AS TotalQuantity');
        $this->db->from('order_product_details');
        $this->db->join('product','product.product_id=order_product_details.product_id');
        $this->db->where('product.admin_approval',1);
        $this->db->order_by('SUM(order_product_details.qty)','DESC');
        $this->db->group_by('order_product_details.product_id');
        $result=$this->db->get()->result_array();
        if(count($result) > 0){
            foreach ($result as $key => $value) {
                $product_id=$value['product_id'];
                $wishlist=0;
                if($customer_id > 0){
                    $wishlist_status=$this->db->select('id')->get_where('wishlist',array('customer_id'=>$customer_id,'product_id'=>$value['product_id']))->row_array();
                    if(count($wishlist_status) > 0){
                        $wishlist=1;
                    }
                }
                $result[$key]['wishlist']=$wishlist;
                $result[$key]['image_path']=file_upload_base_url().'product/';
                $result[$key]['img_list']=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$value['product_id'],'type'=>'main'))->result_array();
                $result[$key]['doc_path']=file_upload_base_url().'product_doc/';
                $result[$key]['doc_list']=$this->db->select('product_attach_document.*')->get_where('product_attach_document',array('product_id'=>$value['product_id']))->result_array();
                $result[$key]['vara_list']=$this->db->select('product_more.*')->get_where('product_more',array('product_id'=>$value['product_id']))->result_array();
            }
        }
        return $result;
    }

    function getFilterProductList()
    {
        if ($this->input->post('page_number') != '') {
            $page_number = $this->input->post('page_number');
        } else {
            $page_number = 1;
        }
        $item_per_page = 10;
        $customer_id=0;
        $discount='';
        $customer_review='';
        if($this->input->post('customer_id')){
            $customer_id=$this->input->post('customer_id');
        }
        //Categories
        $cat_level_name = $this->input->post('cat_level_name');
        $cat_level_id = $this->input->post('cat_level_id');
        $level4_id = $this->input->post('cat_level4');
        //Brand
        $brand = $this->input->post('brand');
        //Variation
        $varient_value = $this->input->post('varient_value');
        //Minimum & Maximum Price
        $minprice = $this->input->post('minprice');
        $maxprice = $this->input->post('maxprice');
        //SortBy
        $sortby = $this->input->post('sortby');
        //Combo Product
        $is_combo = $this->input->post('is_combo');

        //Discount Percent
        $discount = $this->input->post('discount');

        //Customer Review
        $customer_review = $this->input->post('customer_review');

        $myFilterSql = "select * from product where admin_approval=1 and is_active=1 and cat_" . $cat_level_name . "=" . $cat_level_id;

        if ($level4_id) {
            $myFilterSql .= " and cat_level4=" . $level4_id;
        }

        if ($brand) {
            $brand = explode(',', $brand);
            $brand = "'" . implode("', '", $brand) . "'";
            $myFilterSql .= " and brand in ($brand)";
        }

        $varient_value = explode(',', $varient_value);
        if (count($varient_value) > 0) {
            $varient_product_id_list = array();
            $product_more_sql='select * from product_more where product_id in (select product_id from product where cat_'.$cat_level_name.'='.$cat_level_id.')';
            $product_more_data=$this->db->query($product_more_sql)->result();
            if($this->db->query($product_more_sql)->num_rows() > 0){
                foreach ($product_more_data as $prdmore) {
                    foreach (json_decode($prdmore->variation_value) as $var_value) {
                        if (in_array($var_value, $varient_value))
                        {
                            array_push($varient_product_id_list,$prdmore->product_id);
                        }
                    }


                }
            }
            if(count($varient_product_id_list)>0){
                $varient_product_id_list=implode(',', array_unique($varient_product_id_list));
                $myFilterSql.=" and product_id in ($varient_product_id_list)";
            }
        }

        if ($is_combo) {
            $myFilterSql .= " and is_combo=1";
        }

        if ($minprice > 0 && $maxprice > 0) {
            $myFilterSql .= " and purchase_price between $minprice and $maxprice";
        }

        if ($discount!='') {
            $myFilterSql.=" and ((purchase_price - sale_price) * 100 / purchase_price)>=$discount";
        }

        if ($customer_review!='' && $customer_review>0) {
            $myFilterSql.=" and (SELECT ROUND( SUM( rate ) / COUNT( rate ) ) AS rating FROM product_rating WHERE product_id= product.product_id) >=$customer_review";
        }

        if ($sortby == 'newest_first') {
            $myFilterSql .= " order by product_id desc";
        }

        if ($sortby == 'price_low_to_heigh') {
            $myFilterSql .= " order by purchase_price asc";
        }

        if ($sortby == 'price_high_to_low') {
            $myFilterSql .= " order by purchase_price desc";
        }
        //Pagination section
        $get_total_rows = $this->db->query($myFilterSql)->num_rows();
        $total_pages = ceil($get_total_rows / $item_per_page);
        $page_position = (($page_number - 1) * $item_per_page);
        $myFilterSql .= " LIMIT " . $page_position . "," . $item_per_page;

        $myFilterSqlResult = $this->db->query($myFilterSql);
        $result = $myFilterSqlResult->result_array();
            if (count($result) > 0) {
                foreach ($result as $key => $value) {
                        $product_id=$value['product_id'];
                        $wishlist=0;
                        if($customer_id > 0){
                            $wishlist_status=$this->db->select('id')->get_where('wishlist',array('customer_id'=>$customer_id,'product_id'=>$value['product_id']))->row_array();
                            if(count($wishlist_status) > 0){
                                $wishlist=1;
                            }
                        }
                        $result[$key]['wishlist']=$wishlist;
                        $result[$key]['image_path']=file_upload_base_url().'product/';
                        $result[$key]['img_list']=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$value['product_id'],'type'=>'main'))->result_array();
                        $result[$key]['doc_path']=file_upload_base_url().'product_doc/';
                        $result[$key]['doc_list']=$this->db->select('product_attach_document.*')->get_where('product_attach_document',array('product_id'=>$value['product_id']))->result_array();
                        $result[$key]['vara_list']=$this->db->select('product_more.*')->get_where('product_more',array('product_id'=>$value['product_id']))->result_array();

                }
            }

        return $result;

    }

    function getFilterProductListTotalCount()
    {
        $get_total_rows=0;
        $customer_id=0;
        $discount='';
        $customer_review='';
        if($this->input->post('customer_id')){
            $customer_id=$this->input->post('customer_id');
        }
        //Categories
        $cat_level_name = $this->input->post('cat_level_name');
        $cat_level_id = $this->input->post('cat_level_id');
        $level4_id = $this->input->post('cat_level4');
        //Brand
        $brand = $this->input->post('brand');
        //Variation
        $varient_value = $this->input->post('varient_value');
        //Minimum & Maximum Price
        $minprice = $this->input->post('minprice');
        $maxprice = $this->input->post('maxprice');
        //SortBy
        $sortby = $this->input->post('sortby');
        //Combo Product
        $is_combo = $this->input->post('is_combo');

        //Discount Percent
        $discount = $this->input->post('discount');

        //Customer Review
        $customer_review = $this->input->post('customer_review');

        $myFilterSql = "select * from product where admin_approval=1 and is_active=1 and cat_" . $cat_level_name . "=" . $cat_level_id;

        if ($level4_id) {
            $myFilterSql .= " and cat_level4=" . $level4_id;
        }

        if ($brand) {
            $brand = explode(',', $brand);
            $brand = "'" . implode("', '", $brand) . "'";
            $myFilterSql .= " and brand in ($brand)";
        }

        $varient_value = explode(',', $varient_value);
        if (count($varient_value) > 0) {
            $varient_product_id_list = array();
            $product_more_sql='select * from product_more where product_id in (select product_id from product where cat_'.$cat_level_name.'='.$cat_level_id.')';
            $product_more_data=$this->db->query($product_more_sql)->result();
            if($this->db->query($product_more_sql)->num_rows() > 0){
                foreach ($product_more_data as $prdmore) {
                    foreach (json_decode($prdmore->variation_value) as $var_value) {
                        if (in_array($var_value, $varient_value))
                        {
                            array_push($varient_product_id_list,$prdmore->product_id);
                        }
                    }


                }
            }
            if(count($varient_product_id_list)>0){
                $varient_product_id_list=implode(',', array_unique($varient_product_id_list));
                $myFilterSql.=" and product_id in ($varient_product_id_list)";
            }
        }

        if ($is_combo) {
            $myFilterSql .= " and is_combo=1";
        }

        if ($minprice > 0 && $maxprice > 0) {
            $myFilterSql .= " and purchase_price between $minprice and $maxprice";
        }

        if ($discount!='') {
            $myFilterSql.=" and ((purchase_price - sale_price) * 100 / purchase_price)>=$discount";
        }

        if ($customer_review!='' && $customer_review>0) {
            $myFilterSql.=" and (SELECT ROUND( SUM( rate ) / COUNT( rate ) ) AS rating FROM product_rating WHERE product_id= product.product_id) >=$customer_review";
        }

        if ($sortby == 'newest_first') {
            $myFilterSql .= " order by product_id desc";
        }

        if ($sortby == 'price_low_to_heigh') {
            $myFilterSql .= " order by purchase_price asc";
        }

        if ($sortby == 'price_high_to_low') {
            $myFilterSql .= " order by purchase_price desc";
        }
        //Pagination section
        $get_total_rows = $this->db->query($myFilterSql)->num_rows();

        return $get_total_rows;
    }

    function getSearchFilterProductList()
    {
        $search_keyword =$this->input->post('search_keyword');
        if ($this->input->post('page_number') != '') {
            $page_number = $this->input->post('page_number');
        } else {
            $page_number = 1;
        }
        $item_per_page = 10;
        $customer_id=0;
        $discount='';
        $customer_review='';
        if($this->input->post('customer_id')){
            $customer_id=$this->input->post('customer_id');
        }
        //Categories
        $level4_id = $this->input->post('cat_level4');
        //Brand
        $brand = $this->input->post('brand');
        //Variation
        $varient_value = $this->input->post('varient_value');
        //Minimum & Maximum Price
        $minprice = $this->input->post('minprice');
        $maxprice = $this->input->post('maxprice');
        //SortBy
        $sortby = $this->input->post('sortby');
        //Combo Product
        $is_combo = $this->input->post('is_combo');

        //Discount Percent
        $discount = $this->input->post('discount');

        //Customer Review
        $customer_review = $this->input->post('customer_review');

        $myFilterSql="select prd.* from product as prd left join category_level_1 as cat_l_1 on cat_l_1.id=prd.cat_level1 left join category_level_2 as cat_l_2 on cat_l_2.id=prd.cat_level2 left join category_level_3 as cat_l_3 on cat_l_3.id=prd.cat_level3 left join category_level_4 as cat_l_4 on cat_l_4.id=prd.cat_level4 where (prd.is_active=1 and prd.admin_approval=1) and (cat_l_1.name like '%".$search_keyword."%' or cat_l_2.name like '%".$search_keyword."%' or cat_l_3.name like '%".$search_keyword."%' or cat_l_4.name like '%".$search_keyword."%'  or prd.title like   '%".$search_keyword."%')";

        if($level4_id){
            $myFilterSql.=" and prd.cat_level4=".$level4_id;
        }

        if($is_combo){
            $myFilterSql.=" and prd.is_combo=1";
        }


        if ($brand) {
            $brand = explode(',', $brand);
            $brand = "'" . implode ( "', '", $brand) . "'";
            $myFilterSql.=" and prd.brand in ($brand)";
        }
        $varient_value = explode(',', $varient_value);
        if (count($varient_value) > 0) {
            $varient_product_id_list = array();
            $product_more_data=$this->db->query(" select * from product_more where product_id in (select prd.product_id from product as prd left join category_level_1 as cat_l_1 on cat_l_1.id=prd.cat_level1 left join category_level_2 as cat_l_2 on cat_l_2.id=prd.cat_level2 left join category_level_3 as cat_l_3 on cat_l_3.id=prd.cat_level3 left join category_level_4 as cat_l_4 on cat_l_4.id=prd.cat_level4 where (prd.is_active=1 and prd.admin_approval=1) and (cat_l_1.name like '%".$search_keyword."%' or cat_l_2.name like '%".$search_keyword."%' or cat_l_3.name like '%".$search_keyword."%' or cat_l_4.name like '%".$search_keyword."%' or prd.title like '%".$search_keyword."%'))")->result();
            foreach ($product_more_data as $prdmore) {
                foreach (json_decode($prdmore->variation_value) as $var_value) {
                    if (in_array($var_value, $varient_value))
                    {
                        array_push($varient_product_id_list,$prdmore->product_id);
                    }
                }


            }
            if(count($varient_product_id_list)>0){
                $varient_product_id_list=implode(',', array_unique($varient_product_id_list));
                $myFilterSql.=" and product_id in ($varient_product_id_list)";
            }
        }

        if($minprice>0 && $maxprice>0){
            $myFilterSql.=" and prd.purchase_price between $minprice and $maxprice";
        }

        if($minprice>0 && $maxprice>0){
            $myFilterSql.=" and prd.purchase_price between $minprice and $maxprice";
        }

        if ($discount!='') {
            $myFilterSql.=" and ((prd.purchase_price - prd.sale_price) * 100 / prd.purchase_price)>=$discount";
        }

        if ($customer_review!='' && $customer_review>0) {
            $myFilterSql.=" and (SELECT ROUND( SUM( rate ) / COUNT( rate ) ) AS rating FROM product_rating WHERE product_id= prd.product_id) >=$customer_review";
        }

        if ($sortby=='newest_first') {
            $myFilterSql.=" order by product_id desc";
        }

        if ($sortby=='price_low_to_heigh') {
            $myFilterSql.=" order by purchase_price asc";
        }

        if ($sortby=='price_high_to_low') {
            $myFilterSql.=" order by purchase_price desc";
        }

        $get_total_rows =$this->db->query($myFilterSql)->num_rows();
        $total_pages = ceil($get_total_rows/$item_per_page);
        $page_position = (($page_number-1) * $item_per_page);
        $myFilterSql.=" LIMIT ".$page_position.",".$item_per_page;

        $myFilterSqlResult = $this->db->query($myFilterSql);
        $result = $myFilterSqlResult->result_array();
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $product_id=$value['product_id'];
                $wishlist=0;
                if($customer_id > 0){
                    $wishlist_status=$this->db->select('id')->get_where('wishlist',array('customer_id'=>$customer_id,'product_id'=>$value['product_id']))->row_array();
                    if(count($wishlist_status) > 0){
                        $wishlist=1;
                    }
                }
                $result[$key]['wishlist']=$wishlist;
                $result[$key]['image_path']=file_upload_base_url().'product/';
                $result[$key]['img_list']=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$value['product_id'],'type'=>'main'))->result_array();
                $result[$key]['doc_path']=file_upload_base_url().'product_doc/';
                $result[$key]['doc_list']=$this->db->select('product_attach_document.*')->get_where('product_attach_document',array('product_id'=>$value['product_id']))->result_array();
                $result[$key]['vara_list']=$this->db->select('product_more.*')->get_where('product_more',array('product_id'=>$value['product_id']))->result_array();

            }
        }

        return $result;

    }

    function getSearchFilterProductListTotalCount()
    {
        $get_total_rows=0;
        $search_keyword =$this->input->post('search_keyword');

        $customer_id=0;
        $discount='';
        $customer_review='';
        if($this->input->post('customer_id')){
            $customer_id=$this->input->post('customer_id');
        }
        //Categories
        $level4_id = $this->input->post('cat_level4');
        //Brand
        $brand = $this->input->post('brand');
        //Variation
        $varient_value = $this->input->post('varient_value');
        //Minimum & Maximum Price
        $minprice = $this->input->post('minprice');
        $maxprice = $this->input->post('maxprice');
        //SortBy
        $sortby = $this->input->post('sortby');
        //Combo Product
        $is_combo = $this->input->post('is_combo');

        //Discount Percent
        $discount = $this->input->post('discount');

        //Customer Review
        $customer_review = $this->input->post('customer_review');


        $myFilterSql="select prd.* from product as prd left join category_level_1 as cat_l_1 on cat_l_1.id=prd.cat_level1 left join category_level_2 as cat_l_2 on cat_l_2.id=prd.cat_level2 left join category_level_3 as cat_l_3 on cat_l_3.id=prd.cat_level3 left join category_level_4 as cat_l_4 on cat_l_4.id=prd.cat_level4 where (prd.is_active=1 and prd.admin_approval=1) and ( cat_l_1.name like '%".$search_keyword."%' or cat_l_2.name like '%".$search_keyword."%' or cat_l_3.name like '%".$search_keyword."%' or cat_l_4.name like '%".$search_keyword."%'  or prd.title like   '%".$search_keyword."%')";

        if($level4_id){
            $myFilterSql.=" and prd.cat_level4=".$level4_id;
        }

        if($is_combo){
            $myFilterSql.=" and prd.is_combo=1";
        }


        if ($brand) {
            $brand = explode(',', $brand);
            $brand = "'" . implode ( "', '", $brand) . "'";
            $myFilterSql.=" and prd.brand in ($brand)";
        }
        $varient_value = explode(',', $varient_value);
        if (count($varient_value) > 0) {
            $varient_product_id_list = array();
            $product_more_data=$this->db->query(" select * from product_more where product_id in (select prd.product_id from product as prd left join category_level_1 as cat_l_1 on cat_l_1.id=prd.cat_level1 left join category_level_2 as cat_l_2 on cat_l_2.id=prd.cat_level2 left join category_level_3 as cat_l_3 on cat_l_3.id=prd.cat_level3 left join category_level_4 as cat_l_4 on cat_l_4.id=prd.cat_level4 where (prd.is_active=1 and prd.admin_approval=1) and (cat_l_1.name like '%".$search_keyword."%' or cat_l_2.name like '%".$search_keyword."%' or cat_l_3.name like '%".$search_keyword."%' or cat_l_4.name like '%".$search_keyword."%' or prd.title like '%".$search_keyword."%'))")->result();
            foreach ($product_more_data as $prdmore) {
                foreach (json_decode($prdmore->variation_value) as $var_value) {
                    if (in_array($var_value, $varient_value))
                    {
                        array_push($varient_product_id_list,$prdmore->product_id);
                    }
                }


            }
            if(count($varient_product_id_list)>0){
                $varient_product_id_list=implode(',', array_unique($varient_product_id_list));
                $myFilterSql.=" and product_id in ($varient_product_id_list)";
            }
        }

        if($minprice>0 && $maxprice>0){
            $myFilterSql.=" and prd.purchase_price between $minprice and $maxprice";
        }

        if($minprice>0 && $maxprice>0){
            $myFilterSql.=" and prd.purchase_price between $minprice and $maxprice";
        }

        if ($discount!='') {
            $myFilterSql.=" and ((prd.purchase_price - prd.sale_price) * 100 / prd.purchase_price)>=$discount";
        }

        if ($customer_review!='' && $customer_review>0) {
            $myFilterSql.=" and (SELECT ROUND( SUM( rate ) / COUNT( rate ) ) AS rating FROM product_rating WHERE product_id= prd.product_id) >=$customer_review";
        }

        if ($sortby=='newest_first') {
            $myFilterSql.=" order by product_id desc";
        }

        if ($sortby=='price_low_to_heigh') {
            $myFilterSql.=" order by purchase_price asc";
        }

        if ($sortby=='price_high_to_low') {
            $myFilterSql.=" order by purchase_price desc";
        }

        $get_total_rows =$this->db->query($myFilterSql)->num_rows();


        return $get_total_rows;

    }
    function getUserCartDetails($member_id,$ip){
        if($member_id!='' && $member_id>0){
            $query = $this->db->query("select * from cart where member_id=$member_id or ip_address='".$ip."'");
            }else{
                $query = $this->db->query("select * from cart where ip_address='".$ip."'");
            }
        $result=$query->result();
        return $result;
    }
    function add_to_cart($data){
      $this->db->insert('cart',$data);
      if($this->db->insert_id() > 0){
        return true;
      }
      return false;
    }

    function add_cart_review($data){
      $this->db->insert('cart_review',$data);
      if($this->db->insert_id() > 0){
        return true;
      }
      return false;
    }
    function getUserCartProductDetails($customer_id,$ip){
        // echo $ip;exit;
        $path=file_upload_base_url()."product";
        $this->db->select('cart.*');
        $this->db->select('product.sale_price as sale_price,product.shipping_cost as shipping_cost');
        $this->db->select('product.purchase_price as purchase_price');
        $this->db->select('(IF(product_images.path="","",CONCAT("'.$path.'","/",product_images.path))) as picture', FALSE);
        $this->db->from('cart');
        $this->db->join('product','product.product_id=cart.product_id','left');
        $this->db->join('product_images','product_images.product_id=cart.product_id','left');
        $this->db->where('cart.ip_address',$ip);
        if($customer_id>0){
        $this->db->or_where('cart.member_id',$customer_id);
        }
        $this->db->group_by('cart.product_id');
        $res= $this->db->get()->result_array();
        return $res;
    }

    function getAllCategoies(){
        $returnCategory=array();
        $this->db->select('*');
        $this->db->from('category_level_1');
        $allCategory=$this->db->get()->result_array();

        foreach ($allCategory as $cat1) {
           $this->db->select('*');
           $this->db->from('category_level_2');
           $this->db->where('level1',$cat1['id']);
           $level2=$this->db->get()->result_array();
         $alllevel2Cat=array();
        foreach ($level2 as $cat2) {
           $this->db->select('*');
           $this->db->from('category_level_3');
           $this->db->where('level2',$cat2['id']);
           $level3=$this->db->get()->result_array();
        $alllevel3Cat=array();
        foreach ($level3 as $cat3) {
           $this->db->select('*');
           $this->db->from('category_level_4');
           $this->db->where('level3',$cat3['id']);
           $cat4['level4']=$this->db->get()->result_array();
           array_push($alllevel3Cat,$cat3);
        }
        $cat2['level3']=$alllevel3Cat;
        array_push($alllevel2Cat,$cat2);
     }
        $cat1['level2']=$alllevel2Cat;
        array_push($returnCategory,$cat1);
    }
        return $returnCategory;
    }

    function getBrandList(){
        $levelname='';
        $level_id='';
        $levelname = $this->input->post('level_name');
        $level_id = $this->input->post('level_id');
        $result=array();
        if($levelname!='' && $level_id!='') {
            $brand_sql = 'select brnd.id,brnd.name from product as prod left join brands as brnd on brnd.id=prod.brand where prod.cat_' . $levelname . '=' . $level_id . ' and prod.is_active=1 group by prod.brand order by brnd.name asc';
            $lavel4_brand_query = $this->db->query($brand_sql);
            if ($lavel4_brand_query->num_rows() > 0) {
                foreach ($lavel4_brand_query->result_array() as $row) {
                    $brand_id = $row['id'];
                    $brand_product_count = $this->db->query("select count(*) as brand_product_count from product where cat_" . $levelname . "=" . $level_id . " and brand=$brand_id")->row()->brand_product_count;
                    $row['total_item'] = $brand_product_count;
                    array_push($result, $row);
                }
            }
        }else{
            $brand_sql = 'select brnd.id,brnd.name from product as prod left join brands as brnd on brnd.id=prod.brand where prod.is_active=1 group by prod.brand order by brnd.name asc';
            $brand_query = $this->db->query($brand_sql);
            if ($brand_query->num_rows() > 0) {
                foreach ($brand_query->result_array() as $row) {
                    $brand_id = $row['id'];
                    if($brand_id) {
                        $bc_query = 'select count(*) as brand_product_count from product where brand=' . $brand_id;
                        $brand_product_count = $this->db->query($bc_query)->row()->brand_product_count;
                        $row['total_item'] = $brand_product_count;
                        array_push($result, $row);
                    }
                }
            }
        }
        return $result;
    }

    function getVariationList(){
        $levelname='';
        $level_id='';
        $levelname = $this->input->post('level_name');
        $level_id = $this->input->post('level_id');
        $result=array();
        if($levelname!='' && $level_id!='') {
            $varient_sql='select * from variation_attribute where cat_'.$levelname.'='.$level_id.' and is_active=1 order by id asc';
            $product_more_sql = 'select * from product_more where product_id in (select product_id from product where cat_' . $levelname . '=' . $level_id . ')';
        }else{
            $varient_sql='select * from variation_attribute where is_active=1 order by id asc';
            $product_more_sql = 'select * from product_more where product_id in (select product_id from product where is_active=1)';
        }
        $variation_attribute_query = $this->db->query($varient_sql);
        if($variation_attribute_query->num_rows() > 0){
            foreach ($variation_attribute_query->result_array() as $row){
                $varient_id=$row['id'];
                $varient_values = $this->db->query("select * from variation_attribute_value where variation_id=$varient_id");
                if($varient_values->num_rows()>0){
                    $variation_tmp_val=array();
                    foreach ($varient_values->result_array() as $varient_val) {
                        $product_more_data = $this->db->query($product_more_sql)->result_array();
                        $varient_values_product_count = 0;
                        foreach ($product_more_data as $prdmore) {
                            if (strpos($prdmore['variation_value'], $varient_val['name'])) {
                                $varient_values_product_count++;
                            }
                        }
                        if($varient_values_product_count>0){
                            $varient_val['total_item']=$varient_values_product_count;
                            array_push($variation_tmp_val,$varient_val);
                        }
                    }
                    if(count($variation_tmp_val)>0){
                        $row['variation_attribute_value']=$variation_tmp_val;
                        array_push($result,$row);
                    }

                }
            }
        }
        return $result;
    }

    function getMinMaxPrice(){
        $levelname='';
        $level_id='';
        $levelname = $this->input->post('level_name');
        $level_id = $this->input->post('level_id');
        if($levelname!='' && $level_id!='') {
            $minMaxSql ='select max(NULLIF(purchase_price,0)) as MaxPrice ,min(NULLIF(purchase_price,0)) as MinPrice from product where cat_'.$levelname.'='.$level_id.' and is_active=1';
        }else{
            $minMaxSql ='select max(NULLIF(purchase_price,0)) as MaxPrice ,min(NULLIF(purchase_price,0)) as MinPrice from product where is_active=1';
        }
        $productMainPriceMinMaxQuery = $this->db->query($minMaxSql);
        if($productMainPriceMinMaxQuery->num_rows() > 0){
            return $productMainPriceMinMaxQuery->row_array();
        }
        return array();
    }


    function checkCoupon(){
        if(!$this->input->post('coupon_code')){
            $data = array('status' => false, 'message' => 'coupon_code is missing','data'=>array());
            return $data;
        }elseif (!$this->input->post('ip_address')){
            $data = array('status' => false, 'message' => 'ip_address is missing','data'=>array());
            return $data;
        }else {
            $fl = true;
            $ip = $this->input->post('ip_address');
            $customer_id=$this->input->post('customer_id');
            $couponCode= $this->input->post('coupon_code');
            $couponCode = "'" . $couponCode . "'";
            $date = "'" . date('Y-m-d') . "'";
            $total_discount_price = 0;
            $total_product_price = 0;
            $tot = 0;
            $coupon_code_check_query = $this->db->query("select * from coupon where coupon_code=$couponCode and ( $date BETWEEN start_date AND end_date) and is_active=1");
            if ($coupon_code_check_query->num_rows() > 0) {
                $coupon_details = $coupon_code_check_query->row();
                $coupon_apply_for = $coupon_details->discount_for;

                if ($coupon_apply_for == 'product') {
                    $cart_product_id_list = $this->_get_product_id_from_cart($customer_id,$ip);
                    $discount_product_ids = explode(",", $coupon_details->discount_select);
                    foreach ($discount_product_ids as $prod_id) {
                        $total_product_price += $this->_get_cart_product_price($prod_id,$customer_id,$ip);
                    }
                } elseif ($coupon_apply_for == 'category') {
                    $categories = "'" . $coupon_details->discount_select . "'";
                    $discount_product_ids = $this->_get_product_id_using_categories_from_cart($categories);
                    foreach ($discount_product_ids as $prod_id) {
                        $total_product_price += $this->_get_cart_product_price($prod_id,$customer_id,$ip);
                    }

                } else {
                    $discount_product_ids = $this->_get_product_id_from_cart($customer_id,$ip);
                    foreach ($discount_product_ids as $prod_id) {
                        $total_product_price += $this->_get_cart_product_price($prod_id,$customer_id,$ip);
                        $tot = $prod_id;
                    }
                }
                $total_payable_amount = 0;
                $total_payable_amount = $this->get_total_payable_amount($customer_id,$ip,0);
                if ($coupon_details->discount_type == 'percent') {
                    $total_discount_price = $total_product_price * ($coupon_details->discount_value / 100);
                } elseif ($total_payable_amount > $coupon_details->discount_value) {
                    $total_discount_price = $coupon_details->discount_value;
                    $message = 'Coupon Code Applied Successfully';
                } else {
                    $fl = false;
                    $message = 'Given Coupon code is not applicable for your purchase item.';
                }
                $data = array('status' => $fl, 'message' => $message,'data'=>array('coupon_code' => $couponCode, 'discount_amount' => $total_discount_price));
                return $data;

                //$this->nsession->set_userdata('coupon', array('coupon_code' => $couponCode, 'discount_amount' => $total_discount_price));
            } else {
                $fl = 0;
                $data = array('status' => false, 'message' => 'This coupon code is invalid or has expired.','data'=>array());
                return $data;
            }
        }
    }

    function _get_product_id_from_cart($customer_id,$ip){
        if($customer_id!='' && $customer_id>0){
            $cart_query = $this->db->query("select * from cart where member_id=$customer_id");
        }else{
            $cart_query = $this->db->query("select * from cart where ip_address='".$ip."'");
        }
        $product_list = array();
        if($cart_query->num_rows() > 0){
            foreach ($cart_query->result() as $cart) {
                array_push($product_list,$cart->product_id);
            }
        }
        return array_unique($product_list);
    }

    function _get_product_id_using_categories_from_cart($categories,$customer_id,$ip){
        if($customer_id!='' && $customer_id>0){
            $cart_query = $this->db->query("select product.product_id,product.cat_level1 from cart left join product on product.product_id=cart.product_id where cart.member_id=$customer_id and product.cat_level1 in ($categories)");
        }else{
            $cart_query = $this->db->query("select product.product_id,product.cat_level1 from cart left join product on product.product_id=cart.product_id where cart.ip_address='".$ip."' and product.cat_level1 in ($categories)");
        }
        $product_list = array();
        if($cart_query->num_rows() > 0){
            foreach ($cart_query->result() as $cart) {
                array_push($product_list,$cart->product_id);
            }
        }
        return array_unique($product_list);
    }

    function _get_cart_product_price($product_id,$customer_id,$ip){
        $total_price=0;
        if($customer_id!='' && $customer_id>0){
            $cart_query = $this->db->query("select * from cart where member_id=$customer_id and product_id=$product_id");
        }else{
            $cart_query = $this->db->query("select * from cart where ip_address='".$ip."' and product_id=$product_id");
        }
        if($cart_query->num_rows()>0){
            foreach ($cart_query->result() as $cart) {
                if($cart->options!=''){
                    $options = array();
                    $varient_name= array();
                    $varient_value = array();
                    $empty_varient = array();
                    foreach (json_decode($cart->options) as $key => $value) {
                        array_push($varient_name,$key);
                        array_push($varient_value,$value);

                    }
                    $varient_name ="'".json_encode($varient_name)."'";
                    $varient_value="'".json_encode($varient_value)."'";
                    $checkQuantity = $this->db->query("select * from product_more where variation_name=$varient_name and variation_value=$varient_value and product_id=$product_id");
                    $product_varient_result = $checkQuantity->row();
                    if(count($product_varient_result) > 0){
                        $item_price=$product_varient_result->start_price;
                    }
                }else{
                    $checkQuantity = $this->db->query("select * from product where product_id=$product_id");
                    $product_varient_result = $checkQuantity->row();
                    if(count($product_varient_result) > 0){
                        if($product_varient_result->sale_price>0){
                            $item_price=$product_varient_result->sale_price;
                        }else{
                            $item_price=$product_varient_result->purchase_price;
                        }
                    }
                }
                $total_price+=$item_price*$cart->qty;
            }

        }
        return $total_price;
    }

    public function get_total_payable_amount($member_id,$ip,$coupon_discount_amount){
        $cartDetails = $this->getUserCartDetails($member_id,$ip);
        $cart_total_price_befor_discount=0;
        $cart_total_price=0;
        foreach ($cartDetails as $item) {
            $product_id = $item->product_id;
            $options = json_decode($item->options);
            $productDetails = $this->db->query("select * from product where product_id=$product_id")->row();
            $description = $productDetails->description;

            $varient_name= array();
            $varient_value = array();
            foreach ($options as $key => $value) {
                array_push($varient_name,$key);
                array_push($varient_value,$value);
            }
            $varient_name ="'".json_encode($varient_name)."'";
            $varient_value="'".json_encode($varient_value)."'";
            $checkVarientPrice = $this->db->query("select * from product_more where variation_name=$varient_name and variation_value=$varient_value and product_id=$product_id");

            if($checkVarientPrice->num_rows()>0){
                $price=$checkVarientPrice->row()->start_price;
                $cart_total_price_befor_discount+=$checkVarientPrice->row()->start_price *$item->qty;
            }else{
                $cart_total_price_befor_discount+=$productDetails->purchase_price *$item->qty;
                if ($productDetails->sale_price>0) {
                    $price = $productDetails->sale_price;
                }else{
                    $price = $productDetails->purchase_price;
                }
            }

            $cart_total_price+=$price*$item->qty;
        }
        $cart_total_price = $cart_total_price - $coupon_discount_amount;

        return $cart_total_price;
    }

    function checkCartQuantity($cart_id){
        $cartDetails = $this->db->query("select * from cart where id=$cart_id")->row();
        $product_id = $cartDetails->product_id;
        $checkVarientProduct = $this->db->query("select * from product_more where product_id=$product_id");
        if($checkVarientProduct->num_rows() > 0){
            $ex_cart_varient_name = array();
            $ex_cart_varient_value=array();
            foreach(json_decode($cartDetails->options) as $key=>$value){
                array_push($ex_cart_varient_name,$key);
                array_push($ex_cart_varient_value,$value);
            }

            $ex_cart_varient_name ="'".json_encode($ex_cart_varient_name)."'";
            $ex_cart_varient_value="'".json_encode($ex_cart_varient_value)."'";
            $checkQuantity=0;
            $checkQuantity = $this->db->query("select quantity from product_more where variation_name=$ex_cart_varient_name and variation_value=$ex_cart_varient_value and product_id=$product_id")->row()->quantity;
            return $checkQuantity;

        }else{
            $checkQuantity = $this->db->query("select quantity from product where product_id=$product_id")->row()->quantity;
            return $checkQuantity;
        }



    }

    function getProductAllReview($product_id){
        $total_reviews = $this->db->query("select sum(rate) as total_rating,count(rate) as rating_count,round(sum(rate)/count(*)) as rating from product_rating where product_id=$product_id and is_active=1")->row_array();
        $total_reviews['comments']=$this->db->query("select * from product_rating where product_id=$product_id and is_active=1")->result_array();
        return $total_reviews;
    }
    function getOrderLists($customer_id,$num){
        $res=array();
        $path=file_upload_base_url()."product";
        $this->db->select('orders.*');
        $this->db->select('order_product_details.name as product_name');
        $this->db->select('order_product_details.product_id as product_id');
        $this->db->select('order_product_details.price as product_price');
        $this->db->select('order_product_details.options as product_options');
        $this->db->select('order_product_details.status as product_status');
        $this->db->select('order_status.status as order_status');
        $this->db->select('(IF(product_images.path="","",CONCAT("'.$path.'","/",product_images.path))) as picture', FALSE);
        $this->db->from('orders');
        $this->db->join('order_product_details','order_product_details.order_id=orders.id','left');
        $this->db->join('order_status','order_status.id=order_product_details.status','left');
        $this->db->join('product_images','product_images.product_id=order_product_details.product_id and product_images.type="main"','left');
        $this->db->where('orders.customer_id',$customer_id);
        //$this->db->group_by('orders.id');        
        //$this->db->limit(20,$num);                
        $res['orders'] = $this->db->get()->result_array();
        $res['total'] = $this->getOrderListsCount($customer_id);
        if($res['total'] > 0){
            return $res;
        }
        return false;
    }

    function getOrderListsCount($customer_id){
        $res=array();
        $path=file_upload_base_url()."product";
        $this->db->select('orders.*');
        $this->db->select('order_product_details.name as product_name');
        $this->db->select('order_product_details.product_id as product_id');
        $this->db->select('order_product_details.price as product_price');
        $this->db->select('order_product_details.options as product_options');
        $this->db->select('order_product_details.status as product_status');
        $this->db->select('order_status.status as order_status');        
        $this->db->from('orders');
        $this->db->join('order_product_details','order_product_details.order_id=orders.id','left');
        $this->db->join('order_status','order_status.id=order_product_details.status','left');
        $this->db->join('product_images','product_images.product_id=order_product_details.product_id','left');       
        $this->db->where('orders.customer_id',$customer_id);
        //$this->db->group_by('orders.id');
        
        
        return  $this->db->get()->num_rows();
    }


    function getOrderDetails($order_id,$product_id){
        /*$path=file_upload_base_url()."product";
        $this->db->select('order_product_details.*');
        $this->db->select('order_status.status as order_status');
        $this->db->select('(IF(product_images.path="","",CONCAT("'.$path.'","/",product_images.path))) as picture', FALSE);
        $this->db->from('order_product_details');
        $this->db->join('order_status','order_status.id=order_product_details.status','left');
        $this->db->join('product_images','product_images.product_id=order_product_details.product_id','left');
        $this->db->where('order_product_details.order_id',$order_id);
*/
        $res=array();
        $path=file_upload_base_url()."product";
        $this->db->select('orders.*');
        $this->db->select('order_product_details.name as product_name');
        $this->db->select('order_product_details.product_id as product_id');
        $this->db->select('order_product_details.price as product_price');
        $this->db->select('order_product_details.options as product_options');
        $this->db->select('order_product_details.status as product_status');
        $this->db->select('order_status.status as order_status');
        $this->db->select('(IF(product_images.path="","",CONCAT("'.$path.'","/",product_images.path))) as picture', FALSE);
        $this->db->from('orders');
        $this->db->join('order_product_details','order_product_details.order_id=orders.id','left');
        $this->db->join('order_status','order_status.id=order_product_details.status','left');
        $this->db->join('product_images','product_images.product_id=order_product_details.product_id and product_images.type="main"','left');
        $this->db->where('order_product_details.order_id',$order_id);
        $this->db->where('order_product_details.product_id',$product_id);
        $res=$this->db->get()->row_array();
        return $res;
    }
    function checkOrderStatus($order_id){
        $this->db->select('*');
        $this->db->from('order_product_details');
        $this->db->where('order_id',$order_id);
        $res= $this->db->get()->row_array();
        return $res;
    }
    function requestOrderReturn($order_id){
        $this->db->where('order_id',$order_id);
        $this->db->update('order_product_details',array('status'=>7));
        return true;

    }
    function requestOrderCancel($order_id){
        $this->db->where('order_id',$order_id);
        $this->db->update('order_product_details',array('status'=>6));
        return true;
    }
    function getWalletBalance($customer_id){
        $this->db->select_sum('amount');
        $this->db->from('wallet_orders');
        $this->db->where('customer_id',$customer_id);
        $this->db->where('type','credit');
        $credit= $this->db->get()->row_array();

        $this->db->select_sum('amount');
        $this->db->from('wallet_orders');
        $this->db->where('customer_id',$customer_id);
        $this->db->where('type','debit');
        $debit= $this->db->get()->row_array();

        $availableAmnt= $credit['amount'] - $debit['amount'];
        return $availableAmnt;
    }
    function getWallethistory($customer_id){
        $this->db->select('*');
        $this->db->from('wallet_orders');
        $this->db->where('customer_id',$customer_id);
        $credit= $this->db->get()->result_array();
        return $credit;
        
    }
    function check_wallet($customer_id){
        $this->db->select('*');
        $this->db->from('wallet_orders');
        $this->db->where('customer_id',$customer_id);
        $res = $this->db->get()->num_rows();
        return $res;
    }
    function insertIntoOrder($odata){
        $this->db->insert('orders',$odata);
        $last_insert_id = $this->db->insert_id(); 
        
        if($last_insert_id)
        {
            return $last_insert_id;
        }
        else
        {
            return false;
        }
    }
    function getProductName($id){
        $this->db->select('*');
        $this->db->from('product');
        $this->db->where('product_id',$id);
        return $this->db->get()->row_array();
    }
    function insertIntoOrderDetails($opdata){
        $this->db->insert('order_product_details',$opdata);
        $this->removeCart($opdata);
        $this->updateCartReview($opdata);
        return true;
    }
    function insertPaytmResponce($pdata){
        $this->db->insert('paytm_payment_details',$pdata);
        return true;
    }
    function insertRpayResponce($rpdata){
        $this->db->insert('razorpay_payment_details',$rpdata);
        return true;
    }
    function insertIntoWallet($odata){
        $this->db->insert('wallet_orders',$odata);
        return true;
    }

    function removeCart($odata){
        if($odata['member_id']!='' && $odata['member_id'] > 0){
        $this->db->where(array('product_id' => $odata['product_id'], 'member_id' => $odata['member_id']));
        $this->db->or_where(array('product_id' => $odata['product_id'], 'ip_address' => $odata['ip_address']));
        }else{
            $this->db->where(array('product_id' => $odata['product_id'], 'ip_address' => $odata['ip_address']));                       
        }
      $this->db->delete('cart');
    }

    function updateCartReview($odata){
        if($odata['member_id']!='' && $odata['member_id'] > 0){
        $this->db->where(array('product_id' => $odata['product_id'], 'member_id' => $odata['member_id']));
        $this->db->or_where(array('product_id' => $odata['product_id'], 'ip_address' => $odata['ip_address']));
        }else{
            $this->db->where(array('product_id' => $odata['product_id'], 'ip_address' => $odata['ip_address']));                       
        }
       $this->db->update('cart_review', array('purchase_status'=>1));
    }

}