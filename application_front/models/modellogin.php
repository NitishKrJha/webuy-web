<?php

class ModelLogin extends CI_Model {

    var $username = '';
    var $email = '';
	var $admin_id = '';
	var $password = '';

	function __construct()
    {
        parent::__construct();
    }

	function authenticateUser($data)
    {
		$this->email   	    =  $data['email'];
		$this->password 	=  $data['password'];
		$login = false;

		$usercheck=$this->db->select('*')->get_where('merchants',array('email'=>$this->email,'status'=>1))->row();
		if(count($usercheck) > 0){
			$this->db->select('manage_password.user_id');
			$this->db->where('manage_password.user_id',$usercheck->merchants_id);
			$this->db->where('manage_password.password',md5($this->password));
			$this->db->where('manage_password.user_type','merchants');

			$rs = $this->db->get('manage_password');

			if ($rs->num_rows() >0 )
			{
				$row = $usercheck;
				$login = true;			
			}
			//print_r($row);exit;

			if($login == true)	{			
				$this->nsession->set_userdata('merchants_session_id', $row->merchants_id);
				$this->nsession->set_userdata('merchants_session_username', $row->username);
				$this->nsession->set_userdata('merchants_session_fname', $row->first_name);
				$this->nsession->set_userdata('merchants_session_usertype', 'merchants');
				$this->nsession->set_userdata('merchants_session_img_path', $row->picture);
				return true;
			}
			else
			{
				return false;
			}
		}else{
			return false;
		}

	}

	function checktoken($token,$email){
		$user=$this->db->select('merchants_id')->get_where('merchants',array('email'=>$email))->row_array();
		if(count($user) > 0){
			$result=$this->db->get_where('manage_password',array('pass_token'=>$token,'user_id'=>$user['merchants_id'],'user_type'=>'merchants'))->row();
			//echo $this->db->last_query(); die();
			if(count($result) > 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}

	function change_password($password,$email){
		$user=$this->db->select('merchants_id')->get_where('merchants',array('email'=>$email))->row_array();
		if(count($user) > 0){
			$this->db->update('manage_password',array('password'=>$password,'pass_token'=>''),array('user_id'=>$user['user_id'],'user_type'=>'merchants'));
			return true;
		}else{
			return false;
		}
		
	}

	function isValidUsername($data)
    {

		$this->username   	= $data['username'];
		$query = "SELECT merchants_id FROM merchants WHERE username = '".$this->username."' and status = '1' ";

		$rs = $this->db->query($query);
		if ($rs->num_rows() >0 )
		{
			$row = $rs->row();
			$id = $row->id;
			return $id;			
		}
		else
		{
			return false;
		}
    }

	function updateAdminEmail($id,$data)
	{
		$this->email_address	= $data['email'];
		$query = "update merchants set email ='".$this->email_address."' where merchants_id ='".$id."'";
		$rs = $this->db->query($query);
		
		if($this->db->affected_rows())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function profileUpdate($id,$data)
	{
		$full_name			= $data['first_name'];
		$email_address		= $data['email'];

		$query = "UPDATE merchants SET 
				first_name 				= '".$full_name."',
				email 			= '".$email_address."' where id ='".$id."'";

		$rs = $this->db->query($query);

		if($this->db->affected_rows())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function getEmail($id)
	{
		$query = "select email_address from merchants where merchants_id ='".$id."'";
		$rs = $this->db->query($query);

		if($rs->num_rows() > 0)
		{
			$row = $rs->row();
			$email = $row->email;
			return $email;
		}
		else
		{
			return "";
		}
	}

	function updateAdminPass($id,$data)
	{
		$new_admin_pwd	=	$data['new_admin_pwd'];
		$result=$this->db->update('manage_password',array('password'=>md5($new_admin_pwd)),array('user_id'=>$this->nsession->userdata('merchants_session_id'),'user_type'=>'merchants'));
		if($result > 0){
			return true;
		}else{
			return false;
		}
	}

	function getProfileData($id = 0)
	{
		$this->db->select('merchants.*,countries.name as country_name,states.name as state_name,cities.name as city_name,countries1.name as company_country_name,states1.name as company_state_name,cities1.name as company_city_name');
		$this->db->join('countries','countries.id=merchants.country','Left Outer');
		$this->db->join('states','states.id=merchants.state','Left Outer');
		$this->db->join('cities','cities.id=merchants.city','Left Outer');
		$this->db->join('countries countries1','countries1.id=merchants.company_country','Left Outer');
		$this->db->join('states states1','states1.id=merchants.company_state','Left Outer');
		$this->db->join('cities cities1','cities1.id=merchants.company_city','Left Outer');
		$result=$this->db->get_where('merchants',array('merchants.merchants_id'=>$id));
		return $result->row_array();		
	}

	function valideOldPassword(&$data)
	{	
		$oldpassword = $data['oldpassword'];
		$id = $this->nsession->userdata('merchants_session_id');

		$admin_pwd_sql = "SELECT count(*) as CNT FROM manage_password WHERE user_id ='".$id."' and password ='".md5($oldpassword)."' and user_type ='merchants'";

		//echo $admin_pwd_sql; die();

		$recordSet = $this->db->query($admin_pwd_sql);

		$rs = false;		

		if($recordSet->num_rows() > 0)
		{
			$rs = array();
			$isEscapeArr = array();
			$row = $recordSet->row_array();

			if($row['CNT']>0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	function getCountryCityStateList($tbl_name,$check=array()){
		if(count($check) >0){
			$this->db->where($check);
		}
		$result=$this->db->get($tbl_name)->result_array();
		return $result;
	}

	function checkEmail($email,$id=0){
		if($id > 0){
			$this->db->where('merchants_id !=',$id);
		}
		$result=$this->db->select('merchants_id')->get_where('merchants',array('email'=>$email))->num_rows();
		return $result;
	}

	function checkPhone($phone,$id=0){
		if($id > 0){
			$this->db->where('merchants_id !=',$id);
		}
		$result=$this->db->select('merchants_id')->get_where('merchants',array('phone'=>$phone))->num_rows();
		return $result;
	}

	function checkComapnyPhone($compnay_phone,$id=0){
		if($id > 0){
			$this->db->where('merchants_id !=',$id);
		}
		$result=$this->db->select('merchants_id')->get_where('merchants',array('compnay_phone'=>$compnay_phone))->num_rows();
		return $result;
	}

	function checkUsername($username,$id=0){
		if($id > 0){
			$this->db->where('merchants_id !=',$id);
		}
		$result=$this->db->select('merchants_id')->get_where('merchants',array('username'=>$username))->num_rows();
		return $result;
	}
	

}
?>