<?php
class ModelCommon extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
	function insertData($tbl_name,$data){
		$result=$this->db->insert($tbl_name,$data);
		return $this->db->insert_id();
	}

	function updateData($tbl_name,$data,$check){
		$result=$this->db->update($tbl_name,$data,$check);
		return true;
	}

	function delData($tbl_name,$where){
		return $this->db->delete($tbl_name,$where);
	}

	function getSingleData($tbl_name,$where){
		return $this->db->get_where($tbl_name,$where)->row_array();
	}

	function getCountryCityStateList($tbl_name,$check=array()){
		if(count($check) >0){
			$this->db->where($check);
		}
		$result=$this->db->get($tbl_name)->result_array();
		return $result;
	}

	function getUserprofile($member_id){
		if($member_id){
			$param['member_id'] = $member_id;
	        $main_url=API_URL."myProfile";
	        $apiresult=$this->functions->httpPost($main_url,$param);
	        if($apiresult==''){
	            return false;
	        }
	        $returndata=json_decode($apiresult);
	        if($returndata->status==false){
	            return false;
	        }
	        return $returndata->data;
		}else{
			return false;
		}
	}

	function getWishlist(&$config,&$start,&$param){
		
		// GET DATA FROM GET/POST  OR   SESSION ====================
		$Count = 0;
		$page = $this->uri->segment(3,0); // page
		$isSession = $this->uri->segment(4,0); // read data from SESSION or POST     (1 == POST , 0 = SESSION )
		
		$start = 0;
				
		$sortType 		= $param['sortType'];
		$sortField 		= $param['sortField'];
		$searchField 	= $param['searchField'];
		$searchString 	= $param['searchString'];
		$searchText 	= $param['searchText']; 
		$searchFromDate	= $param['searchFromDate']; 
		$searchToDate 	= $param['searchToDate']; 
		$searchAlpha	= $param['searchAlpha']; 
		$searchMode		= $param['searchMode'];	
		$searchDisplay 	= $param['searchDisplay'];
		$customer_id 	= $param['customer_id']; 

		if($isSession == 0)
		{
			$sortType    	= $this->nsession->get_param('WISH_LIST','sortType','DESC');
			$sortField   	= $this->nsession->get_param('WISH_LIST','sortField','id');
			$searchField 	= $this->nsession->get_param('WISH_LIST','searchField','');
			$searchString 	= $this->nsession->get_param('WISH_LIST','searchString','');
			$searchText  	= $this->nsession->get_param('WISH_LIST','searchText','');
			$searchFromDate = $this->nsession->get_param('WISH_LIST','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('WISH_LIST','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('WISH_LIST','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('WISH_LIST','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('WISH_LIST','searchDisplay',20);
		}
		
		//========= SET SESSION DATA FOR SEARCH / PAGE / SORT Condition etc =====================
		$sessionDataArray = array();
		$sessionDataArray['sortType'] 		= $sortType;
		$sessionDataArray['sortField'] 		= $sortField;
		if($searchField!=''){
			$sessionDataArray['searchField'] 	= $searchField;
			$sessionDataArray['searchString'] 	= $searchString ;
		}
		$sessionDataArray['searchText'] 	= $searchText;		
		$sessionDataArray['searchFromDate'] = $searchFromDate;		
		$sessionDataArray['searchToDate'] 	= $searchToDate;
		$sessionDataArray['searchAlpha'] 	= $searchAlpha;	
		$sessionDataArray['searchMode'] 	= $searchMode;
		$sessionDataArray['searchDisplay'] 	= $searchDisplay;		
		
		$this->nsession->set_userdata('WISH_LIST', $sessionDataArray);
		//==============================================================
		$this->db->select('COUNT(id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->join('product','product.product_id=wishlist.product_id','Left outer');
		$this->db->where('product.is_active',1);
        $this->db->where('product.admin_approval',1);
		$this->db->where('wishlist.customer_id',$customer_id);
		$this->db->where('wishlist.status',1);
		$this->db->select('wishlist.*');

		$recordSet = $this->db->get('wishlist'); 
		$config['total_rows'] = 0;
		$config['per_page'] = $searchDisplay;
		if ($recordSet)
		{
			$row = $recordSet->row();
			$config['total_rows'] = $row->TotalrecordCount;
		}
		else
		{
			return false;
		}

		if($page > 0 && $page < $config['total_rows'] )
			$start = $page;
			$this->db->select('product.*,wishlist.id as wishlist_id');
			$this->db->join('product','product.product_id=wishlist.product_id','Left outer');
			$this->db->where('wishlist.customer_id',$customer_id);
			$this->db->where('wishlist.status',1);
			$this->db->where('product.is_active',1);
            $this->db->where('product.admin_approval',1);
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		//$this->db->order_by($sortField,$sortType);
		$this->db->order_by('wishlist.id','desc');
		$this->db->limit($config['per_page'],$start);

		$recordSet = $this->db->get('wishlist');
		$rs = false;

		if ($recordSet->num_rows() > 0)
        {
           	$rs = array();
			$isEscapeArr = array();
			foreach ($recordSet->result_array() as $row)
			{
				$picData=$this->db->select('path,path_sm')->get_where('product_images',array('product_id'=>$row['product_id'],'type'=>'main'))->row_array();
				if(isset($picData['path'])){
					if($picData['path_sm']!=''){
						$pic=file_upload_base_url().'product/'.$picData['path_sm'];
					}else{
						$pic=file_upload_base_url().'product/'.$picData['path'];
					}
				}else{
					//$pic=file_upload_base_url().'product/'
					$pic=css_images_js_base_url().'images/no_pr_img.jpg';
				}
				$row['pic']=$pic;
				$wishlist=0;
                if($customer_id > 0){
                    $wishlist_status=$this->db->select('id')->get_where('wishlist',array('customer_id'=>$customer_id,'product_id'=>$row['product_id']))->row_array();
                    if(count($wishlist_status) > 0){
                        $wishlist=1;
                    }
                }
                $row['wishlist']=$wishlist;
                $ratingData=$this->db->select('AVG(rate) as avg_rate,COUNT(rate) as total_count')->get_where('product_rating',array('is_active'=>1,'product_id'=>$row['product_id']))->row_array();
				if(isset($ratingData['avg_rate'])){
					$rating=round($ratingData['avg_rate']);
				}else{
					$rating=0;
				}
				if(isset($ratingData['total_count'])){
					$rating_count=round($ratingData['total_count']);
				}else{
					$rating_count=0;
				}
				$row['rating']=$rating;
				$row['rating_count']=$rating_count;
				foreach($row as $key=>$val){
					if(!in_array($key,$isEscapeArr)){
						$recordSet->fields[$key] = outputEscapeString($val);
						}
					}
				$rs[] = $recordSet->fields;
			}
		}
		else
		{
			return false;
		}
		return $rs;		
	}

	function getAllHomeCategory(){
		$ncat=array();
		$this->db->limit(10);
		$result=$this->db->select('id,name')->get('category_level_1')->result_array();
		if(count($result) >0){
			foreach ($result as $key => $value) {
				$this->db->limit(10);
				$level2=$this->db->select('id,name')->get_where('category_level_2',array('level1'=>$value['id']))->result_array();
				if(count($level2) > 0){
					foreach ($level2 as $key2 => $value2) {
						$this->db->limit(6);
						$level3=$this->db->select('id,name')->get_where('category_level_3',array('level2'=>$value2['id']))->result_array();
						$level2[$key2]['level3']=$level3;
					}
				}
				$result[$key]['level2']=$level2;
			}
			$ncat=$result;
		}
		return $ncat;
	}

	function getCategoryListByOrder(){
		$ncat=array();
		 foreach (range('A', 'Z') as $char) {
			$result=$this->db->select('id,name')->like('name',$char,'after')->get('category_level_1')->result_array();
			if(count($result) >0){
				//pr($result);
				foreach ($result as $key => $value) {
					$level2=$this->db->select('id,name')->get_where('category_level_2',array('level1'=>$value['id']))->result_array();
					if(count($level2) > 0){
						foreach ($level2 as $key2 => $value2) {
							$level3=$this->db->select('id,name')->get_where('category_level_3',array('level2'=>$value2['id']))->result_array();
							$level2[$key2]['level3']=$level3;
						}
					}
					$result[$key]['level2']=$level2;
				}
				$ncat[$char]=$result;
			}
		 }

		 return $ncat;
	}

	public function getAllData($tbl_name,$select,$where=array(),$join=array(),$limit=0){
		if($limit > 0){
			$this->db->limit($limit);
		}
    	if(count($join) > 0){
    		$this->db->join($join['tbl_name'],$join['where'],'Left outer');
    	}
    	$result=$this->db->select($select)->get_where($tbl_name,$where)->result_array();
    	return $result;
    }

	public function creatThumbImage($filename='',$source_path,$target_path){
		//$source_path = file_upload_absolute_path() .$source_path. '/profile_pic/merchants/' . $filename;
	    //$target_path = file_upload_absolute_path() .$destination_path. '/profile_pic/merchants/';
	    $config_manip = array(
	        'image_library' => 'gd2',
	        'source_image' => $source_path,
	        'new_image' => $target_path,
	        'maintain_ratio' => TRUE,
	        'create_thumb' => TRUE,
	        'thumb_marker' => '_thumb',
	        'width' => 150,
	        'height' => 150
	    );
	    $this->load->library('image_lib', $config_manip);
	    if (!$this->image_lib->resize()) {
	        //echo $this->image_lib->display_errors();
	        $this->image_lib->clear();
	    	return false;
	    }else{
	    	$imgDetailArray=explode('.',$filename);
 			$thumbimgname=$imgDetailArray[0].'_thumb';
 			$this->image_lib->clear();
  			return $thumbimgname.'.'.$imgDetailArray[1]; 	
	    }
	}

	function getTopOffer(){
		$this->db->limit(10);
		//$result=$this->db->get_where('product',array('is_active'=>1))->result_array();
        $result=$this->db->query("SELECT *, (purchase_price - sale_price) * 100 / purchase_price AS discount FROM product WHERE is_active=1 and admin_approval=1 and purchase_price > 0 and ((purchase_price - sale_price) * 100 / purchase_price) > 0 ORDER BY discount DESC limit 0,20")->result_array();
		if(count($result) > 0){
			foreach ($result as $key => $value) {
				$pic=$this->db->select('path,path_sm')->get_where('product_images',array('type'=>'main','product_id'=>$value['product_id']))->row_array();
				if(isset($pic['path'])){
					if($pic['path_sm']!=''){
						$result[$key]['pic']=file_upload_base_url().'product/'.$pic['path_sm'];
					}else{
						$result[$key]['pic']=file_upload_base_url().'product/'.$pic['path'];
					}
				}else{
					$result[$key]['pic']=css_images_js_base_url().'images/no_pr_img.jpg';
				}

				$rating=$this->db->select('AVG(rate) as avg_rate')->get_where('product_rating',array('is_active'=>1,'product_id'=>$value['product_id']))->row_array();
				if(isset($rating['avg_rate'])){
					$result[$key]['rating']=round($rating['avg_rate']);
				}else{
					$result[$key]['rating']=0;
				}
			}
		}

		return $result;
	}

	function getTrendingproduct(){
		$this->db->limit(10);
        $this->db->select('product.*,SUM(order_product_details.qty) AS TotalQuantity');
        $this->db->from('order_product_details');
        $this->db->join('product','product.product_id=order_product_details.product_id');
        $this->db->where('product.is_active',1);
        $this->db->where('product.admin_approval',1);
        $this->db->order_by('SUM(order_product_details.qty)','DESC');
        $this->db->group_by('order_product_details.product_id');
        $result=$this->db->get()->result_array();
		//$result=$this->db->get_where('product',array('is_active'=>1,'admin_approval'=>1))->result_array();
		if(count($result) > 0){
			foreach ($result as $key => $value) {
				$pic=$this->db->select('path,path_sm')->get_where('product_images',array('type'=>'main','product_id'=>$value['product_id']))->row_array();
				if(isset($pic['path'])){
					if($pic['path_sm']!=''){
						$result[$key]['pic']=file_upload_base_url().'product/'.$pic['path_sm'];
					}else{
						$result[$key]['pic']=file_upload_base_url().'product/'.$pic['path'];
					}
				}else{
					$result[$key]['pic']=css_images_js_base_url().'images/no_pr_img.jpg';
				}

				$rating=$this->db->select('AVG(rate) as avg_rate')->get_where('product_rating',array('is_active'=>1,'product_id'=>$value['product_id']))->row_array();
				if(isset($rating['avg_rate'])){
					$result[$key]['rating']=round($rating['avg_rate']);
				}else{
					$result[$key]['rating']=0;
				}
			}
		}

		return $result;
	}

	function getBestSeller(){
		$this->db->limit(10);
		//$result=$this->db->get_where('product',array('is_active'=>1))->result_array();
        $result=$this->db->query("SELECT prod.*,sum(prod_rating.rate) as total_rating FROM product as prod right join product_rating as prod_rating on prod_rating.product_id=prod.product_id where prod_rating.is_active=1 group by prod_rating.product_id order by total_rating desc limit 0,24")->result_array();
		if(count($result) > 0){
			foreach ($result as $key => $value) {
				$pic=$this->db->select('path,path_sm')->get_where('product_images',array('type'=>'main','product_id'=>$value['product_id']))->row_array();
				if(isset($pic['path'])){
					if($pic['path_sm']!=''){
						$result[$key]['pic']=file_upload_base_url().'product/'.$pic['path_sm'];
					}else{
						$result[$key]['pic']=file_upload_base_url().'product/'.$pic['path'];
					}
				}else{
					$result[$key]['pic']=css_images_js_base_url().'images/no_pr_img.jpg';
				}

				$rating=$this->db->select('AVG(rate) as avg_rate')->get_where('product_rating',array('is_active'=>1,'product_id'=>$value['product_id']))->row_array();
				if(isset($rating['avg_rate'])){
					$result[$key]['rating']=round($rating['avg_rate']);
				}else{
					$result[$key]['rating']=0;
				}
			}
		}

		return $result;
	}

	/////////GET NAME BY TABLE NAME AND ID/////////////
    function get_type_name_by_id($type, $type_id = '', $field = 'name')
    {
        if ($type_id != '') {
            $l = $this->db->get_where($type, array(
                $type . '_id' => $type_id
            ));
            $n = $l->num_rows();
            if ($n > 0) {
                return $l->row()->$field;
            }
        }
    }

    //GETTING PRODUCT PRICE CALCULATING DISCOUNT

	function get_product_price($product_id)
	{
	    $price         = $this->get_type_name_by_id('product', $product_id, 'sale_price');
	    $discount      = $this->get_type_name_by_id('product', $product_id, 'discount');
	    $discount_type = $this->get_type_name_by_id('product', $product_id, 'discount_type');
	    if ($discount_type == 'amount') {
	        $number = ($price - $discount);
	    }
	    if ($discount_type == 'percent') {
	        $number = ($price - ($discount * $price / 100));
	    }
	    return number_format((float) $number, 2, '.', '');
	}

	//GETTING SHIPPING COST

	function get_shipping_cost($product_id)
	{
	    $price              = $this->get_type_name_by_id('product', $product_id, 'sale_price');
	    $shipping           = $this->get_type_name_by_id('product', $product_id, 'shipping_cost');
	    $shipping_cost_type = $this->get_type_name_by_id('business_settings', '3', 'value');
	    if ($shipping_cost_type == 'product_wise') {
	        if($shipping == ''){
	            return 0;
	        } else {
	            return ($shipping);                
        	}
        }
        if ($shipping_cost_type == 'fixed') {
            return 0;
        }
    }

    //GETTING PRODUCT TAX
	function get_product_tax($product_id)
	{
	    $price    = $this->get_type_name_by_id('product', $product_id, 'sale_price');
	    $tax      = $this->get_type_name_by_id('product', $product_id, 'tax');
	    $tax_type = $this->get_type_name_by_id('product', $product_id, 'tax_type');
	    if ($tax_type == 'amount') {
	        if($tax == ''){
	            return 0;
	        } else {
	            return $tax;                
	        }
        }
        if ($tax_type == 'percent') {
            if($tax == ''){
                $tax = 0;
            }
            return ($tax * $price / 100);
        }
    }

    function file_view($product_id){
    	$result=$this->db->select('path,path_sm')->get_where('product_images',array('product_id'=>$product_id,'type'=>'main'))->row_array();
    	if(isset($result['path'])){
    		if($result['path_sm']!=''){
    			$pic=file_upload_base_url().'product/'.$result['path_sm'];
    		}else{
    			$pic=file_upload_base_url().'product/'.$result['path'];
    		}
    	}else{
    		$pic=css_images_js_base_url().'images/no_pr_img.jpg';
    	}

    	return $pic;
    }

    //IF PRODUCT ADDED TO CART
	function is_added_to_cart($product_id, $set = '', $op = '')
	{
	    $carted = $this->cart->contents();
	    //var_dump($carted);
	    if (count($carted) > 0) {
	        foreach ($carted as $items) {
	            if ($items['id'] == $product_id) {
	                if ($set == '') {
	                    return true;
	                } else {
	                    if($set == 'option'){
	                        $option = json_decode($items[$set],true);
	                        return $option[$op]['value'];
	                    } else {
	                        return $items[$set];
	                    }

                    }
                }
            }
        } else {
            return false;
        }
    }

    //TOTALING OF CART ITEMS BY TYPE
    function cart_total_it($type)
    {
        $carted = $this->cart->contents();
        $ret    = 0;
        if (count($carted) > 0) {
            foreach ($carted as $items) {
                $ret += $items[$type] * $items['qty'];
            }
            return $ret;
        } else {
            return false;
        }
    }

    /* FUNCTION: Regarding Digital*/
	function is_digital($id){
	    if($this->db->get_where('product',array('product_id'=>$id))->row()->download == 'ok'){
	        return true;
	    } else {
	        return false;
	    }
    }

     function getAutoCompleteKeywordList(){
   	   $keywordList = array();
      $categories_list = $this->db->query("select * from category_level_1 order by name asc")->result();
      foreach ($categories_list as $cat_details) {
        array_push($keywordList,$cat_details->name);
      }
      $categories_list = $this->db->query("select * from category_level_2 order by name asc")->result();
      foreach ($categories_list as $cat_details) {
        array_push($keywordList,$cat_details->name);
      }
      $categories_list = $this->db->query("select * from category_level_3 order by name asc")->result();
      foreach ($categories_list as $cat_details) {
        array_push($keywordList,$cat_details->name);
      }
      $categories_list = $this->db->query("select * from category_level_4 order by name asc")->result();
      foreach ($categories_list as $cat_details) {
        array_push($keywordList,$cat_details->name);
      } 
      $product_list = $this->db->query("select * from product order by title asc")->result();
      foreach ($product_list as $product_details) {
        array_push($keywordList,$product_details->title);
      } 

      return $keywordList;
   }

    public function send_email($to,$template_name,$data){
        $this->load->library('email');
        $this->email->set_mailtype("html");
        $this->email->from($this->config->item('webmaster_email'), $this->config->item('website_name'));
        $this->email->reply_to($this->config->item('webmaster_email'), $this->config->item('website_name'));
        $this->email->to($to);
        $this->email->subject(sprintf($data['subject'], $this->config->item('website_name')));
        $this->email->message($this->load->view('email/'.$template_name,$data, TRUE));
        $this->email->send();
        return $this->email->print_debugger();
    }


}