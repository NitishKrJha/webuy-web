<?php

class ModelProduct extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
    }

    function getAllBrands(){
		$this->db->select('*');
		$this->db->from('brands');
		return $this->db->get()->result_array();
	}

	function delData($tbl_name,$where){
		return $this->db->delete($tbl_name,$where);
	}

	function getUniqueID(){
		$n_id= time() + (int)mt_rand();
		$max_id=$this->db->select('MAX(product_id) as max_id')->get('product')->row();
		if(isset($max_id->max_id)){
			$n_id=time() + (int)$max_id->max_id;
		}

		return $n_id;
	}

	function delImg($id){
		$check=$this->db->select('path,path_sm')->get_where('product_images',array('image_id'=>$id))->row_array();
		if(count($check) > 0){
			$this->db->delete('product_images',array('image_id'=>$id));
			if($check['path']!=''){
				unlink(file_upload_absolute_path().'product/'.$check['path']);
			}
			if($check['path_sm']!=''){
				unlink(file_upload_absolute_path().'product/'.$check['path_sm']);
			}
			return true;
		}else{
			return false;
		}
	}

	function savePrImage($last_id,$pr_image,$type){
		//pr($pr_image);
		if(count($pr_image['upload_img'])>0){
			foreach($pr_image['upload_img'] as $key=>$value){
				 $insertImgData=array();
				 $insertImgData = array(
					 'product_id'=>$last_id,
					 'path'=>$value['main_img'],
					 'path_sm'=>$value['thumb_img'],
					 'type'=>$type
					);
				$this->db->insert('product_images',$insertImgData);
				//echo "<pre>"; print_r($insertImgData);
			}
		}
		return true;
	}

	function addData($tbl_name,$data,$where=array()){
		if(count($where) > 0){
			$result=$this->db->update($tbl_name,$data,$where);
			if($result > 0){
				return true;
			}else{
				return false;
			}
		}else{
			$result=$this->db->insert($tbl_name,$data);

			if($result > 0){
				return $this->db->insert_id();
			}else{
				//pr($result);
				return false;
			}
		}
	}

	function getProductData($id){
		$product=$this->db->select('product.*')->get_where('product',array('product_id'=>$id))->row_array();
		$sessionImageArray=array();
		if(count($product) > 0){
			$img_list=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$product['product_id'],'type'=>'main'))->result_array();
			$product['img_list']=$img_list;
            $doc_list=$this->db->select('product_attach_document.*')->get_where('product_attach_document',array('product_id'=>$product['product_id']))->result_array();
            $product['doc_list']=$doc_list;
			$vara_list=$this->db->select('product_more.*')->get_where('product_more',array('product_id'=>$product['product_id']))->result_array();
			
			if(count($vara_list) > 0){
				$i=1;
				foreach($vara_list as $key => $value) {
					$vara_pic=$this->db->get_where('product_images',array('product_id'=>$value['id'],'type'=>'variation'))->result_array();
					$vara_list[$key]['pic']=$vara_pic;
					if(count($vara_pic) > 0){
						$sub_pic=array();
						foreach($vara_pic as $key_p => $value_p) {
							$sub_pic[]=$value_p['path'];
						}
						
					}
					$sessionImageArray[$i]=$sub_pic;
					$i++;
				}
			}
			//pr($sessionImageArray);
			if(count($sessionImageArray) > 0){
				$this->nsession->set_userdata('upload_image',$sessionImageArray);
			}
			//pr($this->nsession->userdata('upload_image'));
			$product['vara_list']=$vara_list;
			return $product;
		}else{
			return array();
		}
	}

    function getAllProduct(&$config,&$start,&$param,$merchants_id=0)
	{
		
		// GET DATA FROM GET/POST  OR   SESSION ====================
		$Count = 0;
		$page = $this->uri->segment(3,0); // page
		$isSession = $this->uri->segment(4,0); // read data from SESSION or POST     (1 == POST , 0 = SESSION )
		//echo $isSession;
		//print_r($param);
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
		//pr($param);
		if($isSession == 0)
		{
			$sortType    	= $this->nsession->get_param('PRODUCT','sortType','DESC');
			$sortField   	= $this->nsession->get_param('PRODUCT','sortField','id');
			$searchField 	= $this->nsession->get_param('PRODUCT','searchField','');
			$searchString 	= $this->nsession->get_param('PRODUCT','searchString','');
			$searchText  	= $this->nsession->get_param('PRODUCT','searchText','');
			$searchFromDate = $this->nsession->get_param('PRODUCT','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('PRODUCT','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('PRODUCT','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('PRODUCT','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('PRODUCT','searchDisplay',10);
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
		
		$this->nsession->set_userdata('PRODUCT', $sessionDataArray);
		//==============================================================
		$this->db->select('COUNT(product_id) as TotalrecordCount');
		//$this->db->where('product.created_by',$merchants_id);
		//$this->db->where('product.created_by_type','merchants');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->select('product.*');

		$recordSet = $this->db->get('product'); 
		//echo $this->db->last_query(); die();
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
			//$this->db->where('product.created_by',$merchants_id);
			//$this->db->where('product.created_by_type','merchants');
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
				foreach($row as $key=>$val){
					if(!in_array($key,$isEscapeArr)){
						$recordSet->fields[$key] = outputEscapeString($val);
						}
					}
				$rs[] = $recordSet->fields;
			}

			if(count($rs) > 0){
				foreach ($rs as $key => $value) {
					$rs[$key]['img_list']=$this->db->select('product_images.*')->get_where('product_images',array('product_id'=>$value['product_id']))->result_array();
				}
			}
		}
		else
		{
			return false;
		}
		return $rs;		
	}
	
	function getList(&$config,&$start,&$param)
	{
		
		// GET DATA FROM GET/POST  OR   SESSION ====================
		$Count = 0;
		$page = $this->uri->segment(3,0); // page
		//echo $page; die();
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
		
		if($isSession == 0)
		{
			$sortType    	= $this->nsession->get_param('ADMIN_PRODUCT','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_PRODUCT','sortField','product_id');
			$searchField 	= $this->nsession->get_param('ADMIN_PRODUCT','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_PRODUCT','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_PRODUCT','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_PRODUCT','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_PRODUCT','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_PRODUCT','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_PRODUCT','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_PRODUCT','searchDisplay',10);
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

		$this->nsession->set_userdata('ADMIN_PRODUCT', $sessionDataArray);
		//==============================================================
		$this->db->select('COUNT(product_id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->group_by('product_id'); 
		$recordSet = $this->db->get('product'); 
		//echo $this->db->last_query(); die();
		$config['total_rows'] = 0;
		$config['per_page'] = $searchDisplay;
		if ($recordSet)
		{
			$row = $recordSet->row();
			$config['total_rows'] = $recordSet->num_rows();
		}
		else
		{
			return false;
		}

		//echo $config['total_rows']; die();

		if($page > 0 && $page < $config['total_rows'] )
			$start = $page;
			$this->db->select('product.*,product_images.path as imagepath,product_images.path_sm as smimagepath,product_images.type');
			$this->db->join('product_images','product_images.product_id=product.product_id','right');
			//$this->db->where('product.first_name !=','Administrator');
			$this->db->where('product_images.type','main');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		$this->db->order_by($sortField,$sortType);
		//$this->db->order_by('product.product_id','desc');
		 $this->db->group_by('product.product_id');
		$this->db->limit($config['per_page'],$start);

		$recordSet = $this->db->get('product');
		//echo $this->db->last_query(); die();
		$rs = false;

		if ($recordSet->num_rows() > 0)
        {
           	$rs = array();
			$isEscapeArr = array();
			foreach ($recordSet->result_array() as $row)
			{
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
	
	function getSingle($id){
		$this->db->select('product.*,product_images.*');
			$this->db->join('product_images','product_images.product_id=product.product_id','right');
			$this->db->where('product.product_id',$id);
			//$this->db->where('product_images.type','main');
			
			
		//$this->db->order_by($sortField,$sortType);
		

		$result = $this->db->get('product')->row_array();
		
		return $result;
	}
	function getAllimgage($id){
		$this->db->select('path,path_sm,type');
		$this->db->where('product_id',$id);
		$result = $this->db->get('product_images')->result_array();
		
		return $result;
	}



	function multi_img(){
		$query = $this->db->query('SELECT path_sm from product_images where product_id=$id');
		return $query;
	}

	function addContent($data)
	{
		$this->db->insert('product',$data);
		$insert_id = $this->db->insert_id();
		
		if($insert_id)
		{
			return $insert_id;
		}
		else
		{
			return false;
		}
	}
	
	function editContent($data,$id)
	{		
		$this->db->where('product_id', $id);
		$this->db->update('product', $data); 
		return true;
	}
	
	function activate($id)
	{
		$sql = "UPDATE product SET is_active = 1 WHERE product_id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}

	function inactive($id)
	{
		$sql = "UPDATE product SET is_active = 0 WHERE product_id = ".$id."";	
		$recordSet = $this->db->query($sql);
		//echo $this->db->last_query(); die();
		if (!$recordSet )
		{
			return false;
		}
	}

	function feature($id)
	{
		$sql = "UPDATE product SET featured = 1 WHERE product_id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}

	function infeature($id)
	{
		$sql = "UPDATE product SET featured = 0 WHERE product_id = ".$id."";	
		$recordSet = $this->db->query($sql);
		//echo $this->db->last_query(); die();
		if (!$recordSet )
		{
			return false;
		}
	}
	function getsingle_empdata($id){
		$this->db->select('product.*,countries.name as country_name,states.name as state_name,cities.name as city_name');
		$this->db->join('countries','countries.id=product.country','Left Outer');
		$this->db->join('states','states.id=product.state','Left Outer');
		$this->db->join('cities','cities.id=product.city','Left Outer');
		$this->db->from('product');
		$this->db->where('product.product_id',$id);
		$data = $this->db->get();
		return $data->row();
		
	}
	

	function getBusinessList($id){
		$this->db->join('countries','countries.id=a.business_country','Left Outer');
		$this->db->join('states','states.id=a.business_state','Left Outer');
		$this->db->join('cities','cities.id=a.business_city','Left Outer');
		$result=$this->db->select('a.*,countries.name as country_name,states.name as state_name,cities.name as city_name')->get_where('product_business_details a',array('a.product_id'=>$id))->row_array();
		return $result;
	}

	function getBankDetails($id){
		$this->db->join('business_types','business_types.id=a.business_type','Left Outer');
		$this->db->join('address_proof','address_proof.id=a.address_proof','Left Outer');
		$this->db->join('countries','countries.id=a.country','Left Outer');
		$this->db->join('states','states.id=a.state','Left Outer');
		$this->db->join('cities','cities.id=a.city','Left Outer');
		$result=$this->db->select('a.*,countries.name as country_name,states.name as state_name,cities.name as city_name,address_proof.name as address_proof_name,business_types.name as business_type_name')->get_where('product_bank_details a',array('a.product_id'=>$id))->row_array();
		return $result;
	}

	function updateData($tbl_name,$data,$where){
		$this->db->update($tbl_name,$data,$where);
		return true;
	}

	function delete($id){
		$this->db->delete('product',array('product_id'=>$id));
		$this->db->delete('manage_password',array('user_id'=>$id,'user_type'=>'product'));
		return true;
	}

	function updatePassword($data,$id){
		$check=$this->db->select('user_id,last_password')->get_where('manage_password',array('user_id'=>$id,'user_type'=>'product'))->row_array();
		if(count($check) <= 0){
			$data['user_type']='product';
			$data['user_id']=$id;
			$data['last_password']=$data['password'];
			$this->db->insert('manage_password',$data);
		}else{
			$data['last_password']=$check['last_password'];
			$this->db->update('manage_password',$data,array('user_id'=>$id,'user_type'=>'product'));
		}
		return true;
	}

	function getCountryCityStateList($tbl_name,$check=array()){
		if(count($check) >0){
			$this->db->where($check);
		}
		$result=$this->db->get($tbl_name)->result_array();
		return $result;
	}

	function checkEmail($email,$id=0){
		if($id > 0){
			$this->db->where('product_id !=',$id);
		}
		$result=$this->db->select('product_id')->get_where('product',array('email'=>$email))->num_rows();
		return $result;
	}

	function checkPhone($phone,$id=0){
		if($id > 0){
			$this->db->where('product_id !=',$id);
		}
		$result=$this->db->select('product_id')->get_where('product',array('phone'=>$phone))->num_rows();
		return $result;
	}

	function checkComapnyPhone($compnay_phone,$id=0){
		if($id > 0){
			$this->db->where('product_id !=',$id);
		}
		$result=$this->db->select('product_id')->get_where('product',array('compnay_phone'=>$compnay_phone))->num_rows();
		return $result;
	}

	function checkUsername($username,$id=0){
		if($id > 0){
			$this->db->where('product_id !=',$id);
		}
		$result=$this->db->select('product_id')->get_where('product',array('username'=>$username))->num_rows();
		return $result;
	}

	function getMerchantProductDetails($product_id){
	    $product_query=$this->db->query("select prod.*,mrch.email,mrcb.business_name from product as prod left join merchants as mrch on mrch.merchants_id=prod.created_by left join merchants_business_details as mrcb on mrcb.merchants_id=prod.created_by where prod.product_id=$product_id");
        if($product_query->num_rows() >0){
            return $product_query->row_array();
        }
        return false;
	}


}

?>