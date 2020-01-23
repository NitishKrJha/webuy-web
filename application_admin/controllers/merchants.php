<?php
//error_reporting(E_ALL);
class Merchants extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'merchants';
		$this->load->model('ModelMerchants');
	}
	
	function index()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=merchant;
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

		$data['recordset'] 		= $this->ModelMerchants->getList($config,$start,$param);
		//pr($data['recordset']);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_merchants');
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
		$elements['main'] = 'merchants/index';

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

	function aadhar_card_verified($status='',$merchants_id){
		if($status!=''){
			$ndata=array();
			$ndata['aadhar_card_verified']=$status;
			$result=$this->ModelMerchants->updateData('merchants_business_details',$ndata,array('merchants_id'=>$merchants_id));

			$merchants_business_profile_details = $this->ModelMerchants->getSingle($merchants_id);
			$data=array();
	        $data['subject']='Notify about AADHAR Card Verification.';
	        $page_content='<p>Hi '.$merchants_business_profile_details['first_name'].',</p>';   
	        

			if($result !=false){
				$msg='Aadhar Card not verified Successfully';
				if($status==1){
					$msg='Aadhar Card verified Successfully';
					$page_content.='<p>Your given ADHAR document has been successfully verified.</p>';
	                $data['page_content']=$page_content;
					$this->send_email($merchants_business_profile_details['email'],'merchant-notify-template',$data);
				}else{
					$page_content.='<p>Your Aadhar document is not verified successfully,Please verify your ADHAR ASAP.</p>';
	                $data['page_content']=$page_content;
					$this->send_email($merchants_business_profile_details['email'],'merchant-notify-template',$data);
				}
				$this->nsession->set_userdata('succmsg',$msg);
				redirect(base_url('merchants/viewdetails/'.$merchants_id));
			}else{
				$this->nsession->set_userdata('errmsg','Unable to change , please try once again');
				redirect(base_url('merchants/viewdetails/'.$merchants_id));
			}
		}else{
			$this->nsession->set_userdata('errmsg','Unable to change , please try once again');
			redirect(base_url('merchants/viewdetails/'.$merchants_id));
		}
	}

	function pan_verified($status='',$merchants_id){
		if($status!=''){
			$ndata=array();
			$ndata['pan_verified']=$status;
			$result=$this->ModelMerchants->updateData('merchants_business_details',$ndata,array('merchants_id'=>$merchants_id));

			$merchants_business_profile_details = $this->ModelMerchants->getSingle($merchants_id);
			$data=array();
	        $data['subject']='Notify about PAN Card Verification.';
	        $page_content='<p>Hi '.$merchants_business_profile_details['first_name'].',</p>';   
	        

			if($result !=false){
				$msg='Pan Card not verified Successfully';
				if($status==1){
					$msg='Pan Card verified Successfully';
					$page_content.='<p>Your given PAN document has been successfully verified.</p>';
	                $data['page_content']=$page_content;
					$this->send_email($merchants_business_profile_details['email'],'merchant-notify-template',$data);
				}else{
					$page_content.='<p>Your PAN document is not verified successfully,Please verify your PAN ASAP.</p>';
	                $data['page_content']=$page_content;
					$this->send_email($merchants_business_profile_details['email'],'merchant-notify-template',$data);
				}
				$this->nsession->set_userdata('succmsg',$msg);
				redirect(base_url('merchants/viewdetails/'.$merchants_id));
			}else{
				$this->nsession->set_userdata('errmsg','Unable to change , please try once again');
				redirect(base_url('merchants/viewdetails/'.$merchants_id));
			}
		}else{
			$this->nsession->set_userdata('errmsg','Unable to change , please try once again');
			redirect(base_url('merchants/viewdetails/'.$merchants_id));
		}
	}

	function gstin_verified($status='',$merchants_id){
		if($status!=''){
			$ndata=array();
			$ndata['gstin_verified']=$status;
			$result=$this->ModelMerchants->updateData('merchants_business_details',$ndata,array('merchants_id'=>$merchants_id));
			$merchants_business_profile_details = $this->ModelMerchants->getSingle($merchants_id);
			$data=array();
	        $data['subject']='Notify about GSTIN Verification.';
	        $page_content='<p>Hi '.$merchants_business_profile_details['first_name'].',</p>'; 
			if($result !=false){
				$msg='GSTIN not verified Successfully';
				if($status==1){
					$msg='GSTIN verified Successfully';
					$page_content.='<p>Your given GSTIN has been successfully verified.</p>';
	                $data['page_content']=$page_content;
					$this->send_email($merchants_business_profile_details['email'],'merchant-notify-template',$data);
				}else{
					$page_content.='<p>Your GSTIN is not verified successfully,Please verify your GSTIN ASAP.</p>';
	                $data['page_content']=$page_content;
					$this->send_email($merchants_business_profile_details['email'],'merchant-notify-template',$data);
				}
				$this->nsession->set_userdata('succmsg',$msg);
				redirect(base_url('merchants/viewdetails/'.$merchants_id));
			}else{
				$this->nsession->set_userdata('errmsg','Unable to change , please try once again');
				redirect(base_url('merchants/viewdetails/'.$merchants_id));
			}
		}else{
			$this->nsession->set_userdata('errmsg','Unable to change , please try once again');
			redirect(base_url('merchants/viewdetails/'.$merchants_id));
		}
	}
	
	//==========Initialize $data for Add =======================
	
	function addedit($id = 0)
	{
		$UserID = $this->nsession->userdata('admin_session_id');
		$name=merchant;
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
			$data['id']			= $contentId;
			$data['action'] = "Edit";
			//=================prepare DATA ==================
			$rs = $this->ModelMerchants->getSingle($contentId);
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
		$data['allCountry']=$this->ModelMerchants->getCountryCityStateList('countries');
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'merchants/add_edit';
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
		
		if($this->input->post('hidData')!='JSENABLE'){
			$this->nsession->set_userdata('errmsg','unable to process because some extension is disable of this browser');
			redirect(base_url($this->controller));
			die();
		}	
		$this->form_validation->set_error_delimiters('<ul class="parsley-errors-list filled error text-left" ><li class="parsley-required">', '</li></ul>');
		$file_name = $_FILES['picture']['name'];
		$new_file_name = time().$file_name;
		$config['upload_path'] 	 = file_upload_absolute_path().'profile_pic/merchants/';
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
			$rid=$this->ModelMerchants->addContent($data);
			if($rid!=false){
				$ndata=array();
				$ndata['password']=md5($this->input->post('password'));
				$this->ModelMerchants->updatePassword($ndata,$rid);
				/***************Email Content**********************/
				$home_page_url='<a href="'.front_base_url().'">'.front_base_url().'</a>';

				$login_link='<a href="'.front_base_url().'login'.'">'.front_base_url().'login'.'</a>';

				$email_template=$this->db->get_where('email_template',array('id'=>10))->row_array();
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
				$this->nsession->set_userdata('succmsg','merchants added successfully.');
				redirect(base_url($this->controller));
			}else{
				$this->nsession->set_userdata('errmsg','Unable to add , please try once again..');
				redirect(base_url($this->controller));
			}
			
		}else{
			$this->ModelMerchants->editContent($data,$contentId);
			$this->nsession->set_userdata('succmsg','merchants edited successfully.');
			redirect(base_url($this->controller));
		}
			
		
	}
	
	function activate()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=merchant;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
	
		if($y==1){


		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);
		$this->ModelMerchants->activate($id);	
		$result = $this->ModelMerchants->getsingle_empdata($id);
		$email = $result->email;
		$first_name = $result->first_name;
		
		$to = $email;
		$subject='Profile Activated';
		$body='<tr><td>Hi,</td></tr>
				<tr><td>Name : '.$first_name.'</td></tr>
				<tr style="background:#fff;"><td>Your profile has been activated successfully click on the link below to login.</td></tr> 
				<tr style="background:#fff;"><td><a href="'.front_base_url().'login'.'">Login</a></td></tr>';
		$return_check = $this->functions->mail_template($to,$subject,$body);
		$this->nsession->set_userdata('succmsg', 'Successfully merchants Activated.');
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
		$name=merchant;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
	
		if($y==1){


		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelMerchants->inactive($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully merchants Inactivated.');
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
		$name=merchant;
		$for=2;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
	
		if($y==1){


		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelMerchants->delete($id);		
		$this->nsession->set_userdata('succmsg', 'Merchant deleted successfully.');
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
			$rs = $this->ModelMerchants->getSingle($id);
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

			$data['business_details']=$this->ModelMerchants->getBusinessList($id);
			$data['bank_details']=$this->ModelMerchants->getBankDetails($id);
			//pr($data['bank_details']);
			$data['succmsg'] = $this->nsession->userdata('succmsg');
			$data['errmsg'] = $this->nsession->userdata('errmsg');
			$this->nsession->set_userdata('succmsg', "");
			$this->nsession->set_userdata('errmsg', "");
			$elements = array();
			$elements['menu'] = 'menu/index';
			$elements['topmenu'] = 'menu/topmenu';
			$elements['main'] = 'merchants/view_details';
			$element_data['menu'] = $data;//array();
			$element_data['main'] = $data;
			$this->layout->setLayout('layout_main_view'); 
			$this->layout->multiple_view($elements,$element_data);
		}
		
	}

	function change_password($id = 0)
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=merchant;
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
		$data['do_addedit_link']	= base_url($this->controller."/update_password/".$contentId."/".$page."/");
		$data['back_link']			= base_url($this->controller."/index/");
		
		if($contentId > 0)
		{
			$data['adminpage_id'] = $contentId;
			$data['action'] = "Edit";
			//=================prepare DATA ==================
			$rs = $this->ModelMerchants->getSingle($contentId);
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
		$elements['main'] = 'merchants/change_password';
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

	function update_password($id){
		if($this->input->post('password')){
			$ndata['password']=md5($this->input->post('password'));
			$this->ModelMerchants->updatePassword($ndata,$id);
			$this->nsession->set_userdata('succmsg', 'Password has been changed');
			redirect(base_url('merchants/index/0/1'));
		}else{
			$this->nsession->set_userdata('errmsg', 'Unable to change password, please try once again');
			redirect(base_url('merchants/index/0/1'));
		}
	}

	function getState($id,$val=0){
		$data=$this->ModelMerchants->getCountryCityStateList('states',array('country_id'=>$id));
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
		$data=$this->ModelMerchants->getCountryCityStateList('cities',array('state_id'=>$id));
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
			$return = $this->ModelMerchants->checkEmail($email_id,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else if($type=='phone'){
			$phone = $this->input->post('phone');
			$return = $this->ModelMerchants->checkPhone($phone,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else if($type=='compnay_phone'){
			$compnay_phone = $this->input->post('compnay_phone');
			$return = $this->ModelMerchants->checkComapnyPhone($compnay_phone,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else{
			$username = $this->input->post('username');
			$return = $this->ModelMerchants->checkUsername($username,$id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}
		
	}

	public function creatThumbImage($filename=''){
		$source_path = file_upload_absolute_path() . '/profile_pic/merchants/' . $filename;
	    $target_path = file_upload_absolute_path() . '/profile_pic/merchants/';
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

	public function send_email($to,$template_name,$data){
        $this->load->library('email');
        $this->email->set_mailtype("html");
        $this->email->from($this->config->item('webmaster_email'), $this->config->item('website_name'));
        $this->email->reply_to($this->config->item('webmaster_email'), $this->config->item('website_name'));
        $this->email->to($to);
        $this->email->subject(sprintf($data['subject'], $this->config->item('website_name')));
        $this->email->message($this->load->view('email/'.$template_name,$data, TRUE));
        $this->email->send();
        return $this->email->print_debugger();
    }

}
?>