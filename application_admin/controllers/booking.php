<?php
//error_reporting(E_ALL);
class Booking extends CI_Controller {

	var $urlfix = "";
	
	function __construct()
	{
		parent::__construct();
		$this->controller = 'booking';
		$this->load->model('ModelBooking');
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

		$data['recordset'] 		= $this->ModelBooking->getList($config,$start,$param);
		//pr($data['recordset']);
		$data['startRecord'] 	= $start;
		
		$this->pagination->initialize($config);
		
		$data['params'] 			= $this->nsession->userdata('ADMIN_booking');
		$data['reload_link']      	= base_url($this->controller."/index/");
		$data['search_link']        = base_url($this->controller."/index/0/1/");
		$data['add_link']         	= base_url($this->controller."/addedit/0/0/");
		$data['edit_link']        	= base_url($this->controller."/addedit/{{ID}}/0");
		$data['member_link']        	= base_url("customer/viewdetails/{{ID}}/0");
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
		$elements['main'] = 'booking/index';

		$element_data['menu'] = $data;
		$element_data['main'] = $data;
		
		$this->layout->setLayout('layout_main_view'); 
		$this->layout->multiple_view($elements,$element_data);
	
	}
	
	function activate()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);
		$this->ModelBooking->activate($id);	
		$result = $this->ModelBooking->getsingle_empdata($id);
		//echo $this->db->last_query();
		//pr($result);
		$email = $result->member_email;
		$first_name = $result->member_first_name;
		
		$to = $email;
		$subject='Car Booking';
		$body='<tr><td>Hi,</td></tr>
				<tr><td>Name : '.$first_name.'</td></tr>
				<tr style="background:#fff;"><td>Your Car '.$result->car_name.' booking has been confirmed.You can also check from your account. </td></tr>';
		$return_check = $this->functions->mail_template($to,$subject,$body);
		$this->nsession->set_userdata('succmsg', 'Successfully booking Approved.');
		redirect(base_url($this->controller."/index/"));
		return true;
	}
	function inactive()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelBooking->inactive($id);		
		$this->nsession->set_userdata('succmsg', 'Successfully booking Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
	}

	function delete()
	{
		$this->functions->checkAdmin($this->controller.'/');
		$id = $this->uri->segment(3, 0);		
		$this->ModelBooking->delete($id);		
		$this->nsession->set_userdata('succmsg', 'Deleted booking Inactivated.');
		redirect(base_url($this->controller."/index/"));
		return true;
	}
	function viewdetails($id){
		if($id){
			$rs = (array)$this->ModelBooking->getsingle_empdata($id);
			//echo $this->db->last_query();
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
			$elements['main'] = 'booking/view_details';
			$element_data['menu'] = $data;//array();
			$element_data['main'] = $data;
			$this->layout->setLayout('layout_main_view'); 
			$this->layout->multiple_view($elements,$element_data);
		}
		
	}

	function EditBill($id){
		if($id){
			$rs = (array)$this->ModelBooking->getsingle_empdata($id);
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
			$elements['main'] = 'booking/EditBill';
			$element_data['menu'] = $data;//array();
			$element_data['main'] = $data;
			$this->layout->setLayout('layout_main_view'); 
			$this->layout->multiple_view($elements,$element_data);
		}
		
	}

	function updateBill(){
		$result['error']=0;
		$result['msg']="Invalid Request,Please try once again";
		if($this->input->post('book_id') && $this->input->post('totalAmount')){
			$book_id=$this->input->post('book_id');
			$totalAmount=(float)$this->input->post('totalAmount');
			$name=($this->input->post('name'))?$this->input->post('name'):array();
			$value=($this->input->post('value'))?$this->input->post('value'):array();
			if(count($value) > 0){
				foreach ($value as $key => $val) {
					$totalAmount = $totalAmount +  $val;
				}
			}
			$totalAmount=number_format((float)$totalAmount, 2, '.', '');
			$ndata=array();
			$ndata['extra_name']=(count($name) > 0)?json_encode($name):'';
			$ndata['extra_value']=(count($value) > 0)?json_encode($value):'';
			$ndata['total_amount']=$totalAmount;
			//pr($ndata);
			$car_books=$this->input->post('car_accept');
			$extra_reason=$this->input->post('extra_reason');
			$return=$this->ModelBooking->updateBooking($ndata,$book_id,$car_books,$extra_reason);
			if($return > 0){
				$result['msg']='Updated Successfully';
				$result['error']=0;
				$this->nsession->set_userdata('succmsg', 'Updated Successfully');
				$car_book = (array)$this->ModelBooking->getsingle_empdata($id);
				$subject='Car Booking Updated Bill';
				$body=$this->load->view('booking/invoiceSend',$car_book, true);
				$this->functions->mail_template($car_book['member_email'],$subject,$body);
			}else{
				$result['msg']="Invalid Request,Please try once again";
				$this->nsession->set_userdata('errmsg', $result['msg']);
				$result['error']=1;
			}
		}else{
			$result['error']=1;
			$this->nsession->set_userdata('errmsg', $result['msg']);
		}
		echo json_encode($result);
	}

}
?>