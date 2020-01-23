<?php
class User extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('ModelLogin');
		$this->load->model('ModelSetting');
		$this->load->model('ModelStaff');
	    $this->load->library('Ajax_pagination');
		$this->controller = 'user';
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

		$this->ajax_pagination->setAdminPaginationStyle($config);
		$start = 0;

		$this->ajax_pagination->initialize($config);
		$data['controller'] = $this->controller;
		$data['section']=$this->functions->createBreadcamp('Dashboard');

		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		
		$data['ownerCount'] 		= $this->ModelLogin->getCustomerCount();
		$data['bannerCount'] 		= $this->ModelLogin->getBannerCount();

		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");

		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'user/home';
		
		$element_data['menu'] = $data;
		$element_data['main'] = $data;

		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	}

	function changepass()
	{
		$this->functions->checkAdmin($this->controller.'/changepass/',true);
		
		$data['section']=$this->functions->createBreadcamp('Change Password',$this->controller,'Dashboard');
		$data['controller']=$this->controller;

		$data['msg'] = "";
		$id = $this->nsession->userdata('admin_session_id');

		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');

		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'user/changepass';
		
		$element_data['menu'] = $data;
		$element_data['main'] = $data;

		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	}

	function myprofile()
	{
		$this->functions->checkAdmin($this->controller.'/changeprofile/',true);

		$data['msg'] = "";
		$id = $this->nsession->userdata('admin_session_id');
		$rs = $this->ModelLogin->getProfileData($id);

		if(is_array($rs))
		{
			foreach($rs as $key =>$val)
			{
				$data[$key] = $val;
			}
		}

		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');

		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");

		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'user/myprofile';
		

		$element_data['main'] = $data;

		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	}

	function edit_profile()
	{
		$this->functions->checkAdmin($this->controller.'/changeprofile/',true);

		$data['msg'] = "";
		$id = $this->nsession->userdata('admin_session_id');
		$rs = $this->ModelLogin->getProfileData($id);
		//pr($rs);
		if(is_array($rs))
		{
			foreach($rs as $key =>$val)
			{
				$data[$key] = $val;
			}
		}
		$data['do_addedit_link']	= base_url($this->controller."/do_changeprofile/");
		$data['back_link']			= base_url($this->controller."/index/");
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		$data['allCountry']=$this->ModelLogin->getCountryCityStateList('countries');
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");

		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'user/edit_profile';

		$element_data['main'] = $data;

		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	}

	function do_changeprofile()
	{
		$this->functions->checkAdmin($this->controller.'/changeprofile/',true);

		$contentId = $this->nsession->userdata('admin_session_id');
		
		if($this->input->post('hidData')!='JSENABLE'){
			$this->nsession->set_userdata('errmsg','unable to process because some extension is disable of this browser');
			redirect(base_url($this->controller));
			die();
		}	
		$this->form_validation->set_error_delimiters('<ul class="parsley-errors-list filled error text-left" ><li class="parsley-required">', '</li></ul>');
		$file_name = $_FILES['picture']['name'];
		$new_file_name = time().$file_name;
		$config['upload_path'] 	 = file_upload_absolute_path().'profile_pic/staff/';
		$config['allowed_types'] = '*';
		$config['file_name']     = $new_file_name;  
		$this->upload->initialize($config);
		if(!$this->upload->do_upload('picture')) {
			//echo $this->upload->display_errors(); die();
			//$error = array('error' => $this->upload->display_errors()); 
		}
		else{ 
			$upload_data = $this->upload->data();
			$data['picture'] 			= $upload_data['file_name'];
			$thumb=$this->creatThumbImage($data['picture']);
			if($thumb!=false){
				$data['picture_sm']=$thumb;
			}
		} 
		$data['first_name'] 		= $this->input->post('first_name');
		$data['last_name'] 			= $this->input->post('last_name');
		$data['phone'] 			    = $this->input->post('phone');
		$data['email'] 				= $this->input->post('email');
		$data['username'] 			= $this->input->post('username');
		$data['country'] 			= $this->input->post('country');
		$data['state'] 				= $this->input->post('state');
		$data['city'] 				= $this->input->post('city');
		$data['zipcode'] 			= $this->input->post('zipcode');
		$data['address'] 			= $this->input->post('address');
		$data['address2'] 			= $this->input->post('address2');

		$data['created_by_type']    		= 'staff';
		$data['status_modified_by']         = $this->nsession->userdata('admin_session_id');
		$data['modified_date'] 				= date('Y-m-d h:m:s');
		$this->ModelStaff->editContent($data,$contentId);
		$this->nsession->set_userdata('succmsg','Updated successfully.');
		$this->nsession->set_userdata('admin_session_img_path', $data['picture']);
		redirect(base_url($this->controller.'/myprofile'));
	}

	function do_changepass()
	{

		$this->functions->checkAdmin($this->controller.'/changepass/',true);

		$this->form_validation->set_rules('old_admin_pwd', 'Old Password', 'trim|required|min_length[5]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('new_admin_pwd', 'New Password', 'trim|required|min_length[5]|max_length[20]|matches[conf_new_admin_pwd]|xss_clean');
		$this->form_validation->set_rules('conf_new_admin_pwd', 'Comfirm New Password','trim|required|min_length[5]|max_length[20]|xss_clean');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		$id = $this->nsession->userdata('admin_session_id');
		$data['msg'] = "";

		if($this->form_validation->run() == TRUE)
		{
			$data['oldpassword'] = $this->input->request('old_admin_pwd');
			$isTrue = $this->ModelLogin->valideOldPassword($data);
	
			if($isTrue)
			{
				$data['new_admin_pwd'] = $this->input->request('new_admin_pwd');
				$this->ModelLogin->updateAdminPass($id,$data);
				$this->nsession->set_userdata('succmsg',"Password Updated");
			}
			else
			{
				$this->nsession->set_userdata('errmsg',"Invalid Old Password ...");
			}			

			redirect(base_url($this->controller."/changepass/"));
			return true;
		}
		else
		{
			$this->changepass(); 
			return true;
		}
	}

	function getState($id,$val=0){
		$data=$this->ModelLogin->getCountryCityStateList('states',array('country_id'=>$id));
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
		$data=$this->ModelLogin->getCountryCityStateList('cities',array('state_id'=>$id));
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
			$return = $this->ModelLogin->checkEmail($email_id,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else if($type=='phone'){
			$phone = $this->input->post('phone');
			$return = $this->ModelLogin->checkPhone($phone,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else{
			$username = $this->input->post('username');
			$return = $this->ModelLogin->checkUsername($username,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}
		
	}

	public function creatThumbImage($filename=''){
		$source_path = file_upload_absolute_path() . '/profile_pic/staff/' . $filename;
	    $target_path = file_upload_absolute_path() . '/profile_pic/staff/';
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
