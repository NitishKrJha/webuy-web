<?php
class ModelCar extends CI_Model {

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
		//pr($param);
		if($isSession == 0)
		{
			$sortType    	= $this->nsession->get_param('ADMIN_car','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_car','sortField','car_id');
			$searchField 	= $this->nsession->get_param('ADMIN_car','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_car','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_car','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_car','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_car','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_car','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_car','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_car','searchDisplay','20');
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
		
		$this->nsession->set_userdata('ADMIN_car', $sessionDataArray);
		//==============================================================
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->select('COUNT(car_id) as TotalrecordCount');
		$recordSet = $this->db->get('car');
		
		$config['total_rows'] = 0;
		//echo $searchDisplay; die();
		$config['per_page'] = $searchDisplay;
		//echo count($recordSet); die();
		if ($recordSet)
		{
			$row = $recordSet->row();
			$config['total_rows'] = $row->TotalrecordCount;
		}
		else
		{
			return false;
		}
		//pr($config['total_rows']);
		if($page > 0 && $page < $config['total_rows'])
			$start = $page;
			$this->db->select('car.*');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			//pr($config); die();
		/*$this->db->join('car_images','car_images.car_id=car.id','Left Outer');	*/
		//$this->db->order_by($sortField,$sortType);
		$this->db->order_by('car_id','desc');
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('car');
		$rs = false;
		//echo $this->db->last_query(); die();
		//pr($recordSet->result_array());
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
				$rs[$row['car_id']] = $recordSet->fields;
				$path=$this->db->select('path')->get_where('car_images',array('car_id'=>$row['car_id']))->row();
				if(isset($path->path)){
					$rs[$row['car_id']]['path'] = $path->path;
				}else{
					$rs[$row['car_id']]['path'] = '';
				}
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
		$recordSet = $this->db->where(array('car_id'=>(int)$id))->set('status',1)->update('car');
		if (!$recordSet )
		{
			return false;
		}
	}

	function inactive($id)
	{
		$recordSet = $this->db->where(array('car_id'=>(int)$id))->set('status',0)->update('car');
		
		if (!$recordSet )
		{
			return false;
		}
	}
	function featured($id)
	{
		$recordSet = $this->db->where(array('car_id'=>(int)$id))->set('featured',1)->update('car');
		if (!$recordSet )
		{
			return false;
		}
	}

	function unfeatured($id)
	{
		$recordSet = $this->db->where(array('car_id'=>(int)$id))->set('featured',0)->update('car');
		//echo $this->db->last_query(); die();
		if (!$recordSet )
		{
			return false;
		}
	}
	function getsingle_empdata($id){
		$this->db->select('first_name,email,modification_for');
		$this->db->from('member');
		$this->db->where('id',$id);
		$data = $this->db->get();
		return $data->row();
		
	}
	function getsingle_quartersdata($id){
		$result=$this->db->select('a.Car_name,a.id,b.first_name,b.last_name')->join('member b','b.member_id=a.member_id','Left Outer')->get_where('car a',array('car_id'=>$id))->row();
		return $result;
		
	}
	function getSingle($id){
		$query= $this->db->where(array('car_id' => (int)$id))->get('car')->row_array();
		if(isset($query['car_id'])){
			$query['path']=$this->db->select('path')->get_where('car_images',array('car_id'=>$query['car_id']))->result_array();
		}

		return $query;
	}
	function getCategory(){
		$this->db->select('*');
		$this->db->from('ad_category');
		$this->db->where(array('level'=>0,'parent'=>0));
		return $this->db->get()->result_array();
	}
	
	function getAmenitiesData(){
		$this->db->order_by('sort_num','asc');
		return $this->db->select('*')->where(array('is_active'=>1))->get('amenities')->result();
	}
	function getFeaturesData(){
		$this->db->order_by('sort_num','asc');
		return $this->db->select('*')->where(array('is_active'=>1))->get('features')->result();
	}
	
	function updateData($data,$check){
		if(isset($data['video_path'])){
			$list=$this->db->select('video_path')->get_where('car',$check)->row_array();
			if(isset($list['video_path'])){
				$src=file_upload_absolute_path().'car_video/'.$list['video_path'];
				if (file_exists($src)){
					unlink($src);
				}
			}	
		}
		$query=$this->db->update('car',$data,$check);
		return $query;
	}

