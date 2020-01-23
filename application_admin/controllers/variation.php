<?php
class Variation extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'variation';
		$this->load->model('ModelVariation');
	}
	
	function index()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		//echo $UserID;exit;
		$name=variation_attribute;
		$for=0;
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

		$data['recordset'] 		= $this->ModelVariation->getList($config,$start,$param);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_variation');
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
		
		$data['module'] = 'variation Management';
		
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'variation/index';
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
		$name=variation_attribute;
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
			$rs = $this->ModelVariation->getSingle($contentId);
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
            $data['variation_value']=$this->ModelVariation->getAllVaraValue($contentId);
		}else{
			$data['action'] 	= "Add";
			$data['id']			= 0;
			
			//$data['title'] 		= $this->input->request('title');
			
		}
		$data['category_level_1']=$this->ModelVariation->getListOfTable('category_level_1',array('is_active'=>1),'multiple');
		//pr($data);
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'variation/add_edit';
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

	function getCategory(){
        $return=array('error'=>1,'data'=>'','msg'=>'No Data Found');
        //pr($_POST);
        if($this->input->post('type') && $this->input->post('id')){
            $type=$this->input->post('type');
            if($type=='level2'){
                $nlevel='level1';
                $tbl='category_level_2';
            }else if($type=='level3'){
                $nlevel='level2';
                $tbl='category_level_3';
            }else if($type=='level4'){
                $nlevel='level3';
                $tbl='category_level_4';
            }else{
                $nlevel='level4';
                $tbl='category_level_1';
            }
            $id=$this->input->post('id');
            $result=$this->ModelVariation->getListOfTable($tbl,array('is_active'=>1,$nlevel=>$id),'multiple');
            //echo $this->db->last_query();
            $html='';
            if(count($result) > 0){
                $html .='<option value="">Select</option>';
                foreach ($result as $key => $value) {
                    $html .='<option value="'.$value['id'].'">'.$value['name'].'</option>';
                }
            }
            if($html!=''){
                $return['error']=0;
                $return['data']=$html;
            }
        }
        echo json_encode($return);
    }
	
	function do_addedit()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$contentId = $this->uri->segment(3, 0); 
		$page = $this->uri->segment(4, 0); 
		//pr($_POST);
		//$data['title_en'] 		 = $this->input->request('title_en');
		//$data['title_ch'] 		 = $this->input->request('title_ch');
		$data['name'] 			= htmlentities($this->input->post('name'));
		$data['cat_level1']		=$this->input->post('level1');
		$data['cat_level2']		=$this->input->post('level2');
		$data['cat_level3']		=$this->input->post('level3');
		$value=$this->input->post('value');
		
		//pr($value);
		if($contentId > 0)   //edit
		{
			$affected_row = $this->ModelVariation->editContent($contentId,$data,$value);
			$this->nsession->set_userdata('succmsg', 'Modify Successfully.');
			redirect(base_url($this->controller."/index/"));
			return true;
		}
		else    //add
		{	
			$insert_id = $this->ModelVariation->addContent($data,$value);
			if($insert_id)
			{
				$this->nsession->set_userdata('succmsg', 'Added Successfully.');
				redirect(base_url($this->controller."/index/"));
				return true;
			}
		}
	}
	
	function delete()
	{

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=variation_attribute;
		$for=1;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
		if($y==1){


		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);	
		$this->ModelVariation->delete($id);
		
		$this->nsession->set_userdata('succmsg', 'Successfully variation Deleted.');

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
				$this->ModelVariation->delete($id);
		}		
		$this->nsession->set_userdata('succmsg', 'Successfully variation Deleted.');
		redirect(base_url($this->controller."/"));
		return true;
	}
	function inactive()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelVariation->inactive($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully Template Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
	}
	function activate()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelVariation->activate($id);		
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
			$result=$this->ModelVariation->changeorder($val,$id);
			if(isset($result['intrchng_id'])){
				$rdata['id']=$result['intrchng_id'];
				$rdata['val']=$result['intrchng_val'];
			}
		}
		echo json_encode($rdata);
	}

}
?>