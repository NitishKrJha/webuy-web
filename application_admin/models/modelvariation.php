<?php

class ModelVariation extends CI_Model {
	
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
			$sortType    	= $this->nsession->get_param('ADMIN_variation','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_variation','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_variation','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_variation','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_variation','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_variation','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_variation','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_variation','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_variation','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_variation','searchDisplay',20);
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
		$this->db->select('variation_attribute.*');

		$recordSet = $this->db->get('variation_attribute'); 
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
			$this->db->select('variation_attribute.*');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('variation_attribute');
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

	function getListOfTable($tbl_name,$where,$listedType=''){
		$result=$this->db->get_where($tbl_name,$where);
		if($listedType=='single'){
			return $result->row_array();
		}else{
			return $result->result_array();
		}
	}
	
	function addContent($data,$valu)
	{
		$this->db->insert('variation_attribute',$data);
		$last_insert_id = $this->db->insert_id(); 
		//echo $this->db->last_query(); die();
		if($last_insert_id)
		{
			if(count($valu) > 0){
				foreach ($valu as $key => $val) {
					$ndata=array();
					$ndata['variation_id']=$last_insert_id;
					$ndata['name']=$val;
					$this->db->insert('variation_attribute_value',$ndata);
				}
			}
			return $last_insert_id;
		}
		else
		{
			return false;
		}
	}

	function getAllVaraValue($variation_id){
		return $this->db->get_where('variation_attribute_value',array('variation_id'=>$variation_id))->result_array();
	}
	
	function editContent($id,$data,$value)
	{
		//pr($value);
		$this->db->where('id', $id);
		$result=$this->db->update('variation_attribute', $data); 
		if($result)
		{
			if(count($value) > 0){
				$this->db->delete('variation_attribute_value',array('variation_id'=>$id));
				foreach ($value as $key => $val) {
					$ndata=array();
					$ndata['variation_id']=$id;
					$ndata['name']=$val;
					$this->db->insert('variation_attribute_value',$ndata);
					//echo $this->db->last_query();
					//die();
				}
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	function delete($id)
	{
		$this->db->delete('variation_attribute', array('id' => $id)); 
		return true;
	}

    function getSingle($id)
	{
		$sql = "SELECT * FROM variation_attribute WHERE id = ".$id;
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
		$sql = "UPDATE variation_attribute SET is_active = '1' WHERE id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}
	function inactive($id)
	{
		$sql = "UPDATE variation_attribute SET is_active = '0' WHERE id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}
	
}

?>