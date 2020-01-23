<?php
class ModelUser extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function getMyDtl($member_id){
    	$result=$this->db->get_where('member',array('id'=>$member_id))->row_array();
    	return $result;
    }

    function updateProfileImage($member_id,$data){
		$this->db->where('id',$member_id);
		$this->db->update('member',$data);
		return true;
	}

	function checkOldPassword($member_id,$data){
		return $this->db->select('*')->where(array('id'=>$member_id,'password'=>md5($data['old_password'])))->get('member')->num_rows();
	}

	function updatePassword($data,$member_id){
		$updatePassword = array(
			'password'=>md5($data['cfm_new_password'])
		);
		$this->db->where('id',$member_id);
		$this->db->update('member',$updatePassword);
		return true;
	}

	function updateAccount($member_id,$data){
		if(isset($data['first_name'])){
			$this->nsession->set_userdata('member_session_name', $data['first_name']);
		}
		$this->db->where('id',$member_id);
		$this->db->update('member',$data);
		return true;
	}

	function getCountryCityStateList($tbl_name,$check=array()){
		if(count($check) >0){
			$this->db->where($check);
		}
		$result=$this->db->get($tbl_name)->result_array();
		return $result;
	}

	function getMyBookingDtl(&$config,&$start,&$param,$member_id){
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
			$sortType    	= $this->nsession->get_param('ADMIN_OWNER','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_OWNER','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_OWNER','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_OWNER','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_OWNER','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_OWNER','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_OWNER','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_OWNER','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_OWNER','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_OWNER','searchDisplay',10);
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
		
		//==============================================================
		$this->db->where('car_book.status !=',0);
		$this->db->where('car_book.member_id',$member_id);
		$this->db->select('COUNT(book_id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->select('car_book.*');
		
		$recordSet = $this->db->get('car_book'); 
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
			$this->db->select('car_book.*,car.name as car_name');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		//$this->db->order_by($sortField,$sortType);
		$this->db->where('car_book.status !=',0);
		$this->db->where('car_book.member_id',$member_id);	
		$this->db->join('car','car.car_id=car_book.car_id','Left Outer');	
		$this->db->order_by('car_book.book_id','desc');
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('car_book');
		
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

	function getMyBookingRDtl(&$config,&$start,&$param,$member_id){
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
			$sortType    	= $this->nsession->get_param('ADMIN_OWNER','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_OWNER','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_OWNER','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_OWNER','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_OWNER','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_OWNER','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_OWNER','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_OWNER','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_OWNER','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_OWNER','searchDisplay',10);
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
		
		//==============================================================
		$this->db->where('car_book.car_return',1);
		$this->db->where('car_book.status !=',0);
		$this->db->where('car_book.member_id',$member_id);
		$this->db->select('COUNT(book_id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->select('car_book.*');
		
		$recordSet = $this->db->get('car_book'); 
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
			$this->db->select('car_book.*,car.name as car_name');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		//$this->db->order_by($sortField,$sortType);
		$this->db->where('car_book.car_return',1);	
		$this->db->where('car_book.status !=',0);
		$this->db->where('car_book.member_id',$member_id);	
		$this->db->join('car','car.car_id=car_book.car_id','Left Outer');	
		$this->db->order_by('car_book.book_id','desc');
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('car_book');
		
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

	function getInvoice($id){
		$this->db->select('car_book.*,member.address as member_address,member.zipcode as member_zipcode,member.first_name as member_first_name,member.last_name as member_last_name,member.email as member_email,car.name as car_name,countries.name as book_country_name,m_countries.name as country_name,states.name as state_name,cities.name as city_name,car_book_payment.*');
		$this->db->from('car_book');
		$this->db->join('member','member.id=car_book.member_id','Left Outer');
		$this->db->join('countries m_countries','m_countries.id=member.country','Left Outer');
		$this->db->join('countries','countries.id=car_book.country','Left Outer');
		$this->db->join('cities','cities.id=member.city','Left Outer');
		$this->db->join('states','states.id=member.state','Left Outer');
		$this->db->join('car','car.car_id=car_book.car_id','Left Outer');
		$this->db->join('car_book_payment','car_book_payment.book_id=car_book.book_id','Left Outer');
		$this->db->where('car_book.book_id',$id);
		$data = $this->db->get();
		return $data->row();
	}
}