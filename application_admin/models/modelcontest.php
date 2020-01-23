<?php

class ModelContest extends CI_Model {
	
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
		//pr($this->nsession->get_param('ADMIN_ROOM'));
		
		if($isSession == 0)
		{
			$sortType    	= $this->nsession->get_param('ADMIN_ROOM','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_ROOM','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_ROOM','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_ROOM','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_ROOM','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_ROOM','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_ROOM','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_ROOM','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_ROOM','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_ROOM','searchDisplay',20);
		}
		//echo $sortField; die();
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
		
		$this->nsession->set_userdata('ADMIN_ROOM', $sessionDataArray);
		//==============================================================
		$this->db->select('COUNT(id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->select('room.*');
		$this->db->where('room.created_by','admin');

		$recordSet = $this->db->get('room'); 
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
		//echo $config['total_rows']; die();
		if($page > 0 && $page < $config['total_rows'])
			$start = $page;
			$this->db->where('room.created_by','admin');
			$this->db->select('room.*');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
		$this->db->order_by($sortField,$sortType);
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('room');
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


	function delete($id)
	{
		$check=$this->db->select('contestId')->get_where('joinContest',array('contestId'=>$id))->num_rows();
		if($check > 0){
			return false;
		}else{
			$this->db->delete('room', array('id' => $id)); 
			return true;	
		}
	}

	function getSingle($id)
	{
		$sql = "SELECT * FROM room WHERE id = ".$id;
		$recordSet = $this->db->query($sql);
		
		$rs = false;
		if ($recordSet->num_rows() > 0)
        {
           	$rs = array();
			$isEscapeArr = array('icon');
			foreach ($recordSet->result_array() as $row)
				{
					foreach($row as $key=>$val){
						if(!in_array($key,$isEscapeArr)){
							$recordSet->fields[$key] = outputEscapeString($val);
						}else{
							$recordSet->fields[$key] = outputEscapeString($val,'TEXTAREA');
						}
					}
					$rs[] = $recordSet->fields;
					
				}
        }

		return $rs;			
	}

	function editContact($id,$reply)
	{
		
		//echo "qwer";
		//echo $id;
		//echo $reply;

		$update_data= array('reply'=>$reply);
		$this->db->where('id', $id);
		$this->db->update('room', $update_data); 
		$affected_rows =$this->db->affected_rows();
		
		if($affected_rows)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function getAllMatches($dateTime,$season='',$status=''){
		// $main_url=SPORT_API_URL."getSchedule/".$dateTime;
		// if($season!=''){
		// 	$main_url =SPORT_API_URL."getSchedule/".$dateTime."/".urlencode($season);
		// }
		$main_url =SPORT_API_URL."getSchedule/".$dateTime."/".urlencode($season);
		if($status!=''){
			$main_url =SPORT_API_URL."getSchedule/".$dateTime."/".urlencode($season)."/".$status;
		}

		
		//echo $main_url; die();
		$result=$this->functions->httpGet($main_url);
		$allData=json_decode($result);
		$data=array();
		if(isset($allData->data)){
			$data=$allData->data;
		}
		return $data;
	}

	function getAllSeries($dateTime){
		$main_url=SPORT_API_URL."getSeries/".$dateTime;
		//echo $main_url; die();
		$result=$this->functions->httpGet($main_url);
		$allData=json_decode($result,true);
		$data=array();
		if(isset($allData['data'])){
			$data=$allData['data'];
		}
		return $data;
	}

	function getMatchDtl($matchID){
		return $this->db->select('*')->get_where('matchlist',array('key_name'=>$matchID))->row_array();
	}

	function insertData($tbl_name,$data){
		$result=$this->db->insert($tbl_name,$data);
		return $this->db->insert_id();
	}

	function updateData($tbl_name,$data,$check){
		$result=$this->db->update($tbl_name,$data,$check);
		return true;
	}

}
?>	