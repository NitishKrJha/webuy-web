<?php

class ModelBooking extends CI_Model {
	
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
			$searchDisplay  = $this->nsession->get_param('ADMIN_OWNER','searchDisplay',10);
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
		
		//==============================================================
		$this->db->select('COUNT(book_id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->join('member','member.id=car_book.member_id','Left Outer');
		$this->db->join('car','car.car_id=car_book.car_id','Left Outer');	
		$recordSet = $this->db->get('car_book'); 
		//echo $this->db->last_query(); die();
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
			$this->db->select('car_book.*,car.name as car_name,member.first_name as customer_first_name,member.last_name as customer_last_name');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		//$this->db->order_by($sortField,$sortType);
		$this->db->join('member','member.id=car_book.member_id','Left Outer');
		$this->db->join('car','car.car_id=car_book.car_id','Left Outer');	
		$this->db->order_by('car_book.book_id','desc');
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('car_book');
		
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
	
	function activate($id)
	{
		$sql = "UPDATE car_book SET status = '2' WHERE book_id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}

	function inactive($id)
	{
		$sql = "UPDATE car_book SET status = '1' WHERE book_id = ".$id."";	
		$recordSet = $this->db->query($sql);
		
		if (!$recordSet )
		{
			return false;
		}
	}

	function delete($id){
		$this->db->delete('member',array('id'=>$id));
		return true;
	}

	function updatePassword($data,$id){
		$this->db->update('member',$data,array('id'=>$id));
		return true;
	}

	function getsingle_empdata($id){
		$this->db->select('car_book.*,member.address as member_address,member.zipcode as member_zipcode,member.first_name as member_first_name,member.last_name as member_last_name,member.email as member_email,car.name as car_name,countries.name as book_country_name,m_countries.name as country_name,states.name as state_name,cities.name as city_name,car_book_payment.*');
		$this->db->from('car_book');
		$this->db->join('member','member.id=car_book.member_id','Left Outer');
		$this->db->join('countries m_countries','m_countries.id=member.country','Left Outer');
		$this->db->join('countries','countries.id=car_book.country','Left Outer');
		$this->db->join('cities','cities.id=member.city','Left Outer');
		$this->db->join('states','states.id=member.state','Left Outer');
		$this->db->join('car','car.car_id=car_book.car_id','Left Outer');
		$this->db->join('car_book_payment','car_book_payment.book_id=car_book.book_id','Left Outer');
		$this->db->where('car_book.book_id',$id);
		$data = $this->db->get();
		return $data->row();
		
	}

	function updateBooking($data,$book_id,$car_book,$extra_reason){
		$result=$this->db->update('car_book_payment',$data,array('book_id'=>$book_id));
		if($result > 0){
			$this->db->update('car_book',array('car_return'=>$car_book,'extra_reason'=>$extra_reason),array('book_id'=>$book_id));
		}
		return $result;
	}
}

?>