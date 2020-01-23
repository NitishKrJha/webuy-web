<?php
class Setting extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('ModelSetting');
		$this->controller = 'setting';
		
		$this->functions->checkAdmin($this->controller.'/');
	}

	function index()
	{
		redirect(base_url($this->controller."/global_setting/"));
		return true;
	}

	function global_setting()
	{
		$this->functions->checkAdmin($this->controller.'/global_setting/');
		
		$data['module']='Setting';
		$data['msg'] = "";
		$data['section'] = "Global Setting";
		$data['controller'] = $this->controller;
		$data['global'] = $this->ModelSetting->getGlobalData();
		$data['submit_link'] = base_url($this->controller.'/do_global_setting/');

		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');

		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");

		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'setting/global_setting';

		$element_data['menu'] = array();
		$element_data['main'] = $data;

		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	}

	function do_global_setting()
	{
		$this->functions->checkAdmin($this->controller.'/global_setting/');
		
		$data['global_site_name']			= $this->input->request("global_site_name");
    	$data['global_meta_title']			= $this->input->request("global_meta_title");
    	$data['global_meta_keywords']		= $this->input->request("global_meta_keywords");
    	$data['global_meta_description']	= $this->input->request("global_meta_description");
    	$data['global_phone_number']	    = $this->input->request("global_phone_number");
		$data['global_web_admin_headquarters_address']	= $this->input->request("global_web_admin_headquarters_address");
		$data['global_web_admin_secondary_address']	= $this->input->request("global_web_admin_secondary_address");
    	$data['global_paypal_business_email']= $this->input->request("global_paypal_business_email");
    	$data['global_paypal_mode']         = $this->input->request("global_paypal_mode");

		$data['global_facebook_url']		= $this->input->request("global_facebook_url");
    	$data['global_twitter_url']		    = $this->input->request("global_twitter_url");
		$data['global_youtube_url']			= $this->input->request("global_youtube_url");
    	$data['global_google_plus_url']		= $this->input->request("global_google_plus_url");
		$data['global_playstore_url']		= $this->input->request("global_playstore_url");
		$data['global_appstore_url']		= $this->input->request("global_appstore_url");

		$data['global_footer_details']		= $this->input->request("global_footer_details");
		
		$data['global_linkedin_url']		= $this->input->request("global_linkedin_url");

		$data['global_web_admin_name']		= $this->input->request("global_web_admin_name");
    	$data['global_webadmin_email']		= $this->input->request("global_webadmin_email");
    	$data['global_email_signature']		= $this->input->request("global_email_signature");

    	$data['global_product_page_count']	= $this->input->request("global_product_page_count");
		
		$data['global_credit_price']		= $this->input->request("global_credit_price");
		
		$data['global_contact_email']		= $this->input->request("global_contact_email");

		$data['global_tax_name']		= $this->input->request("global_tax_name");

		$data['global_tax_value']		= $this->input->request("global_tax_value");

		$data['global_gratuity_value']		= $this->input->request("global_gratuity_value");

		$data['global_google_analytics_code']		= '';
		//pr($_FILES); 
		if($_FILES['global_sub_banner']['size']>0){
		$file_name = $_FILES['global_sub_banner']['name'];

		$new_file_name = time().$file_name;
		$config['upload_path'] 	 = file_upload_absolute_path().'images/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['file_name']     = $new_file_name;  
		
		$this->load->library('upload', $config);
		
		$this->upload->initialize($config);
		$upload_data=array();
		if(!$this->upload->do_upload('global_sub_banner')) {
			$error = array('error' => $this->upload->display_errors()); 
		}
		else{ 
			$upload_data = array('upload_data' => $this->upload->data()); 
		} 
		
		if(isset($upload_data['upload_data']['file_name'])) {
			$data['global_sub_banner'] 			= $upload_data['upload_data']['file_name'];
		}

		}
		//pr($data);
		$this->ModelSetting->updateGlobalSetting($data);

		$this->nsession->set_userdata('succmsg', "Configuration Updated Successfully");
		redirect(base_url($this->controller."/global_setting/"));
		return true;
	}
	
}


?>