<?php
class Subscriber extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'subscriber';
		$this->load->model('ModelSubscriber');
	}
	
	function index()

	{
		$UserID = $this->nsession->userdata('admin_session_id');
		$for=0;
		$name=category;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
		//echo $y;exit;
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

		$data['recordset'] 		= $this->ModelSubscriber->getList($config,$start,$param);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_category');
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
		
		$data['module'] = 'subscriber Management';
		
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'subscriber/index';
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
		$for=1;
		$name=category;
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
			$data['action'] = "Edit";
			//=================prepare DATA ==================
			$rs = $this->ModelSubscriber->getSingle($contentId,'subscriber');
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
		$elements['main'] = 'subscriber/add_edit';
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
		//pr($_FILES);
		if($_FILES['icon']['size'] > 0){
			$file_name = $_FILES['icon']['name'];
			$new_file_name = time().$file_name;
			$config['upload_path'] 	 = file_upload_absolute_path().'subscriber/';
			$config['allowed_types'] = '*';
			$config['file_name']     = $new_file_name;  
			$this->upload->initialize($config);
			$img_data=array();
			if($this->upload->do_upload('icon')) {
				$img_data = array('upload_data' => $this->upload->data()); 
				//echo $this->upload->display_errors(); die();
				if($img_data['upload_data']['file_name']) {
					$data['icon'] 	= $img_data['upload_data']['file_name'];
				}
			}
		}
		
		
		$data['name'] 		 = $this->input->request('name');
		
		
		if($contentId > 0)   //edit
		{
			$affected_row = $this->ModelSubscriber->editContent($contentId,$data,'subscriber');
			$this->nsession->set_userdata('succmsg', 'Successfully brand Edited.');
			redirect(base_url($this->controller."/index/"));
			return true;
		}
		else    //add
		{	
			$insert_id = $this->ModelSubscriber->addContent($data,'subscriber');
			if($insert_id)
			{
				$this->nsession->set_userdata('succmsg', 'Successfully brand Added.');
				redirect(base_url($this->controller."/index/"));
				return true;
			}
		}
	}
	
	function delete()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$for=2;
		$name=category;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
		//echo $y;exit;
		if($y==1){

		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);	
		$file = $this->functions->getNameTable('subscriber','icon','id',$id,'','');	
		$this->ModelSubscriber->delete($id,'subscriber');
		$path= file_upload_absolute_path().'subscriber/'.$file;
		unlink($path);
		$this->nsession->set_userdata('succmsg', 'subscriber Deleted.');

		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Delete this brand.');
				redirect(base_url($this->controller));
		}
	}
	
	
	function inactive()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$for=1;
		$name=subscriber;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
		//echo $y;exit;
		if($y==1){

		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelSubscriber->inactive($id,'category_level_1');		
		$this->nsession->set_userdata('succmsg', 'Successfully Template Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Inactive this Item.');
				redirect(base_url($this->controller));
		}

	}
	function activate()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$for=1;
		$name=subscriber;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
		//echo $y;exit;
		if($y==1){


		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelSubscriber->activate($id,'category_level_1');		
		$this->nsession->set_userdata('succmsg', 'Successfully Template Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Active this Item.');
				redirect(base_url($this->controller));
		}
	}

	function changeorder(){

		$UserID = $this->nsession->userdata('admin_session_id');
		$for=1;
		$name=subscriber;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
		//echo $y;exit;
		if($y==1){
		


		//-----------------------------------
		$rdata=array();
		$rdata['status']='false';
		$rdata['id']='0';
		$rdata['val']='0';
		if($this->input->post('val') || $this->input->post('id')){
			$rdata['status']='true';
			$val=$this->input->post('val');
			$id=$this->input->post('id');
			$result=$this->ModelSubscriber->changeorder($val,$id,'category_level_1');
			if(isset($result['intrchng_id'])){
				$rdata['id']=$result['intrchng_id'];
				$rdata['val']=$result['intrchng_val'];
			}
		}
		echo json_encode($rdata);
	}
	else{
		$this->nsession->set_userdata('errmsg','You are Not Authorized to Active this Item.');
			redirect(base_url($this->controller));
	}
}


}
?>