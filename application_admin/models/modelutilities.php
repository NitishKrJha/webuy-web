<?php

class ModelUtilities extends CI_Model {
	
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
			$sortType    	= $this->nsession->get_param('ADMIN_property_utilities','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_property_utilities','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_property_utilities','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_property_utilities','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_property_utilities','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_property_utilities','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_property_utilities','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_property_utilities','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_property_utilities','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_property_utilities','searchDisplay',20);
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
		
		$this->nsession->set_userdata('ADMIN_MANAGEPOSITION', $sessionDataArray);
		//==============================================================
		$this->db->select('COUNT(id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		
		$this->db->select('property_utilities.*');

		$recordSet = $this->db->get('property_utilities'); 
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
			$this->db->select('property_utilities.*');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		$this->db->order_by('sort_num','asc');
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('property_utilities');
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
		$this->db->insert('property_utilities',$data);
		$last_insert_id = $this->db->insert_id(); 
		//echo $this->db->last_query(); die();
		if($last_insert_id)
		{
			return $last_insert_id;
		}
		else
		{
			return false;
		}
	}
	
	function editContent($id,$data)
	{
		$this->db->where('id', $id);
		$this->db->update('property_utilities', $data); 
		$affected_rows =$this->db->affected_rows();
		
		if($result)
		{
			return $affected_rows;
		}
		else
		{
			return false;
		}
	}
	
	function delete($id)
	{
		$this->db->delete('property_utilities', array('id' => $id)); 
		return true;
	}

    function getSingle($id)
	{
		$sql = "SELECT * FROM property_utilities WHERE id = ".$id;
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
		$sql = "UPDATE property_utilities SET is_active = '1' WHERE id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}
	function inactive($id)
	{
		$sql = "UPDATE property_utilities SET is_active = '0' WHERE id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}

	function changeorder($val,$id){
		$result=array();
		$result['intrchng_id']='0';
		$result['intrchng_val']='0';
		$getproperty_utilities=$this->db->select('id,sort_num')->get_where('property_utilities',array('id'=>$id))->row_array();
		if(count($getproperty_utilities) > 0){
			$prev_val=$getproperty_utilities['sort_num'];
			$result['intrchng_val']=$prev_val;
			$getproperty_utilitiesPre=$this->db->select('id,sort_num')->get_where('property_utilities',array('sort_num'=>$val))->row_array();
			if(count($getproperty_utilitiesPre) > 0){
				$intrchng_id=$getproperty_utilitiesPre['id'];
				$result['intrchng_id']=$intrchng_id;
				$this->db->update('property_utilities',array('sort_num'=>$prev_val),array('id'=>$intrchng_id));
				
			}
			

			$this->db->update('property_utilities',array('sort_num'=>$val),array('id'=>$id));

		}
		
		return $result;

	}
	
}

?>