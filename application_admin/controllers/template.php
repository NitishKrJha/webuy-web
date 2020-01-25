<?php
//error_reporting(E_ALL);
class Template extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'template';
		$this->load->model('ModelTemplate');
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

		$data['recordset'] 		= $this->ModelTemplate->getList($config,$start,$param);
		//pr($data['recordset']);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_faq');
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
		$elements['main'] = 'template/index';

		$element_data['menu'] = $data;
		$element_data['main'] = $data;
		
		$this->layout->setLayout('layout_main_view'); 
        $this->layout->multiple_view($elements,$element_data);
        
        

		
		

	
    }


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
			$data['id'] = $contentId;
			$data['action'] = "Edit";
			//=================prepare DATA ==================
			$rs = $this->ModelTemplate->getSingle($contentId);
			//$data['category_level_1']=$this->ModelOffer->getCategoryType();
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
		//pr($data);
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		//$data['allCountry']=$this->ModelOffer->getCountryCityStateList('countries');
		//$data['category_level_1_data']=$this->ModelOffer->getCategoryType();
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'template/add_edit';
		$element_data['menu'] = $data;//array();
		$element_data['main'] = $data;
		$this->layout->setLayout('layout_main_view'); 
        $this->layout->multiple_view($elements,$element_data);
    
    

	
	
		
	}
	
	function do_addedit()
	{
		
		//-----------------------------------------------
		$this->functions->checkAdmin($this->controller.'/');
		$contentId = $this->uri->segment(3, 0); 
		$page = $this->uri->segment(4, 0); 
		
	
		//$this->form_validation->set_error_delimiters('<ul class="parsley-errors-list filled error text-left" ><li class="parsley-required">', '</li></ul>');

		$data['name'] 		= $this->input->post('name');
        $data['email'] 			= $this->input->post('email');
        $data['sms'] 			= $this->input->post('sms');
		
		
		if($contentId==0){
			$data['created_date'] 			= date('Y-m-d h:m:s');
			$data['modified_date'] 			= date('Y-m-d h:m:s');
			$this->ModelTemplate->addContent($data);
				$this->nsession->set_userdata('succmsg','Offer added successfully.');
				redirect(base_url($this->controller));
			}
			else{
				
				$data['modified_date'] 			= date('Y-m-d h:m:s');


			$rid=$this->ModelTemplate->editContent($data,$contentId);

			$this->nsession->set_userdata('succmsg','FAQ edited successfully.');
			redirect(base_url($this->controller));
		}
		
			

		
    }
    

}
?>
