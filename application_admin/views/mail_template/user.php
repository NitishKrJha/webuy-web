<?php
class User extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('ModelUser');
        $this->controller = 'user';
        $this->load->library('instamojo');
	}

	function index()
	{

		$this->functions->checkUser($this->controller.'/',true);
		$id=$this->nsession->userdata('user_session_id');
		$config['base_url'] 	= base_url($this->controller."/index/");
		$data['controller'] 	= $this->controller;

		$data['succmsg'] 		= $this->nsession->userdata('succmsg');
		$data['errmsg'] 		= $this->nsession->userdata('errmsg');

        $data['recordset']        = $this->ModelUser->getMemberDetails($config,$start,$param);
        $data['gamewallet']       = $this->ModelUser->getGameWallet($id);
        $data['shoppingwallet']   = $this->ModelUser->getShoppingWallet($id);
        $data['rummywallet']      = $this->ModelUser->rummywallet($id);
        $data['pay4healthwallet'] = $this->ModelUser->pay4healthwallet($id);
        $data['rblandsterwallet'] = $this->ModelUser->rblandsterwallet($id);
        $data['chesswallet']      = $this->ModelUser->chesswallet($id);
        $data['refferalUserCount']= $this->ModelUser->refferalUserCount($id);
        $data['activeUserCount']  = $this->ModelUser->activeUserCount($id);
        $data['inactiveUserCount']= $this->ModelUser->inactiveUserCount($id);
        $data['inviteUserCount']  = $this->ModelUser->inviteUserCount($id);
        $data['lastoneday']       = $this->ModelUser->lastoneday($id);
        $data['lastsevendays']    = $this->ModelUser->lastsevendays($id);
        $data['lastonemonth']     = $this->ModelUser->lastonemonth($id);
        $data['invitationCount']  = $this->ModelUser->invitationCount($id);
        $data['offerCount']       = $this->ModelUser->offerCount();
        $data['startRecord']      = $start;

		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu'] 		= 'menu/index';
        $elements['topmenu'] 	= 'menu/topmenu';
        $elements['main'] 		= 'user/home';

        $element_data['menu'] 	= $data;
        $element_data['main'] 	= $data;
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	}

	function changepass()
	{
        $this->functions->checkUser($this->controller.'/',true);
		
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
	function transPassword()
	{
        $this->functions->checkUser($this->controller.'/',true);

//        $apiUrl = $this->functions->getGlobalInfo('api_url').'api/myprofile';
//        $params = array('member_id'=>$this->nsession->userdata('user_session_id'));
//        $jsonResponce = $this->functions->httpPost($apiUrl,$params);
//        $data['transPassword'] = json_decode($jsonResponce,true);

		$data['controller'] = $this->controller;
		$data['succmsg']    = $this->nsession->userdata('succmsg');
		$data['errmsg']     = $this->nsession->userdata('errmsg');

		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");

		$elements = array();
		$elements['menu']       = 'menu/index';
		$elements['topmenu']    = 'menu/topmenu';
		$elements['main']       = 'user/transPassword';

		$element_data['menu']   = $data;
		$element_data['main']   = $data;

		$this->layout->setLayout('layout_main_view');
		$this->layout->multiple_view($elements,$element_data);
	}

	function myprofile()
	{
        $this->functions->checkUser($this->controller.'/',true);

        $data['succmsg'] = $this->nsession->userdata('succmsg');
        $data['errmsg'] = $this->nsession->userdata('errmsg');

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        /*Get profile data*/
        $apiUrl = $this->functions->getGlobalInfo('api_url').'api/myprofile';
        $params = array('member_id'=>$this->nsession->userdata('user_session_id'));
        $jsonResponce = $this->functions->httpPost($apiUrl,$params);
        $data['profileData'] = json_decode($jsonResponce,true);

        /*Get profile document data*/
        $apiUrl = $this->functions->getGlobalInfo('api_url').'api/getMemberDocument';
        $Docparams = array('member_id'=>$this->nsession->userdata('user_session_id'));
        $jsonResponce = $this->functions->httpPost($apiUrl,$Docparams);
        $data['profileDocumentData'] = json_decode($jsonResponce,true);

        /*Get bank details data*/
        $apiUrl = $this->functions->getGlobalInfo('api_url').'api/bank_details_data';
        $Docparams = array('member_id'=>$this->nsession->userdata('user_session_id'));
        $jsonResponce = $this->functions->httpPost($apiUrl,$Docparams);
        $data['bankdetailsData'] = json_decode($jsonResponce,true);

        /*Get Country data*/
        $apiUrl = $this->functions->getGlobalInfo('api_url').'api/country';
        $jsonResponce = $this->functions->httpGet($apiUrl);
        $data['countryLists'] = json_decode($jsonResponce,true);

        // echo '<pre>';
        // print_r($data);

		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'user/myprofile';
		

		$element_data['main'] = $data;

		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	}
    function getStateData(){
        $country_id     = $this->input->post('country_id');
        $state_id       = $this->input->post('state_id');
        $selectHtml     = '';
        $apiUrl         = $this->functions->getGlobalInfo('api_url').'api/state?country_id='.$country_id;
        $jsonResponce   = $this->functions->httpGet($apiUrl);
        $stateLists     = json_decode($jsonResponce,true);
        if($stateLists['status']==TRUE){
            $error = 0;
            $selectHtml = '<option value="">Select State</option>';
            foreach ($stateLists['data'] as $stateList){
                if($state_id==$stateList['id']){
                    $selected="selected";
                }else{
                    $selected="";
                }
                $selectHtml .= '<option value="'.$stateList['id'].'"'.$selected.'>'.$stateList['name'].'</option>';
            }
            $dataSet = $selectHtml;
        }else {
            $error = 1;
            $dataSet = '<option value="">Select State</option>';
        }
        echo json_encode(array('error'=>$error,'dataSet'=>$dataSet));
    }
    function getCityData(){
        $state_id       = $this->input->post('state_id');
        $city_id        = $this->input->post('city_id');
        $selectHtml     = '';
        $apiUrl         = $this->functions->getGlobalInfo('api_url').'api/city?state_id='.$state_id;
        $jsonResponce   = $this->functions->httpGet($apiUrl);
        $cityLists      = json_decode($jsonResponce,true);
        if($cityLists['status']==TRUE){
            $error = 0;
            $selectHtml .= '<option value="">Select City</option>';
            foreach ($cityLists['data'] as $cityList){
                if($city_id==$cityList['id']){
                    $selected="selected";
                }else{
                    $selected="";
                }
                $selectHtml .= '<option value="'.$cityList['id'].'"'.$selected.'>'.$cityList['name'].'</option>';
            }
            $dataSet = $selectHtml;
        }else {
            $error = 1;
            $dataSet = '<option value="">Select City</option>';
        }
        echo json_encode(array('error'=>$error,'dataSet'=>$dataSet));
    }
	function edit_profile()
	{
        $this->functions->checkUser($this->controller.'/',true);

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

	function doUpdateProfile()
	{
       
       $this->functions->checkUser($this->controller.'/',true);
        
		$data['first_name'] 		= $this->input->post('first_name');
		$data['last_name'] 			= $this->input->post('last_name');
        $data['member_id']          = $this->nsession->userdata('user_session_id');
        $data['address'] 			= $this->input->post('address');
        $data['country'] 			= $this->input->post('country');
        $data['state'] 				= $this->input->post('state');
        $data['city'] 				= $this->input->post('city');
        $data['zipcode'] 			= $this->input->post('zipcode');
        $data['weblink']    		= 'affiliate_website';

		/*Update Profile*/
        $apiUrl = $this->functions->getGlobalInfo('api_url').'api/editProfile';
        $params = $data;
        $jsonResponce = $this->functions->httpPost($apiUrl,$params);
        $updateResponce = json_decode($jsonResponce,true);
        $post=array();
        $llp_post = array();
        $pan_post = array();
        $id_post = array(); 
        $address_post = array();
        if($updateResponce['status']){
            $id=$this->nsession->userdata('user_session_id');
            $editProfile=$this->ModelUser->editProfile($id,$data);
          $this->nsession->set_userdata('succmsg',$updateResponce['message']);
            redirect(base_url($this->controller.'/myprofile')); 
        }else{
            $this->nsession->set_userdata('errmsg',$updateResponce['message']);
            redirect(base_url($this->controller.'/myprofile'));
        }   
	}
    function doUploadkyc(){
       $this->functions->checkUser($this->controller.'/',true);
       $post=array();
        $llp_post = array();
        $pan_post = array();
        $id_post = array();
        $documentParams['member_id'] = $this->nsession->userdata('user_session_id');
        $documentParams['gst_number'] = $this->input->post('gst_number');
        $main_url            = $this->functions->getGlobalInfo('api_url').'api/documentUpload';
        if(isset($_FILES['pan_tan_card']['name'])){
            $filename   = $_FILES['pan_tan_card']['name'];
            $filesize   = $_FILES['pan_tan_card']['size'];
            $filedata   = $_FILES['pan_tan_card']['tmp_name'];
            $headers    = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
            $postfields = array("filedata" => "@$filedata", "filename" => $filename);
            if($filesize > 0){
                $pan_post = array(
                    'pan_tan_card' => new CurlFile($_FILES["pan_tan_card"]["tmp_name"], $_FILES["pan_tan_card"]["type"], $_FILES["pan_tan_card"]["name"]),
                );
            }
        }
        if(isset($_FILES['id_proof']['name'])){
            $filename   = $_FILES['id_proof']['name'];
            $filesize   = $_FILES['id_proof']['size'];
            $filedata   = $_FILES['id_proof']['tmp_name'];
            $headers    = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
            $postfields = array("filedata" => "@$filedata", "filename" => $filename);
            if($filesize > 0){
                $id_post = array(
                    'id_proof' => new CurlFile($_FILES["id_proof"]["tmp_name"], $_FILES["id_proof"]["type"], $_FILES["id_proof"]["name"]),
                );
            }
        }
        if(isset($_FILES['address_proof']['name'])){
            $filename   = $_FILES['address_proof']['name'];
            $filesize   = $_FILES['address_proof']['size'];
            $filedata   = $_FILES['address_proof']['tmp_name'];
            $headers    = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
            $postfields = array("filedata" => "@$filedata", "filename" => $filename);
            if($filesize > 0){
                $address_post = array(
                    'address_proof' => new CurlFile($_FILES["address_proof"]["tmp_name"], $_FILES["address_proof"]["type"], $_FILES["address_proof"]["name"]),
                );
            }
        }
        if(isset($_FILES['prop_llp_certificate']['name'])){
            $filename   = $_FILES['prop_llp_certificate']['name'];
            $filesize   = $_FILES['prop_llp_certificate']['size'];
            $filedata   = $_FILES['prop_llp_certificate']['tmp_name'];
            $headers    = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
            $postfields = array("filedata" => "@$filedata", "filename" => $filename);
            if($filesize > 0){
                $llp_post = array(
                    'prop_llp_certificate' => new CurlFile($_FILES["prop_llp_certificate"]["tmp_name"], $_FILES["prop_llp_certificate"]["type"], $_FILES["prop_llp_certificate"]["name"]),
                );
            }
        }
        // Include the other $_POST fields from the form?
        $post = array_merge($pan_post,$id_post,$address_post,$llp_post,$documentParams);
        //pr($post);
        // Prepare the cURL call to upload the external script
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $main_url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        $returndata=json_decode($result,true);
        if($returndata['status']==true){
            $id=$this->nsession->userdata('user_session_id');
            $kycverified=$this->ModelUser->makeKycVerified($id);
            $this->nsession->set_userdata('succmsg',$returndata['message']);
            redirect(base_url($this->controller.'/myprofile'));
        }else{
            $this->nsession->set_userdata('errmsg',$returndata['message']);
            redirect(base_url($this->controller.'/myprofile'));
        }
    }
    function doUploadBankDetails(){
        $this->functions->checkUser($this->controller.'/',true);

        $data['member_id']           = $this->nsession->userdata('user_session_id');
        $data['bank_name']           = $this->input->post('bank_name');
        $data['account_number']      = $this->input->post('account_number');
        $data['account_holder_name'] = $this->input->post('bank_holder_name');
        $data['ifsc_code']           = $this->input->post('ifsc_code');
        $data['micr_code']           = $this->input->post('micr_code');

        /*Update Profile*/
        $apiUrl = $this->functions->getGlobalInfo('api_url').'api/updateBankDetails';
        $params = $data;
        $jsonResponce = $this->functions->httpPost($apiUrl,$params);
        $updateResponce = json_decode($jsonResponce,true);
        $post=array();
        $llp_post = array();
        $pan_post = array();
        $id_post = array(); 
        $address_post = array();
        if($updateResponce['status']){
            $id=$this->nsession->userdata('user_session_id');
            $bankDetails=$this->ModelUser->makeBankdelailsGiven($id);
          $this->nsession->set_userdata('succmsg',$updateResponce['message']);
            redirect(base_url($this->controller.'/myprofile')); 
        }else{
            $this->nsession->set_userdata('errmsg',$updateResponce['message']);
            redirect(base_url($this->controller.'/myprofile'));
        } 
    }

	function do_changepass()
	{
        $this->functions->checkUser($this->controller.'/',true);
		$this->form_validation->set_rules('old_admin_pwd', 'Old Password', 'trim|required|min_length[6]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('new_admin_pwd', 'New Password', 'trim|required|min_length[6]|max_length[20]|matches[conf_new_admin_pwd]|xss_clean');
		$this->form_validation->set_rules('conf_new_admin_pwd', 'Comfirm New Password','trim|required|min_length[6]|max_length[20]|xss_clean');

		if($this->form_validation->run() == TRUE)
		{
		    $data['old_password']   = $this->input->post('old_admin_pwd');
            $data['new_password']   = $this->input->post('conf_new_admin_pwd');
            $data['member_id']      = $this->nsession->userdata('user_session_id');

            /*Update Password*/
            $apiUrl = $this->functions->getGlobalInfo('api_url').'api/update_password';
            $params = $data;
            $jsonResponce = $this->functions->httpPost($apiUrl,$params);
            $updateResponce = json_decode($jsonResponce,true);

            if($updateResponce['status']==TRUE){
                $this->nsession->set_userdata('succmsg','Password Updated successfully.');
                redirect(base_url($this->controller.'/changepass'));
            }else{
                $this->nsession->set_userdata('errmsg',$updateResponce['message']);
                redirect(base_url($this->controller.'/changepass'));
            }
		}
		else
		{
			$this->changepass(); 
			return true;
		}
    }
    function do_transPassword()
	{
        $this->functions->checkUser($this->controller.'/',true);
		$this->form_validation->set_rules('trans_password', 'Transaction Password', 'trim|required|min_length[6]|max_length[20]|xss_clean');

		if($this->form_validation->run() == TRUE)
		{
		    $data['trans_password']   = $this->input->post('trans_password');
            $data['member_id']        = $this->nsession->userdata('user_session_id');

            /*Update Password*/
            $apiUrl = $this->functions->getGlobalInfo('api_url').'api/doUpdateTransPassword';
            $params = $data;
            $jsonResponce = $this->functions->httpPost($apiUrl,$params);
            $updateResponce = json_decode($jsonResponce,true);

            if($updateResponce['status']==TRUE){
                $this->nsession->set_userdata('succmsg','Password Updated successfully.');
                redirect(base_url($this->controller.'/transPassword'));
            }else{
                $this->nsession->set_userdata('errmsg',$updateResponce['message']);
                redirect(base_url($this->controller.'/transPassword'));
            }
		}
		else
		{
            $this->nsession->set_userdata('errmsg',validation_errors());
            redirect(base_url($this->controller.'/transPassword'));
		}
    }
    function makePyament(){
        $this->functions->checkUser($this->controller.'/',true);
        $payment_price  = $this->functions->getGlobalInfo('payment_price');
        $userDetails    = $this->ModelUser->getUserDetails();
        $payerName      = $userDetails['first_name'].' '.$userDetails['middle_name'].' '.$userDetails['last_name'];
        $redirectUrl    = base_url('user/afterPaymentResponse');
        $result         = $this->instamojo->pay_request(
                            $amount     = $payment_price , 
                            $purpose    = "Joining Fee" , 
                            $buyer_name = $payerName , 
                            $email      = $userDetails['email'] , 
                            $phone      = $userDetails['phone'],
                            $redirectUrl
                        );
        redirect($result['longurl']);
    }
    function afterPaymentResponse(){
        $this->functions->checkUser($this->controller.'/',true);
        $ndata                  = array();
        $payment_id             = $this->input->get('payment_id');
        $payment_request_id     = $this->input->get('payment_request_id');
        $returnPaymentResponse  = $this->instamojo->payment_status($payment_request_id,$payment_id);
        if($returnPaymentResponse['payments'][0]['status']=='Credit'){
            $ndata['member_id']          = $this->nsession->userdata('user_session_id');
            $ndata['amount']             = $returnPaymentResponse['payments'][0]['amount'];
            $ndata['payment_id']         = $payment_id;
            $ndata['payment_request_id'] = $payment_request_id;
            $ndata['date_created']       = date('Y-m-d H:i:s');
            $ndata['status']             = $returnPaymentResponse['payments'][0]['status'];
            if($this->ModelUser->savePaymentResponse($ndata)){
                $data['paySuccess']         = 1;
                $data['payment_id']         = $ndata['payment_id'];
                $data['payment_request_id'] =  $ndata['payment_request_id'];
                $data['payMessage']         = "Your payment of <i class='fa fa-inr' aria-hidden='true'></i>'".$returnPaymentResponse['payments'][0]['amount']."' was successfully completed";
            }
        }else{
            $ndata['member_id']          = $this->nsession->userdata('user_session_id');
            $ndata['amount']             = $returnPaymentResponse['payments'][0]['amount'];
            $ndata['payment_id']         = $payment_id;
            $ndata['payment_request_id'] = $payment_request_id;
            $ndata['date_created']       = date('Y-m-d H:i:s');
            $ndata['status']             = $returnPaymentResponse['payments'][0]['status'];
            if($this->ModelUser->saveFailedPaymentResponse($ndata)){
            $data['paySuccess']         = 0;
            $data['payment_id']         = $ndata['payment_id'];
            $data['payment_request_id'] =  $ndata['payment_request_id'];
            $data['payMessage']         = $returnPaymentResponse['payments'][0]['failure']['message'];
        }
        }

        $elements = array();
		$elements['menu']       = 'menu/index';
		$elements['topmenu']    = 'menu/topmenu';
		$elements['main']       = 'user/afterPaymentResponse';

		$element_data['main']   = $data;

		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
    }
    function invite_others(){
        $this->functions->checkUser($this->controller.'/',true);
        
        $config['base_url']     = base_url($this->controller."/invitation_by_email/");
        $data['controller']     = $this->controller;

        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = ""; 
        $config['extra_params']         = "";
         $this->nsession->set_userdata('succmsg', "");
         $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/inviteFriend';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view'); 
        $this->layout->multiple_view($elements,$element_data);
    }
    function invitation_by_email(){
        $this->functions->checkUser($this->controller.'/',true);
        
        $config['base_url']     = base_url($this->controller."/invitation_by_email/");
        $data['controller']     = $this->controller;

        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = ""; 
        $config['extra_params']         = "";
        
        $this->pagination->setAdminPaginationStyle($config);
        $start = 0;
        
        $data['controller'] = $this->controller;
        
        $param['sortType']          = $this->input->request('sortType','DESC');
        $param['sortField']         = $this->input->request('sortField','id');
        $param['searchField']       = $this->input->request('searchField','');
        $param['searchString']      = $this->input->request('searchString','');
        $param['searchText']        = $this->input->request('searchText','');
        $param['searchFromDate']    = $this->input->request('searchFromDate','');
        $param['searchToDate']      = $this->input->request('searchToDate','');
        $param['searchDisplay']     = $this->input->request('searchDisplay','20');
        $param['searchAlpha']       = $this->input->request('txt_alpha','');
        $param['searchMode']        = $this->input->request('search_mode','STRING');

        $data['recordset']      = $this->ModelUser->invitation_by_emailList($config,$start,$param);
        //pr($data['recordset']);
        $data['startRecord']    = $start;
        
        $this->pagination->initialize($config);
        
        $data['params']             = $this->nsession->userdata('ADMIN_merchants');
        $data['reload_link']        = base_url($this->controller."/index/");
        $data['search_link']        = base_url($this->controller."/index/0/1/");
        $data['add_link']           = base_url($this->controller."/addedit/0/0/");
        $data['pwd_link']           = base_url($this->controller."/change_password/{{ID}}/0");
        $data['edit_link']          = base_url($this->controller."/addedit/{{ID}}/0");
        $data['delete_link']        = base_url($this->controller."/deleteinvitationbyemail/{{ID}}/0");
        $data['resend_link']        = base_url($this->controller."/resendinvitationbyemail/{{ID}}/0");
        $data['activated_link']     = base_url($this->controller."/activate/{{ID}}/0");
        $data['inacttived_Link']    = base_url($this->controller."/inactive/{{ID}}/0");
        
        $data['total_rows']         = $config['total_rows'];
        $data['succmsg']        = $this->nsession->userdata('succmsg');
        $data['errmsg']         = $this->nsession->userdata('errmsg');

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/invitation_by_email';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view'); 
        $this->layout->multiple_view($elements,$element_data);
    }

    function addedit($id = 0)
    {
        $this->functions->checkUser($this->controller.'/');
        //if add or edit
        $startRecord    = 0;
        $contentId      = $this->uri->segment(3, 0); 
        $page           = $this->uri->segment(4, 0); 
        
        if($page > 0)
            $startRecord = $page; 

        $page = $startRecord;
        
        $data['controller']         = $this->controller;
        $data['action']             = "Add";
        $data['params']['page']     = $page;
        $data['do_addedit_link']    = base_url($this->controller."/do_addedit/".$contentId."/".$page."/");
        $data['back_link']          = base_url($this->controller."/invitation_by_email/");
        
        if($contentId > 0)
        {
            $data['adminpage_id'] = $contentId;
            $data['action'] = "Edit";
            //=================prepare DATA ==================
            $rs = $this->ModelUser->invitation_by_emaildetails($contentId);
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
            $data['action']     = "Add";
            $data['id']         = 0;
        }
        $data['succmsg'] = $this->nsession->userdata('succmsg');
        $data['errmsg'] = $this->nsession->userdata('errmsg');
        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");
        $elements = array();
        $elements['menu'] = 'menu/index';
        $elements['topmenu'] = 'menu/topmenu';
        $elements['main'] = 'user/invitation_by_emailadd_edit';
        $element_data['menu'] = $data;//array();
        $element_data['main'] = $data;
        $this->layout->setLayout('layout_main_view'); 
        $this->layout->multiple_view($elements,$element_data);
        
    }
    
    function do_addedit()
    { 
        $this->functions->checkUser($this->controller.'/');
        $contentId = $this->uri->segment(3, 0); 
        $page = $this->uri->segment(4, 0); 
                
        $invitedata['name']                     = $this->input->post('name');
        $invitedata['email_id']                 = $this->input->post('email_id');
        $invitedata['promotion_for']            = $this->input->post('promote');
        $invitedata['promotion_for_website']    = $this->input->post('promotion_for_website');
        $promotion_for_product_game             = $this->input->post('promotion_for_product_game');
        $invitedata['member_id']                = $this->nsession->userdata('user_session_id');
        $invitedata['message']                  = $this->input->post('message');
        $invitedata['date_created']             = date('Y-m-d H:i:s');
        $username=$this->nsession->userdata('user_session_username');
        if($invitedata['promotion_for']=='registration') {
            if($invitedata['promotion_for_website']=='affiliate'){
                $invitedata['promotion_item_link'] = 'http://stake4winaffiliates.com/registration?refUsername='.$username.'&invitationBy=email';
            }
            else if($invitedata['promotion_for_website']=='winskart'){
                $invitedata['promotion_item_link'] = 'http://winskart.com/?refUsername='.$username.'&invitationBy=email';
            }
            else if($invitedata['promotion_for_website']=='perfect_eleven'){
              $invitedata['promotion_item_link'] = 'http://perfect11.in/?refUsername='.$username.'&invitationBy=email';
            }
        }

        if($invitedata['promotion_for']=='item') {
            $invitedata['promotion_item_link'] = $promotion_for_product_game.'?refUsername='.$username;
        }
         if($invitedata['promotion_for']=='game') {
             $invitedata['promotion_item_link'] = $promotion_for_product_game.'?refUsername='.$username;
        }
        if($contentId==0)
        {
            //$to = 'bhanut974@gmail.com';
            $to         = $invitedata['email_id'];
            $subject    = 'Invitation Mail';
            $body       = '<tr><td>Hi, '.$invitedata['name'].'</td></tr>
                            <tr><td>Message : '.$invitedata['message'].'</td></tr>
                            <tr style="background:#fff;"><td>Click on the link :<a href="'.$invitedata['promotion_item_link'].'">Invitation Link</a></td></tr>';

            if($this->functions->mail_template($to,$subject,$body)){
                $issuccess=$this->ModelUser->addinvitation_by_email($invitedata);
                if($issuccess)
                {
                    $this->nsession->set_userdata('succmsg','Email Invitation Sent Successfully.');
                    redirect(base_url("user/invitation_by_email/"));
                }
                else
                {
                    $this->nsession->set_userdata('errmsg','Some Problem Occur.Try Again');
                    redirect(base_url("user/invitation_by_email/"));
                }
            }else{
                $this->nsession->set_userdata('errmsg','Invitation mail not sent.Try Again');
                redirect(base_url("user/invitation_by_email/"));
            }
        }
    }
    function deleteinvitationbyemail(){
        $id = $this->uri->segment(3, 0);
        $issuccess=$this->ModelUser->deleteinvitationbyemail($id);
        if($issuccess)
        {
            $this->nsession->set_userdata('succmsg','Invitation by Email Deleted successfully.');
            redirect(base_url("user/invitation_by_email/"));
        }
        else
        {
            $this->nsession->set_userdata('errmsg','Some error occured. Try again.');
            redirect(base_url("user/invitation_by_email/"));
        }
    }

    /***Promotion by social media/personal website****/
    function invitation_by_socialmedia(){
        $this->functions->checkUser($this->controller.'/',true);

        $config['base_url']     = base_url($this->controller."/invitation_by_socialmedia/");
        $data['controller']     = $this->controller;

        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = "";
        $config['extra_params']         = "";

        $this->pagination->setAdminPaginationStyle($config);
        $start = 0;

        $data['controller'] = $this->controller;

        $param['sortType']          = $this->input->request('sortType','DESC');
        $param['sortField']         = $this->input->request('sortField','id');
        $param['searchField']       = $this->input->request('searchField','');
        $param['searchString']      = $this->input->request('searchString','');
        $param['searchText']        = $this->input->request('searchText','');
        $param['searchFromDate']    = $this->input->request('searchFromDate','');
        $param['searchToDate']      = $this->input->request('searchToDate','');
        $param['searchDisplay']     = $this->input->request('searchDisplay','20');
        $param['searchAlpha']       = $this->input->request('txt_alpha','');
        $param['searchMode']        = $this->input->request('search_mode','STRING');

        $data['recordset']      = $this->ModelUser->getInvitationBySocialmedia($config,$start,$param);
        //pr($data['recordset']);
        $data['startRecord']    = $start;

        $this->pagination->initialize($config);

        $data['params']             = $this->nsession->userdata('USER_INIVITATION_BY_SOCIAL_MEDIA');
        $data['reload_link']        = base_url($this->controller."/index/");
        $data['search_link']        = base_url($this->controller."/index/0/1/");
        $data['add_link']           = base_url($this->controller."/addeditInvitationBySoaiclmedia/0/0/");
        $data['pwd_link']           = base_url($this->controller."/change_password/{{ID}}/0");
        $data['edit_link']          = base_url($this->controller."/addedit/{{ID}}/0");
        $data['delete_link']        = base_url($this->controller."/deleteInvitationBySocialmedia/{{ID}}/0");
        $data['activated_link']     = base_url($this->controller."/activate/{{ID}}/0");
        $data['inacttived_Link']    = base_url($this->controller."/inactive/{{ID}}/0");

        $data['total_rows']         = $config['total_rows'];
        $data['succmsg']        = $this->nsession->userdata('succmsg');
        $data['errmsg']         = $this->nsession->userdata('errmsg');

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/invitation_by_socialmedia';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);
    }

    function addeditInvitationBySoaiclmedia($id = 0)
    {
        $this->functions->checkUser($this->controller.'/');
        //if add or edit
        $startRecord    = 0;
        $contentId      = $this->uri->segment(3, 0);
        $page           = $this->uri->segment(4, 0);

        if($page > 0)
            $startRecord = $page;

        $page = $startRecord;

        $data['controller']         = $this->controller;
        $data['action']             = "Add";
        $data['params']['page']     = $page;
        $data['do_addedit_link']    = base_url($this->controller."/do_addeditInvitationBySoaiclmedia/".$contentId."/".$page."/");
        $data['back_link']          = base_url($this->controller."/invitation_by_email/");

        if($contentId > 0)
        {
            $data['adminpage_id'] = $contentId;
            $data['action'] = "Edit";
            //=================prepare DATA ==================
            $rs = $this->ModelUser->invitation_by_emaildetails($contentId);
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
            $data['action']     = "Add";
            $data['id']         = 0;
        }
        $data['succmsg'] = $this->nsession->userdata('succmsg');
        $data['errmsg'] = $this->nsession->userdata('errmsg');
        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");
        $elements = array();
        $elements['menu'] = 'menu/index';
        $elements['topmenu'] = 'menu/topmenu';
        $elements['main'] = 'user/addeditInvitationBySoaiclmedia';
        $element_data['menu'] = $data;//array();
        $element_data['main'] = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);

    }

    function do_addeditInvitationBySoaiclmedia()
    {
        $this->functions->checkUser($this->controller.'/');
        $contentId = $this->uri->segment(3, 0);
        $page = $this->uri->segment(4, 0);

        $file_name = $_FILES['icon']['name'];

        $new_file_name = time().$file_name;

        $config['upload_path'] 	 = file_upload_absolute_path().'social_media/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        //$config['max_size']      = 100;
        $config['max_width']     = 2000;
        $config['max_height']    = 1000;
        $config['file_name']     = $new_file_name;

        $this->upload->initialize($config);

        if(!$this->upload->do_upload('icon')) {
            $error = array('error' => $this->upload->display_errors());
            $this->nsession->set_userdata('errmsg',$error['error']);
            redirect(base_url("user/invitation_by_socialmedia"));
        }
        else{
            $data = array('upload_data' => $this->upload->data());
        }
        if($data['upload_data']['file_name']) {
            $savedata['icon'] 			= $data['upload_data']['file_name'];
        }else{
            if($this->input->request('hdFileID_icon') != "") {
                $savedata['icon']       = $this->input->request('hdFileID_icon');
            }else{
                $savedata['icon'] = "";
            }
        }

        if($contentId==0)
        {
            $savedata['member_id']      = $this->nsession->userdata('user_session_id');
            $imgPath                    = base_url().'uploads/social_media/'.$savedata['icon'];
            $shareLink                  = base_url('registration').'?refUsername='.$this->nsession->userdata('user_session_username').'&invitationBy=social';
            $savedata['link']           = '<a href="'.$shareLink.'"><img src="'.$imgPath.'"></a>';
            $savedata['created_date']   = date('Y-m-d H:i:s');
            $insert_id=$this->ModelUser->addinvitation_by_socialmedia($savedata);
            if($insert_id!='')
            {
                $this->nsession->set_userdata('succmsg','Share Link Created Successfully.');
                redirect(base_url("user/invitation_by_socialmedia_details/".$insert_id));
            }
            else
            {
                $this->nsession->set_userdata('errmsg','Some Problem Occur Try Again.');
                redirect(base_url("user/invitation_by_socialmedia/"));
            }
        }
        else
        {
            $issuccess=$this->ModelUser->editinvitation_by_email($data,$contentId);
            if($issuccess)
            {
                $this->nsession->set_userdata('succmsg','Shopkeeper edited successfully.');
                redirect(base_url("InvitationBySoaiclmediaDetails/"));
            }
            else
            {
                $this->nsession->set_userdata('errmsg','Shopkeeper update unsuccessfull.');
                redirect(base_url("shopkeeper/addedit/".$contentId));
            }
        }
    }
    function invitation_by_socialmedia_details($id = 0)
    {
        $this->functions->checkUser($this->controller.'/');
        $rs = $this->ModelUser->invitationBySocialMediaDetails($id);
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
        $elements['main'] = 'user/invitation_by_socialmedia_details';
        $element_data['menu'] = $data;//array();
        $element_data['main'] = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);

    }
    function deleteInvitationBySocialmedia(){
        $id = $this->uri->segment(3, 0);
        $issuccess=$this->ModelUser->deleteinvitationbysocialmedia($id);
        if($issuccess)
        {
            $this->nsession->set_userdata('succmsg','Invitation by Social Media Deleted successfully.');
            redirect(base_url("user/invitation_by_socialmedia/"));
        }
        else
        {
            $this->nsession->set_userdata('errmsg','Some error occured. Try again.');
            redirect(base_url("user/invitation_by_socialmedia/"));
        }
    
    }
    function master_affilate(){
         $this->functions->checkUser($this->controller.'/',true);

        $config['base_url']     = base_url($this->controller."/master_affilate/");
        $data['controller']     = $this->controller;

        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = "";
        $config['extra_params']         = "";

        $this->pagination->setAdminPaginationStyle($config);
        $start = 0;

        $data['controller'] = $this->controller;

        $param['sortType']          = $this->input->request('sortType','DESC');
        $param['sortField']         = $this->input->request('sortField','id');
        $param['searchField']       = $this->input->request('searchField','');
        $param['searchString']      = $this->input->request('searchString','');
        $param['searchText']        = $this->input->request('searchText','');
        $param['searchFromDate']    = $this->input->request('searchFromDate','');
        $param['searchToDate']      = $this->input->request('searchToDate','');
        $param['searchDisplay']     = $this->input->request('searchDisplay','20');
        $param['searchAlpha']       = $this->input->request('txt_alpha','');
        $param['searchMode']        = $this->input->request('search_mode','STRING');

        $data['recordset']      = $this->ModelUser->master_affilate($config,$start,$param);
        //pr($data['recordset']);
        $data['startRecord']    = $start;

        $this->pagination->initialize($config);

        $data['params']             = $this->nsession->userdata('USER_master_affilate');
        $data['reload_link']        = base_url($this->controller."/master_affilate/");
        $data['search_link']        = base_url($this->controller."/index/0/1/");
        $data['add_link']           = base_url($this->controller."/addedit_master_affilate/0/0/");
        $data['pwd_link']           = base_url($this->controller."/change_password/{{ID}}/0");
        $data['edit_link']          = base_url($this->controller."/addedit/{{ID}}/0");
        $data['delete_link']        = base_url($this->controller."/deletemaster_affilate/{{ID}}/0");
        $data['activated_link']     = base_url($this->controller."/activate/{{ID}}/0");
        $data['inacttived_Link']    = base_url($this->controller."/inactive/{{ID}}/0");

        $data['total_rows']         = $config['total_rows'];
        $data['succmsg']        = $this->nsession->userdata('succmsg');
        $data['errmsg']         = $this->nsession->userdata('errmsg');

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/master_affilate';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);
    }
