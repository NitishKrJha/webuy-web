<?php
class Action extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->controller = 'action';
		$this->load->model('ModelAction');
		$this->load->model('ModelCommon');
	}
	
	public function index()
	{
		$result['error']=1;
		echo json_encode($result);
	}

	public function do_register(){
    	$response=array();
    	$response['error']=1;
    	$response['msg']="Invalid Request , Please try once again";
    	$this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('first_name', 'first_name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('last_name', 'last_name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('username', 'username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('phone', 'phone', 'trim|required|xss_clean');
		$this->form_validation->set_rules('gender', 'gender', 'trim|required|xss_clean');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if ($this->form_validation->run() == TRUE)
		{

			$ndata=array();
			$ndata['first_name']=$this->input->post('first_name');
			$ndata['last_name']=$this->input->post('last_name');
			$ndata['email']=$this->input->post('email');
			$ndata['gender']=$this->input->post('gender');
			$ndata['phone']=$this->input->post('phone');
			$ndata['username']=$this->input->post('username');
			$ndata['password']=$this->input->post('password');
			$ndata['weblink']='winskart';
			$main_url=API_URL."register";
			$result=$this->functions->httpPost($main_url,$ndata);
			if($result!=''){
				$returndata=json_decode($result);
				if($returndata->status==true){
					$response['error']=0;
					$response['msg']=$returndata->message;
				}else{
					$response['error']=1;
					$response['msg']=$returndata->message;
				}
			}else{
				$response['error']=1;
				$response['msg']="Invalid Request , Please try once again";
			}
		}
		else
		{
			$response['error']=1;
			$response['msg']="Invalid Request , Please try once again";
		}

		echo json_encode($response);
    }

    function updateProfile(){
    	$response=array();
    	$response['error']=1;
    	$response['msg']="Invalid Request , Please try once again";
    	$this->form_validation->set_rules('first_name', 'first_name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('last_name', 'last_name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('gender', 'gender', 'trim|required|xss_clean');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if ($this->form_validation->run() == TRUE)
		{

			$ndata=array();
			$ndata['first_name']=$this->input->post('first_name');
			$ndata['last_name']=$this->input->post('last_name');
			$ndata['city']=$this->input->post('city');
			$ndata['gender']=$this->input->post('gender');
			$ndata['address']=$this->input->post('address');
			$ndata['country']=$this->input->post('country');
			$ndata['state']=$this->input->post('state');
			$ndata['zipcode']=$this->input->post('zipcode');
			$ndata['member_id']=$this->nsession->userdata('member_session_id');
			$main_url=API_URL."editProfile";
			$result=$this->functions->httpPost($main_url,$ndata);
			if($result!=''){
				$returndata=json_decode($result);
				if($returndata->status==true){
					$response['error']=0;
					$response['msg']=$returndata->message;
				}else{
					$response['error']=1;
					$response['msg']=$returndata->message;
				}
			}else{
				$response['error']=1;
				$response['msg']="Invalid Request , Please try once again";
			}
		}
		else
		{
			$response['error']=1;
			$response['msg']="Invalid Request , Please try once again";
		}

		echo json_encode($response);
    }

    function getState($id,$val=0){
		$data=$this->ModelCommon->getCountryCityStateList('states',array('country_id'=>$id));
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
		$data=$this->ModelCommon->getCountryCityStateList('cities',array('state_id'=>$id));
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

    public function activated($id=''){
    	if($this->input->get('token')){
    		if($id!=''){
	    		$user=$this->ModelCommon->getSingleData('customer',array('customer_id'=>$id));

	    		if(count($user) > 0){
	    			$user_token=md5((string)$user['email'].(string)$user['customer_id']);
	    			//echo ($user_token);
	    			//pr($user);
	    			if($user_token==$this->input->get('token')){
	    				$ndata=array();
	    				$ndata['status']=1;
	    				$result=$this->ModelCommon->updateData('customer',$ndata,array('customer_id'=>$id));
	    				if($result!=false){
	    					$this->nsession->set_userdata('succmsg', "Profile Activated Successfully");
							redirect(base_url());
	    				}else{
	    					$this->nsession->set_userdata('errmsg', "Invalid Token Found, Please contact to our support.");
							redirect(base_url());
	    				}
	    			}else{
	    				$this->nsession->set_userdata('errmsg', "Invalid Token Found, Please contact to our support.");
						redirect(base_url());
	    			}
	    		}else{
	    			$this->nsession->set_userdata('errmsg', "Invalid Token Found, Please contact to our support.");
					redirect(base_url());
	    		}
	    	}else{
	    		$this->nsession->set_userdata('errmsg', "Invalid Token Found, Please contact to our support.");
				redirect(base_url());
	    	}
    	}else{
    		$this->nsession->set_userdata('errmsg', "Invalid Token Found, Please contact to our support.");
			redirect(base_url());
    	}
    	
    }

    function do_login(){
		//pr($_POST);
		$result=array();
		$result['error']=1;
		$result['msg']="Invalid Login credential";
		if($this->input->post('email') && $this->input->post('password')){
			$data['username'] 			= $this->input->post('email');
			$data['password'] 		    = $this->input->post('password');
			$main_url=API_URL."login";
			$apiresult=$this->functions->httpPost($main_url,$data);
			if($apiresult!=''){
				$returndata=json_decode($apiresult);
				//pr((array)$returndata->data);
				if($returndata->status==true){
					if(count($returndata->data) > 0){
						$userdata=(array)$returndata->data;
						//echo $returndata->message; die();
						//pr((array)$returndata);
						$this->nsession->set_userdata('member_login', 'true');
						$this->nsession->set_userdata('member_session_id', $userdata['member_id']);
						$this->nsession->set_userdata('member_session_email', $userdata['email']);
						$this->nsession->set_userdata('member_session_name', $userdata['first_name']);
						$this->nsession->set_userdata('member_session_lname', $userdata['last_name']);
						$this->nsession->set_userdata('member_session_image', $userdata['picture']);
						$this->nsession->set_userdata('member_type', 'customer');
						$result['error']=0;
						$result['msg']=$returndata->message;
					}else{
						$result['error']=1;
						$result['msg']="Invalid Request , Please try once again";
					}
					
				}else{
					$result['error']=1;
					$result['msg']=$returndata->message;
				}
			}else{
				$result['error']=1;
				$result['msg']="Invalid Request , Please try once again";
			}
		}else{
			$result['error']=1;
		}
		echo json_encode($result);
	}
	
	function password(){
		$data = array();
		if($this->input->get('token') && $this->input->get('email')){
        	$data['token'] 			= $this->input->get('token');
			$data['email'] 		    = $this->input->get('email');
			$main_url=API_URL."check_forgot_password_token";
			$apiresult=$this->functions->httpPost($main_url,$data);
			if($apiresult!=''){
				$returndata=json_decode($apiresult);
				//pr((array)$returndata);
				if($returndata->status!=true){
					$this->nsession->set_userdata('errmsg','Token has been expired');
					redirect(base_url());
					die();
				}
			}
        	$token=base64_decode($this->input->get('token'));
			$email=$data['email'];
			$data['email']=$email;
			$data['ptoken']=$data['token'];
			$data['token']='valid';
		}else{
			$data['token']='invalid';
		}
		$data['controller'] = $this->controller;
		
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");

		$elements = array(); 
		$elements['header'] = 'layout/header';  
		$element_data['header'] = $data;
		$elements['main'] = 'page/password';  
		$element_data['main'] = $data;
		$elements['footer'] = 'layout/footer';  
		$element_data['footer'] = $data;
		$this->layout->setLayout('layout_home'); 
		$this->layout->multiple_view($elements,$element_data);
	}

	public function checkuser($type,$id=0){
		if($type=='email'){
			$email_id = $this->input->post('email');
			$return = $this->ModelAction->checkEmail($email_id);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else if($type=='phone'){
			$phone = $this->input->post('phone');
			$return = $this->ModelAction->checkPhone($phone);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}else{
			$username = $this->input->post('username');
			$return = $this->ModelAction->checkUsername($username);
			if($return > 0){
				echo 'false';
			}else{
				echo 'true';
			}
		}
		
	}


	function password_change(){
		//pr($_POST);
		if($this->input->post('email') && $this->input->post('password') && $this->input->post('token')){
			$params['email']=$this->input->post('email');
			$params['password']=$this->input->post('password');
			$params['token']=$this->input->post('token');
			$main_url=API_URL."update_forgot_password_token";
			$apiresult=$this->functions->httpPost($main_url,$params);
			if($apiresult!=''){
				$returndata=json_decode($apiresult);
				//pr((array)$returndata->data);
				if($returndata->status==true){
					$this->nsession->set_userdata('succmsg', $returndata->message);
						redirect(base_url());
					
				}else{
					$this->nsession->set_userdata('errmsg', $returndata->message);
					redirect(base_url());
				}
			}else{
				$this->nsession->set_userdata('errmsg', 'Unable to change, Please try once again');
				redirect(base_url());
			}
		}else{
			$this->nsession->set_userdata('errmsg', 'Unable to change, Please try once again');
			redirect(base_url());
		}
	}

	public function isEmailExist($email){
    	$return = $this->ModelAction->checkEmail($email);
		if($return > 0){
			return false;
		}else{
			return true;
		}
    }

    public function isPhoneExist($phone){
    	$return = $this->ModelAction->checkPhone($phone);
		if($return > 0){
			return false;
		}else{
			return true;
		}
    }

    public function isUsernameExist($username){
    	$return = $this->ModelAction->checkUsername($username);
		if($return > 0){
			return false;
		}else{
			return true;
		}
    }

    function do_forgetpwd(){
	//echo $this->input->request('forgetemailId');exit;
    	$link='';
		if($this->input->post('email')){
			$email=$this->input->post('email');
			$data['email'] 			= $this->input->post('email');
			$data['weblink'] 		= 'winskart';
			$main_url=API_URL."forgot_password";
			$apiresult=$this->functions->httpPost($main_url,$data);
			if($apiresult!=''){
				$returndata=json_decode($apiresult);
				if($returndata->status==true){
					$error=0;
					$message=$returndata->message;
				}else{
					$error=1;
					$message=$returndata->message;
				}
			}else{
				$error=1;
				$message="Invalid Request , Please try once again";
			}
		}else{
			$error = 1;
			$message = "Plese fill up email Id.";
		}
		$jsonData = array('error'=>$error,'message'=>$message,'link'=>$link);
		echo json_encode($jsonData);
	}

	function do_forgetpwd_old(){
	//echo $this->input->request('forgetemailId');exit;
    	$link='';
		if($this->input->post('email')){
			$email=$this->input->post('email');
			$check_email = $this->ModelAction->checkEmailData($email);
			//pr($check_email);
			if(count($check_email) > 0){
				if($check_email['status']==0){
					$error = 1;
					$message = "This Account is inactive ";
				}else if($check_email['status']==2){
					$error = 1;
					$message = "This Account is suspended ";
				}else{
				$insertoken=$this->ModelAction->inserttokenforpassword($check_email['customer_id']);
				$link=base_url()."action/password?email=".$email."&token=".base64_encode($insertoken);
				$to = $email;
				$subject="Forgot Password";
				$body="<tr><td>Hi,</td></tr>
						<tr><td>Please click below link to create a new password.</td></tr>
						<tr><td><a href='".$link."'>Click here</a></td></tr>";
				$return_check = $this->functions->mail_template($to,$subject,$body);
				if($return_check){
					$error = 0;
					$message = "";
					$message = 'Please check your mail ID to reset your password';
				}else{
					$error = 0;
					$this->nsession->set_userdata('succmsg','Please check your mail ID to reset your password');
					$message = "Some Mail problem occurred.Please try again.";
				}
				}
			}else {
				$error = 1;
				$message = "Please check your email Id.";
			}
		}else{
			$error = 1;
			$message = "Plese fill up email Id.";
		}
		$jsonData = array('error'=>$error,'message'=>$message,'link'=>$link);
		echo json_encode($jsonData);
	}

	function updateProfie(){
		$return['msg']='Invalid Request';
		$return['error']=1;
		if($this->input->post('submit') && $this->nsession->userdata('member_session_id')){
			$ndata=array();
			if($this->input->post('first_name')){
				$ndata['first_name']=$this->input->post('first_name');
			}
			if($this->input->post('last_name')){
				$ndata['last_name']=$this->input->post('last_name');
			}
			if($this->input->post('gender')){
				$ndata['gender']=$this->input->post('gender');
			}
			if(count($ndata) > 0){
				$this->ModelCommon->updateData('customer',$ndata,array('customer_id'=>$this->nsession->userdata('member_session_id')));
				$return['error']=0;
				$return['msg']='Updated Successfully';
			}
		}
		echo json_encode($return);
	}

	function updatePassowrd(){
		 $response=array();
		 $response['error']=1;
		 $response['msg']="Invalid Request , Please try once again";
		    $this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean');
		    $this->form_validation->set_rules('retype_password', 'Retype password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('current_password', 'current password', 'trim|required|xss_clean');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() == TRUE && $this->input->post('password')==$this->input->post('password'))
			{

			$ndata=array();
			$ndata['old_password']=$this->input->post('current_password');
			$ndata['new_password']=$this->input->post('password');
			$ndata['member_id']=$this->nsession->userdata('member_session_id');
			//pr($ndata); die();
			$main_url=API_URL."update_password";
			$result=$this->functions->httpPost($main_url,$ndata);
			if($result!=''){
			$returndata=json_decode($result);
			if($returndata->status==true){
			$response['error']=0;
			$response['msg']=$returndata->message;
			}else{
			$response['error']=1;
			$response['msg']=$returndata->message;
			}
			}else{
			$response['error']=1;
			$response['msg']="Invalid Request , Please try once again";
			}
			}
			else
			{
			$response['error']=1;
			$response['msg']="Invalid Request , Please try once again";
			}

			echo json_encode($response);
		 }
}
