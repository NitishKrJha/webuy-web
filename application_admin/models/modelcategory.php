<?php

class ModelCategory extends CI_Model {
	
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
			$sortType    	= $this->nsession->get_param('ADMIN_category','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_category','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_category','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_category','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_category','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_category','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_category','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_category','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_category','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_category','searchDisplay',20);
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
		$this->db->select('COUNT(category_level_1.id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->select('category_level_1.*');

		$recordSet = $this->db->get('category_level_1'); 
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
			$this->db->select('category_level_1.*');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		$this->db->order_by('sort_num','asc');
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('category_level_1');
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

	function getListlevel2(&$config,&$start,&$param)
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
			$sortType    	= $this->nsession->get_param('ADMIN_category','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_category','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_category','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_category','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_category','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_category','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_category','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_category','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_category','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_category','searchDisplay',20);
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
		$this->db->select('COUNT(category_level_2.id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->select('category_level_2.*');
		$this->db->join('category_level_1','category_level_1.id=category_level_2.level1','Left Outer');
		$recordSet = $this->db->get('category_level_2'); 
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
			$this->db->select('category_level_2.*,category_level_1.name as level1_name');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
		$this->db->join('category_level_1','category_level_1.id=category_level_2.level1','Left Outer');
		$this->db->order_by('sort_num','asc');
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('category_level_2');
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

	function getListlevel3(&$config,&$start,&$param)
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
			$sortType    	= $this->nsession->get_param('ADMIN_category','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_category','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_category','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_category','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_category','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_category','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_category','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_category','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_category','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_category','searchDisplay',20);
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
		$this->db->select('COUNT(category_level_3.id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->select('category_level_3.*');
		$this->db->join('category_level_2','category_level_2.id=category_level_3.level2','Left Outer');
		$recordSet = $this->db->get('category_level_3'); 
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
			$this->db->select('category_level_3.*,category_level_2.name as level2_name');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
		$this->db->join('category_level_2','category_level_2.id=category_level_3.level2','Left Outer');
		$this->db->order_by('sort_num','asc');
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('category_level_3');
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

	function getListlevel4(&$config,&$start,&$param)
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
			$sortType    	= $this->nsession->get_param('ADMIN_category','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_category','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_category','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_category','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_category','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_category','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_category','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_category','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_category','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_category','searchDisplay',20);
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
		$this->db->select('COUNT(category_level_4.id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->select('category_level_4.*');
		$this->db->join('category_level_3','category_level_3.id=category_level_4.level3','Left Outer');
		$recordSet = $this->db->get('category_level_4'); 
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
			$this->db->select('category_level_4.*,category_level_3.name as level3_name');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
		$this->db->join('category_level_3','category_level_3.id=category_level_4.level3','Left Outer');
		$this->db->order_by('sort_num','asc');
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('category_level_4');
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
	
	function addContent($data,$tbl_name)
	{
		$max_num=$this->db->select('MAX(sort_num) as max_num')->get_where($tbl_name)->row()->max_num;
		$data['sort_num']=(int)$max_num + 1;

		$this->db->insert($tbl_name,$data);
		$last_insert_id = $this->db->insert_id(); 
		
		if($last_insert_id)
		{
			return $last_insert_id;
		}
		else
		{
			return false;
		}
	}
	
	function editContent($id,$data,$tbl_name)
	{
		$this->db->where('id', $id);
		$this->db->update($tbl_name, $data); 
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
	
	function delete($id,$tbl_name)
	{
		$this->db->delete($tbl_name, array('id' => $id)); 
		return true;
	}

    function getSingle($id,$tbl_name)
	{
		$sql = "SELECT * FROM $tbl_name WHERE id = ".$id;
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
	function activate($id,$tbl_name)
	{	
		$sql = "UPDATE $tbl_name SET is_active = '1' WHERE id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}
	function inactive($id,$tbl_name)
	{
		$sql = "UPDATE $tbl_name SET is_active = '0' WHERE id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}

	function changeorder($val,$id,$tbl_name){
		$result=array();
		$result['intrchng_id']='0';
		$result['intrchng_val']='0';
		$getFaq=$this->db->select('id,sort_num')->get_where($tbl_name,array('id'=>$id))->row_array();
		if(count($getFaq) > 0){
			$prev_val=$getFaq['sort_num'];
			$result['intrchng_val']=$prev_val;
			$getFaqPre=$this->db->select('id,sort_num')->get_where($tbl_name,array('sort_num'=>$val))->row_array();
			if(count($getFaqPre) > 0){
				$intrchng_id=$getFaqPre['id'];
				$result['intrchng_id']=$intrchng_id;
				$this->db->update('category_level_1',array('sort_num'=>$prev_val),array('id'=>$intrchng_id));
				
			}
			

			$this->db->update($tbl_name,array('sort_num'=>$val),array('id'=>$id));

		}
		
		return $result;

	}

	function getTblData($tbl_name,$where=array()){
		return $this->db->get_where($tbl_name,$where)->result_array();
	}
	
}

?>