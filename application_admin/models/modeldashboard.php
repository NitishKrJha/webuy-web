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
	function getBrandsDetails(){
		$this->db->select('*');
		$this->db->from('brands');
		$result=$this->db->get()->result_array();
		return $result;
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
			$all_data=$result->result_array();
			return $all_data;
		}
	}

	function getAllProductTitle(){
		$query = $this->db->query("select title from product order by title asc");
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return false;
	}

	function getListOfAllVariation($tbl_name,$where){
		$result=$this->db->get_where($tbl_name,$where);
		$all_data=$result->result_array();
		//echo $this->db->last_query(); die();
		//pr($all_data);
		$variation=array();
		if($tbl_name=='variation_attribute'){
			if(count($all_data) > 0){

				foreach ($all_data as $key => $value) {
					if(isset($value['id'])){
						$var_val=$this->db->select('id,name')->get_where('variation_attribute_value',array('variation_id'=>$value['id']))->result_array();
						if(count($var_val) > 0){
							$allval=array();
							foreach ($var_val as $key_sub => $val_sub) {
								$allval[$val_sub['id']]=$val_sub['name'];
							}
							$variation[$value['name']]=$allval;
						}
					}
				}
			}
		}
		return $variation;
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

	function check_current_password($mid,$current_password){
		$result=$this->db->get_where('manage_password',array('user_id'=>$mid,'user_type'=>'merchants','password'=>$current_password))->result_array();;
		if($result){			
			return $mid;
		}else{
			return false;
		}
		
	}

	function update_password($mid,$password){
		$memberMoreData = array(
		'password'	=> md5($password)		
		);	

        $this->db->where('user_id',$mid);
		$this->db->where('user_type','merchants');
		$this->db->update('manage_password', $memberMoreData); 
		return true;		
	}

    function update_profile_image($mid,$imagename){
		$memberMoreData = array(
		'picture'	=> $imagename		
		);	

        $this->db->where('merchants_id',$mid);
		$this->db->update('merchants', $memberMoreData); 
		return true;		
	}

	function get_all_order($merchant_id,$status_id){
        $order_product= array();
        $order_product_query = $this->db->query("select opd.*,odr.id as order_id,odr.name as customer_name,odr.phone,odr.shipping_address,odr.created_at,ods.status as order_status from order_product_details as opd  left join orders as odr on odr.id=opd.order_id left join order_status as ods on ods.id=opd.status where opd.merchant_id=$merchant_id and opd.status=$status_id order by opd.id desc ");
        if($order_product_query->num_rows() > 0){
            foreach ($order_product_query->result_array() as $ordproduct){
                $product_id = $ordproduct['product_id'];
                $product_image = $this->db->query("select path_sm from product_images where product_id=$product_id and type='main'")->row_array();
                if($product_image['path_sm']!=''){
                    $pic=file_upload_base_url().'product/'.$product_image['path_sm'];
                }else{
                    $pic=css_images_js_base_url().'images/no_pr_img.jpg';
                }
                $ordproduct['pic_sm']=$pic;

                array_push($order_product,$ordproduct);
            }

            return $order_product;
        }
        return false;
    }


    function getOrder(){
    	$product_pagination='';
    	if($this->input->post('page_number')!=''){
          $page_number = $this->input->post('page_number');
	      }else{
	          $page_number = 1;
	      }
	      $item_per_page  = 5;

    	$ordertype = $this->input->post('ordertype');
    	$orderdate = $this->input->post('orderdate');
    	$ordersearch = $this->input->post('ordersearch');
        $merchant_id=$this->nsession->userdata('merchants_session_id');
        $status_id = $this->input->post('orderstatus');
        $sql ='select opd.*,odr.id as order_id,odr.order_id as orderid,odr.name as customer_name,odr.phone,odr.shipping_address,odr.created_at,ods.status as order_status from order_product_details as opd  left join orders as odr on odr.id=opd.order_id left join order_status as ods on ods.id=opd.status where opd.merchant_id='.$merchant_id;
        if($ordertype!='neworder' && $status_id!='' && $status_id >0){
          $sql .=' and opd.status='.$status_id;
        }elseif($ordertype=='neworder'){
           $sql .=' and opd.status=1';
        }else{

        }

        if($orderdate!=''){
        	$sql .=' and DATE(odr.order_dt ) ="'.date('Y-m-d',strtotime($orderdate)).'"';
        }

        if($ordersearch!=''){
        	$sql .=" and odr.order_id like '%".$ordersearch."%'";
        }
        $sql .=' order by opd.id desc';
    	
      $get_total_rows =$this->db->query($sql)->num_rows();
      $total_pages = ceil($get_total_rows/$item_per_page);
      $page_position = (($page_number-1) * $item_per_page);
      $sql.=" LIMIT ".$page_position.",".$item_per_page;

    	$orderhtml='';
    	$sqlFilterQuery =  $this->db->query($sql);

    	if($sqlFilterQuery->num_rows() > 0){
    		$order_product= array();
            foreach ($sqlFilterQuery->result_array() as $ordproduct){
                $product_id = $ordproduct['product_id'];
                $product_image = $this->db->query("select path_sm from product_images where product_id=$product_id and type='main'")->row_array();
                if($product_image['path_sm']!=''){
                    $pic=file_upload_base_url().'product/'.$product_image['path_sm'];
                }else{
                    $pic=css_images_js_base_url().'images/no_pr_img.jpg';
                }
                $ordproduct['pic_sm']=$pic;
               array_push($order_product,$ordproduct); 
            }
            $data=array();
            $data['new_order_data']=$order_product;
            $data['order_status'] = $this->getListOfTable('order_status');
            $data['page_number']=$page_number;
            $data['item_per_page']=$item_per_page;
            $orderhtml=$this->load->view('ajax_page/seller_order',$data,true);
            $product_pagination=$this->paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);
        }else{
           $orderhtml='<tr><td colspan="9" align="center"><p class="para1">No data to display</p></td></tr>';
        }
       header('Content-Type: application/json');
      echo json_encode( array('product_list'=>$orderhtml,'product_pagination'=>$product_pagination));
       // echo $orderhtml;
     }

    function get_invoice_order($order_id,$product_id){
        $order_product= array();
        $order_product_query = $this->db->query("select opd.*,odr.id as order_id,odr.name as customer_name,odr.phone,odr.shipping_address,odr.created_at,ods.status as order_status,prod.gst from order_product_details as opd  left join orders as odr on odr.id=opd.order_id left join order_status as ods on ods.id=opd.status left join product as prod on prod.product_id=opd.product_id where odr.id=$order_id and opd.product_id=$product_id order by opd.id desc ");
        if($order_product_query->num_rows() > 0){
            foreach ($order_product_query->result_array() as $ordproduct){
                $product_id = $ordproduct['product_id'];
                $product_image = $this->db->query("select path_sm from product_images where product_id=$product_id and type='main'")->row_array();
                if($product_image['path_sm']!=''){
                    $pic=file_upload_base_url().'product/'.$product_image['path_sm'];
                }else{
                    $pic=css_images_js_base_url().'images/no_pr_img.jpg';
                }
                $merchant_id=$ordproduct['merchant_id'];
                $ordproduct['pic_sm']=$pic;
                $ordproduct['merchant']=$this->db->query("select * from merchants_business_details where merchants_id=$merchant_id ")->row_array();

                array_push($order_product,$ordproduct);
            }

            return $order_product[0];
        }
        return false;
    }

    function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
    {
        $pagination = '';
        if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number

            $pagination .= '<div class="col-sm-12"> <ul class="pagination pull-right">';
            $right_links    = $current_page + 3;
            $previous       = $current_page - 1; //previous link
            $next           = $current_page + 1; //next link
            $first_link     = true; //boolean var to decide our first link
            if($current_page > 1){
                $previous_link = ($previous==0)? 1: $previous;
                $pagination .= '<li class="paginate_button previous"><a href="#" data-page="1" title="First">First</a></li>'; //first link

                $pagination .= '<li class="paginate_button previous"><a href="#" data-page="'.$previous_link.'" title="Previous">Previous</a></li>'; //previous link

                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
                    }

                }
                $first_link = false; //set first link to false
            }

            if($first_link){ //if current active page is first link
                $pagination .= '<li class="active"><a href="#">'.$current_page.'</a></li>';
            }elseif($current_page == $total_pages){ //if it's the last active link
                $pagination .= '<li class="active"><a href="#">'.$current_page.'</a></li>';
            }else{ //regular current link
                $pagination .= '<li class="active"><a href="#">'.$current_page.'</a></li>';
            }

            for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
                if($i<=$total_pages){
                    $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'" >'.$i.'</a></li>';
                }
            }

            if($current_page < $total_pages){
                $next_link = ($next > $total_pages) ? $total_pages : $next;
                $pagination .= '<li class="paginate_button next"><a href="#" data-page="'.$next_link.'" title="Next">Next</a></li>'; //next link

                $pagination .= '<li class="paginate_button next"><a href="#" data-page="'.$total_pages.'" title="Last">Last</a></li>'; //last link

            }
            $pagination .= '</ul></div>';
        }
        return $pagination; //return pagination links
    }





}