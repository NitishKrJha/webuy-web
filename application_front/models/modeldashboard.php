<?php
class ModelDashboard extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
	function getContent($page_name,$lang){
		if($lang=='en'){
			$this->db->select('content_en');
		}else{
			$this->db->select('content_ch');
		}
		$this->db->from('contents');
		$this->db->where_in('page_name', $page_name);
		
		$query = $this->db->get();
		return $query->result_array();
	}
	function getHomeBanner(){
		$this->db->select('*');
		$this->db->from('banner');
		$this->db->where('is_active','1');
		$this->db->order_by('sort_num', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

	function getFaq(){
		$this->db->order_by('sort_num','asc');
		$result=$this->db->get_where('faq',array('is_active'=>1))->result_array();
		return $result;
	}

	function do_contact($data){
		$result=$this->db->insert('contact_us',$data);
		return $result;
	}
	
	function getListOfTable($tbl_name,$where,$listedType=''){
		$result=$this->db->get_where($tbl_name,$where);
		if($listedType=='single'){
			return $result->row_array();
		}else{
			return $result->result_array();
		}
	}

	function getSelectedData($selected,$tbl_name,$where){
		$result=$this->db->select($selected)->get_where($tbl_name,$where)->row_array();
		return $result;
	}

	function checkSellerStatus($merchants_id){
		$result=$this->db->select('business_status,gstin_verified,aadhar_card_verified,pan_verified')->get_where('merchants_business_details',array('merchants_id'=>$merchants_id))->row_array();
		$return=array();
		$return['error']=0;
		$return['msg']="All verified";
		//pr($result);
		if(count($result) > 0){
			if($result['gstin_verified']==0){
					$return['error']=1;
					$return['msg']="Your gstin detail is not verified yet";
				}
				if($result['aadhar_card_verified']==0){
					$return['error']=1;
					$return['msg']="Your aadhar detail is not verified yet";
				}
				if($result['pan_verified']==0){
					$return['error']=1;
					$return['msg']="Your pan detail is not verified yet";
				}
			
		}else{
			$return['error']=1;
			$return['msg']="You didn't add business details yet";
		}
		return $return;
	}

	function getCatLevelAllName($level1,$level2,$level3,$level4){
		$return=array('level1'=>'','level1_id'=>$level1,'level2'=>'','level2_id'=>$level2,'level3'=>'','level3_id'=>$level3,'level4'=>'','level4_id'=>$level4,'all_name'=>'');
		$query1=$this->db->select('name')->get_where('category_level_1',array('id'=>$level1))->row();
		$all_name='';
		if(isset($query1->name)){
			$return['level1']=$query1->name;
			$all_name .=$return['level1'];
		}
		$query2=$this->db->select('name')->get_where('category_level_2',array('id'=>$level2))->row();
		if(isset($query2->name)){
			$return['level2']=$query2->name;
			$all_name .="-> ".$return['level2'];
		}
		$query3=$this->db->select('name')->get_where('category_level_3',array('id'=>$level3))->row();
		if(isset($query3->name)){
			$return['level3']=$query3->name;
			$all_name .="-> ".$return['level3'];
		}
		$query4=$this->db->select('name')->get_where('category_level_4',array('id'=>$level4))->row();
		if(isset($query4->name)){
			$return['level4']=$query4->name;
			$all_name .="-> ".$return['level4'];
		}
		$return['all_name']=$all_name;
		return $return;
	}

	function checkEmail($email_id){
		$sql = "SELECT * FROM member WHERE email='".$email_id."'";
		$result = $this->db->query($sql);
		return $result->result_array();
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
	function checktoken($token,$email){		
		$result=$this->db->get_where('member',array('forgetpass'=>$token,'email'=>$email))->row();		
		if(count($result) > 0){			
			return true;		
		}else{			
			return false;		
		}	
	}	
	function inserttokenforpassword($email){		
		$token=$this->getToken(20);		
		$this->db->update('member',array('password'=>md5($token)),array('email'=>$email));		
		return $token;	
	}
	function newforpassword($email){		
		$token=$this->randomPassword();		
		$this->db->update('member',array('password'=>md5($token)),array('email'=>$email));
		return $token;	
	}	
	function change_password($password,$email){		
		$this->db->update('member',array('password'=>$password,'forgetpass'=>''),array('email'=>$email));		
		return true;	
	}

	function checkMember($check){
		$result=$this->db->get_where('member',$check)->row_array();
		return $result;
	}

	function authenticateUser($data){
		$this->db->where('email',$data['username']);
		$this->db->or_where('phone_no',$data['username']);
		$username_check=$this->db->select('id')->get_where('member')->row_array();
		if(count($username_check) > 0){
			$password_check=$this->db->get_where('member',array('id'=>$username_check['id'],'password'=>$data['password']))->row_array();
			if(count($password_check) > 0){
				$this->nsession->set_userdata('member_login', 'true');
				$this->nsession->set_userdata('member_session_id', $password_check['id']);
				$this->nsession->set_userdata('member_session_email', $password_check['email']);
				$this->nsession->set_userdata('member_session_name', $password_check['first_name']);
				return $password_check;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function do_register($data){
		$result=$this->db->insert('member',$data);
		if($result){
			$id=$this->db->insert_id();
			$to 			= $data['email'];
			$subject		= "Registration";
			$body			= "<tr><td>Hi,</td></tr>
							<tr><td>Thanks for opening an account on our platform.</td></tr>";
			$this->functions->mail_template($to,$subject,$body);
			return $id;
		}else{
			return false;
		}
		
	}

	function memberlogin($id){
		$details=$this->db->get_where('member',array('id'=>$id))->row_array();
		if(count($details) > 0){
			$this->nsession->set_userdata('member_login', 'true');
			$this->nsession->set_userdata('member_session_id', $details['id']);
			$this->nsession->set_userdata('member_session_email', $details['email']);
			$this->nsession->set_userdata('member_session_name', $details['first_name']);
			return $details;
		}else{
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

	function getCarDetails($car_id){
		$result=$this->db->get_where('car',array('car_id'=>$car_id))->row_array();
		if(count($result) > 0){
			$img=$this->db->get_where('car_images',array('car_id'=>$car_id))->result_array();
			$result['img']=$img;
		}
		return $result;
	}

	function randomPassword() {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}

	function getCreditType(){
		$result=$this->db->get_where('credit_card_type',array('status'=>1))->result_array();
		return $result;
	}

	function insertData($tbl_name,$data){
		$result=$this->db->insert($tbl_name,$data);
		return $this->db->insert_id();
	}

	function updateData($tbl_name,$data,$check){
		$result=$this->db->update($tbl_name,$data,$check);
		return true;
	}

	function getCarBookDtl($id){
		$this->db->select('car_book.*,member.first_name as member_first_name,member.last_name as member_last_name,member.email as member_email,car.name as car_name');
		$this->db->from('car_book');
		$this->db->join('member','member.id=car_book.member_id','Left Outer');
		$this->db->join('car','car.car_id=car_book.car_id','Left Outer');
		$this->db->where('car_book.book_id',$id);
		$data = $this->db->get();
		return $data->row();
	}

	function getReferenceNumber()
	{
		$maxID=$this->db->select('MAX(book_id) as max_id')->get('car_book')->row_array();
		$rand = time() . rand(10*45, 100*98);
		$number=1;
		if($max_id->max_id > 0){
			$number = (int)$max_id->max_id + 1;
		}
		$rand = $number + $rand;
		return $rand;
	}

	function getInvoice($id){
		$this->db->select('car_book.*,member.address as member_address,member.zipcode as member_zipcode,member.first_name as member_first_name,member.last_name as member_last_name,member.email as member_email,car.name as car_name,countries.name as book_country_name,m_countries.name as country_name,states.name as state_name,cities.name as city_name,car_book_payment.*');
		$this->db->from('car_book');
		$this->db->join('member','member.id=car_book.member_id','Left Outer');
		$this->db->join('countries m_countries','m_countries.id=member.country','Left Outer');
		$this->db->join('countries','countries.id=car_book.country','Left Outer');
		$this->db->join('cities','cities.id=member.city','Left Outer');
		$this->db->join('states','states.id=member.state','Left Outer');
		$this->db->join('car','car.car_id=car_book.car_id','Left Outer');
		$this->db->join('car_book_payment','car_book_payment.book_id=car_book.book_id','Left Outer');
		$this->db->where('car_book.book_id',$id);
		$data = $this->db->get();
		return $data->row();
	}

	function getAllAddresslist(&$config,&$start,&$param){
		
		// GET DATA FROM GET/POST  OR   SESSION ====================
		$Count = 0;
		$page = $this->uri->segment(3,0); // page
		$isSession = $this->uri->segment(4,0); // read data from SESSION or POST     (1 == POST , 0 = SESSION )
		
		$start = 0;
				
		$sortType 		= $param['sortType'];
		$sortField 		= $param['sortField'];
		$searchField 	= $param['searchField'];
		$searchString 	= $param['searchString'];
		$searchText 	= $param['searchText']; 
		$searchFromDate	= $param['searchFromDate']; 
		$searchToDate 	= $param['searchToDate']; 
		$searchAlpha	= $param['searchAlpha']; 
		$searchMode		= $param['searchMode'];	
		$searchDisplay 	= $param['searchDisplay'];
		$customer_id 	= $param['customer_id']; 

		if($isSession == 0)
		{
			$sortType    	= $this->nsession->get_param('ADDRESS_LIST','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADDRESS_LIST','sortField','id');
			$searchField 	= $this->nsession->get_param('ADDRESS_LIST','searchField','');
			$searchString 	= $this->nsession->get_param('ADDRESS_LIST','searchString','');
			$searchText  	= $this->nsession->get_param('ADDRESS_LIST','searchText','');
			$searchFromDate = $this->nsession->get_param('ADDRESS_LIST','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADDRESS_LIST','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADDRESS_LIST','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADDRESS_LIST','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADDRESS_LIST','searchDisplay',20);
		}
		
		//========= SET SESSION DATA FOR SEARCH / PAGE / SORT Condition etc =====================
		$sessionDataArray = array();
		$sessionDataArray['sortType'] 		= $sortType;
		$sessionDataArray['sortField'] 		= $sortField;
		if($searchField!=''){
			$sessionDataArray['searchField'] 	= $searchField;
			$sessionDataArray['searchString'] 	= $searchString ;
		}
		$sessionDataArray['searchText'] 	= $searchText;		
		$sessionDataArray['searchFromDate'] = $searchFromDate;		
		$sessionDataArray['searchToDate'] 	= $searchToDate;
		$sessionDataArray['searchAlpha'] 	= $searchAlpha;	
		$sessionDataArray['searchMode'] 	= $searchMode;
		$sessionDataArray['searchDisplay'] 	= $searchDisplay;		
		
		$this->nsession->set_userdata('ADDRESS_LIST', $sessionDataArray);
		//==============================================================
		$this->db->select('COUNT(address_id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->where('shipping_address.customer_id',$customer_id);
		//$this->db->where('shipping_address.status',1);
		$this->db->select('shipping_address.*');

		$recordSet = $this->db->get('shipping_address'); 
		$config['total_rows'] = 0;
		$config['per_page'] = $searchDisplay;
		if ($recordSet)
		{
			$row = $recordSet->row();
			$config['total_rows'] = $row->TotalrecordCount;
		}
		else
		{
			return false;
		}

		if($page > 0 && $page < $config['total_rows'] )
			$start = $page;
			$this->db->select('shipping_address.*');
			$this->db->where('shipping_address.customer_id',$customer_id);
			//$this->db->where('shipping_address.status',1);
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		//$this->db->order_by($sortField,$sortType);
		$this->db->order_by('shipping_address.address_id','desc');
		$this->db->limit($config['per_page'],$start);

		$recordSet = $this->db->get('shipping_address');
		//echo $this->db->last_query(); die();
		$rs = false;

		if ($recordSet->num_rows() > 0)
        {
           	$rs = array();
			$isEscapeArr = array();
			foreach ($recordSet->result_array() as $row)
			{
				foreach($row as $key=>$val){
					if(!in_array($key,$isEscapeArr)){
						$recordSet->fields[$key] = outputEscapeString($val);
						}
					}
				$rs[] = $recordSet->fields;
			}
		}
		else
		{
			return false;
		}
		return $rs;		
	}

	function setDeaultToAddress($address_id,$customer_id){
		$disableall=$this->db->update('shipping_address',array('status'=>0),array('customer_id'=>$customer_id));
		if($disableall > 0){
			$result=$this->db->update('shipping_address',array('status'=>1),array('customer_id'=>$customer_id,'address_id'=>$address_id));
			if($result > 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function getMyAllReview(){
        $my_reviews=array();
        $customer_id = $this->nsession->userdata('member_session_id');
        $product_review_query = $this->db->query("select * from product_rating where customer_id=$customer_id and is_active=1");
        if($product_review_query->num_rows() >0){
            foreach ($product_review_query->result_array() as $review){
                $product_id=$review['product_id'];
                $review['details'] = $this->db->query("select * from product where product_id=$product_id")->row_array();
                $product_image = $this->db->query("select path_sm from product_images where product_id=$product_id and type='main'")->row_array();
                if($product_image['path_sm']!=''){
                    $pic=file_upload_base_url().'product/'.$product_image['path_sm'];
                }else{
                    $pic=css_images_js_base_url().'images/no_pr_img.jpg';
                }
                $review['pic_sm']=$pic;
                array_push($my_reviews,$review);
            }
        }
        return $my_reviews;

    }

    function getMyEarningHistory(){
        $data=array();
        $customer_id = $this->nsession->userdata('member_session_id');
        $my_earn_query = $this->db->query("select * from customer_earn_history where refer_by=$customer_id order by earn_id desc");
        if($my_earn_query->num_rows() >0){
            foreach ($my_earn_query->result_array() as $row){
                $product_id=$row['refer_product'];
                $row['product']=$this->db->query("select * from product where product_id=$product_id")->row()->title;
                array_push($data,$row);
            }
            return $data;
        }
        return false;
    }

    function total_earning_balance(){
        $customer_id = $this->nsession->userdata('member_session_id');
        $my_earn_query = $this->db->query("select sum(earn_amount) as total_earn from customer_earn_history where refer_by=$customer_id order by earn_id desc");
        if($my_earn_query->num_rows() >0){
            return $my_earn_query->row()->total_earn;
        }
        return 0;
    }
    function success_earning_balance(){
        $customer_id = $this->nsession->userdata('member_session_id');
        $my_earn_query = $this->db->query("select sum(earn_amount) as success_earn from customer_earn_history where refer_by=$customer_id and status=1 order by earn_id desc");
        if($my_earn_query->num_rows() >0){
            return $my_earn_query->row()->success_earn;
        }
        return 0;
    }

    function pending_earning_balance(){
        $customer_id = $this->nsession->userdata('member_session_id');
        $my_earn_query = $this->db->query("select sum(earn_amount) as pending_earn from customer_earn_history where refer_by=$customer_id and status=0 order by earn_id desc");
        if($my_earn_query->num_rows() >0){
            return $my_earn_query->row()->pending_earn;
        }
        return 0;
    }
}