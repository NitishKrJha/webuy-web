<?php
//error_reporting(E_ALL);
class Offer extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'offer';
		$this->load->model('ModelOffer');
	}
	
	function index()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=various_offer;
		$for=0;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);

		if($y==1){
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
		$param['sortField'] 		= $this->input->request('sortField','id');
		$param['searchField'] 		= $this->input->request('searchField','');
		$param['searchString'] 		= $this->input->request('searchString','');
		$param['searchText'] 		= $this->input->request('searchText','');
		$param['searchFromDate'] 	= $this->input->request('searchFromDate','');
		$param['searchToDate'] 		= $this->input->request('searchToDate','');
		$param['searchDisplay'] 	= $this->input->request('searchDisplay','20');
		$param['searchAlpha'] 		= $this->input->request('txt_alpha','');
		$param['searchMode'] 		= $this->input->request('search_mode','STRING');

		$data['recordset'] 		= $this->ModelOffer->getList($config,$start,$param);
		//pr($data['recordset']);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_offer');
		$data['reload_link']      	= base_url($this->controller."/index/");
		$data['search_link']        = base_url($this->controller."/index/0/1/");
		$data['add_link']         	= base_url($this->controller."/addedit/0/0/");
		$data['pwd_link']        	= base_url($this->controller."/change_password/{{ID}}/0");
		$data['edit_link']        	= base_url($this->controller."/addedit/{{ID}}/0");
		$data['activated_link']    	= base_url($this->controller."/activate/{{ID}}/0");
		$data['inacttived_Link']    = base_url($this->controller."/inactive/{{ID}}/0");
		$data['showall_link']     	= base_url($this->controller."/index/0/1");
		$data['total_rows']			= $config['total_rows'];
		
		$data['succmsg'] 	= $this->nsession->userdata('succmsg');
		$data['errmsg'] 	= $this->nsession->userdata('errmsg');
		
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'offer/index';

		$element_data['menu'] = $data;
		$element_data['main'] = $data;
		
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Access this Option.');
			redirect(base_url($this->user));
		}

	
	}
	
	//==========Initialize $data for Add =======================
	
	function addedit($id = 0)

	{

		$UserID = $this->nsession->userdata('admin_session_id');
		//echo $UserID;exit;
		$name=various_offer;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
		 //echo $y;exit;
		if($y==1){





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
			$data['id'] = $contentId;
			$data['action'] = "Edit";
			//=================prepare DATA ==================
			$rs = $this->ModelOffer->getSingle($contentId);
			$data['category_level_1']=$this->ModelOffer->getCategoryType();
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
		//pr($data);
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		$data['allCountry']=$this->ModelOffer->getCountryCityStateList('countries');
		$data['category_level_1_data']=$this->ModelOffer->getCategoryType();
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'offer/add_edit';
		$element_data['menu'] = $data;//array();
		$element_data['main'] = $data;
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	}
	else{
		//$this->load->view('asd');
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Access this option.');
		redirect(base_url($this->controller));
	}
		
	}
	
	function do_addedit()
	{
		
		//-----------------------------------------------
		$this->functions->checkAdmin($this->controller.'/');
		$contentId = $this->uri->segment(3, 0); 
		$page = $this->uri->segment(4, 0); 
		
	
		$this->form_validation->set_error_delimiters('<ul class="parsley-errors-list filled error text-left" ><li class="parsley-required">', '</li></ul>');

		$data['offer_name'] 		= $this->input->post('offer_name');
		$data['category_level_1'] 			= $this->input->post('category_level_1');
		$data['offer_type'] 			    = $this->input->post('offer_type');
		$data['offer_amount'] 				= $this->input->post('offer_amount');
		$data['short_description'] 			= $this->input->post('short_description');
		$data['offer_limit'] 			= $this->input->post('offer_limit');

		
		if($contentId==0){
			$data['created_date'] 			= date('Y-m-d h:m:s');
			$data['modified_date'] 			= date('Y-m-d h:m:s');
			$this->ModelOffer->addContent($data);
				$this->nsession->set_userdata('succmsg','Offer added successfully.');
				redirect(base_url($this->controller));
			}
			else{
				
				$data['modified_date'] 			= date('Y-m-d h:m:s');


			$rid=$this->ModelOffer->editContent($data,$contentId);

			$this->nsession->set_userdata('succmsg','offer edited successfully.');
			redirect(base_url($this->controller));
		}
		
			

		
	}
	
	function activate()
	{


		$UserID = $this->nsession->userdata('admin_session_id');
		$name=various_offer;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);

		if($y==1){

		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);
		$this->ModelOffer->activate($id);	
		//$result = $this->ModelOffer->getsingle_empdata($id);
		//$email = $result->email;
		//$first_name = $result->first_name;
		
		//$to = $email;
		//$subject='Profile Activated';
		//$body='<tr><td>Hi,</td></tr>
				//<tr><td>Name : '.$first_name.'</td></tr>
				//<tr style="background:#fff;"><td>Your profile has been activated successfully click on the link below to login.</td></tr> 
				//<tr style="background:#fff;"><td><a href="'.front_base_url().'login'.'">Login</a></td></tr>';
		//$return_check = $this->functions->mail_template($to,$subject,$body);
		$this->nsession->set_userdata('succmsg', 'Successfully offer Activated !!.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Active this item.');
			redirect(base_url($this->controller));
			
		}

	}
	function inactive()
	{


		$UserID = $this->nsession->userdata('admin_session_id');
		$name=various_offer;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);

		if($y==1){

		
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelOffer->inactive($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully offer Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Inactive this item.');
			redirect(base_url($this->controller));
			
		}
	}

	function delete()
	{


		$UserID = $this->nsession->userdata('admin_session_id');
		//echo $UserID;exit;
		$name=various_offer;
		$for=2;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
		 //echo $y;exit;
		if($y==1){



		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelOffer->delete($id);		
		$this->nsession->set_userdata('succmsg', 'Deleted offer Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Delete this Item.');
			redirect(base_url($this->controller));
		}
	}
	function viewdetails($id){
		if($id){
			$rs = $this->ModelOffer->getSingle($id);
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
			$data['succmsg'] = $this->nsession->userdata('succmsg');
			$data['errmsg'] = $this->nsession->userdata('errmsg');
			$this->nsession->set_userdata('succmsg', "");
			$this->nsession->set_userdata('errmsg', "");
			$elements = array();
			$elements['menu'] = 'menu/index';
			$elements['topmenu'] = 'menu/topmenu';
			$elements['main'] = 'offer/view_details';
			$element_data['menu'] = $data;//array();
			$element_data['main'] = $data;
			$this->layout->setLayout('layout_main_view'); 
			$this->layout->multiple_view($elements,$element_data);
		}
		
	}

	function change_password($id = 0)
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
		$data['do_addedit_link']	= base_url($this->controller."/update_password/".$contentId."/".$page."/");
		$data['back_link']			= base_url($this->controller."/index/");
		
		if($contentId > 0)
		{
			$data['adminpage_id'] = $contentId;
			$data['action'] = "Edit";
			//=================prepare DATA ==================
			$rs = $this->ModelOffer->getSingle($contentId);
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
		$elements['main'] = 'offer/change_password';
		$element_data['menu'] = $data;//array();
		$element_data['main'] = $data;
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
		
	}

	function update_password($id){
		if($this->input->post('password')){
			$ndata['password']=md5($this->input->post('password'));
			$this->ModelOffer->updatePassword($ndata,$id);
			$this->nsession->set_userdata('succmsg', 'Password has been changed');
			redirect(base_url('offer/index/0/1'));
		}else{
			$this->nsession->set_userdata('errmsg', 'Unable to change password, please try once again');
			redirect(base_url('offer/index/0/1'));
		}
	}

	function getState($id,$val=0){
		$data=$this->ModelOffer->getCountryCityStateList('states',array('country_id'=>$id));
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
		$data=$this->ModelOffer->getCountryCityStateList('cities',array('state_id'=>$id));
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

	public function checkuser($type,$id=0){
		if($type=='email'){
			$email_id = $this->input->post('email');
			$return = $this->ModelOffer->checkEmail($email_id,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else if($type=='phone'){
			$phone = $this->input->post('phone');
			$return = $this->ModelOffer->checkPhone($phone,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else{
			$username = $this->input->post('username');
			$return = $this->ModelOffer->checkUsername($username,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}
		
	}

	public function creatThumbImage($filename=''){
		$source_path = file_upload_absolute_path() . '/profile_pic/offer/' . $filename;
	    $target_path = file_upload_absolute_path() . '/profile_pic/offer/';
	    $config_manip = array(
	        'image_library' => 'gd2',
	        'source_image' => $source_path,
	        'new_image' => $target_path,
	        'maintain_ratio' => TRUE,
	        'create_thumb' => TRUE,
	        'thumb_marker' => '_thumb',
	        'width' => 150,
	        'height' => 150
	    );
	    $this->load->library('image_lib', $config_manip);
	    if (!$this->image_lib->resize()) {
	        //echo $this->image_lib->display_errors();
	        $this->image_lib->clear();
	    	return false;
	    }else{
	    	$imgDetailArray=explode('.',$filename);
 			$thumbimgname=$imgDetailArray[0].'_thumb';
 			$this->image_lib->clear();
  			return $thumbimgname.'.'.$imgDetailArray[1]; 	
	    }
	}

}
?>