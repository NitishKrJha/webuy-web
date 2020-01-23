<?php
//error_reporting(E_ALl);
class Content extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'content';
		$this->load->model('ModelContent');
	}
	
	function pages($id)
	{

		

		$UserID = $this->nsession->userdata('admin_session_id');
		$name=cms;
		$for=0;
		$y=$this->functions->acc_permission_r($UserID,$name,$for);
		//echo $y; exit;
		if($y==1){



		$this->functions->checkAdmin($this->controller.'/');
		$data['controller'] 	= $this->controller;
		$data['back_link']		= base_url($this->controller."/pages/".$id);
		$data['action'] 		= "Add";
		$data['pages']			= $id;
		$this->form_validation->set_rules('cms_hid', '', 'trim|required|xss_clean');
		
		$data['id']		= 0;
		if($this->form_validation->run() == TRUE)
		{
			
			$data['content'] = $this->input->request('content');
			$data['page_name'] = $this->input->request('cms_hid');
			$data['contact_row'] = $this->ModelContent->getContent($data['page_name']);
			if(count($data['contact_row'])>0)
			{
				
				$affected_row = $this->ModelContent->editCmsContent($data,$id);	

				$this->nsession->set_userdata('succmsg', 'Content Edited Successfully.');
				redirect(base_url($this->controller."/pages/".$id));
				return true;
			}
			else{
				$insert_id = $this->ModelContent->addCmsContent($data,$id);					
				if($insert_id)
				{
					$this->nsession->set_userdata('succmsg', 'Content Added Successfully .');
					redirect(base_url($this->controller."/pages/".$id));
					return true;
				}
				
			}
		}
		if(count($this->ModelContent->getContent($id)) > 0)
		{
			$data['content']=$this->ModelContent->getContent($id);
			$data['action']='Edit';
		}else{
			$data['content'] = new stdClass;
			$data['content']->content='';
			$data['action']='Add';
		}

		
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		// ================================================================
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'content/cms';
		
		$element_data['menu'] = array();
		$element_data['main'] = $data;
		
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
		
	}

else{
	$this->nsession->set_userdata('errmsg','You are Not Authorized to Access this Option.');
	redirect(base_url($this->user));
}
}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */

?>