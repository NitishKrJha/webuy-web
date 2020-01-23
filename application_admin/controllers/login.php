<?php
class Login extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('ModelLogin');
		$this->controller = 'login';
	}

	function index()
	{
		$UserID = $this->nsession->userdata('admin_session_id');
		if($UserID)		{
			redirect(base_url("user/"));
			return true;
		}
		$data['succmsg'] = $this->nsession->userdata('succmsg');
		$data['errmsg'] = $this->nsession->userdata('errmsg');
		$data['do_login']=base_url($this->controller."/do_login/");

		$this->nsession->set_userdata('succmsg', "");
		$this->nsession->set_userdata('errmsg', "");

		$this->layout->setLayout('layout_login'); 
		$this->layout->view('login/index',$data);
	}

	function do_login()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		if ($this->form_validation->run() == TRUE)
		{
			$data['username'] = $this->input->request('username','');
			$data['password'] = $this->input->request('password','');
			if($this->ModelLogin->authenticateUser($data))
			{
				$this->nsession->set_userdata('succmsg','You have logged in successfully...');
				$referer_path = get_cookie('admin_referer_path', TRUE);
				delete_cookie("admin_referer_path");
				if($referer_path)
					redirect(base_url($referer_path));
				else
					redirect(base_url("user/"));
				return true;
			}
			else
			{
				$this->nsession->set_userdata('errmsg', "Invalid username and/or password.");
				redirect(base_url($this->controller."/"));
				return true;
			}
		}
		else
		{
			$this->index();
			die;
		}
	}
}