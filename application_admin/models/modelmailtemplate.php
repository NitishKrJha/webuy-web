<?php
class ModelMailtemplate extends CI_Model {
	
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
			$sortType    	= $this->nsession->get_param('ADMIN_Adsimage','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_Adsimage','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_Adsimage','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_Adsimage','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_Adsimage','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_Adsimage','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_Adsimage','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_Adsimage','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_Adsimage','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_Adsimage','searchDisplay',20);
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
		
		$this->nsession->set_userdata('ADMIN_Adsimage', $sessionDataArray);
		//==============================================================
		$this->db->select('COUNT(id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}

		$recordSet = $this->db->get('mail_template');
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
			$this->db->select('mail_template.*');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		$this->db->order_by($sortField,$sortType);
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('mail_template');
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
		
		$this->db->insert('mail_template',$data);
		$last_insert_id = $this->db->insert_id(); 
		
		if($last_insert_id)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function editContent($id,$data)
	{
		
		$this->db->where('id', $id);
		$this->db->update('mail_template', $data);
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
	
	function delete($id)
	{
		$this->db->delete('mail_template', array('id' => $id));
		return true;
	}

    function getSingle($id)
	{
		$sql = "SELECT * FROM mail_template WHERE id = ".$id;
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
	function activate($id)
	{	
		$sql = "UPDATE mail_template SET is_active = '1' WHERE id = ".$id."";
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}
	function inactive($id)
	{
		$sql = "UPDATE mail_template SET is_active = '0' WHERE id = ".$id."";
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}
	
}

?>