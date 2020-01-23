<?php

class ModelLogin extends CI_Model {

    var $username = '';
	var $admin_id = '';
	var $password = '';

	function __construct()
    {
        parent::__construct();
    }

	function authenticateUser($data)
    {
		$this->username   	= $data['username'];
		$this->password 	=  $data['password'];
		$login = false;
		$usercheck=$this->db->select('*')->get_where('staff',array('username'=>$this->username,'status'=>1))->row();
		if(count($usercheck) > 0){
			$this->db->select('manage_password.user_id');
			$this->db->where('manage_password.user_id',$usercheck->staff_id);
			$this->db->where('manage_password.password',md5($this->password));
			$this->db->where('manage_password.user_type','staff');

			$rs = $this->db->get('manage_password');

			if ($rs->num_rows() >0 )
			{
				$row = $usercheck;
				$login = true;			
			}
			//print_r($row);exit;

			if($login == true)	{			
				$this->nsession->set_userdata('admin_session_id', $row->staff_id);
				$this->nsession->set_userdata('admin_session_username', $row->username);
				$this->nsession->set_userdata('admin_session_fname', $row->first_name);
				$this->nsession->set_userdata('admin_session_usertype', $row->user_type);
				$this->nsession->set_userdata('admin_session_img_path', $row->picture);
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



	function updateAdminPass($id,$data)
	{
		$new_admin_pwd	=	$data['new_admin_pwd'];
		$result=$this->db->update('manage_password',array('password'=>md5($new_admin_pwd)),array('user_id'=>$this->nsession->userdata('admin_session_id'),'user_type'=>'staff'));
		//echo $this->db->last_query(); die();
		if($result > 0){
			return true;
		}else{
			return false;
		}
	}

	function getProfileData($id = 0)
	{
		$this->db->select('staff.*,countries.name as country_name,states.name as state_name,cities.name as city_name');
		$this->db->join('countries','countries.id=staff.country','Left Outer');
		$this->db->join('states','states.id=staff.state','Left Outer');
		$this->db->join('cities','cities.id=staff.city','Left Outer');
		$result=$this->db->get_where('staff',array('staff.staff_id'=>$id));
		return $result->row_array();		
	}

	function valideOldPassword(&$data)
	{	
		$oldpassword = $data['oldpassword'];
		$id = $this->nsession->userdata('admin_session_id');

		$admin_pwd_sql = "SELECT count(*) as CNT FROM manage_password WHERE user_id ='".$id."' and password ='".md5($oldpassword)."' and user_type ='staff'";

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
			$this->db->where('staff_id !=',$id);
		}
		$result=$this->db->select('staff_id')->get_where('staff',array('email'=>$email))->num_rows();
		return $result;
	}

	function checkPhone($phone,$id=0){
		if($id > 0){
			$this->db->where('staff_id !=',$id);
		}
		$result=$this->db->select('staff_id')->get_where('staff',array('phone'=>$phone))->num_rows();
		return $result;
	}

	function checkComapnyPhone($compnay_phone,$id=0){
		if($id > 0){
			$this->db->where('staff_id !=',$id);
		}
		$result=$this->db->select('staff_id')->get_where('staff',array('compnay_phone'=>$compnay_phone))->num_rows();
		return $result;
	}

	function checkUsername($username,$id=0){
		if($id > 0){
			$this->db->where('staff_id !=',$id);
		}
		$result=$this->db->select('staff_id')->get_where('staff',array('username'=>$username))->num_rows();
		return $result;
	}

	
	function getCustomerCount(){
		$this->db->select('*');
		$this->db->from('customer');
		return $this->db->get()->num_rows();
	}
	
	function getBannerCount(){
		$this->db->select('*');
		$this->db->from('banner');
		return $this->db->get()->num_rows();
	}

}
?>