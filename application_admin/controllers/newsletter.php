<?php
//error_reporting(E_ALL);
class Newsletter extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'newsletter';
		$this->load->model('ModelNewsletter');
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

		$data['recordset'] 		= $this->ModelNewsletter->getList($config,$start,$param);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_NEWSLETTER');
		$data['reload_link']      	= base_url($this->controller."/index/");
		$data['search_link']        = base_url($this->controller."/index/0/1/");
		$data['add_link']         	= base_url($this->controller."/addedit/0/0/");
		$data['edit_link']        	= base_url($this->controller."/addedit/{{ID}}/0");
		$data['delete_link']      	= base_url($this->controller."/delete/{{ID}}/0");
		$data['showall_link']     	= base_url($this->controller."/index/0/1");
		$data['total_rows']			= $config['total_rows'];
        $data['templateData']       = $this->ModelNewsletter->getTemplate();
		
		$data['succmsg'] 	= $this->nsession->userdata('succmsg');
		$data['errmsg'] 	= $this->nsession->userdata('errmsg');

		$data['module']     = 'Newsletter Email List';
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'newsletter/index';
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
			$rs = $this->ModelNewsletter->getSingle($contentId);
			$row = $rs->fields;
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
		}
		
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');

		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");
		
		$elements = array();
		$elements['menu'] = 'menu/index';
		$elements['topmenu'] = 'menu/topmenu';
		$elements['main'] = 'newsletter/add_edit';
		$element_data['main'] = $data;
		$element_data['main'] = $data;
		
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
		
	}
	
	function do_addedit()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$contentId = $this->uri->segment(3, 0);
		if($contentId=='')   //add
		{
            if($contentId>0)   //edit
            {
                $returnCheck = $this->functions->checkContentAlreadyExist('subscriber','email',trim($this->input->request('email_id')),'edit',$contentId);
                if($returnCheck==false){
                    $this->nsession->set_userdata('errmsg', 'Email ID Alreday Exist.');
                    redirect(base_url($this->controller."/index/"));
                    return true;
                }
            }else{
                $returnCheck = $this->functions->checkContentAlreadyExist('subscriber','email',trim($this->input->request('email_id')),'add',0);
                if($returnCheck==false){
                    $this->nsession->set_userdata('errmsg', 'Email ID Alreday Exist.');
                    redirect(base_url($this->controller."/index/"));
                    return true;
                }
            }
		}
		$data['email_id'] 			= $this->input->post('email_id');
		if($contentId > 0)   //edit
		{
			$affected_row = $this->ModelNewsletter->editContent($contentId,$data);
			$this->nsession->set_userdata('succmsg', 'Successfully Newsletter Email Updated.');
			redirect(base_url($this->controller."/index/"));
			return true;
		}
		else    //add
		{	
			$data['join_dt']			= date('Y-m-d H:i:s');
			$insert_id = $this->ModelNewsletter->addContent($data);
			if($insert_id)
			{
				$this->nsession->set_userdata('succmsg', 'Successfully Newsletter Email Added.');
				redirect(base_url($this->controller."/index/"));
				return true;
			}
		}	
	}
	
	function delete()
	{
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelNewsletter->delete($id);
		$this->nsession->set_userdata('succmsg', 'Successfully Newsletter Email Deleted.');
		redirect(base_url($this->controller."/index/"));
		return true;
	}
	function importData(){
        $this->functions->checkAdmin($this->controller.'/');
        $file_name = $_FILES['icon']['name'];
        $new_file_name = time().$file_name;
        $config['upload_path'] 	 = file_upload_absolute_path().'uploademail/';
        //$config['allowed_types'] = 'xlsx|csv|xlsm|xls';
        $config['allowed_types'] = '*';
        $config['file_name']     = $new_file_name;
        $this->upload->initialize($config);
        if(!$this->upload->do_upload('icon')) {
            $error = $this->upload->display_errors();
            $this->nsession->set_userdata('errmsg', $error);
            redirect(base_url($this->controller."/index/"));
            return true;
        }
        $file_type	= PHPExcel_IOFactory::identify($config['upload_path'] . $new_file_name);
        $objReader	= PHPExcel_IOFactory::createReader($file_type);
        $objPHPExcel = $objReader->load($config['upload_path'] . $new_file_name);
        $sheet_data	= $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        foreach($sheet_data as $data)
        {
            $result = array(
                'name' => $data['A'],
                'email_id' => $data['B'],
            );
            if($result['name']!='' && $result['email_id']!=''){
                $this->ModelNewsletter->doSaveData($result);
            }
        }
        $this->nsession->set_userdata('succmsg', 'Successfully emails uploaded.');
        redirect(base_url($this->controller."/index/"));
        return true;
    }
    function sendNotification(){
        $data['notification_subject']   = $this->input->post('notification_subject');
        $template_no                    = $this->input->post('template_no');
        $getData                        = $this->ModelNewsletter->getNotificationData($template_no);
        $data['notification_data']      = htmlspecialchars_decode($getData['content']);
        $data['memberNeedNotify']       = explode(',',$this->input->post('memberNeedNotify'));
        // pr($data);exit;
        foreach ($data['memberNeedNotify'] as $memberNeedNotify){
            $result     = $this->ModelNewsletter->getsingleData($memberNeedNotify);
            $to      = $result['email'];
            $subject    = $data['notification_subject'];
            $body='<tr style="background:#fff;"><td>'.$data['notification_data'].'</td></tr>';
            $this->functions->mail_template($to,$subject,$body);
            // echo $to;exit;
        }
        $this->nsession->set_userdata('succmsg', 'Notification Message sent Successfully.');
        redirect(base_url($this->controller));
        return true;
    }
}
?>