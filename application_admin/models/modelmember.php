<?php
class ModelMember extends CI_Model {
	
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
			$sortType    	= $this->nsession->get_param('ADMIN_MEMBER','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_MEMBER','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_MEMBER','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_MEMBER','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_MEMBER','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_MEMBER','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_MEMBER','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_MEMBER','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_MEMBER','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_MEMBER','searchDisplay',20);
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
		
		$this->nsession->set_userdata('ADMIN_MEMBER', $sessionDataArray);
		//==============================================================
		$this->db->select('COUNT(member.id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		//$this->db->select('member.*');
		
        
		$recordSet = $this->db->get('member'); 
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
			$this->db->select('member.*');
			
			
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		$this->db->order_by($sortField,$sortType);
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('member');
		//echo $this->db->last_query();
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
	
	function addContent($data)
	{
		$this->db->where('email',$data['email']);
		$result=$this->db->get('member')->row_array();
		//echo count($result);exit;
		if(count($result) > 0){
			return false;
		}else{
			$memberArr = array(
				'name'=>$data['name'],
				'email'=>$data['email'],
				'phone_no'=>$data['phone_no'],
				'password'=>$data['password'],
				'address'=>$data['address'],
				'gender'=>$data['gender'],
				'dob'=>$data['dob'],
				'city'=>$data['city'],
				'zip'=>$data['zip'],
				'is_active'=>$data['status'],
				'created'=>$data['created']
			);
			$this->db->insert('member',$memberArr);
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
	}
	
	function editContent($data,$id)
	{		
		$memberData = array(
			'name'=>$data['name'],
			'email'=>$data['email'],
			'phone_no'=>$data['phone_no'],
			'address'=>$data['address'],
			'gender'=>$data['gender'],
			'dob'=>$data['dob'],
			'city'=>$data['city'],
			'zip'=>$data['zip'],
			'modified'=>$data['modified']
		);

		$this->db->where('id', $id);
		$this->db->update('member', $memberData); 
		return true;
	}

	function addImage($data1,$id)
	{		
		$memberData = array(
			'image'=>$data1['image']
		);

		$this->db->where('id', $id);
		$this->db->update('member', $memberData); 
		return true;
	}
	
	function activate($id)
	{
		$sql = "UPDATE member SET is_active = '1' WHERE id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}

	function inactive($id)
	{
		$sql = "UPDATE member SET is_active = '2' WHERE id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}
	function getsingle_empdata($id){
		$this->db->select('name,email');
		$this->db->from('member');
		$this->db->where('id',$id);
		$data = $this->db->get();
		return $data->row();
		
	}
	function getSingle($id){
		$sql = "SELECT
				  *
				FROM
				  member
				WHERE
				 id = ".$id."";
		$result = $this->db->query($sql);
		return $result->row_array();
	}
}

?>