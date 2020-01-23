<?php
class Banner extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'banner';
		$this->load->model('ModelBanner');
	}
	
	function index()

	{
		$UserID = $this->nsession->userdata('admin_session_id');
		$name=banner;
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

		$data['recordset'] 		= $this->ModelBanner->getList($config,$start,$param);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_BANNER');
		$data['reload_link']      	= base_url($this->controller."/index/");
		$data['search_link']        = base_url($this->controller."/index/0/1/");
		$data['add_link']         	= base_url($this->controller."/addedit/0/0/");
		$data['edit_link']        	= base_url($this->controller."/addedit/{{ID}}/0");
		$data['activated_link']     = base_url($this->controller."/activate/{{ID}}/0");
		$data['inacttived_Link']    = base_url($this->controller."/inactive/{{ID}}/0");
		$data['delete_link']      	= base_url($this->controller."/delete/{{ID}}/0");
		$data['showall_link']     	= base_url($this->controller."/index/0/1");
		$data['total_rows']			= $config['total_rows'];
		
		$data['succmsg'] 	= $this->nsession->userdata('succmsg');
		$data['errmsg'] 	= $this->nsession->userdata('errmsg');
		
		$data['module'] = 'Banner Management';
		
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'banner/index';
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
		$name=banner;
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
			$data['action'] = "Edit";
			//=================prepare DATA ==================
			$rs = $this->ModelBanner->getSingle($contentId);
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
			
			//$data['title'] 		= $this->input->request('title');
			$data['icon'] 		= $this->input->request('icon');
		}
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'banner/add_edit';
		$element_data['main'] = $data;
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

		if(isset($_FILES['icon']['name'])){

			$file_name = $_FILES['icon']['name'];

			$new_file_name = time().$file_name;
			
			//$config['upload_path']   = $this->config->config["server_absolute_path"].'uploads/';
			$config['upload_path'] 	 = file_upload_absolute_path().'banner/';
			$config['allowed_types'] = '*';
			//$config['max_size']      = 100; 
			//$config['max_width']     = 1024; 
			//$config['max_height']    = 768;  
			$config['file_name']     = $new_file_name;  
			
			//$this->load->library('upload', $config);
			
			$this->upload->initialize($config);
			
			if(!$this->upload->do_upload('icon')) {
				//$error = array('error' => $this->upload->display_errors()); 
			}
			else{ 
				$data_icon = array('upload_data' => $this->upload->data()); 
			} 
		}
		//pr($data_icon);
		if(isset($_FILES['icon_app']['name'])){
			$file_name = $_FILES['icon_app']['name'];

			$new_file_name = "app_".time().$file_name;
			//echo $new_file_name;
			//$config['upload_path']   = $this->config->config["server_absolute_path"].'uploads/';
			$config['upload_path'] 	 = file_upload_absolute_path().'banner/';
			$config['allowed_types'] = '*';
			//$config['max_size']      = 100; 
			//$config['max_width']     = 1024; 
			//$config['max_height']    = 768;  
			$config['file_name']     = $new_file_name;  
			
			//$this->load->library('upload', $config);
			//pr($config);
			$this->upload->initialize($config);
			
			if(!$this->upload->do_upload('icon_app')) {
				//echo $this->upload->display_errors(); die();
				//$error = array('error' => $this->upload->display_errors()); 
			}
			else{ 
				//echo 4; die();
				$data_icon_app = array('upload_data' => $this->upload->data()); 
			} 
		}
		
		$data['title'] 		 = $this->input->request('title');
		$data['page_url'] 		 = $this->input->request('page_url');
		if($data_icon['upload_data']['file_name']) {
			$data['icon'] 			= $data_icon['upload_data']['file_name'];
		}else{
			if($this->input->request('hdFileID_icon') != "") {
				$data['icon'] = $this->input->request('hdFileID_icon');
			}else{
				$data['icon'] = "";
			}
		}

		if($data_icon_app['upload_data']['file_name']) {
			$data['icon_app'] 			= $data_icon_app['upload_data']['file_name'];
		}else{
			if($this->input->request('hdFileID_icon_app') != "") {
				$data['icon_app'] = $this->input->request('hdFileID_icon_app');
			}else{
				$data['icon_app'] = "";
			}
		}
		//pr($data); die();
		if($contentId > 0)   //edit
		{
			$affected_row = $this->ModelBanner->editContent($contentId,$data);
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
		}
	}
	
	function delete()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=banner;
		$for=2;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);

		if($y==1){


		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);	
		$file = $this->functions->getNameTable('banner','icon','id',$id,'','');	
		$this->ModelBanner->delete($id);
		$path= file_upload_absolute_path().'banner/'.$file;
		unlink($path);
		$this->nsession->set_userdata('succmsg', 'Successfully Banner Deleted.');

		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Delete this Item.');
			redirect(base_url($this->controller));
		}
	}
	
	function deleteall()
	{
		$this->functions->checkAdmin($this->controller.'/');		
		$id_str = $this->input->request('check_ids',0);
		$id_arr = explode("^",$id_str);
		foreach($id_arr as $id){	
			if($id<>'' && $id<>0)		
				$this->ModelBanner->delete($id);
		}		
		$this->nsession->set_userdata('succmsg', 'Successfully Banner Deleted.');
		redirect(base_url($this->controller."/"));
		return true;
	}
	function inactive()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=banner;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);

		if($y==1){


		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelBanner->inactive($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully Template Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to inactive this item.');
			redirect(base_url($this->controller));
			
		}
	}
	function activate()
	{


		$UserID = $this->nsession->userdata('admin_session_id');
		$name=banner;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);

		if($y==1){



		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelBanner->activate($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully Template Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Active this item.');
			redirect(base_url($this->controller));
			
		}
	}

	function changeorder(){
		$rdata=array();
		$rdata['status']='false';
		$rdata['id']='0';
		$rdata['val']='0';
		if($this->input->post('val') || $this->input->post('id')){
			$rdata['status']='true';
			$val=$this->input->post('val');
			$id=$this->input->post('id');
			$result=$this->ModelBanner->changeorder($val,$id);
			if(isset($result['intrchng_id'])){
				$rdata['id']=$result['intrchng_id'];
				$rdata['val']=$result['intrchng_val'];
			}
		}
		echo json_encode($rdata);
	}

}
?>