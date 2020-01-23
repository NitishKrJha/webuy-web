<?php
class Amenities extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'amenities';
		$this->load->model('ModelAmenities');
	}
	
	function index()
	{
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

		$data['recordset'] 		= $this->ModelAmenities->getList($config,$start,$param);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_amenities');
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
		
		$data['module'] = 'amenities Management';
		
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'amenities/index';
		$element_data['menu'] = $data;
		$element_data['main'] = $data;
		
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	
	}
	
	//==========Initialize $data for Add =======================
	
	function addedit($id = 0)
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
		$data['do_addedit_link']	= base_url($this->controller."/do_addedit/".$contentId."/".$page."/");
		$data['back_link']			= base_url($this->controller."/index/");
		
		if($contentId > 0)
		{
			$data['adminpage_id'] = $contentId;
			$data['action'] = "Edit";
			//=================prepare DATA ==================
			$rs = $this->ModelAmenities->getSingle($contentId);
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
			
		}
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'amenities/add_edit';
		$element_data['main'] = $data;
		$element_data['main'] = $data;
		
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
		
	}
	
	function do_addedit()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$contentId = $this->uri->segment(3, 0); 
		$page = $this->uri->segment(4, 0); 
		
		//$data['title_en'] 		 = $this->input->request('title_en');
		//$data['title_ch'] 		 = $this->input->request('title_ch');
		$data['name'] 			= htmlentities($this->input->post('name'));
		
		//pr($data);
		if($contentId > 0)   //edit
		{
			$affected_row = $this->ModelAmenities->editContent($contentId,$data);
			$this->nsession->set_userdata('succmsg', 'Successfully amenities Edited.');
			redirect(base_url($this->controller."/index/"));
			return true;
		}
		else    //add
		{	
			$insert_id = $this->ModelAmenities->addContent($data);
			if($insert_id)
			{
				$this->nsession->set_userdata('succmsg', 'Successfully amenities Added.');
				redirect(base_url($this->controller."/index/"));
				return true;
			}
		}
	}
	
	function delete()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);	
		$this->ModelAmenities->delete($id);
		
		$this->nsession->set_userdata('succmsg', 'Successfully amenities Deleted.');

		redirect(base_url($this->controller."/index/"));
		return true;
	}
	
	function deleteall()
	{
		$this->functions->checkAdmin($this->controller.'/');		
		$id_str = $this->input->request('check_ids',0);
		$id_arr = explode("^",$id_str);
		foreach($id_arr as $id){	
			if($id<>'' && $id<>0)		
				$this->ModelAmenities->delete($id);
		}		
		$this->nsession->set_userdata('succmsg', 'Successfully amenities Deleted.');
		redirect(base_url($this->controller."/"));
		return true;
	}
	function inactive()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelAmenities->inactive($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully Template Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
	}
	function activate()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelAmenities->activate($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully Template Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
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
			$result=$this->ModelAmenities->changeorder($val,$id);
			if(isset($result['intrchng_id'])){
				$rdata['id']=$result['intrchng_id'];
				$rdata['val']=$result['intrchng_val'];
			}
		}
		echo json_encode($rdata);
	}

}
?>