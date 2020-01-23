<?php
class ModelAction extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function authenticateUser($data)
    {
		$this->db->where('email',$data['email']);
		//$this->db->or_where('phone',$data['email']);
		$usercheck=$this->db->select('customer_id,email,first_name,last_name')->get_where('customer',array('status'=>1))->row();
		if(count($usercheck) > 0){
			$this->db->select('manage_password.user_id');
			$this->db->where('manage_password.user_id',$usercheck->customer_id);
			$this->db->where('manage_password.password',$data['password']);
			$this->db->where('manage_password.user_type','customer');

			$rs = $this->db->get('manage_password');

			//echo $this->db->last_query(); die();
			if ($rs->num_rows() >0 )
			{
				$userdata=(array)$usercheck;
				$this->nsession->set_userdata('member_login', 'true');
				$this->nsession->set_userdata('member_session_id', $userdata['customer_id']);
				$this->nsession->set_userdata('member_session_email', $userdata['email']);
				$this->nsession->set_userdata('member_session_name', $userdata['first_name']);
				$this->nsession->set_userdata('member_session_lname', $userdata['last_name']);
				$this->nsession->set_userdata('member_type', 'customer');
				return $userdata;		
			}else{
				return false;
			}
		}else{
			return false;
		}
		
    }

    function checktoken($token,$email){
		$user=$this->db->select('customer_id')->get_where('customer',array('email'=>$email))->row_array();
		if(count($user) > 0){
			$result=$this->db->get_where('manage_password',array('pass_token'=>$token,'user_id'=>$user['customer_id'],'user_type'=>'customer'))->row();
			//echo $this->db->last_query(); die();
			//pr($result);
			if(count($result) > 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}

	function updatePassword($data,$id){
		$check=$this->db->select('user_id,last_password')->get_where('manage_password',array('user_id'=>$id,'user_type'=>'customer'))->row_array();
		if(count($check) <= 0){
			$data['user_type']='customer';
			$data['user_id']=$id;
			$data['last_password']=$data['password'];
			$this->db->insert('manage_password',$data);
		}else{
			$data['last_password']=$check['last_password'];
			$this->db->update('manage_password',$data,array('user_id'=>$id,'user_type'=>'customer'));
		}
		return true;
	}

	function change_password($password,$email){
		$user=$this->db->select('customer_id')->get_where('customer',array('email'=>$email))->row_array();
		//pr($user);
		if(count($user) > 0){
			$this->db->update('manage_password',array('password'=>$password,'pass_token'=>''),array('user_id'=>$user['customer_id'],'user_type'=>'customer'));
			//echo $this->db->last_query(); die();
			return true;
		}else{
			return false;
		}
		
	}

	function checkEmail($email){
		$result=$this->db->select('customer_id')->get_where('customer',array('email'=>$email))->num_rows();
		return $result;
	}

	function checkEmailData($email){
		$result=$this->db->get_where('customer',array('email'=>$email))->row_array();
		return $result;
	}

	function checkPhone($phone){
		$result=$this->db->select('customer_id')->get_where('customer',array('phone'=>$phone))->num_rows();
		return $result;
	}

	function checkUsername($username){
		$result=$this->db->select('customer_id')->get_where('customer',array('username'=>$username))->num_rows();
		return $result;
	}

	function inserttokenforpassword($customer_id){
		//echo $email; die();
		$token=$this->getToken(20);		
		$this->db->update('manage_password',array('pass_token'=>$token),array('user_id'=>$customer_id,'user_type'=>'customer'));
		return $token;	
	}

	function crypto_rand_secure($min, $max)	{	    
		$range = $max - $min;	    
		if ($range < 1) 
			return $min; 	    
		$log = ceil(log($range, 2));	    
		$bytes = (int) ($log / 8) + 1; 	    
		$bits = (int) $log + 1; 	    
		$filter = (int) (1 << $bits) - 1; 	    
		do {	        
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));	        
			$rnd = $rnd & $filter; 	    
		} while ($rnd > $range);	    
		return $min + $rnd;	
	}

	function getToken($length)	{	    
		$token = "";	    
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";	    
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";	    
		$codeAlphabet.= "0123456789";	    
		$max = strlen($codeAlphabet);	    
		for ($i=0; $i < $length; $i++) {	        
			$token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];	   
			 }	    
		return $token;	
	}
}