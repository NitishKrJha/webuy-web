<?php
//error_reporting(E_ALL);
class Staff extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'staff';
		$this->load->model('ModelStaff');
	}
	
	function index()
	{
		$UserID = $this->nsession->userdata('admin_session_id');
		//$name=staff;
		//$for=0;
		//$y=$this->functions->acc_permission_r($UserID,$name,$for);
		//echo $UserID ;exit;
		//echo $y ;exit;
		if($UserID==1){



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

		$data['recordset'] 		= $this->ModelStaff->getList($config,$start,$param);
		//pr($data['recordset']);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_staff');
		$data['reload_link']      	= base_url($this->controller."/index/");
		$data['search_link']        = base_url($this->controller."/index/0/1/");
		$data['add_link']         	= base_url($this->controller."/addedit/0/0/");
		$data['pwd_link']        	= base_url($this->controller."/change_password/{{ID}}/0");
		$data['edit_link']        	= base_url($this->controller."/addedit/{{ID}}/0");
		$data['activated_link']    	= base_url($this->controller."/activate/{{ID}}/0");
		$data['inacttived_Link']    = base_url($this->controller."/inactive/{{ID}}/0");
		$data['showall_link']     	= base_url($this->controller."/index/0/1");
		$data['total_rows']			= $config['total_rows'];
		$data['permission_link']    = base_url($this->controller."/set_permission/{{ID}}/0");
		$data['set_permission_link']= base_url($this->controller."/do_set_permission/{{ID}}/0");
		
		
		$data['succmsg'] 	= $this->nsession->userdata('succmsg');
		$data['errmsg'] 	= $this->nsession->userdata('errmsg');
		
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'staff/index';

		$element_data['menu'] = $data;
		$element_data['main'] = $data;
		
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	}
	else{
		//$this->load->view('asd');
		$this->nsession->set_userdata('errmsg','You are Not Authorized to Access this Option.');
		redirect(base_url($this->user));
	}
	
	}
	
	//==========Initialize $data for Add =======================
	
	function addedit($id = 0)
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		
		$name=staff;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
	
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
			$rs = $this->ModelStaff->getSingle($contentId);
			//pr($rs);die();
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
		$data['allCountry']=$this->ModelStaff->getCountryCityStateList('countries');
		$data['staffType']=$this->ModelStaff->getStaffType();
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'staff/add_edit';
		$element_data['menu'] = $data;//array();
		$element_data['main'] = $data;
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	}
	else{
		$this->nsession->set_userdata('errmsg','You are Not Authorized to Access this Option.');
		redirect(base_url($this->controller));
	}
		
	}
	
	function do_addedit()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$contentId = $this->uri->segment(3, 0); 
		$page = $this->uri->segment(4, 0); 
		echo $contentId;
		
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
		$data['user_type'] 			= $this->input->post('user_type');
		$data['address2'] 			= $this->input->post('address2');
		$data['created_by_type']    = 'staff';
		$data['status_modified_by']         = $this->nsession->userdata('admin_session_id');
		if($contentId==0){
			$data['status'] 			= 1;
			$data['created_by']         = $this->nsession->userdata('admin_session_id');
			$data['created_date'] 			= date('Y-m-d h:m:s');
			$data['modified_date'] 			= date('Y-m-d h:m:s');
		}else{
			$data['modified_date'] 			= date('Y-m-d h:m:s');
		}
		if($contentId==0){
			$rid=$this->ModelStaff->addContent($data);
			if($rid!=false){
				$ndata=array();
				$ndata['password']=md5($this->input->post('password'));
				$this->ModelStaff->updatePassword($ndata,$rid);
				/***************Email Content**********************/
				$home_page_url='<a href="'.front_base_url().'">'.front_base_url().'</a>';

				$login_link='<a href="'.front_base_url().'login'.'">'.front_base_url().'login'.'</a>';

				$email_template=$this->db->get_where('email_template',array('id'=>1))->row_array();
				if(count($email_template) > 0){
					$full_name=$data['first_name']." ".$data['last_name'];
					$subject=$email_template['subject'];
					$body=$email_template['content'];
					$body=str_replace('#full_name#', $full_name, $body);
					$body=str_replace('#property_link#', $property_link, $body);
					$body=str_replace('#home_page_url#', $home_page_url, $body);
					$body=str_replace('#login_link#', $login_link, $body);
					
				}else{
					$full_name=$data['first_name']." ".$data['last_name'];
					$subject		= "Registration";
					$body			= "<tr><td>Hi,".$full_name."</td></tr>
									<tr><td>Our team has been created an account for you. Please open this link to check is there correct or not.</td></tr><tr><td>Login Link : ".$login_link."</td><td>Email : ".$data['email']." Password: ".$this->input->post('password')."</td></tr>";
				}
				$to 			= $data['email'];
				
				$this->functions->mail_template($to,$subject,$body);
				/*************************************/
				$this->nsession->set_userdata('succmsg','Staff added successfully.');
				redirect(base_url($this->controller));
			}else{
				$this->nsession->set_userdata('errmsg','Unable to add , please try once again..');
				redirect(base_url($this->controller));
			}
		}else{
			$rid=$this->ModelStaff->editContent($data,$contentId);
			$this->nsession->set_userdata('succmsg','Staff edited successfully.');
			redirect(base_url($this->controller));
		}
			
		
	}


	function set_permission($id = 0)
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		if($UserID==1){

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
		//$data['do_addedit_link']	= base_url($this->controller."/do_addedit/".$contentId."/".$page."/");
		$data['back_link']			= base_url($this->controller."/index/");
		$data['set_permission_link']= base_url($this->controller."/do_set_permission/".$contentId."/".$page."/");
		
		if($contentId > 0)
		{
			$data['adminpage_id'] = $contentId;
			$data['id'] = $contentId;
			$data['action'] = "Edit";
			//=================prepare DATA ==================
			$rs = $this->ModelStaff->getSingle($contentId);
			// pr($rs);
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

		//pr($data['permission']);die();
		$data['jsonvalue']=json_decode($data['permission'],true);
		//pr($data['jsonvalue']);die();
		//pr((int)$data['jsonvalue']['category'][0]);die;

		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		//$data['allCountry']=$this->ModelStaff->getCountryCityStateList('countries');
		//$data['staffType']=$this->ModelStaff->getStaffType();
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'staff/permission';
		$element_data['menu'] = $data;//array();
		$element_data['main'] = $data;
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	}
	else{
		$this->nsession->set_userdata('errmsg','You are Not Authorized to Access this Option.');
		redirect(base_url($this->controller));
	}
		
	}




	function do_set_permission()
	{	
		//echo "hooola";
		
		//-----------------------------------------------
		$this->functions->checkAdmin($this->controller.'/');
		$contentId = $this->uri->segment(3, 0); 
		$page = $this->uri->segment(4, 0);
		echo "$contentId"; 
		
	
		//$this->form_validation->set_error_delimiters('<ul class="parsley-errors-list filled error text-left" ><li class="parsley-required">', '</li></ul>');

		//$data['offer_name'] 		= $this->input->post('offer_name');
		//$data['category_level_1'] 			= $this->input->post('category_level_1');
		//$data['offer_type'] 			    = $this->input->post('offer_type');
		//$data['offer_amount'] 				= $this->input->post('offer_amount');
		//$data['short_description'] 			= $this->input->post('short_description');
		//$data['offer_limit'] 			= $this->input->post('offer_limit');
		//$data['permission']  = $this->input->post('permission');

		/*$permission_set = array();
		$permission_set['category']		= $this->input->post('category');
		$permission_set['cas']			= $this->input->post('cms');
		$permission_set['variation_attribute']			= $this->input->post('variation_attribute');
		$permission_set['various_offer']			= $this->input->post('cms');
		$permission_set['banner']			= $this->input->post('cms');
		$permission_set['staff']			= $this->input->post('cms');
		$permission_set['merchant']			= $this->input->post('merchant');*/
		$permission_set = array();
		$permission_set['category']		= $this->input->post('category');
		$permission_set['cms']			= $this->input->post('cms');
		$permission_set['variation_attribute']			= $this->input->post('variation_attribute');
		$permission_set['various_offer']			= $this->input->post('various_offer');
		$permission_set['banner']			= $this->input->post('banner');
		$permission_set['staff']			= $this->input->post('staff');
		$permission_set['merchant']			= $this->input->post('merchant');
		$permission_set['customer']			= $this->input->post('customer');
		$permission_set['product']			= $this->input->post('product');
		$permission_set['setting']			= $this->input->post('setting');
		$permission_set['brands']			= $this->input->post('brands');

		//pr($permission_set);
		 $jsondata=json_encode($permission_set);
		 $data['permission']  = $jsondata;

			//$ac = implode(",",$mal);
			//$data['permission'] = $ac;
		//pr($data['permission']);

		
		if($contentId==0){
			$data['created_date'] 			= date('Y-m-d h:m:s');
			$data['modified_date'] 			= date('Y-m-d h:m:s');
			$this->ModelOffer->addContent($data);
				$this->nsession->set_userdata('succmsg','Offer added successfully.');
				redirect(base_url($this->controller));
			}
			else{
				echo "gola";
				
				$data['modified_date'] 			= date('Y-m-d h:m:s');


			$rid=$this->ModelStaff->editPermission($data,$contentId);

			$this->nsession->set_userdata('succmsg','offer edited successfully.');
			redirect(base_url($this->controller));
		}
		
			

		
	}
	
	function activate()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);
		$this->ModelStaff->activate($id);	
		$result = $this->ModelStaff->getsingle_empdata($id);
		$email = $result->email;
		$first_name = $result->first_name;
		
		$to = $email;
		$subject='Profile Activated';
		$body='<tr><td>Hi,</td></tr>
				<tr><td>Name : '.$first_name.'</td></tr>
				<tr style="background:#fff;"><td>Your profile has been activated successfully click on the link below to login.</td></tr> 
				<tr style="background:#fff;"><td><a href="'.front_base_url().'login'.'">Login</a></td></tr>';
		$return_check = $this->functions->mail_template($to,$subject,$body);
		$this->nsession->set_userdata('succmsg', 'Successfully staff Activated.');
		redirect(base_url($this->controller."/index/"));
		return true;
	}
	function inactive()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelStaff->inactive($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully staff Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
	}

	function delete()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=staff;
		$for=2;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
	
		if($y==1){


		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelStaff->delete($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully Deleted.');
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
			$rs = $this->ModelStaff->getSingle($id);
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
			$elements['main'] = 'staff/view_details';
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
			$rs = $this->ModelStaff->getSingle($contentId);
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
		$elements['main'] = 'staff/change_password';
		$element_data['menu'] = $data;//array();
		$element_data['main'] = $data;
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
		
	}

	function update_password($id){
		if($this->input->post('password')){
			$ndata['password']=md5($this->input->post('password'));
			$this->ModelStaff->updatePassword($ndata,$id);
			$this->nsession->set_userdata('succmsg', 'Password has been changed');
			redirect(base_url('staff/index/0/1'));
		}else{
			$this->nsession->set_userdata('errmsg', 'Unable to change password, please try once again');
			redirect(base_url('staff/index/0/1'));
		}
	}

	function getState($id,$val=0){
		$data=$this->ModelStaff->getCountryCityStateList('states',array('country_id'=>$id));
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
		$data=$this->ModelStaff->getCountryCityStateList('cities',array('state_id'=>$id));
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
			$return = $this->ModelStaff->checkEmail($email_id,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else if($type=='phone'){
			$phone = $this->input->post('phone');
			$return = $this->ModelStaff->checkPhone($phone,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else{
			$username = $this->input->post('username');
			$return = $this->ModelStaff->checkUsername($username,$id);
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
?>