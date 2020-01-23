<?php
class Logout extends CI_Controller {
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		$this->nsession->destroy();
		redirect(base_url());
	}
}