// ============Add reffered Member Affilate=========================
 
    function addedit_master_affilate($id = 0)
    {
        $this->functions->checkUser($this->controller.'/');
        //if add or edit
        $startRecord    = 0;
        $contentId      = $this->uri->segment(3, 0);
        $page           = $this->uri->segment(4, 0);

        if($page > 0)
            $startRecord = $page;

        $page = $startRecord;

        $data['controller']         = $this->controller;
        $data['action']             = "Add";
        $data['params']['page']     = $page;
        $data['do_addedit_link']    = base_url($this->controller."/do_addedit_master_affilate/".$contentId."/".$page."/");
        $data['back_link']          = base_url($this->controller."/index/");

        if($contentId > 0)
        {
            $data['adminpage_id'] = $contentId;
            $data['action'] = "Edit";
            //=================prepare DATA ==================
            $rs = $this->ModelUser->getSingle($contentId);
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
            $data['action']     = "Add";
            $data['id']         = 0;
        }
        $data['countryData'] = $this->functions->getCountry();
        $data['succmsg'] = $this->nsession->userdata('succmsg');
        $data['errmsg'] = $this->nsession->userdata('errmsg');
        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");
        $elements = array();
        $elements['menu'] = 'menu/index';
        $elements['topmenu'] = 'menu/topmenu';
        $elements['main'] = 'user/addedit_master_affilate';
        $element_data['menu'] = $data;//array();
        $element_data['main'] = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);

    }
    function doRegistration(){
        $config = array(
            array(
                'field' => 'first_name',
                'label' => 'First Name',
                'rules' => 'trim|xss_clean|required'
            ),array(
                'field' => 'last_name',
                'label' => 'Last Name',
                'rules' => 'trim|xss_clean|required'
            ),array(
                'field' => 'dob',
                'label' => 'Date of Birth',
                'rules' => 'xss_clean|required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email ID',
                'rules' => 'trim|xss_clean|required'
            ),
            array(
                'field' => 'phone',
                'label' => 'Phone Number',
                'rules' => 'trim|xss_clean|required'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|xss_clean|required'
            ),
            array(
                'field' => 'password_confirm',
                'label' => 'Password Confirmation',
                'rules' => 'trim|xss_clean|required|matches[password]'
            )
        );
        $this->form_validation->set_rules($config);
        if (!$this->form_validation->run() == FALSE)
        {
             $memcondition= $this->input->post('member_condition');
            if($member_condition==1){
            $zipcode         =$this->input->post('zipcode');
            $checkZipcode=$this->ModelUser->checkZipcode($zipcode);
            if($checkZipcode<=0){
            $data['email']          = $this->input->post('email');
            $data['phone']          = $this->input->post('phone');

            
              $apiUrl = $this->functions->getGlobalInfo('api_url').'api/check_email';
              $params = array(
                'email'=>$data['email']
                );
                $jsonResponce = $this->functions->httpPost($apiUrl,$params);
                $arrResponce = json_decode($jsonResponce,true);
                if($arrResponce['status']==1){
                  $apiUrl = $this->functions->getGlobalInfo('api_url').'api/check_phone';
                  $params = array(
                    'phone'=>$data['phone']
                    );
                    $jsonResponce = $this->functions->httpPost($apiUrl,$params);
                    $arrResponce = json_decode($jsonResponce,true);
                    if($arrResponce['status']==1){
                        $error          = 0;
                        $message        = "Success";
                        $redirect_url   = "";
                    }
                    else{
                        $error          = 1;
                        $message        = $arrResponce['message'];
                        $redirect_url   = "";
                    }
                }
                else{
                    $error          = 1;
                    $message        = $arrResponce['message'];
                    $redirect_url   = "";
                }
            }
            else{
                    $error          = 1;
                    $message        = "Another Master Affiliate already assign to this zipcode";
                    $redirect_url   = "";
             }
         }
             else{
                $data['email']          = $this->input->post('email');
            $data['phone']          = $this->input->post('phone');

            
              $apiUrl = $this->functions->getGlobalInfo('api_url').'api/check_email';
              $params = array(
                'email'=>$data['email']
                );
                $jsonResponce = $this->functions->httpPost($apiUrl,$params);
                $arrResponce = json_decode($jsonResponce,true);
                if($arrResponce['status']==1){
                  $apiUrl = $this->functions->getGlobalInfo('api_url').'api/check_phone';
                  $params = array(
                    'phone'=>$data['phone']
                    );
                    $jsonResponce = $this->functions->httpPost($apiUrl,$params);
                    $arrResponce = json_decode($jsonResponce,true);
                    if($arrResponce['status']==1){
                        $error          = 0;
                        $message        = "Success";
                        $redirect_url   = "";
                    }
                    else{
                        $error          = 1;
                        $message        = $arrResponce['message'];
                        $redirect_url   = "";
                    }
                }
                else{
                    $error          = 1;
                    $message        = $arrResponce['message'];
                    $redirect_url   = "";
                }
             }
         }
        else
        {
            $error          = 1;
            $message        = validation_errors();
            $redirect_url   = "";
        }
        echo json_encode(array('error'=>$error,'message'=>$message,'redirect_url'=>$redirect_url));
        exit;
    
    
    }
    function sendVerifcationCodeOnMobile(){
        $returnData = '';
        $mobile_nbr = $this->input->post('mobile_nbr');
        
        $apiUrl = $this->functions->getGlobalInfo('api_url').'api/check_phone';
          $params = array(
            'phone'=>$mobile_nbr
            );
            $jsonResponce = $this->functions->httpPost($apiUrl,$params);
            $arrResponce = json_decode($jsonResponce,true);
        if($arrResponce['status']==1){
            $verificion_code = $this->functions->randomNo(6);
            $this->nsession->set_userdata('mobile_verify_code',$verificion_code);
            $message ="Your OTP code for stake4winaffiliates registration is $verificion_code";
            $this->functions->sendMsg($mobile_nbr,$message);
            $error = 0;
            $message = "";

        }else{
            $error = 1;
            $message= $arrResponce['message'];
        }
        echo json_encode(array('error'=>$error,'message'=>$message));
        exit;
    }
    function validateVerificationCode(){
      $mobile_verify_code  = $this->nsession->userdata('mobile_verify_code');
       $enter_code          = trim($this->input->post('enter_code'));
       if($mobile_verify_code==$enter_code){
           $error = 0;
           $this->nsession->set_userdata('mobile_verify_code','');
       }else{
           $error = 1;
       }
        echo json_encode(array('error'=>$error));
        exit;
    }
    function do_addedit_master_affilate()
    {
        $this->functions->checkUser($this->controller.'/');
        $contentId = $this->uri->segment(3, 0); 
        $page = $this->uri->segment(4, 0); 
        if($contentId==0){
            $this->form_validation->set_rules('first_name', 'First name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('phone', 'Phone Number', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password_confirm', 'Re-Password', 'trim|required|matches[password]|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('first_name', 'First name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('phone', 'Phone Number', 'trim|required|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        }
        
        $this->form_validation->set_error_delimiters('<ul class="parsley-errors-list filled error text-left" ><li class="parsley-required">', '</li></ul>');
        if($this->form_validation->run() == TRUE)
        {
            if($contentId==0){
                $data['member_type']                = 3;
                $data['refer_by']                   = $this->nsession->userdata('user_session_id');
                $data['first_name']                 = $this->input->post('first_name');
                $data['last_name']                  = $this->input->post('last_name');
                $data['middle_name']                = $this->input->post('middle_name');
                $data['dob']                        = date('Y-m-d',strtotime($this->input->post('dob')));
                $data['member_individual_corporate']= $this->input->post('member_individual_corporate');
                $data['email']                      = $this->input->post('email');
                $data['phone']                      = $this->input->post('phone');
                $data['password']                   = $this->input->post('password');
                $data['country']                    = $this->input->post('country');
                $data['state']                      = $this->input->post('state');
                $data['city']                       = $this->input->post('city');
                $data['address']                    = $this->input->post('address');
                $data['country_for_super_affiliate']= $this->input->post('countrywork');
                $data['state_for_super_affiliate']  = $this->input->post('statework');
                $data['city_for_super_affiliate']   = $this->input->post('citywork');
                $data['zipcode_for_master_affiliate']= $this->input->post('zipcode');
                $data['is_conditional']             = $this->input->post('member_condition');
                $data['created_date']               = date('Y-m-d h:i:s');
                $data['expiry_date']                = date('Y-m-d',strtotime('+7 days'));
            }else{
                $data['first_name']                 = $this->input->post('first_name');
                $data['last_name']                  = $this->input->post('last_name');
                $data['middle_name']                = $this->input->post('middle_name');
                $data['dob']                        = date('Y-m-d',strtotime($this->input->post('dob')));
                $data['member_individual_corporate']= $this->input->post('member_individual_corporate');
                $data['email']                      = $this->input->post('email');
                $data['phone']                      = $this->input->post('phone');
                $data['password']                   = $this->input->post('password');
                $data['country']                    = $this->input->post('country');
                $data['state']                      = $this->input->post('state');
                $data['city']                       = $this->input->post('city');
                $data['address']                    = $this->input->post('address');
                $data['modified_date']              = date('Y-m-d h:i:s');
            }
            if($contentId==0){

                $apiUrl = $this->functions->getGlobalInfo('api_url').'api/register';
                $params = array(
                    'member_individual_corporate'=>$data['member_individual_corporate'],
                    'member_type'=>$data['member_type'],
                    'email'=>$data['email'],
                    'password'=>$data['password'],
                    'first_name'=>$data['first_name'],
                    'last_name'=>$data['last_name'],
                    'middle_name'=>$data['middle_name'],
                    'dob'=>$data['dob'],
                    'country'=>$data['country'],
                    'state'=>$data['state'],
                    'city'=>$data['city'],
                    'device_type'=>'Web',
                    'weblink'=>'affiliate_website',
                    'phone'=>$data['phone'],
                    'address'=>$data['address']
                );
                $jsonResponce = $this->functions->httpPost($apiUrl,$params);
                $arrResponce = json_decode($jsonResponce,true);
                if($arrResponce['status']==1){
                    $data['reference_id']   = $arrResponce['data']['reference_id'];
                    $data['member_id']      = $arrResponce['data']['member_id'];
                    if($this->ModelUser->save_refered_MasterAffiliate($data)){
                    $phone_no     = $data['phone'];
                    $reference_id =$data['reference_id'];
                    $password     =$data['password'];
                    $message      = "Your login details for stake4winaffiliates Username:".$reference_id." and password:".$password."";
                    $this->functions->sendMsg($phone_no,$message);
                        $this->nsession->set_userdata('succmsg','Master affiliate registered Successfully.');
                        redirect(base_url($this->controller."/master_affilate/"));
                    }else{
                        $this->nsession->set_userdata('errmsg','Some Problem occur while saving data.Please try again');
                        redirect(base_url($this->controller."/master_affilate/"));
                    }
                }else{
                    $this->nsession->set_userdata('errmsg',$arrResponce['message']);
                    redirect(base_url($this->controller."/master_affilate/"));
                }
            }else{
                $this->ModelMemberMaster->editContent($data,$contentId);
                $this->nsession->set_userdata('succmsg','Master affiliate updated successfully.');
                redirect(base_url($this->controller."/master_affilate/"));
            }
            
        }else{
            $this->nsession->set_userdata('errmsg',validation_errors());
            redirect(base_url($this->controller."/master_affilate/"));
        }   

    }

//====================End of Add reffered Member Affilate==========

        function general_affilate(){
         $this->functions->checkUser($this->controller.'/',true);

        $config['base_url']     = base_url($this->controller."/general_affilate/");
        $data['controller']     = $this->controller;

        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = "";
        $config['extra_params']         = "";

        $this->pagination->setAdminPaginationStyle($config);
        $start = 0;

        $data['controller'] = $this->controller;

        $param['sortType']          = $this->input->request('sortType','DESC');
        $param['sortField']         = $this->input->request('sortField','id');
        $param['searchField']       = $this->input->request('searchField','');
        $param['searchString']      = $this->input->request('searchString','');
        $param['searchText']        = $this->input->request('searchText','');
        $param['searchFromDate']    = $this->input->request('searchFromDate','');
        $param['searchToDate']      = $this->input->request('searchToDate','');
        $param['searchDisplay']     = $this->input->request('searchDisplay','20');
        $param['searchAlpha']       = $this->input->request('txt_alpha','');
        $param['searchMode']        = $this->input->request('search_mode','STRING');

        $data['recordset']      = $this->ModelUser->master_affilate($config,$start,$param);
        //pr($data['recordset']);
        $data['startRecord']    = $start;

        $this->pagination->initialize($config);

        $data['params']             = $this->nsession->userdata('USER_general_affilate');
        $data['reload_link']        = base_url($this->controller."/general_affilate/");
        $data['search_link']        = base_url($this->controller."/index/0/1/");
        $data['add_link']           = base_url($this->controller."/addedit_general_affilate/0/0/");
        $data['pwd_link']           = base_url($this->controller."/change_password/{{ID}}/0");
        $data['edit_link']          = base_url($this->controller."/addedit/{{ID}}/0");
        $data['delete_link']        = base_url($this->controller."/deletegeneral_affilate/{{ID}}/0");
        $data['activated_link']     = base_url($this->controller."/activate/{{ID}}/0");
        $data['inacttived_Link']    = base_url($this->controller."/inactive/{{ID}}/0");

        $data['total_rows']         = $config['total_rows'];
        $data['succmsg']        = $this->nsession->userdata('succmsg');
        $data['errmsg']         = $this->nsession->userdata('errmsg');

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/general_affilate';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);
    }
//============Add reffered General Affilate=========================
 
    function addedit_general_affilate($id = 0)
    {
        $this->functions->checkUser($this->controller.'/');
        //if add or edit
        $startRecord    = 0;
        $contentId      = $this->uri->segment(3, 0);
        $page           = $this->uri->segment(4, 0);

        if($page > 0)
            $startRecord = $page;

        $page = $startRecord;

        $data['controller']         = $this->controller;
        $data['action']             = "Add";
        $data['params']['page']     = $page;
        $data['do_addedit_link']    = base_url($this->controller."/do_addedit_general_affilate/".$contentId."/".$page."/");
        $data['back_link']          = base_url($this->controller."/index/");

        if($contentId > 0)
        {
            $data['adminpage_id'] = $contentId;
            $data['action'] = "Edit";
            //=================prepare DATA ==================
            $rs = $this->ModelUser->getSingle($contentId);
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
            $data['action']     = "Add";
            $data['id']         = 0;
        }
        $data['countryData'] = $this->functions->getCountry();
        $data['succmsg'] = $this->nsession->userdata('succmsg');
        $data['errmsg'] = $this->nsession->userdata('errmsg');
        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");
        $elements = array();
        $elements['menu'] = 'menu/index';
        $elements['topmenu'] = 'menu/topmenu';
        $elements['main'] = 'user/addedit_general_affilate';
        $element_data['menu'] = $data;//array();
        $element_data['main'] = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);

    }
    function do_addedit_general_affilate()
    {
        $this->functions->checkUser($this->controller.'/');
        $contentId = $this->uri->segment(3, 0); 
        $page = $this->uri->segment(4, 0); 
        if($contentId==0){
            $this->form_validation->set_rules('first_name', 'First name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('phone', 'Phone Number', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password_confirm', 'Re-Password', 'trim|required|matches[password]|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        }else{
            $this->form_validation->set_rules('first_name', 'First name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('phone', 'Phone Number', 'trim|required|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
        }
        
        $this->form_validation->set_error_delimiters('<ul class="parsley-errors-list filled error text-left" ><li class="parsley-required">', '</li></ul>');
        if($this->form_validation->run() == TRUE)
        {
            if($contentId==0){
                $data['member_type']                = 1;
                $data['refer_by']                   = $this->nsession->userdata('user_session_id');
                $data['first_name']                 = $this->input->post('first_name');
                $data['last_name']                  = $this->input->post('last_name');
                $data['middle_name']                = $this->input->post('middle_name');
                $data['dob']                        = date('Y-m-d',strtotime($this->input->post('dob')));
                $data['member_individual_corporate']= $this->input->post('member_individual_corporate');
                $data['email']                      = $this->input->post('email');
                $data['phone']                      = $this->input->post('phone');
                $data['password']                   = $this->input->post('password');
                $data['country']                    = $this->input->post('country');
                $data['state']                      = $this->input->post('state');
                $data['city']                       = $this->input->post('city');
                $data['address']                    = $this->input->post('address');
                $data['created_date']               = date('Y-m-d h:i:s');
                $data['expiry_date']                = date('Y-m-d',strtotime('+7 days'));
            }else{
                $data['first_name']                 = $this->input->post('first_name');
                $data['last_name']                  = $this->input->post('last_name');
                $data['middle_name']                = $this->input->post('middle_name');
                $data['dob']                        = date('Y-m-d',strtotime($this->input->post('dob')));
                $data['member_individual_corporate']= $this->input->post('member_individual_corporate');
                $data['email']                      = $this->input->post('email');
                $data['phone']                      = $this->input->post('phone');
                $data['password']                   = $this->input->post('password');
                $data['country']                    = $this->input->post('country');
                $data['state']                      = $this->input->post('state');
                $data['city']                       = $this->input->post('city');
                $data['address']                    = $this->input->post('address');
                $data['modified_date']              = date('Y-m-d h:i:s');
            }
            if($contentId==0){

                $apiUrl = $this->functions->getGlobalInfo('api_url').'api/register';
                $params = array(
                    'member_individual_corporate'=>$data['member_individual_corporate'],
                    'refer_by'=>$data['refer_by'],
                    'member_type'=>$data['member_type'],
                    'email'=>$data['email'],
                    'password'=>$data['password'],
                    'first_name'=>$data['first_name'],
                    'last_name'=>$data['last_name'],
                    'middle_name'=>$data['middle_name'],
                    'dob'=>$data['dob'],
                    'country'=>$data['country'],
                    'state'=>$data['state'],
                    'city'=>$data['city'],
                    'device_type'=>'Web',
                    'weblink'=>'affiliate_website',
                    'phone'=>$data['phone'],
                    'address'=>$data['address']
                );
                $jsonResponce = $this->functions->httpPost($apiUrl,$params);
                $arrResponce = json_decode($jsonResponce,true);
                if($arrResponce['status']==1){
                    $data['reference_id']   = $arrResponce['data']['reference_id'];
                    $data['member_id']      = $arrResponce['data']['member_id'];
                    if($this->ModelUser->save_refered_GeneralAffiliate($data)){
                    $phone_no     = $data['phone'];
                    $reference_id =$data['reference_id'];
                    $password     =$data['password'];
                    $message      = "Your login details for stake4winaffiliates Username:".$reference_id." and password:".$password."";
                    $this->functions->sendMsg($phone_no,$message);
                        $this->nsession->set_userdata('succmsg','General affiliate registered Successfully.');
                        redirect(base_url($this->controller."/general_affilate/"));
                    }else{
                        $this->nsession->set_userdata('errmsg','Some Problem occur while saving data.Please try again');
                        redirect(base_url($this->controller."/general_affilate/"));
                    }
                }else{
                    $this->nsession->set_userdata('errmsg',$arrResponce['message']);
                    redirect(base_url($this->controller."/general_affilate/"));
                }
            }else{
                $this->ModelMemberMaster->editContent($data,$contentId);
                $this->nsession->set_userdata('succmsg','General affiliate updated successfully.');
                redirect(base_url($this->controller."/general_affilate/"));
            }
            
        }else{
            $this->nsession->set_userdata('errmsg',validation_errors());
            redirect(base_url($this->controller."/general_affilate/"));
        }   

    }

//====================End of Add reffered General Affilate==========
   
        function refer_user(){
         $this->functions->checkUser($this->controller.'/',true);

        $config['base_url']     = base_url($this->controller."/refer_user/");
        $data['controller']     = $this->controller;

        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = "";
        $config['extra_params']         = "";

        $this->pagination->setAdminPaginationStyle($config);
        $start = 0;

        $data['controller'] = $this->controller;

        $param['sortType']          = $this->input->request('sortType','DESC');
        $param['sortField']         = $this->input->request('sortField','id');
        $param['searchField']       = $this->input->request('searchField','');
        $param['searchString']      = $this->input->request('searchString','');
        $param['searchText']        = $this->input->request('searchText','');
        $param['searchFromDate']    = $this->input->request('searchFromDate','');
        $param['searchToDate']      = $this->input->request('searchToDate','');
        $param['searchDisplay']     = $this->input->request('searchDisplay','20');
        $param['searchAlpha']       = $this->input->request('txt_alpha','');
        $param['searchMode']        = $this->input->request('search_mode','STRING');

        $data['recordset']      = $this->ModelUser->refer_user($config,$start,$param);
        //pr($data['recordset']);
        $data['startRecord']    = $start;

        $this->pagination->initialize($config);

        $data['params']             = $this->nsession->userdata('USER_refer_user');
        $data['reload_link']        = base_url($this->controller."/refer_user/");
        $data['search_link']        = base_url($this->controller."/index/0/1/");
        $data['add_link']           = base_url($this->controller."/new_user_addedit/0/0/");
        $data['pwd_link']           = base_url($this->controller."/change_password/{{ID}}/0");
        $data['edit_link']          = base_url($this->controller."/new_user_addedit/{{ID}}/0");
        $data['delete_link']        = base_url($this->controller."/delete_refer_user/{{ID}}/0");
        $data['activated_link']     = base_url($this->controller."/activate/{{ID}}/0");
        $data['inacttived_Link']    = base_url($this->controller."/inactive/{{ID}}/0");

        $data['total_rows']         = $config['total_rows'];
        $data['succmsg']        = $this->nsession->userdata('succmsg');
        $data['errmsg']         = $this->nsession->userdata('errmsg');

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/refer_user';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);
    }
//============Add reffered General Affilate=========================
 
    function new_user_addedit($id = 0)
    {
        $this->functions->checkUser($this->controller.'/');
        //if add or edit
        $startRecord    = 0;
        $contentId      = $this->uri->segment(3, 0); 
        $page           = $this->uri->segment(4, 0); 
        
        if($page > 0)
            $startRecord = $page; 

        $page = $startRecord;
        
        $data['controller']         = $this->controller;
        $data['action']             = "Add";
        $data['params']['page']     = $page;
        $data['do_addedit_link']    = base_url($this->controller."/do_new_user_addedit/".$contentId."/".$page."/");
        $data['back_link']          = base_url($this->controller."/new_user/");
        
        if($contentId > 0)
        {
            $data['adminpage_id'] = $contentId;
            $data['action'] = "Edit";
            //=================prepare DATA ==================
            $rs = $this->ModelUser->getSingle($contentId);
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
            $data['action']     = "Add";
            $data['id']         = 0;
        }

        $data['countryData'] = $this->functions->getCountry();

        $data['succmsg'] = $this->nsession->userdata('succmsg');
        $data['errmsg'] = $this->nsession->userdata('errmsg');
        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");
        $elements = array();
        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/add_edit_refer_user';
        $element_data['menu']   = $data;//array();
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view'); 
        $this->layout->multiple_view($elements,$element_data);
        
    }
    function do_new_user_addedit()
    {
        $this->functions->checkUser($this->controller.'/');
        $contentId = $this->uri->segment(3, 0); 
        $page = $this->uri->segment(4, 0); 
        if($contentId==0){
                $data['refer_by']                   = $this->nsession->userdata('user_session_id');
                $data['name']                       = $this->input->post('full_name');
                $data['email']                      = $this->input->post('email');
                $data['phone']                      = $this->input->post('phone');
                $data['country']                    = $this->input->post('country');
                $data['state']                      = $this->input->post('state');
                $data['city']                       = $this->input->post('city');
                $data['created_date']               = date('Y-m-d h:i:s');
            }else{
               $data['name']                        = $this->input->post('full_name');
                $data['email']                      = $this->input->post('email');
                $data['phone']                      = $this->input->post('phone');
                $data['country']                    = $this->input->post('country');
                $data['state']                      = $this->input->post('state');
                $data['city']                       = $this->input->post('city');
                $data['modified_date']               = date('Y-m-d h:i:s');
            }
            if($contentId==0){
                    if($this->ModelUser->saveNewUser($data)){
                        // $phone_no     = $data['phone'];
                        // $reference_id =$data['reference_id'];
                        // $password     =$data['password'];
                        // $message      = "Your login details for stake4winaffiliates Username:".$reference_id." and password:".$password."";
                        // $this->functions->sendMsg($phone_no,$message);
                        $this->nsession->set_userdata('succmsg','User Added Successfully.');
                        redirect(base_url($this->controller."/refer_user/"));
                    }else{
                        $this->nsession->set_userdata('errmsg','Some Problem occur while saving data.Please try again');
                        redirect(base_url($this->controller."/refer_user/"));
                    }
                }
            else{
            $this->ModelUser->editNewUser($data,$contentId);
                $this->nsession->set_userdata('succmsg','User updated successfully.');
                redirect(base_url($this->controller."/refer_user/"));
            }
            
    }
    function delete_refer_user($id){
        $issuccess=$this->ModelUser->delete_refer_user($id);
        if($issuccess)
        {
            $this->nsession->set_userdata('succmsg','User Deleted successfully.');
            redirect(base_url("user/refer_user/"));
        }
        else
        {
            $this->nsession->set_userdata('errmsg','Some error occured. Try again.');
            redirect(base_url("user/refer_user/"));
        }
    }

//====================End of Add reffered General Affilate==========
    function resendinvitationbyemail($id = 0)
    {
        $this->functions->checkUser($this->controller.'/');
        $startRecord    = 0;
        $contentId      = $this->uri->segment(3, 0);
        $page           = $this->uri->segment(4, 0);
       
        $invitedata=$this->ModelUser->resendinvitation_by_email($contentId);
        
      
            //$to = 'bhanut974@gmail.com';
            $to         = $invitedata['email_id'];
            $subject    = 'Invitation Mail';
            $body       = '<tr><td>Hi, '.$invitedata['name'].'</td></tr>
                            <tr><td>Message : '.$invitedata['message'].'</td></tr>
                            <tr style="background:#fff;"><td>Click on the link :<a href="'.$invitedata['promotion_item_link'].'">Invitation Link</a></td></tr>';

            if($this->functions->mail_template($to,$subject,$body)){

                if($invitedata)
                {
                    $this->nsession->set_userdata('succmsg','Email Invitation Resent Successfully.');
                    redirect(base_url("user/invitation_by_email/"));
                }
                else
                {
                    $this->nsession->set_userdata('errmsg','Some Problem Occur.Try Again');
                    redirect(base_url("user/invitation_by_email/"));
                }
            }else{
                $this->nsession->set_userdata('errmsg','Invitation mail not resent.Try Again');
                redirect(base_url("user/invitation_by_email/"));
            }
        
    }
    function game_wallet(){
         $this->functions->checkUser($this->controller.'/',true);

        $config['base_url']     = base_url($this->controller."/game_wallet/");
        $data['controller']     = $this->controller;

        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = "";
        $config['extra_params']         = "";

        $this->pagination->setAdminPaginationStyle($config);
        $start = 0;

        $data['controller'] = $this->controller;

        $param['sortType']          = $this->input->request('sortType','DESC');
        $param['sortField']         = $this->input->request('sortField','id');
        $param['searchField']       = $this->input->request('searchField','');
        $param['searchString']      = $this->input->request('searchString','');
        $param['searchText']        = $this->input->request('searchText','');
        $param['searchFromDate']    = $this->input->request('searchFromDate','');
        $param['searchToDate']      = $this->input->request('searchToDate','');
        $param['searchDisplay']     = $this->input->request('searchDisplay','20');
        $param['searchAlpha']       = $this->input->request('txt_alpha','');
        $param['searchMode']        = $this->input->request('search_mode','STRING');

        $data['recordset']      = $this->ModelUser->game_wallet($config,$start,$param);
        //pr($data['recordset']);
        $data['startRecord']    = $start;

        $this->pagination->initialize($config);

        $data['params']             = $this->nsession->userdata('USER_game_wallet');
        $data['reload_link']        = base_url($this->controller."/game_wallet/");
        $data['search_link']        = base_url($this->controller."/game_wallet/0/1/");

        $data['total_rows']         = $config['total_rows'];
        $data['succmsg']        = $this->nsession->userdata('succmsg');
        $data['errmsg']         = $this->nsession->userdata('errmsg');

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/game_wallet';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);
    }
    function shopping_wallet(){
        $this->functions->checkUser($this->controller.'/',true);

        $config['base_url']     = base_url($this->controller."/shopping_wallet/");
        $data['controller']     = $this->controller;

        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = "";
        $config['extra_params']         = "";

        $this->pagination->setAdminPaginationStyle($config);
        $start = 0;

        $data['controller'] = $this->controller;

        $param['sortType']          = $this->input->request('sortType','DESC');
        $param['sortField']         = $this->input->request('sortField','id');
        $param['searchField']       = $this->input->request('searchField','');
        $param['searchString']      = $this->input->request('searchString','');
        $param['searchText']        = $this->input->request('searchText','');
        $param['searchFromDate']    = $this->input->request('searchFromDate','');
        $param['searchToDate']      = $this->input->request('searchToDate','');
        $param['searchDisplay']     = $this->input->request('searchDisplay','20');
        $param['searchAlpha']       = $this->input->request('txt_alpha','');
        $param['searchMode']        = $this->input->request('search_mode','STRING');

        $data['recordset']      = $this->ModelUser->shopping_wallet($config,$start,$param);
        //pr($data['recordset']);
        $data['startRecord']    = $start;

        $this->pagination->initialize($config);

        $data['params']             = $this->nsession->userdata('USER_shopping_wallet');
        $data['reload_link']        = base_url($this->controller."/shopping_wallet/");
        $data['search_link']        = base_url($this->controller."/shopping_wallet/0/1/");

        $data['total_rows']         = $config['total_rows'];
        $data['succmsg']        = $this->nsession->userdata('succmsg');
        $data['errmsg']         = $this->nsession->userdata('errmsg');

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/shopping_wallet';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);
    }
    function rummy_wallet(){
         $this->functions->checkUser($this->controller.'/',true);

        $config['base_url']     = base_url($this->controller."/rummy_wallet/");
        $data['controller']     = $this->controller;

        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = "";
        $config['extra_params']         = "";

        $this->pagination->setAdminPaginationStyle($config);
        $start = 0;

        $data['controller'] = $this->controller;

        $param['sortType']          = $this->input->request('sortType','DESC');
        $param['sortField']         = $this->input->request('sortField','id');
        $param['searchField']       = $this->input->request('searchField','');
        $param['searchString']      = $this->input->request('searchString','');
        $param['searchText']        = $this->input->request('searchText','');
        $param['searchFromDate']    = $this->input->request('searchFromDate','');
        $param['searchToDate']      = $this->input->request('searchToDate','');
        $param['searchDisplay']     = $this->input->request('searchDisplay','20');
        $param['searchAlpha']       = $this->input->request('txt_alpha','');
        $param['searchMode']        = $this->input->request('search_mode','STRING');

        $data['recordset']      = $this->ModelUser->rummy_wallet($config,$start,$param);
        //pr($data['recordset']);
        $data['startRecord']    = $start;

        $this->pagination->initialize($config);

        $data['params']             = $this->nsession->userdata('USER_rummy_wallet');
        $data['reload_link']        = base_url($this->controller."/rummy_wallet/");
        $data['search_link']        = base_url($this->controller."/rummy_wallet/0/1/");

        $data['total_rows']         = $config['total_rows'];
        $data['succmsg']        = $this->nsession->userdata('succmsg');
        $data['errmsg']         = $this->nsession->userdata('errmsg');

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/rummy_wallet';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);
    }
    function pay4health_wallet(){
        $this->functions->checkUser($this->controller.'/',true);

        $config['base_url']     = base_url($this->controller."/pay4health_wallet/");
        $data['controller']     = $this->controller;

        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = "";
        $config['extra_params']         = "";

        $this->pagination->setAdminPaginationStyle($config);
        $start = 0;

        $data['controller'] = $this->controller;

        $param['sortType']          = $this->input->request('sortType','DESC');
        $param['sortField']         = $this->input->request('sortField','id');
        $param['searchField']       = $this->input->request('searchField','');
        $param['searchString']      = $this->input->request('searchString','');
        $param['searchText']        = $this->input->request('searchText','');
        $param['searchFromDate']    = $this->input->request('searchFromDate','');
        $param['searchToDate']      = $this->input->request('searchToDate','');
        $param['searchDisplay']     = $this->input->request('searchDisplay','20');
        $param['searchAlpha']       = $this->input->request('txt_alpha','');
        $param['searchMode']        = $this->input->request('search_mode','STRING');

        $data['recordset']      = $this->ModelUser->pay4health_wallet($config,$start,$param);
        //pr($data['recordset']);
        $data['startRecord']    = $start;

        $this->pagination->initialize($config);

        $data['params']             = $this->nsession->userdata('USER_pay4health_wallet');
        $data['reload_link']        = base_url($this->controller."/pay4health_wallet/");
        $data['search_link']        = base_url($this->controller."/pay4health_wallet/0/1/");

        $data['total_rows']         = $config['total_rows'];
        $data['succmsg']        = $this->nsession->userdata('succmsg');
        $data['errmsg']         = $this->nsession->userdata('errmsg');

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/pay4health_wallet';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);
    }
    function rblandster_wallet(){
         $this->functions->checkUser($this->controller.'/',true);

        $config['base_url']     = base_url($this->controller."/rblandster_wallet/");
        $data['controller']     = $this->controller;

        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = "";
        $config['extra_params']         = "";

        $this->pagination->setAdminPaginationStyle($config);
        $start = 0;

        $data['controller'] = $this->controller;

        $param['sortType']          = $this->input->request('sortType','DESC');
        $param['sortField']         = $this->input->request('sortField','id');
        $param['searchField']       = $this->input->request('searchField','');
        $param['searchString']      = $this->input->request('searchString','');
        $param['searchText']        = $this->input->request('searchText','');
        $param['searchFromDate']    = $this->input->request('searchFromDate','');
        $param['searchToDate']      = $this->input->request('searchToDate','');
        $param['searchDisplay']     = $this->input->request('searchDisplay','20');
        $param['searchAlpha']       = $this->input->request('txt_alpha','');
        $param['searchMode']        = $this->input->request('search_mode','STRING');

        $data['recordset']      = $this->ModelUser->rblandster_wallet($config,$start,$param);
        //pr($data['recordset']);
        $data['startRecord']    = $start;

        $this->pagination->initialize($config);

        $data['params']             = $this->nsession->userdata('USER_rblandster_wallet');
        $data['reload_link']        = base_url($this->controller."/rblandster_wallet/");
        $data['search_link']        = base_url($this->controller."/rblandster_wallet/0/1/");

        $data['total_rows']         = $config['total_rows'];
        $data['succmsg']        = $this->nsession->userdata('succmsg');
        $data['errmsg']         = $this->nsession->userdata('errmsg');

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/rblandster_wallet';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);
    }
    function chess_wallet(){
        $this->functions->checkUser($this->controller.'/',true);

        $config['base_url']     = base_url($this->controller."/chess_wallet/");
        $data['controller']     = $this->controller;

        $config['uri_segment']          = 3;
        $config['num_links']            = 10;
        $config['page_query_string']    = false;
        $config['extra_params']         = "";
        $config['extra_params']         = "";

        $this->pagination->setAdminPaginationStyle($config);
        $start = 0;

        $data['controller'] = $this->controller;

        $param['sortType']          = $this->input->request('sortType','DESC');
        $param['sortField']         = $this->input->request('sortField','id');
        $param['searchField']       = $this->input->request('searchField','');
        $param['searchString']      = $this->input->request('searchString','');
        $param['searchText']        = $this->input->request('searchText','');
        $param['searchFromDate']    = $this->input->request('searchFromDate','');
        $param['searchToDate']      = $this->input->request('searchToDate','');
        $param['searchDisplay']     = $this->input->request('searchDisplay','20');
        $param['searchAlpha']       = $this->input->request('txt_alpha','');
        $param['searchMode']        = $this->input->request('search_mode','STRING');

        $data['recordset']      = $this->ModelUser->chess_wallet($config,$start,$param);
        //pr($data['recordset']);
        $data['startRecord']    = $start;

        $this->pagination->initialize($config);

        $data['params']             = $this->nsession->userdata('USER_chess_wallet');
        $data['reload_link']        = base_url($this->controller."/chess_wallet/");
        $data['search_link']        = base_url($this->controller."/chess_wallet/0/1/");

        $data['total_rows']         = $config['total_rows'];
        $data['succmsg']        = $this->nsession->userdata('succmsg');
        $data['errmsg']         = $this->nsession->userdata('errmsg');

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/chess_wallet';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);
    }
    function geStateData(){
        $country_id = $this->input->post('country_id');
        $stateData = $this->functions->getState($country_id);
        if(count($stateData)!=''){
            echo "<option value=''>Select State</option>";
            foreach($stateData as $state){
                if($county_id == $state['id']){
                    echo "<option value='".$state['id']."' selected>".$state['name']."</option>";
                }else{
                    echo "<option value='".$state['id']."'>".$state['name']."</option>";
                }

            }
        }else{
            echo 'No data';
        }
    }
    function geCityData(){
        $state_id = $this->input->post('state_id');
        $city_id = $this->input->post('city_id');
        $cityData = $this->functions->getCity($state_id);
        if(count($cityData)!=''){
            echo "<option value=''>Select City</option>";
            foreach($cityData as $city){
                if($city_id == $city['id']){
                    echo "<option value='".$city['id']."' selected>".$city['name']."</option>";
                }else{
                    echo "<option value='".$city['id']."'>".$city['name']."</option>";
                }
            }
        }else{
            echo 'No data';
        }
    }
    function geDistrictData(){
        $state_id = $this->input->post('state_id');
        $districtsData = $this->functions->getDistrict($state_id);
        if(count($districtsData)!=''){
            echo "<option value=''>Select District</option>";
            foreach($districtsData as $district){
                if($state_id == $district['id']){
                    echo "<option value='".$district['id']."' selected>".$district['name']."</option>";
                }else{
                    echo "<option value='".$district['id']."'>".$district['name']."</option>";
                }
            }
        }else{
            echo 'No data';
        }
    }
    function refer_member_details(){
        
         $this->functions->checkUser($this->controller.'/',true);
          $id=$this->uri->segment(3, 0);
        
        $data['verification']         = $this->ModelUser->verification($id);
        //pr($data['verification']);exit;

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/refer_member_details';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data); 
    }
    function legal(){
        $this->functions->checkUser($this->controller.'/',true);
        $data['recordset']         = $this->ModelUser->getlegalList();

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/legal_notice';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data); 
    }
    function becomeACP(){
        $this->functions->checkUser($this->controller.'/',true);
        

        $this->nsession->set_userdata('succmsg', "");
        $this->nsession->set_userdata('errmsg', "");

        $data['countryData']  = $this->functions->getCountry();
        $data['cityTier']     = $this->ModelUser->getCityTier();
        $data['getTVChannel'] = $this->ModelUser->getTVChannel();
        $data['newsSocialPrice'] = $this->ModelUser->getnewsSocialPrice();
        //pr($data['cityTier']);exit;
        $elements = array();

        $elements['menu']       = 'menu/index';
        $elements['topmenu']    = 'menu/topmenu';
        $elements['main']       = 'user/becomeacp';

        $element_data['menu']   = $data;
        $element_data['main']   = $data;
        $this->layout->setLayout('layout_main_view');
        $this->layout->multiple_view($elements,$element_data);
    }
    function geCityTierData(){
        $state_id       = $this->input->post('state_id');
        $city_id        = $this->input->post('city_id');
        $selectHtml     = '';
       
        $cityLists      = $this->ModelUser->getCityTier($state_id);
        //pr($cityLists);exit;
        if($cityLists){
            $error = 0;
            $selectHtml .= '<option value="">Select City</option>';
            foreach ($cityLists as $cityList){
                if($city_id==$cityList['id']){
                    $selected="selected";
                }else{
                    $selected="";
                }
                $selectHtml .= '<option value="'.$cityList['id'].'" data-tierPrice="'.$cityList['price'].'" data-tiername="'.$cityList['tier_name'].'">'.$cityList['name'].'</option>';
            }
            $dataSet = $selectHtml;
        }else {
            $error = 1;
            $dataSet = '<option value="">Select City</option>';
        }
        echo json_encode(array('error'=>$error,'dataSet'=>$dataSet));
    }
    function request_a_call(){
        $sdata['name']              = $this->input->post('full_name');
        $sdata['email']             = $this->input->post('email');
        $sdata['phone']             = $this->input->post('phone');
        $sdata['messege']           = $this->input->post('messege');
        $sdata['best_time_to_call'] = $this->input->post('best_time');
        $sdata['created_date']      = date('Y-m-d H:i:s');
        // pr($sdata);exit;
        $getRegisteredEmail        = $this->ModelUser->getRegisteredEmail();
            $to         = $getRegisteredEmail;
            $subject    = 'Request For Call';
            $body       = '<tr><td>Hi, '.$sdata['name'].'</td></tr>
                            <tr><td>Email : '.$sdata['email'].'</td></tr>
                            <tr><td>Phone No : '.$sdata['phone'].'</td></tr>
                            <tr><td>Best time to Call : '.$sdata['best_time_to_call'].'</td></tr>
                            <tr><td>Message : '.$sdata['messege'].'</td></tr>';

            if($this->functions->mail_template($to,$subject,$body)){
                $issuccess=$this->ModelUser->addreqest_for_call($sdata);
                if($issuccess)
                {
                    $this->nsession->set_userdata('succmsg','Call Back Request Sent Successfully. We will definitely call you back in your suitable time');
                    redirect(base_url());
                }
                else
                {
                    $this->nsession->set_userdata('errmsg','Some Problem Occur.Try Again');
                    redirect(base_url());
                }
            }else{
                $this->nsession->set_userdata('errmsg','Call back request not sent.Try Again');
                redirect(base_url());
            }
    }
}