	function insertData($data){
		$query=$this->db->insert('car',$data);
		if($query > 0){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}

	function getCarData($id=0){

		$result=$this->db->get_where('car',array('car_id'=>$id))->row();
		return $result;
	}

	function saveCarImage($last_id,$car_image){
		if(count($car_image)>0){
			foreach($car_image['upload_img'] as $Car_img){
				 $insertImgData = array(
					 'car_id'=>$last_id,
					 'path'=>$Car_img,
					 'created_date'=>date('Y-m-d H:i:s')
					);
				$this->db->insert('car_images',$insertImgData);
			}
		}
		return true;
	}

	function getCarImageData($id){
		$query=$this->db->get_where('car_images',array('car_id'=>$id))->result();
		return $query;
	}

	function deleteCarData($id){
		$this->db->delete('car',array('car_id'=>$id));
		$this->deleteImage($id);
		return true;
	}

	function deleteImage($id){
		$image=$this->db->select('path')->get_where('car_images',array('id'=>$id))->row();
		if(count($image) > 0){
			$src=file_upload_absolute_path().'car_image/'.$image->path;
			//echo $src;
			if (file_exists($src)){
				unlink($src);
				
				//echo $this->db->last_query(); die();
			}
			$this->db->delete('car_images',array('id'=>$id));
			//echo 123; die();
		}
		return true;
	}

	function insertReview($data){
		$query=$this->db->insert('Car_comment',$data);
		$this->updateCarRating($data['Car_id']);
		return $query;
	}

	function updateCarRating($Car_id){
		$data=array();
		$data['avg_rating']=round($this->getReviewRating($Car_id));
		$this->db->update('car',$data,array('id'=>$Car_id));
		return true;
	}

	function getCarReviewData($Car_id,$total,$limit){
		$query=$this->db->select('a.*,b.first_name,b.last_name,b.picture')->join('member b','b.id=a.member_id','Left Outer')->limit ($total, $limit )->get_where('Car_comment a',array('a.Car_id'=>$Car_id))->result();
		return $query;
	}

	function getReviewRating($Car_id){
		$query=$this->db->select('AVG(rating) as total')->get_where('Car_comment',array('Car_id'=>$Car_id))->row();
		if(isset($query->total)){
			return round($query->total);
		}else{
			return 0;
		}
	}

	function getCountReview($Car_id){
		$query=$this->db->select('id')->get_where('Car_comment',array('Car_id'=>$Car_id))->num_rows();
		return $query;
	}

	function getAllAdvertise(){
		$query=$this->db->get_where('advertise',array('status'=>1))->result_array();
		return $query;
	}

	function getCarSavedData(){
		if($this->nsession->userdata('member_session_id')){
			$member_session_id=$this->nsession->userdata('member_session_id');
			$query=$this->db->select('Car_id')->get_where('member_save_quarters',array('member_id'=>$member_session_id))->result_array();
			if(count($query) > 0){
				$ndata=array();
				foreach ($query as $key => $value) {
					$ndata[]=$value['Car_id'];
				}
				return $ndata;
			}else{
				return array();
			}
		}else{
			return array();
		}
		
	}

	public function getAllOwner(){
		$result=$this->db->get_where('member',array('member_type'=>1))->result();
		return $result;
	}

	function getCountryCityStateList($tbl_name,$check=array()){
		if(count($check) >0){
			$this->db->where($check);
		}
		$result=$this->db->get($tbl_name)->result_array();
		return $result;
	}

	public function getAllUser(){
		$result=$this->db->get('member')->result();
		return $result;
	}

	function addCampus($university,$distance_to_campus,$id){
		if(count($university) > 0){
			$this->db->delete('distance_to_campus',array('Car_id'=>$id));
			foreach ($university as $key => $value) {
				$ndata=array();
				$ndata['Car_id']=$id;
				$ndata['university_id']=$value;
				$ndata['value']=number_format((float)($distance_to_campus[$key]), 1, '.', '');
				$this->db->insert('distance_to_campus',$ndata);
			}
		}
		return true;
	}
}
