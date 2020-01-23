<?php

class ModelStaff extends CI_Model {
	
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
			$sortType    	= $this->nsession->get_param('ADMIN_OWNER','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_OWNER','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_OWNER','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_OWNER','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_OWNER','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_OWNER','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_OWNER','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_OWNER','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_OWNER','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_OWNER','searchDisplay',20);
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
		
		$this->nsession->set_userdata('ADMIN_OWNER', $sessionDataArray);
		//==============================================================
		$this->db->where('staff_id !=',1);
		$this->db->select('COUNT(staff_id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->select('staff.*');

		$recordSet = $this->db->get('staff'); 
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
			$this->db->select('staff.*');
			$this->db->where('staff.first_name !=','Administrator');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		//$this->db->order_by($sortField,$sortType);
		$this->db->order_by('staff.staff_id','desc');
		$this->db->limit($config['per_page'],$start);
		$this->db->where('staff_id !=',1);
		$recordSet = $this->db->get('staff');
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

	function getStaffType(){
		$result=$this->db->get_where('staff_type')->result_array();
		return $result;
	}
	
	function addContent($data)
	{
		$this->db->insert('staff',$data);
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
		$this->db->where('staff_id', $id);
		$this->db->update('staff', $data); 
		//echo $this->db->last_query(); die();
		return true;
	}
	
	function activate($id)
	{
		$sql = "UPDATE staff SET status = 1 WHERE staff_id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}

	function inactive($id)
	{
		$sql = "UPDATE staff SET status = 2 WHERE staff_id = ".$id."";	
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
		$this->db->select('staff.*,countries.name as country_name,states.name as state_name,cities.name as city_name');
		$this->db->join('countries','countries.id=staff.country','Left Outer');
		$this->db->join('states','states.id=staff.state','Left Outer');
		$this->db->join('cities','cities.id=staff.city','Left Outer');
		$result=$this->db->get_where('staff',array('staff.staff_id'=>$id));
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


	function editPermission($data,$id)

	{	$memberMoreData = array(
                
				'permission'				=>$data['permission']

				
					);	
		$this->db->where('staff_id', $id);
		$this->db->update('staff', $memberMoreData); 
		//echo $this->db->last_query(); die();
		return true;
	}
}

?>