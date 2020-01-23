<?php
class ModelNewsletter extends CI_Model {
	
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
			$sortType    	= $this->nsession->get_param('ADMIN_NEWSLETTER','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_NEWSLETTER','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_NEWSLETTER','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_NEWSLETTER','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_NEWSLETTER','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_NEWSLETTER','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_NEWSLETTER','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_NEWSLETTER','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_NEWSLETTER','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_NEWSLETTER','searchDisplay',20);
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
		
		$this->nsession->set_userdata('ADMIN_NEWSLETTER', $sessionDataArray);
		//==============================================================
		$this->db->select('COUNT(id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$recordSet = $this->db->get('subscriber');
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
			$this->db->select('subscriber.*');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		$this->db->order_by($sortField,$sortType);
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('subscriber');
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
		$insertData = array('email'=>$data['email_id'],'join_dt'=>$data['join_dt']);
		$this->db->insert('subscriber',$insertData);
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
	
	function editContent($id,$data)
	{
		$updateData = array(
			'email'=>$data['email_id']
		);
		$this->db->where('id',$id);
		$this->db->update('subscriber',$updateData);
		$affected_rows =$this->db->affected_rows();

		if($affected_rows)
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
		$this->db->delete('subscriber', array('id' => $id));
		return true;
	}
	function doSaveData($data){
	    $this->db->select('*');
	    $this->db->from('subscriber');
	    $this->db->where('email',$data['email_id']);
	    $returnSet = $this->db->get()->num_rows();
	    if($returnSet<=0){
            $this->db->insert('subscriber',$data);
            return true;
        }
    }
    function getTemplate(){
        $this->db->select('*');
        $this->db->from('mail_template');
        $this->db->where('is_active',1);
        $res=$this->db->get()->result_array();
        return $res;
    }
    function getNotificationData($template_no){
        $this->db->select('*');
        $this->db->from('mail_template');
        $this->db->where('id',$template_no);
        $res= $this->db->get()->row_array();
        return $res;

    }
    function getsingleData($member_id){
        $this->db->select('*');
        $this->db->from('subscriber');
        $this->db->where('id',$member_id);
        return $this->db->get()->row_array();
    }
}

?>