<?php

class Modelfaq extends CI_Model {
	
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
		$this->db->select('COUNT(id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->select('faq.*');

		$recordSet = $this->db->get('faq'); 
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
			$this->db->select('faq.*');
			//$this->db->where('merchants.first_name !=','Administrator');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		//$this->db->order_by($sortField,$sortType);
		$this->db->order_by('faq.id','desc');
		$this->db->limit($config['per_page'],$start);

		$recordSet = $this->db->get('faq');
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
		$this->db->select('faq.*');
		//$this->db->join('countries','countries.id=merchants.country','Left Outer');
		//$this->db->join('states','states.id=merchants.state','Left Outer');
		//$this->db->join('cities','cities.id=merchants.city','Left Outer');
		$result=$this->db->get_where('faq',array('faq.id'=>$id));
		return $result->row_array();
    }
    
    function editContent($data,$id)
	{		
		$this->db->where('id', $id);
		$this->db->update('faq', $data); 
		return true;
    }
    
    function addContent($data)
	{
		$this->db->insert('faq',$data);
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
    
    function activate($id)
	{	
		$sql = "UPDATE faq SET is_active = '1' WHERE id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}
	function inactive($id)
	{
		$sql = "UPDATE faq SET is_active = '0' WHERE id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
    }
    
    function delete($id){
		$this->db->delete('faq',array('id'=>$id));
		//$this->db->delete('manage_password',array('user_id'=>$id,'user_type'=>'merchants'));
		return true;
	}
	
}
?>