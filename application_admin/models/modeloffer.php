<?php

class ModelOffer extends CI_Model {
	
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
		
		if($isSession == 0)
		{
			$sortType    	= $this->nsession->get_param('ADMIN_coupon','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_coupon','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_coupon','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_coupon','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_coupon','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_coupon','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_coupon','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_coupon','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_coupon','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_coupon','searchDisplay',20);
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
		
		$this->nsession->set_userdata('ADMIN_offer', $sessionDataArray);
		//==============================================================
		
		//$this->db->select('various_offer.*');
		
		//$recordSet = $this->db->get('various_offer'); 
		//
		//echo $this->db->last_query(); die();
		

		$this->db->select('COUNT(offer_id) as TotalrecordCount');
		//--------------------------------------------------
		$this->db->select('various_offer.*,category_level_1.name');
		$this->db->from('various_offer');
		$this->db->join('category_level_1','category_level_1.id=various_offer.category_level_1');
		//-------------------------------------------------------
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		//echo $this->db->last_query(); die();
		$recordSet=$this->db->get();

		$config['total_rows'] = 0;
		$config['per_page'] = $searchDisplay;
		if ($recordSet>0)
		{
			$row = $recordSet->row();
			$config['total_rows'] = $row->TotalrecordCount;
		}
		else
		{
			return false;
		}


		//--------------------------------------------------------
		if($page > 0 && $page < $config['total_rows'] )
			$start = $page;
			//$this->db->select('merchants.*');
		$this->db->select('various_offer.*,category_level_1.name');
		$this->db->from('various_offer');
		$this->db->join('category_level_1','category_level_1.id=various_offer.category_level_1');

			//$this->db->where('merchants.first_name !=','Administrator');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		//$this->db->order_by($sortField,$sortType);
		$this->db->order_by('various_offer.offer_id','desc');
		$this->db->limit($config['per_page'],$start);

		$recordSet = $this->db->get();
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

	function getCategoryType(){
		$result=$this->db->get_where('category_level_1')->result_array();
		//echo $this->db->last_query();
		//die();
		return $result;
	}
	
	function addContent($data)
	{
		$this->db->insert('various_offer',$data);
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

	{	$memberMoreData = array(
                'offer_name'				=>$data['offer_name'],
				'category_level_1'				=>$data['category_level_1'],
				'offer_type'				=>$data['offer_type'],
				'offer_amount'				=>$data['offer_amount'],
				'short_description'				=>$data['short_description'],
				'offer_limit'				=>$data['offer_limit'],
				'modified_date'				=>$data['modified_date']

				
					);	
		$this->db->where('offer_id', $id);
		$this->db->update('various_offer', $memberMoreData); 
		//echo $this->db->last_query(); die();
		return true;
	}
	
	function activate($id)
	{
		$sql = "UPDATE various_offer SET is_active = 1 WHERE offer_id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}

	function inactive($id)
	{
		$sql = "UPDATE various_offer SET is_active = 0 WHERE offer_id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}
	function getsingle_empdata($id){
		$this->db->select('staff.*,countries.name as country_name,states.name as state_name,cities.name as city_name');
		$this->db->join('countries','countries.id=staff.country','Left Outer');
		$this->db->join('states','states.id=staff.state','Left Outer');
		$this->db->join('cities','cities.id=staff.city','Left Outer');
		$this->db->from('staff');
		$this->db->where('staff.staff_id',$id);
		$data = $this->db->get();
		return $data->row();
		
	}
	function getSingle($id){
		//$this->db->select('staff.*,countries.name as country_name,states.name as state_name,cities.name as city_name');
		//$this->db->join('countries','countries.id=staff.country','Left Outer');
		//$this->db->join('states','states.id=staff.state','Left Outer');
		//$this->db->join('cities','cities.id=staff.city','Left Outer');
		//$result=$this->db->get('various_offer');
		//$sql = "select * from various_offer where offer_id = $id";

		$this->db->select('various_offer.*,category_level_1.name');
		$this->db->from('various_offer');
		$this->db->where('offer_id',$id);
		$this->db->join('category_level_1','category_level_1.id=various_offer.category_level_1');

		//$result = $this->db->query($sql);
		$result=$this->db->get();
		return $result->row_array();
	}


	function delete($id){
		$this->db->delete('staff',array('staff_id'=>$id));
		$this->db->delete('manage_password',array('user_id'=>$id,'user_type'=>'staff'));
		return true;

	}

	function updatePassword($data,$id){
		$check=$this->db->select('user_id,last_password')->get_where('manage_password',array('user_id'=>$id,'user_type'=>'staff'))->row_array();
		if(count($check) <= 0){
			$data['user_type']='staff';
			$data['user_id']=$id;
			$data['last_password']=$data['password'];
			$this->db->insert('manage_password',$data);
		}else{
			$data['last_password']=$check['last_password'];
			$this->db->update('manage_password',$data,array('user_id'=>$id,'user_type'=>'staff'));
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
			$this->db->where('staff_id !=',$id);
		}
		$result=$this->db->select('staff_id')->get_where('staff',array('email'=>$email))->num_rows();
		return $result;
	}

	function checkPhone($phone,$id=0){
		if($id > 0){
			$this->db->where('staff_id !=',$id);
		}
		$result=$this->db->select('staff_id')->get_where('staff',array('phone'=>$phone))->num_rows();
		return $result;
	}

	function checkUsername($username,$id=0){
		if($id > 0){
			$this->db->where('staff_id !=',$id);
		}
		$result=$this->db->select('staff_id')->get_where('staff',array('username'=>$username))->num_rows();
		return $result;
	}
}

?>