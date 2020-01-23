<?php
class ModelPage extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    function getCarList(&$config,&$start,&$param)
	{
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
		
		if($isSession == 0)
		{
			$sortType    	= $this->nsession->get_param('ADMIN_car','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_car','sortField','car_id');
			$searchField 	= $this->nsession->get_param('ADMIN_car','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_car','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_car','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_car','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_car','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_car','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_car','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_car','searchDisplay',10);
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
		
		$this->nsession->set_userdata('ADMIN_MANAGEPOSITION', $sessionDataArray);
		//==============================================================
		$this->db->select('COUNT(car_id) as TotalrecordCount');
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$this->db->select('car.*');
		$this->db->where('status',1);
		$recordSet = $this->db->get('car'); 
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
			$this->db->select('car.*');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
			
		$this->db->order_by('car_id','desc');
		$this->db->limit($config['per_page'],$start);
		$this->db->where('status',1);
		$recordSet = $this->db->get('car');
		//echo $this->db->last_query();
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
			if(count($rs) > 0){
				foreach ($rs as $key => $value) {
					$imgdata=$this->db->get_where('car_images',array('car_id'=>$value['car_id']))->result_array();
					$rs[$key]['img']=$imgdata;
				}
			}
		}
		else
		{
			return false;
		}
		return $rs;	
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
		$this->db->where('icon !=','');
		$this->db->order_by('sort_num', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

    function getHomeAd(){
        $this->db->select('*');
        $this->db->from('adsection');
        $this->db->where('is_active','1');
        $this->db->where('icon !=','');
        $this->db->order_by('sort_num', 'asc');
        $this->db->limit(2,0);
        $query = $this->db->get();
        return $query->result_array();
    }

	function getFaq(){
		$this->db->order_by('id','desc');
		$result=$this->db->get_where('faq',array('is_active'=>1))->result_array();
		return $result;
	}

	function do_contact($data){
		$result=$this->db->insert('contact_us',$data);
		return $result;
	}
	/* Ads listing section start */
	
	function getAdsList(&$config,&$start,&$param)
	{
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
		$searchStatus	= $param['searchStatus']; 
		$searchToDate 	= $param['searchToDate']; 
		$searchAlpha	= $param['searchAlpha']; 
		$searchMode		= $param['searchMode'];	
		$searchDisplay 	= $param['searchDisplay']; 
		$searchFrom 	= $param['startFrom']; 
		
		/* search filter start */
		
		$city 			= $param['city'];
		$categories		= $param['categories'];
		$subcategories	= $param['subcategories'];
		$search_key		= $param['search_key']; 	
		$display_type	= $param['display_type']; 	
		
		$ad_available	= $param['ad_available'];	
		$price_from 	= $param['price_from']; 	
		$price_to		= $param['price_to'];			
		$rental_per 	= $param['rental_per']; 		
		
		/* search filter end */
		
		//========= SET SESSION DATA FOR SEARCH / PAGE / SORT Condition etc =====================
		$sessionDataArray = array();
		$sessionDataArray['sortType'] 		= $sortType;
		$sessionDataArray['sortField'] 		= $sortField;
		if($searchField!=''){
			$sessionDataArray['searchField'] 	= $searchField;
			$sessionDataArray['searchString'] 	= $searchString ;
		}
		$sessionDataArray['searchText'] 	= $searchText;
		$sessionDataArray['searchStatus'] 	= $searchStatus;		
		$sessionDataArray['searchFromDate'] = $searchFromDate;		
		$sessionDataArray['searchToDate'] 	= $searchToDate;
		$sessionDataArray['searchAlpha'] 	= $searchAlpha;	
		$sessionDataArray['searchMode'] 	= $searchMode;
		$sessionDataArray['searchDisplay'] 	= $searchDisplay;	
		$sessionDataArray['startFrom'] 	= $param['startFrom']; 		
		
		
		//===============================Query section start===============================//
		/* Filter search for query start  */
			if($city!=''){
				$this->mongo_db->like('city', $city, 'im', FALSE, TRUE);
			}
			if($categories!=''){
				$this->mongo_db->where(array('parent' => $categories));
			}
			if($subcategories!=''){
				$this->mongo_db->where(array('child' => $subcategories));
			}
			if($search_key!=''){
				$this->mongo_db->like('ads_title', $search_key, 'im', TRUE, TRUE);
			}
			/* if($ad_available!=''){
				$this->mongo_db->where(array('ad_available' => 1));
			}
			if($price_from!='' && $price_to!=''){
				$this->mongo_db->where_between('ads_price', (int)$price_from, (int)$price_to);
			}else{
				
				if($price_from!=''){
					$this->mongo_db->where_gt('ads_price', (int)$price_from);
				}
				if($price_to!=''){
					$this->mongo_db->where_lt('ads_price',(int)$price_to);
				}
			}
			if($rental_per!=''){
				$this->mongo_db->where(array('rental_per' => $rental_per));
			} */	
			
		/* Filter search for query end */
		
		$this->mongo_db->where(array('is_active' => 1));
		$recordSet = $this->mongo_db->get('ad_post_data'); 
		
		$config['total_rows'] = 0;
		$config['per_page'] = $searchDisplay;
		if ($recordSet)
		{
			$config['total_rows'] = count($recordSet);
		}
		else
		{
			return false;
		}
		if(count($recordSet)>0){
			
		/* Filter search for query start  */
			if($city!=''){
				$this->mongo_db->like('city', $city, 'im', TRUE, TRUE);
			}
			if($categories!=''){
				$this->mongo_db->where(array('parent' => $categories));
			}
			if($subcategories!=''){
				$this->mongo_db->where(array('child' => $subcategories));
			}
			if($search_key!=''){
				$this->mongo_db->like('ads_title', $search_key, 'im', TRUE, TRUE);
			}
			/* if($ad_available!=''){
				$this->mongo_db->where(array('ad_available' => 1));
			}
			if($price_from!='' && $price_to!=''){
				$this->mongo_db->where_between('ads_price', (int)$price_from, (int)$price_to);
			}else{
				
				if($price_from!=''){
					$this->mongo_db->where_gt('ads_price', (int)$price_from);
				}
				if($price_to!=''){
					$this->mongo_db->where_lt('ads_price', (int)$price_to);
				}
			}
			if($rental_per!=''){
				$this->mongo_db->where(array('rental_per' => $rental_per));
			}	 */
			
		/* Filter search for query end */
		$this->mongo_db->order_by(array('premium_ad' => 'DESC'));
		$this->mongo_db->where(array('is_active' => 1));
		$this->mongo_db->limit($config['per_page']);
		$this->mongo_db->offset($sessionDataArray['startFrom']);
		$recordSet = $this->mongo_db->get('ad_post_data');
		$config['start'] = $start;
		$rs = false;
		if (count($recordSet) > 0)
        {
			$return_array = array();
			//$i=0
			foreach($recordSet as $rset){
				$dataSet['ads_title'] 		= $rset['ads_title'];
				$dataSet['ads_image'] 		= $rset['ads_pic1'];
				if(date('Y m d') <= date('Y m d',strtotime($rset['post_date']))){
					$dataSet['is_new'] 	  		= 1;
				}else{
					$dataSet['is_new'] 	  		= 0;
				}
				$dataSet['for_sale'] 		= $rset['categoryData']['parent'];
				$dataSet['ads_id'] 			= date('Y',strtotime($rset['post_date'])).$rset['pid'];
				$dataSet['pid'] 			= $rset['pid'];
				$dataSet['ads_posted_on'] 	= date('d M Y',strtotime($rset['post_date']));
				$dataSet['ads_active_till'] = date('d M Y',strtotime($rset['ads_exp_date']));
				$dataSet['offer_type'] 		= ucfirst($rset['rent_type']);
				$todayDate = date('Y-m-d');
				$todayDate=date('Y-m-d', strtotime($todayDate));
				$featureDateBegin = date('Y-m-d', strtotime($rset['feature_ads_starting_date']));
				$featureDateEnd = date('Y-m-d', strtotime($rset['feature_ads_ending_date']));
				if (($todayDate >= $featureDateBegin) && ($todayDate <= $featureDateEnd))
				{
					$dataSet['is_feature'] 		= 1;
				}else{
					$dataSet['is_feature'] 		= 0;
				}
				$dataSet['ads_price'] 		= $rset['rent_price'];
				if($rset['ad_available']==1){
					$dataSet['ads_available'] 	= 1;
				}else{
					$dataSet['ads_available'] 	= 0;
				}
				$return_array[] 			= $dataSet;
			}
           	return $return_array;
		}
		else
		{
			return false;
		}
		}
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
}