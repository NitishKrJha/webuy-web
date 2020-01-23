<?php
class ModelProduct extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
	function getList(&$config,&$start,&$param)
	{
		
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
		$cat 			= $param['para1']; 
		$cat_val 		= $param['para2']; 
		
		if($isSession == 0)
		{
			$sortType    	= $this->nsession->get_param('PRODUCT_LIST','sortType','DESC');
			$sortField   	= $this->nsession->get_param('PRODUCT_LIST','sortField','id');
			$searchField 	= $this->nsession->get_param('PRODUCT_LIST','searchField','');
			$searchString 	= $this->nsession->get_param('PRODUCT_LIST','searchString','');
			$searchText  	= $this->nsession->get_param('PRODUCT_LIST','searchText','');
			$searchFromDate = $this->nsession->get_param('PRODUCT_LIST','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('PRODUCT_LIST','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('PRODUCT_LIST','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('PRODUCT_LIST','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('PRODUCT_LIST','searchDisplay',20);
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
		
		$this->nsession->set_userdata('PRODUCT_LIST', $sessionDataArray);
		//==============================================================
		$this->db->select('COUNT(product_id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->where('product.is_active',1);
		$this->db->where($cat,$cat_val);
		$this->db->select('product.*');

		$recordSet = $this->db->get('product'); 
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
			$this->db->select('product.*');
			$this->db->where('product.is_active',1);
			$this->db->where($cat,$cat_val);
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		//$this->db->order_by($sortField,$sortType);
		$this->db->order_by('product.product_id','desc');
		$this->db->limit($config['per_page'],$start);

		$recordSet = $this->db->get('product');
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
				$customer_id=0;
				if($this->nsession->userdata('member_session_id')){
					$customer_id=$this->nsession->userdata('member_session_id');
				}
                if($customer_id > 0){
                    $wishlist_status=$this->db->select('id')->get_where('wishlist',array('customer_id'=>$customer_id,'product_id'=>$row['product_id']))->row_array();
                    if(count($wishlist_status) > 0){
                        $wishlist=1;
                    }
                }
                $row['wishlist']=$wishlist;
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

	function getRelatedProduct($product_id,$cat_level1='',$cat_level2='',$cat_level3=''){
		$this->db->limit(10);
		$where=array();
		if($cat_level1!=''){
			$where['cat_level1']=$cat_level1;
		}
		if($cat_level2!=''){
			$where['cat_level2']=$cat_level2;
		}
		if($cat_level3!=''){
			$where['cat_level3']=$cat_level3;
		}
		$where['is_active']=1;
		$where['product_id !=']=$product_id;
		$result=$this->db->get_where('product',$where)->result_array();
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

	public function getProductDetail($product_id,$customer_id=0){
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
            $result['comments']=$this->db->select('product_rating.*')->get_where('product_rating',array('product_rating.product_id'=>$result['product_id']))->result_array();
            $result['image_path']=file_upload_base_url().'product/';
            $result['img_list']=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$result['product_id'],'type'=>'main'))->result_array();
            
            $result['vara_list']=$this->db->select('product_more.*')->get_where('product_more',array('product_id'=>$result['product_id']))->result_array();
            //pr($result['vara_list']);
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
                    //unset($result['vara_list'][$key]['variation_name']);
                    //unset($result['vara_list'][$key]['variation_value']);
                    
                    $result['vara_list'][$key]['variations']=$nvara;
                }
            }
            $nnvarvalue=array();
            
            if(count($namevaluelist) > 0){
                foreach ($namevaluelist as $key => $value) {
                    $nnvarvalue[]=array('Name'=>$key,'Value'=>array_unique($value));
                    
                }
            }
            //pr($nnvarvalue);
            $result['namevalue']=$nnvarvalue;
        }

        return $result;

    }




     public function save_recent_view_product($customer_id,$product_id,$ip_address){
	    if ($customer_id!='' && $customer_id>0) {
	    	$check_exist = $this->db->query("select * from product_recent_view where customer=$customer_id and product_id=$product_id")->num_rows();
	    }else{
	    	$check_exist = $this->db->query("select * from product_recent_view where product_id=$product_id and ip_address='".$ip_address."'")->num_rows();
	    }
	    if($check_exist==0){
	    	$this->db->insert('product_recent_view',array('product_id'=>$product_id,'customer'=>$customer_id,'ip_address'=>$ip_address));
	    	return true;
	    }

	    return false;   

	  }

	
}