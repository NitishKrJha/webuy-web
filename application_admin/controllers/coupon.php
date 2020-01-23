<?php

class Coupon extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'coupon';
		$this->load->model('ModelCoupon');
		//$this->load->model('ModelOffer');
	}
	
	function index()

	{
		//echo"hi...";


		$UserID = $this->nsession->userdata('admin_session_id');
		$name=setting;
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

		$data['recordset'] 		= $this->ModelCoupon->getList($config,$start,$param);
		//pr($data['recordset']);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_coupon');
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
		$elements['main'] = 'coupon/index';

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



	function addedit($id = 0)

	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=setting;
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
			$rs = $this->ModelCoupon->getSingle($contentId);
			//pr($rs);
			$data['category_level_1']=$this->ModelCoupon->getCategoryType();
			$data['product']=$this->ModelCoupon->getProductType();

			//pr($data['category_level_1']);
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
		$data['category_level_1']=$this->ModelCoupon->getCategoryType();
		$data['product']=$this->ModelCoupon->getProductType();




		//pr($category_level_1);
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'coupon/add_edit';
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





	
	function checkCouponName(){
		$coupon_name= $this->input->post('coupon_name');
		echo json_encode($this->functions->getAllTable('coupon','coupon_id','coupon_name',$coupon_name));
	}
	function checkCouponCode(){
		$coupon_code= $this->input->post('coupon_code');
		echo json_encode($this->functions->getAllTable('coupon','coupon_id','coupon_code',$coupon_code));
	}


	function do_addedit()
	{
		
		//-----------------------------------------------
		$this->functions->checkAdmin($this->controller.'/');
		$contentId = $this->uri->segment(3, 0); 
		$page = $this->uri->segment(4, 0); 
		
	
		$this->form_validation->set_error_delimiters('<ul class="parsley-errors-list filled error text-left" ><li class="parsley-required">', '</li></ul>');

		$data['coupon_name'] 			= $this->input->post('coupon_name');
		$data['start_date'] 			= date('Y-m-d',strtotime($this->input->post('start_date')));
		$data['end_date'] 			    = date('Y-m-d',strtotime($this->input->post('end_date')));
		$data['coupon_code'] 			= $this->input->post('coupon_code');
		$data['discount_type'] 			= $this->input->post('discount_type');
		$data['discount_value'] 		= $this->input->post('discount_value');
		$data['discount_for'] 			= $this->input->post('discount_for');

		

		if($data['discount_for']=='all'){
			$data['discount_select'] 	='';
		}else if($data['discount_for'] =='category'){
			
			$mal		= $this->input->post('discount_select');
			$ac = implode(",",$mal);
			$data['discount_select'] = $ac;

		}else{
			//$data['discount_select'] 		= $this->input->post('discount_select1');
			$mal1		= $this->input->post('discount_select1');
			$ac1 = implode(",",$mal1);
			$data['discount_select'] = $ac1;
		}
		
		
      //pr($data);
		
		if($contentId==0){
			$data['created_date'] 			= date('Y-m-d h:m:s');
			$data['modified_date'] 			= date('Y-m-d h:m:s');
			$this->ModelCoupon->addContent($data);
				$this->nsession->set_userdata('succmsg','Coupon added successfully.');
				redirect(base_url($this->controller));
			}
			else{
				
				$data['modified_date'] 			= date('Y-m-d h:m:s');


			$rid=$this->ModelCoupon->editContent($data,$contentId);

			$this->nsession->set_userdata('succmsg','Coupon edited successfully.');
			redirect(base_url($this->controller));
		}
		
			

		
	}
	function delete()
	{	
		//echo "hi";

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=setting;
		$for=2;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
	
		if($y==1){

		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelCoupon->delete($id);		
		$this->nsession->set_userdata('succmsg', 'Deleted Coupon Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Delete this Item.');
			redirect(base_url($this->controller));
		}
	}


	function activate()

	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=setting;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
	
		if($y==1){

		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);
		$this->ModelCoupon->activate($id);	
		
		$this->nsession->set_userdata('succmsg', 'Successfully Coupon Activated !!.');
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
		$name=setting;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
	
		if($y==1){


		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelCoupon->inactive($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully Coupon Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
		}
		else{
			$this->nsession->set_userdata('errmsg','You are Not Authorized to Inactive this Item.');
			redirect(base_url($this->controller));
		}
	}



	function viewdetails($id){
		if($id){
			$rs = $this->ModelCoupon->getSingle($id);
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
			$elements['main'] = 'coupon/view_details';
			$element_data['menu'] = $data;//array();
			$element_data['main'] = $data;
			$this->layout->setLayout('layout_main_view'); 
			$this->layout->multiple_view($elements,$element_data);
		}
		
	}
	
	
	//==========Initialize $data for Add =======================
}
?>