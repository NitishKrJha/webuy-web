<?php
//error_reporting(E_ALL);
class Car extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'car';
		$this->load->model('ModelCar');
	}
	function index()
	{
		$this->functions->checkAdmin($this->controller.'/',true);
		
		$config['base_url'] 			= base_url($this->controller."/index/");
		
		$config['uri_segment']  		= 3;
		$config['num_links'] 			= 10;
		$config['page_query_string'] 	= false;
		$config['extra_params'] 		= ""; 
		$config['extra_params'] 		= "";
		
		$this->pagination->setAdminPaginationStyle($config);
		$start = 0;
		
		$data['controller'] = $this->controller;
		
		$param['sortType'] 			= $this->input->request('sortType','DESC');
		$param['sortField'] 		= $this->input->request('sortField','car_id');
		$param['searchField'] 		= $this->input->request('searchField','');
		$param['searchString'] 		= $this->input->request('searchString','');
		$param['searchText'] 		= $this->input->request('searchText','');
		$param['searchFromDate'] 	= $this->input->request('searchFromDate','');
		$param['searchToDate'] 		= $this->input->request('searchToDate','');
		$param['searchDisplay'] 	= $this->input->request('searchDisplay','20');
		$param['searchAlpha'] 		= $this->input->request('txt_alpha','');
		$param['searchMode'] 		= $this->input->request('search_mode','STRING');

		$data['recordset'] 		= $this->ModelCar->getList($config,$start,$param);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_POSTEDADS');
		$data['reload_link']      	= base_url($this->controller."/index/");
		$data['search_link']        = base_url($this->controller."/index/0/1/");
		$data['add_link']         	= base_url($this->controller."/add/0/0/");
		$data['edit_link']        	= base_url($this->controller."/add/{{ID}}/0");
		$data['activated_link']    	= base_url($this->controller."/activate/{{ID}}/0");
		$data['inacttived_Link']    = base_url($this->controller."/inactive/{{ID}}/0");
		$data['featured_link']    	= base_url($this->controller."/featured/{{ID}}/0");
		$data['unfeatured_Link']    = base_url($this->controller."/unfeatured/{{ID}}/0");
		$data['showall_link']     	= base_url($this->controller."/index/0/1");
		$data['total_rows']			= $config['total_rows'];
		
		$data['succmsg'] 	= $this->nsession->userdata('succmsg');
		$data['errmsg'] 	= $this->nsession->userdata('errmsg');
		
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		//pr($data['recordset']);
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'car/index';

		$element_data['menu'] = $data;
		$element_data['main'] = $data;
		
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	
	}
	
	//==========Initialize $data for Add =======================
	
	function addedit($id = 0)
	{
		$this->functions->checkAdmin($this->controller.'/');
		//if add or edit
		$startRecord  	= 0;
		$contentId 		= $this->uri->segment(3, 0); 
		$page 			= $this->uri->segment(4, 0); 
		
		if($page > 0)
			$startRecord = $page; 

		$page = $startRecord;
		
		$data['controller'] 		= $this->controller;
		$data['action'] 			= "Add";
		$data['params']['page'] 	= $page;
		$data['do_addedit_link']	= base_url($this->controller."/do_addedit/".$contentId."/".$page."/");
		$data['back_link']			= base_url($this->controller."/index/");
		
		if($contentId > 0)
		{
			$data['adminpage_id'] = $contentId;
			$data['action'] = "Edit";
			//=================prepare DATA ==================
			$rs = $this->ModelCar->getSingle($contentId);
			//pr($rs);
			//$row = $rs->fields;
			if(is_array($rs))
			{
				foreach($rs as $key => $val)
				{
					if(!is_numeric($key))
					{
						$data[$key] = $val;
					}
				}
			}
		}else{
			$data['action'] 	= "Add";
			$data['id']			= 0;
		}
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'owner/add_edit';
		$element_data['menu'] = $data;//array();
		$element_data['main'] = $data;
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
		
	}
	
	public function dlt_img($id){
		$this->ModelCar->deleteImage($id);
	}

	public function do_addedit($id){
		if($this->input->post('name')){
			//pr($_POST);
			$allimg=$this->douploadimages('images');
			//pr($allimg);
			//pr($_FILES);
			$data['name']=htmlentities($this->input->post('name'));
			$data['basic_info']=htmlentities($this->input->post('basic_info'));
			$data['fuel_charge']=$this->input->post('fuel_charge');
			$data['rental_day']=$this->input->post('rental_day');
			$data['rental_week']=$this->input->post('rental_week');
			$data['rental_month']=$this->input->post('rental_month');
			$data['availability']=$this->input->post('availability');
			$Car_ammenties=$this->input->post('amenities');
			$data['amenities']=implode(',', $Car_ammenties);
			$Car_features=$this->input->post('features');
			$data['features']=implode(',', $Car_features);

			
			//echo $file_name; die();
			if($_FILES['video']['size'] > 0){
				$file_name = $_FILES['video']['name'];
				$new_file_name = time().$file_name;
				$config['upload_path'] 	 = file_upload_absolute_path().'car_video/';
				$config['allowed_types'] = '*';
				$config['file_name']     = $new_file_name;  
				$this->upload->initialize($config);
				if($this->upload->do_upload('video')) {
					//echo $this->upload->display_errors();
					//$error = array('error' => $this->upload->display_errors()); 
					$upload_data = $this->upload->data(); 
					$data['video_path']=$upload_data['file_name'];
				}
			}
			

			if($id > 0){
				$check['car_id']=$id;
				
				$data['modified_date']=date('Y-m-d h:m:s');
				$result=$this->ModelCar->updateData($data,$check);
				$this->ModelCar->saveCarImage($check['car_id'],$allimg);
			}else{
				
				$data['created_date']=date('Y-m-d h:m:s');
				$result=$this->ModelCar->insertData($data);
				$this->ModelCar->saveCarImage($result,$allimg);
			}
			//pr($data);
			if($result > 0){
				$this->nsession->set_userdata('succmsg', 'Saved Successfully');
				redirect(base_url('car'));

			}else{
				$this->nsession->set_userdata('errmsg', 'Unable to save, Please try again');
				redirect(base_url('car'));
			}
		}else{
			$this->nsession->set_userdata('errmsg', 'This Car is not exist,Please try to contact admin');
			redirect(base_url('car'));
		}
		
	}

	public function douploadimages($name)
	{       
	    $this->load->library('upload');
	    $filesCount = count($_FILES[$name]['name']);
	    $car_image=array();
		if($filesCount>0){
			$propertKeys = array_keys($_FILES[$name]['name']);
			$i = 0;
			foreach($propertKeys as $propertKey){
				$_FILES['upload_img']['name'] 		= $_FILES[$name]['name'][$propertKey];
				$_FILES['upload_img']['type'] 		= $_FILES[$name]['type'][$propertKey];
				$_FILES['upload_img']['tmp_name'] 	= $_FILES[$name]['tmp_name'][$propertKey];
				$_FILES['upload_img']['error'] 		= $_FILES[$name]['error'][$propertKey];
				$_FILES['upload_img']['size'] 		= $_FILES[$name]['size'][$propertKey];

				$upload_img = time().$_FILES[$name]['name'][$propertKey];

				$uploadPath = file_upload_absolute_path().'car_image/';
				$config['upload_path'] = $uploadPath;
				$config['allowed_types'] = '*'; 
				$config['file_name']     = $upload_img;  

				$this->upload->initialize($config);
				if($this->upload->do_upload('upload_img')){
					$fileData = $this->upload->data();
					$uploadData[$propertKey]['file_name'] = $fileData['file_name'];
				}else{
					$error = array('error' => $this->upload->display_errors()); 
				}
				if($uploadData[$propertKey]['file_name']) {
					$car_image['upload_img'][$i] = $uploadData[$propertKey]['file_name'];
				}else{
					$car_image['upload_img'][$i] = "";
				}
				$i++;
			 }
		}

	    return $car_image;
	}

	
	function activate()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);
		$this->ModelCar->activate($id);	
		$this->nsession->set_userdata('succmsg', 'Successfully Car Activated.');
		redirect(base_url($this->controller."/index/"));
		return true;
	}
	function inactive()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelCar->inactive($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully Owner Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
	}
	
	
	function viewdetails($id){
		if($id){
			$data['resultData'] = $this->ModelCar->getSingle($id);
			//pr($data['resultData']);
			/* Get Dynamic Form for title */
			$data['succmsg'] = $this->nsession->userdata('succmsg');
			$data['errmsg'] = $this->nsession->userdata('errmsg');
			$this->nsession->set_userdata('succmsg', "");
			$this->nsession->set_userdata('errmsg', "");
			$elements = array();
			$elements['menu'] = 'menu/index';
			$elements['topmenu'] = 'menu/topmenu';
			$elements['main'] = 'car/view_details';
			$element_data['menu'] = $data;
			$element_data['main'] = $data;
			$this->layout->setLayout('layout_main_view'); 
			$this->layout->multiple_view($elements,$element_data);
		}
	}

	public function add($car_id='0'){
		
        //$this->functions->checkUser($this->controller.'/',true);
		$membertype = $this->nsession->userdata('member_session_membertype');
		/*if($membertype==1){}else{
			$this->nsession->set_userdata('errmsg', 'Owner can only post Car.');
			redirect(base_url());
		}*/
			
			$data = array();
			
			$data['controller'] = $this->controller;
			
			$data['succmsg'] = $this->nsession->userdata('succmsg');
			$data['errmsg'] = $this->nsession->userdata('errmsg');
			
			$this->nsession->set_userdata('succmsg', "");
			$this->nsession->set_userdata('errmsg', "");
			
			if($car_id == '0'){
				$data['car_id']=0;
			}else{
				$data['car_id'] = ($car_id);
			}
			
			if($car_id!=0){
				$data['action'] = 'Edit';
			}else{
				$data['action']	= 'Add';
			}
			
			$data['CarData']=$this->ModelCar->getCarData($data['car_id']);
			$data['CarImageData']=$this->ModelCar->getCarImageData($data['car_id']);
			//pr($data['CarImageData']);
			$data['CarAmenities']=$this->ModelCar->getAmenitiesData();
			$data['CarFeatures']=$this->ModelCar->getFeaturesData();
			$data['allCountry']=$this->ModelCar->getCountryCityStateList('countries');

			$elements = array();
			$elements['menu'] = 'menu/index';
			$elements['topmenu'] = 'menu/topmenu';
			$elements['main'] = 'car/addedit';  
			$element_data['menu'] = $data;//array();
			$element_data['main'] = $data;
			$this->layout->setLayout('layout_main_view'); 
			$this->layout->multiple_view($elements,$element_data);
		
	}

	function getCaldistance(){
		if($this->input->post('address') && $this->input->post('name')){
			$data=$this->db->select('latitude,longitude')->get_where('university',array('name'=>$this->input->post('name')))->row();
			if(isset($data->latitude)){
				$lat=$data->latitude;
				$long=$data->longitude;
			}else{
				$lat=0;
				$long=0;
			}
			$address=$this->input->post('address');
			
			$prepAddr = str_replace(' ','+',$address);
			$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
			$output= json_decode($geocode);
			if($output->results[0]->geometry->location->lat=='' || $output->results[0]->geometry->location->lng==''){

			}else{
				$mlat = $output->results[0]->geometry->location->lat;
				$mlong = $output->results[0]->geometry->location->lng;

				echo $this->distance($mlat,$mlong,$lat,$long,"K");
			}
		}else{
			echo 0;
		}
	}

	function distance($lat1, $lon1, $lat2, $lon2, $unit) {

	  $theta = $lon1 - $lon2;
	  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	  $dist = acos($dist);
	  $dist = rad2deg($dist);
	  $miles = $dist * 60 * 1.1515;
	  $unit = strtoupper($unit);

	  return number_format((float)$miles, 1, '.', '');

	  /*if ($unit == "K") {
	    return number_format((float)($miles * 1.609344), 2, '.', '');
	  } else if ($unit == "N") {
	      return ($miles * 0.8684);
	    } else {
	        return $miles;
	      }*/
	}

	function getState($id,$val=0){
		$data=$this->ModelCar->getCountryCityStateList('states',array('country_id'=>$id));
		if(count($data) > 0){
			echo '<option value="">Select State</option>';
			foreach ($data as $key => $value) {
				$selected='';
				if($value['id']==$val){
					$selected="selected='selected'";
				}
				echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['name'].'</option>';
			}
		}
	}

	function getCity($id,$val=0){
		$data=$this->ModelCar->getCountryCityStateList('cities',array('state_id'=>$id));
		if(count($data) > 0){
			echo '<option value="">Select City</option>';
			foreach ($data as $key => $value) {
				$selected='';
				if($value['id']==$val){
					$selected="selected='selected'";
				}
				echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['name'].'</option>';
			}
		}
	}
}
?>