<?php

class Contact extends CI_Controller {

	
	function __construct()
	{


		parent::__construct();
		$this->controller = 'contact';
		$this->load->model('ModelContact');

	}

		function index()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=staff;
		$for=0;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);

		if($y==1){


		$this->functions->checkAdmin($this->controller.'/');
		$config['base_url'] 			= base_url($this->controller."/index/");
		
		$config['uri_segment']  		= 3;
		$config['num_links'] 			= 10;
		$config['page_query_string'] 	= false;
		$config['extra_params'] 		= ""; 
		$config['extra_params'] 		= "";
		
		$this->pagination->setAdminPaginationStyle($config);
		$start = 0;
		
		$data['controller'] = $this->controller;
		
		$param['sortType'] 			= $this->input->request('sortType','ASC');
		$param['sortField'] 		= $this->input->request('sortField','title');
		$param['searchField'] 		= $this->input->request('searchField','');
		$param['searchString'] 		= $this->input->request('searchString','');
		$param['searchText'] 		= $this->input->request('searchText','');
		$param['searchFromDate'] 	= $this->input->request('searchFromDate','');
		$param['searchToDate'] 		= $this->input->request('searchToDate','');
		$param['searchDisplay'] 	= $this->input->request('searchDisplay','20');
		$param['searchAlpha'] 		= $this->input->request('txt_alpha','');
		$param['searchMode'] 		= $this->input->request('search_mode','STRING');

		$data['recordset'] 		= $this->ModelContact->getContactUsList($config,$start,$param);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_BANNER');
		$data['reload_link']      	= base_url($this->controller."/index/");
		$data['search_link']        = base_url($this->controller."/index/0/1/");
		$data['add_link']         	= base_url($this->controller."/addedit/0/0/");
		$data['edit_link']        	= base_url($this->controller."/addedit/{{ID}}/0");
		$data['delete_link']      	= base_url($this->controller."/delete/{{ID}}/0");
		$data['showall_link']     	= base_url($this->controller."/index/0/1");
		$data['total_rows']			= $config['total_rows'];
		
		$data['succmsg'] 	= $this->nsession->userdata('succmsg');
		$data['errmsg'] 	= $this->nsession->userdata('errmsg');
		
		$data['module'] = 'Contact Management';
		
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'contact/index';
		$element_data['menu'] = $data;
		$element_data['main'] = $data;
		
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Access this option.');
			redirect(base_url($this->user));
		}
	
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

		$deleteimagelink=$this->ModelContact->delete($id);


		if($deleteimagelink)
		{	
			$this->nsession->set_userdata('succmsg', 'Successfully Contact Deleted.');
		}
		redirect(base_url($this->controller."/index/0/1"));
	}
	else{
		$this->nsession->set_userdata('errmsg','You are Not Authorized to Delete this Item.');
		redirect(base_url($this->controller));
	}
		
	}


	function addedit($id = 0)
	{  //echo "abcd"; die();

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
		//print_r($page);
		//print_r($contentId);
		 
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
			$rs = $this->ModelContact->getSingle($contentId);
			//$row = $rs->fields;
			if(is_array($rs[0]))
			{
				foreach($rs[0] as $key => $val)
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
			
			$data['title'] 		= $this->input->request('title');
			$data['icon'] 		= $this->input->request('icon');
		}
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'contact/add_edit';
		$element_data['main'] = $data;
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

		$this->functions->checkAdmin($this->controller.'/');
		$contactId = $this->uri->segment(3, 0); 
		$page = $this->uri->segment(4, 0); 
		//print_r($page);
		//print_r($contactId);
		
		/*$file_name = $_FILES['icon']['name'];

		$new_file_name = time().$file_name;
		
		//$config['upload_path']   = $this->config->config["server_absolute_path"].'uploads/';
		$config['upload_path'] 	 = file_upload_absolute_path().'banner/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		//$config['max_size']      = 100; 
		//$config['max_width']     = 1024; 
		//$config['max_height']    = 768;  
		$config['file_name']     = $new_file_name;  
		
		//$this->load->library('upload', $config);
		
		$this->upload->initialize($config);
		
		if(!$this->upload->do_upload('icon')) {
			$error = array('error' => $this->upload->display_errors()); 
			//echo '<pre>';
			//print_r($error);
		}
		else{ 
			$data = array('upload_data' => $this->upload->data()); 
			//echo '<pre>';
			//print_r($data);
		} */
		
		//$data['title'] 		 = $this->input->request('title');
		$data['contact_reply'] 		= $this->input->request('reply');
		/*if($data['upload_data']['file_name']) {
			$data['icon'] 			= $data['upload_data']['file_name'];
		}else{
			if($this->input->request('hdFileID_icon') != "") {
				$data['icon'] = $this->input->request('hdFileID_icon');
			}else{
				$data['icon'] = "";
			}
		}*/
		//echo $data['contact_reply'] ;
		$savereply=$this->ModelContact->editContact($contactId,$data['contact_reply']);
		//print_r($savereply);

		if($savereply)
		{
			$this->nsession->set_userdata('succmsg', 'Replay sent successfully.');
			redirect(base_url($this->controller."/index/"));
			return true;
		}
		else
		{
			$this->nsession->set_userdata('succmsg', 'Replay not sent.');
			redirect(base_url($this->controller."/index/"));
			return true;
		}
		die();
		/*if($contentId > 0)   //edit
		{
			$oldimage=$this->ModelBanner->getimagelink($contentId);
			$filepath=file_upload_absolute_path().'banner/'.$oldimage;
			$affected_row = $this->ModelBanner->editContent($contentId,$data);
			unlink($filepath);
			$this->nsession->set_userdata('succmsg', 'Successfully Banner Edited.');
			redirect(base_url($this->controller."/index/"));
			return true;
		}
		else    //add
		{	
			$insert_id = $this->ModelBanner->addContent($data);
			if($insert_id)
			{
				$this->nsession->set_userdata('succmsg', 'Successfully Banner Added.');
				redirect(base_url($this->controller."/index/"));
				return true;
			}
		}*/
	}

}

?>