<?php

class ModelContact extends CI_Model {
	
	function __construct()
    {
        parent::__construct();

    }
	
	function getContactUsList(&$config,&$start,&$param)
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
			$sortType    	= $this->nsession->get_param('ADMIN_POSTEDADS','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_POSTEDADS','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_POSTEDADS','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_POSTEDADS','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_POSTEDADS','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_POSTEDADS','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_POSTEDADS','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_POSTEDADS','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_POSTEDADS','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_POSTEDADS','searchDisplay',20);
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
		
		$this->nsession->set_userdata('ADMIN_POSTEDADS', $sessionDataArray);
		//==============================================================
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$recordSet = $this->db->get('contact_us');
		
		$config['total_rows'] = 0;
		$config['per_page'] = $searchDisplay;
		if ($recordSet)
		{
			$config['total_rows'] = count($recordSet);
		}
		else
		{
			return false;
		}

		if($page > 0 && $page < $config['total_rows'])
			$start = $page;
			$this->db->select('contact_us.*');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
		//$this->db->order_by($sortField,$sortType);
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('contact_us');
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
		//$link="";
		
		//$this->db->select('icon');
		//$this->db->where('id',$id);
		//$link=$this->db->get('banner');
		//$link=$link->row_array();
		//$link=$link['icon'];
		//echo $link;
		$this->db->delete('contact_us', array('id' => $id)); 
		
		//return $link;
		return true;
	}

	function getSingle($id)
	{
		$sql = "SELECT * FROM contact_us WHERE id = ".$id;
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
		$this->db->update('contact_us', $update_data); 
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

}
?>	