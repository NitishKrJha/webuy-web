<?php
class ModelReveiw extends CI_Model {
	
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
			$sortField   	= $this->nsession->get_param('ADMIN_NEWSLETTER','sortField','rating_id');
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
		$this->db->select('COUNT(rating_id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$recordSet = $this->db->get('product_rating');
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
			$this->db->select('product_rating.*');
			$this->db->select('product.title product_name');
			$this->db->select('product_images.path product_image');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			$this->db->from('product_rating');
			$this->db->join('product','product.product_id=product_rating.product_id','left');
			$this->db->join('product_images','product_images.product_id=product_rating.product_id','left');
			$this->db->group_by('product_rating.rating_id');
		$this->db->order_by('product_rating.rating_id',$sortType);
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get();
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
		$insertData = array('email_id'=>$data['email_id'],'name'=>$data['name']);
		$this->db->insert('newsletter',$insertData);
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
			'email_id'=>$data['email_id'],
            'name'=>$data['name']
		);
		$this->db->where('id',$id);
		$this->db->update('newsletter',$updateData);
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
		$this->db->delete('newsletter', array('id' => $id));
		return true;
	}
	function doSaveData($data){
	    $this->db->select('*');
	    $this->db->from('newsletter');
	    $this->db->where('email_id',$data['email_id']);
	    $returnSet = $this->db->get()->num_rows();
	    if($returnSet<=0){
            $this->db->insert('newsletter',$data);
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
        $this->db->from('newsletter');
        $this->db->where('id',$member_id);
        return $this->db->get()->row_array();
    }
    function activate($id){
    	$this->db->where('rating_id',$id);
    	$this->db->update('product_rating',array('is_active'=>1));
    	return true;
    }
    function inactivate($id){
    	$this->db->where('rating_id',$id);
    	$this->db->update('product_rating',array('is_active'=>0));
    	return true;
    }
}

?>