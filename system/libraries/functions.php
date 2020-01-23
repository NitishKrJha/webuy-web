<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Functions
{

	/**
	 * Constructor
	 */
    function __construct()
    {
        $this->obj =& get_instance();
    }
	// --------------------------------------------------------------------

    	/**
	 * Get Country List
	 *
	 * This function queries database table "country" with name of the country in assecending order
	 * and returns array of country country id and country name
	 *
	 * @access	public
	 * @return	array
	 */
	 
	function getAllTable($table,$col,$field='',$value='')
	{
		$query="SELECT ".$col." FROM ".$table." where 1 ";
		if($field!='' && $value!='')
		{
		  $query.="AND ".$field."='".$value."' ";
		}
		$recordSet = $this->obj->db->query($query);
		if($recordSet->num_rows() > 0)
		{
			$row = $recordSet->result_array();
			return $row;
		}
		else
		{
			return "";
		}
	}
	function getCustomerProfile($member_id){
		if($member_id){
			$param['member_id'] = $member_id;
	        $main_url=API_URL."myProfile";
	        $apiresult=$this->functions->httpPost($main_url,$param);
	        if($apiresult==''){
	            return false;
	        }
	        $returndata=json_decode($apiresult);
	        if($returndata->status==false){
	            return false;
	        }
	        return $returndata->data;
		}else{
			return false;
		}
	}
	function getFreeMinutes($userId){
		$query="SELECT members.callDuration,member_service_subscription.service_subscription_master_id,left(service_subscription_master.type,3) as minutePurchesed from members INNER JOIN member_service_subscription ON member_service_subscription.member_id=members.id INNER JOIN service_subscription_master ON service_subscription_master.id=member_service_subscription.service_subscription_master_id WHERE members.id='".$userId."' AND (member_service_subscription.service_subscription_master_id='4' OR member_service_subscription.service_subscription_master_id='5') and member_service_subscription.`status`='1'";
		$resultSet = $this->obj->db->query($query)->row_array();
		if(!empty($resultSet)){
			$remainingSecond = (($resultSet['minutePurchesed']*60)-$resultSet['callDuration']);

			if($remainingSecond>0){
				  //$minutes = floor(($remainingSecond / 60) % 60);
				  $minutes = floor(($remainingSecond / 60));
				  $seconds = $remainingSecond % 60;
				  return "$minutes:$seconds";
			}else{
				$remainingSecond = 0;
			}
		}else{
			$remainingSecond = 0;
		}
		
		return $remainingSecond;
	}
	 
	function getCountryList($status = 'Active'){

		$query = "SELECT * FROM `country_master` ";
		if($status != 'all')
		{
			$query.=" WHERE `status` = '".$status."'";
		}
		$query.=" ORDER BY `country_name` ='United States' DESC ,`country_name` ASC";

		$recordSet = $this->obj->db->query($query);
		if($recordSet->num_rows() > 0)
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
			return $rs;
		}
		else
		{
			return "";
		}
	}
	// --------------------------------------------------------------------


		// --------------------------------------------------------------------

	/**
	 * Get Content for Page
	 *
	 * This function takes the page-name from the segment of url
	 * and queries database table "contents"
	 * Returns the content and other values such as page-title, meta-tags etc for the page in an array
	 *
	 * @access	public
	 * @param	string
	 * @return	array
	 */
	function getContent($url)
    {
		$query = "SELECT * FROM `cms`
				  WHERE `page_name` = '".$url."' ";
		$recordSet = $this->obj->db->query($query);

		$rs = false;
		if ($recordSet->num_rows() > 0)
        {
           	$rs = array();
			$isEscapeArr = array('content');
			foreach ($recordSet->result_array() as $row)
			{
				foreach($row as $key=>$val){
					if(!in_array($key,$isEscapeArr)){
						$recordSet->fields[$key] = outputEscapeString($val);
					}else{
						$recordSet->fields[$key] = outputEscapeString($val,'HTML');
					}
				}
				$rs = $recordSet->fields;
			}
        }
		return $rs;
    }
	// --------------------------------------------------------------------

	/**
	 * Checks user for page access
	 *
	 * This function takes the page-name and checks for user authenticity
	 * Returns true if user is authentic
	 * Redirects to user login page if user is not authentic
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function checkUser($section_name)
	{
		if($this->obj->input->request('appcall')==1){
			return true;
		}
		$UserID = $this->obj->nsession->userdata('merchants_session_id');
		if(!$UserID)
		{
			$cookie = array(
				'name' => 'fe_referer_path',
				'value' => $section_name,
				'expire' => '86500',
				'domain' => '',
				'path' => '/',
				'prefix' => '',
			);
			set_cookie($cookie);
			redirect(base_url());
		}
		return true;
	}

	function checkCustomer($section_name)
	{
		if($this->obj->input->request('appcall')==1){
			return true;
		}
		$UserID = $this->obj->nsession->userdata('member_session_id');
		//pr($_SESSION);
		//echo $UserID; die();
		if(!$UserID)
		{
			$cookie = array(
				'name' => 'fe_referer_path',
				'value' => $section_name,
				'expire' => '86500',
				'domain' => '',
				'path' => '/',
				'prefix' => '',
			);
			set_cookie($cookie);
			redirect(base_url());
		}
		return true;
	}
	// --------------------------------------------------------------------

	/**
	 * Checks user for page access
	 *
	 * This function takes the page-name and checks for user authenticity for admin section
	 * Returns true if user is authentic
	 * Redirects to user login page if user is not authentic
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function checkAdmin($section_name,$allowed=false)
	{
		$UserID = $this->obj->nsession->userdata('admin_session_id');
		if(!$UserID)
		{
			$cookie = array(
				'name' => 'admin_referer_path',
				'value' => $section_name,
				'expire' => '86500',
				'domain' => '',
				'path' => '/',
				'prefix' => '',
			);

			set_cookie($cookie);
			redirect(base_url().'login/');
			exit;
		}else{
			if($allowed==true){
				return true;
			}
			$userType = $this->obj->nsession->userdata('admin_session_usertype');
			//echo $userType; die();
			$access = array('All');

			// redirect to required section
			if(in_array('All',$access)){
				return true;
			}elseif(in_array($section_name,$access)){
				return true;
			}else{
				redirect(base_url().'user/');
			}
		}
	}

	function checkMerchants($section_name,$allowed=false)
	{
		$UserID = $this->obj->nsession->userdata('merchants_session_id');
		if(!$UserID)
		{
			$cookie = array(
				'name' => 'merchants_referer_path',
				'value' => $section_name,
				'expire' => '86500',
				'domain' => '',
				'path' => '/',
				'prefix' => '',
			);

			set_cookie($cookie);
			redirect(base_url().'login/');
			exit;
		}else{
			if($allowed==true){
				return true;
			}
			$access = array('All');

			// redirect to required section
			if(in_array('All',$access)){
				return true;
			}elseif(in_array($section_name,$access)){
				return true;
			}else{
				redirect(base_url().'user/');
			}
		}
	}
        
    
	function getNameTable($table,$col,$field='',$value='',$field2='',$value2='')
	{
		$query="SELECT ".$col." FROM ".$table." where 1 ";
		if($field!='' && $value!='')
		{
		  $query.="AND ".$field."='".$value."' ";
		}
		
		if($field2!='' && $value2!='')
		{
		  $query.="AND ".$field2."='".$value2."' ";
		}
		$recordSet = $this->obj->db->query($query);
		if($recordSet->num_rows() > 0)
		{
			$row = $recordSet->row_array();
			return $row[$col];
		}
		else
		{
			return "";
		}
	}



	function existRecords($table,$field_name,$field_value,$pk,$pk_value=0,$field_name1="",$field_value1="")
	{
		$query="SELECT COUNT(".$pk.") as CNT FROM ".$table." where ".$field_name."='".$field_value."' ";
		if($field_name1!="" && $field_value1!="")
		{
		$query.=" AND ".$field_name1."='".$field_value1."' ";
		}
		if($pk_value){
			$query.=" AND ".$pk."!='".$pk_value."'";
		}

		$recordSet = $this->obj->db->query($query);
		if($recordSet->num_rows() > 0)
		{
			$row = $recordSet->row_array();
			return $row['CNT'];
		}
		else
		{
			return "";
		}
	}

	function generateUrl($table,$field_name,$field_value,$pk,$pk_value=0){
		$field_value = ereg_replace("[^A-Za-z0-9-]", "", str_replace(array(" "), '-', strtolower($field_value)));

		$existRecords = $this->existRecords($table,$field_name,$field_value,$pk,$pk_value);

		if ($existRecords>0)
		{
			for ($i = 1; $i < 100; $i++)
			{
				$existRecords = $this->existRecords($table,$field_name,$field_value."-".$i,$pk,$pk_value);
				if (!$existRecords)
				{
					$field_value = $field_value."-".$i;
					break;
				}
			}
		}

		$url = strtolower(str_replace("--","-",$field_value));

		return $url;
	}


	function getListTable($table_name, $field='',$value='',$orderfield='',$ordertype='ASC')
	{
		$sql = "SELECT * FROM `".$table_name."` WHERE 1" ;
		if($field != '' && $value!=''){
			$sql.=" AND `".$field."`='".$value."'";
		}
		if($orderfield != ''){
			$sql.=" ORDER BY `".$orderfield."` ".$ordertype."";
		}
		$recordSet = $this->obj->db->query($sql);

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

	function emailTemplate($body='',$footer='',$path='',$site_name='')
	{
		$template = '<style>p{line-height:15px; font:Arial, Helvetica, sans-serif; font-size: 13px;} body{margin:0; padding:0;}</style>
					<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding:0px; color:#000000; font:normal 12px Arial, Helvetica, sans-serif;">
					<tr>
						<td style="background-image:url(##SITEPATH##public/images/inner-page-sm-banner.png); height:184px; height: 184px; vertical-align: bottom;">
							<table width="70%" align="center" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td><img src="##SITEPATH##public/images/logo.png" /></td>
								</tr>
								<tr>
									<td height="20" align="left" style="background-color:#6D9A31;">&nbsp;</td>
								  </tr>
								  
							</table> 
						</td>
					</tr>
					  <tr>
					  	<td>
					  		<table width="70%" align="center" border="0" cellpadding="0" cellspacing="0" style=" position: relative;top: -53px; z-index: -1;">
						  		<tr>
									<td height="20" align="center"><img src="##SITEPATH##public/images/banner-shadow02.png" width="96%;" /></td>
							    </tr>
							</table>
					  	</td>
					  </tr>
					  <tr>
						<td align="left" style="padding:20px;">##EMAIL_BODY##</td>
					  </tr>
					  <tr>
						<td align="left" style="padding:20px;">##EMAIL_FOOTER##</td>
					  </tr>
					  <tr>
						<td height="90" align="left" bgcolor="#FFF" style="padding-left:20px; margin-left:20px;">
							
						</td>
					  </tr>
					   <tr>
					   <tr>
					   		<td>
					   			<table width="70%" align="center" border="0" cellpadding="0" cellspacing="0" style="">
							  		<tr>
										<td height="20" align="center" style="background-color: #111213; display: block;height: 9px; position: relative; top: 7px;"> &nbsp;</td>
								    </tr>
								</table>
					   		</td>
					   </tr>
					  	<td style="background-image:url(##SITEPATH##public/images/footer-bg.png); padding:20px 0;">
					  		<table width="70%" align="center" border="0" cellpadding="0" cellspacing="0" style="">
						  		<tr>
									<td height="20" align="center" style="color:#fff;">
										'.date('Y').' Copyright 9jahookups.com <br /> <br />
										<a href="##SITEPATH##"><img width="233" height="83" alt="" src="##SITEPATH##public/images/footer-logo.jpg"></a>
									</td>
							    </tr>
							</table>
					  	</td>
					  </tr>
					  
					</table>
					';
		$email_body = str_replace(array("##EMAIL_BODY##","##EMAIL_FOOTER##","##SITEPATH##"),array($body,$footer,$path),$template);

		return $email_body;
	}
	
	// --------------------------------------------------------------------
	function getGlobalInfo($field_key)
	{
		$sql = "SELECT field_value FROM global_config WHERE field_key = '".$field_key."'" ;
		$recordSet = $this->obj->db->query($sql);

		$rs = false;
		if ($recordSet->num_rows() > 0)
        {
 			$isEscapeArr = array('global_email_signatur','global_company_info');
			foreach ($recordSet->result_array() as $row)
			{
				foreach($row as $key=>$val){
					if(!in_array($key,$isEscapeArr)){
						$rs = outputEscapeString($val);
					}else{
						$rs = outputEscapeString($val,"HTML");
					}
				}
			}
        }
        return $rs;

	}

	function fetchCategory($parent_id=0)
	{
		if($parent_id == 0)
			$sql = "SELECT id ,	category_name, category_url FROM category_master WHERE parent_id='0' ORDER BY position ASC,category_name ASC";
		else
			$sql = "SELECT id ,	category_name, category_url FROM category_master WHERE parent_id='".$parent_id."' ORDER BY position ASC,category_name ASC";

		$recordSet = $this->obj->db->query($sql);

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
						}else{
							$recordSet->fields[$key] = outputEscapeString($val,'TEXTAREA');
						}
					}
					$rs[]	= $recordSet->fields;
				}
        }
		return $rs;
	}

	function getProductInfo($col='',$data)
	{
		$query="SELECT ".$col." FROM order_temp_cart
		where member_id = '".$data['member_id']."'";

		$recordSet = $this->obj->db->query($query);
		if($recordSet->num_rows() > 0)
		{
			$row = $recordSet->row_array();
			return $row[$col];
		}
		else
		{
			return "";
		}
	}

   // --------------------------------------------------------------------

	function saveLog($logArr)
	{

		$sql = "INSERT INTO department_log SET
				`order_id`		=	'".$logArr['order_id']."',
				`dep_code`		=	'".$logArr['dep_code']."',
				`changed_date`	=	NOW(),
				`changed_by` 	=	'".$logArr['changed_by']."'";

		$recordSet = $this->obj->db->query($sql);

		if(!$recordSet)
		{
			return false;
		}
		else
		{
			return true;
		}

	}

	function sellPriceUpdate($product_info_arr)
	{
		$sell_percentage_arr	 	=	$this->getSellPercentage();
		$discount_percentage_arr	=	$this->getDiscountPercentage();

		if(is_array($product_info_arr))
		{
			foreach($product_info_arr as $value)
			{
				$product_id 		= $value[0];
				$category_id 		= $value[1];
				$subcategory_id 	= $value[2];
				$manufacturer_id 	= $value[3];
				$purchase_price 	= $value[4];

				$sell_percentage 		= 0;
				$discount_percentage 	= 0;
				if($product_id>0){
					if(!empty($sell_percentage_arr['product'][$product_id])){
							$sell_percentage = $sell_percentage_arr['product'][$product_id];
					}elseif(!empty($sell_percentage_arr['manufacturer'][$manufacturer_id])){
							$sell_percentage = $sell_percentage_arr['manufacturer'][$manufacturer_id];
					}elseif(!empty($sell_percentage_arr['subcategory'][$subcategory_id])){
							$sell_percentage = $sell_percentage_arr['subcategory'][$subcategory_id];
					}elseif(!empty($sell_percentage_arr['category'][$category_id])){
							$sell_percentage = $sell_percentage_arr['category'][$category_id];
					}

					if(!empty($discount_percentage_arr['product'][$product_id])){
							$discount_percentage = $discount_percentage_arr['product'][$product_id];
					}elseif(!empty($discount_percentage_arr['manufacturer'][$manufacturer_id])){
							$discount_percentage = $discount_percentage_arr['manufacturer'][$manufacturer_id];
					}elseif(!empty($discount_percentage_arr['subcategory'][$subcategory_id])){
							$discount_percentage = $discount_percentage_arr['subcategory'][$subcategory_id];
					}elseif(!empty($discount_percentage_arr['category'][$category_id])){
							$discount_percentage = $discount_percentage_arr['category'][$category_id];
					}

					$sell_price	= 0;
					$sell_price = (100 + $sell_percentage) * $purchase_price * 0.01;
					$sell_price = (100 - $discount_percentage) * $sell_price * 0.01;
					$sell_price = number_format($sell_price,2,'.','');
					$sql = "UPDATE product_master SET
							sell_price_percentage		=	'".$sell_percentage."',
							discount_price_percentage	=	'".$discount_percentage."',
							sell_price					=	'".$sell_price."'
							WHERE id = '".$product_id."' LIMIT 1";
					$recordSet = $this->obj->db->query($sql);
				}
			}
		}else{
			return false;
		}
		return true;
	}

	/*Sell Price Calculation */
	function getSellPercentage()
	{
		$sell_percentage = false;
		$query="SELECT price_type, reference_id, sell_percentage FROM sell_price_master WHERE 1";
		$recordSet = $this->obj->db->query($query);

		if ($recordSet->num_rows() > 0)
        {
           	$sell_percentage = array();
			$isEscapeArr = array();
			foreach ($recordSet->result_array() as $row)
				{
					foreach($row as $key=>$val){
						if(!in_array($key,$isEscapeArr)){
							$recordSet->fields[$key] = outputEscapeString($val);
						}else{
							$recordSet->fields[$key] = outputEscapeString($val,'TEXTAREA');
						}
					}
					$sell_percentage[$recordSet->fields['price_type']][$recordSet->fields['reference_id']]	= $recordSet->fields['sell_percentage'];
				}
        }
		return $sell_percentage;
	}

	function getDiscountPercentage()
	{
		$discount_percentage = false;
		$query="SELECT price_type, reference_id, discount_percentage FROM discount_price_master WHERE 1";
		$recordSet = $this->obj->db->query($query);

		if ($recordSet->num_rows() > 0)
        {
           	$discount_percentage = array();
			$isEscapeArr = array();
			foreach ($recordSet->result_array() as $row)
				{
					foreach($row as $key=>$val){
						if(!in_array($key,$isEscapeArr)){
							$recordSet->fields[$key] = outputEscapeString($val);
						}else{
							$recordSet->fields[$key] = outputEscapeString($val,'TEXTAREA');
						}
					}
					$discount_percentage[$recordSet->fields['price_type']][$recordSet->fields['reference_id']]	= $recordSet->fields['discount_percentage'];
				}
        }
		return $discount_percentage;
	}

	function getListProducts()
	{
		$rs = false;
		$query="SELECT id, category_id, subcategory_id,manufacturer_id,purchase_price FROM product_master WHERE product_stock>0";
		$recordSet = $this->obj->db->query($query);

		if ($recordSet->num_rows() > 0)
        {
           	$rs = array();
			$isEscapeArr = array();
			foreach ($recordSet->result_array() as $row)
				{
					foreach($row as $key=>$val){
						if(!in_array($key,$isEscapeArr)){
							$recordSet->fields[$key] = outputEscapeString($val);
						}else{
							$recordSet->fields[$key] = outputEscapeString($val,'TEXTAREA');
						}
					}
					$rs[]	= array($recordSet->fields['id'],
									$recordSet->fields['category_id'],
									$recordSet->fields['subcategory_id'],
									$recordSet->fields['manufacturer_id'],
									$recordSet->fields['purchase_price']);
				}
        }
		return $rs;
	}
	
	
		// --------------------------------------------------------------------
	function getPreviousRecord($table,$field, $pk, $id)
	{
		$sql = "SELECT $pk, $field FROM  $table
				where $field < ( SELECT $field FROM
				$table where $pk='".$id."' )
				ORDER BY $field DESC LIMIT 1";

		$recordSet = $this->obj->db->query($sql);

		$rs = false;
		if ($recordSet->num_rows() > 0)
        {
           	$rs = array();
			$isEscapeArr = array();
			foreach ($recordSet->result_array() as $row)
				{
					foreach($row as $key=>$val){
						$recordSet->fields[$key] = outputEscapeString($val);
					}
					$rs = $recordSet->fields;
				}
        }
		return $rs;

	}

	function getNextRecord($table,$field, $pk, $id)
	{
		$sql = "SELECT $pk, $field FROM  $table
				where $field > ( SELECT $field FROM
				$table where $pk='".$id."' )
				ORDER BY $field ASC LIMIT 1";

		$recordSet = $this->obj->db->query($sql);

		$rs = false;
		if ($recordSet->num_rows() > 0)
        {
           	$rs = array();
			$isEscapeArr = array();
			foreach ($recordSet->result_array() as $row)
				{
					foreach($row as $key=>$val){
						$recordSet->fields[$key] = outputEscapeString($val);
					}
					$rs = $recordSet->fields;
				}
        }
		return $rs;

	}
	
	function GenKey($length = 7){
		$password = "";
		$possible = "!@#$%0123456789abcdefghijkmnopqrstuvwxyz"; 
		$i = 0;
		while ($i < $length){ 
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
			if(!strstr($password, $char)) { 
				$password .= $char;
				$i++;
			}
		}
		return $password;
	}
	
	function randomNo($length = 6){
		$randomNo = "";
		$possible = "01234567899876543210"; 
		$i = 0;
		while ($i < $length){ 
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
			if(!strstr($randomNo, $char)) { 
				$randomNo .= $char;
				$i++;
			}
		}
		return $randomNo;
	}
	
	function lenthString($stringGet){
		$string = stripslashes(strlen($content['formno']));
		$strLen = strlen($string);
		return $strLen;
	}
	
	function getdateformat($data)
	{
		$final_date=explode("-",$data);
		$month=$final_date[0];
		$day=$final_date[1];
		$year=$final_date[2];
		$full_date=$year.'-'.$month.'-'.$day;
		
		$date_format = $full_date;
		return $date_format;
	}
	
	function getdateformatslash($data)
	{
		$final_date = explode("/",$data);
		$day = $final_date[0];
		$month = $final_date[1];
		$year = $final_date[2];
		$full_date = $year.'-'.$month.'-'.$day;
		
		$date_format = $full_date;
		return $date_format;
	}

	// --------------------------------------------------------------------
	function getDivision($id='' , $parce=''){
		$regid = $this->getNameTable('school_reg','reg_id','rollno',$id);
		$sql = "SELECT (sum( `theory_marks` + `practical_marks` ) *100) /800 AS perMarks FROM `school_reg_subjects` WHERE `reg_id` = '".$regid."'";
		$recordSet = $this->obj->db->query($sql);
		$result = $recordSet->row_array();
		
		
		
		$perMrks = str_replace(".00","",number_format($result['perMarks'],2));
		
		if($parce!=''){
			return $perMrks;
		}
		else{
			if($perMrks>=60){
			  $div = 'First';
			}elseif($perMrks<60 && $perMrks>=45){
			  $div = 'Second';
			}elseif($perMrks<45 && $perMrks>=30){
			  $div = 'Third';
			}
			return $div;
		//return $perMrks;
		}
	}
	
	function perDivision($alltot='',$type=''){
		$perMrks = '';
		$div = '';
		$perMrks = ($alltot*100/600);
		$perMrks = str_replace(".00","",number_format($perMrks,2));
		if($type=='per'){
  			//$perMrks = (531*100/800);
			return $perMrks;
		}else{
			if($perMrks>=60){
			  $div = 'First';
			}elseif($perMrks<60 && $perMrks>=45){
			  $div = 'Second';
			}elseif($perMrks<45 && $perMrks>=30){
			  $div = 'Third';
			}
			return $div;
		}
	}
	
	function getPerticulerContent($table , $fields,$orderby='',$orderType=''){
		$sql = "SELECT ".$fields." FROM ".$table." WHERE 1";
		if($orderby!='' && $orderType!=''){
		$sql .=" ORDER BY ".$orderby." ".$orderType;
			
			}
		
		$recordSet = $this->obj->db->query($sql);
		
		if($recordSet->num_rows()>0){
			
		$result = $recordSet->result_array();
		
		return $result;
		
		}else{
			
			return '';
			
			}
		
		}
		
		function user_date_format($value){
		     if($value=="" || $value=="--"){
		     	return "";
		     }
		     if (strpos($value,'-') !== false) {
		         //return $value;
			$userdate = explode('-',$value);
			$defineddateformat = DATE_FORMAT;
			if($defineddateformat=='dd/mm' || $defineddateformat=='dd/mm/yy'){
				$retunVal = $userdate[2].'/'.$userdate[1].'/'.$userdate[0];
				}
			if($defineddateformat=='mm/dd' || $defineddateformat=='mm/dd/yy'){
				$retunVal = $userdate[1].'/'.$userdate[2].'/'.$userdate[0];
				}
			if($defineddateformat=='yy/mm' || $defineddateformat=='yy/mm/dd'){
				$retunVal = $userdate[0].'/'.$userdate[1].'/'.$userdate[2];
				}
				return $retunVal;
			}else{
				return "";
			}
			
			}
			
			function db_date_format($value){
			if($value=="" || $value=="//"){
		     	return "";
		        }
			if (strpos($value,'/') !== false) {
				$defineddateformat = DATE_FORMAT;
				$userdate = explode('/',$value);
				
				if($defineddateformat=='dd/mm' || $defineddateformat=='dd/mm/yy'){
					$retunVal = $userdate[2].'-'.$userdate[1].'-'.$userdate[0];
					}
				if($defineddateformat=='mm/dd' || $defineddateformat=='mm/dd/yy'){
					$retunVal = $userdate[2].'-'.$userdate[0].'-'.$userdate[1];
					}
				if($defineddateformat=='yy/mm' || $defineddateformat=='yy/mm/dd'){
					$retunVal = $userdate[0].'-'.$userdate[1].'-'.$userdate[2];
					}
					return $retunVal;
			}else{
				return "";
			}
			
			}
			
		function get_dateDiff($value){
			
				$date1 = $value;
				$date2 = date('Y-m-d');
				
				$diff = abs(strtotime($date2) - strtotime($date1));
				
				$years = floor($diff / (365*60*60*24));
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
				
				return $years;
			
		}
		
		
		function createBreadcamp($presentController,$preController="",$preControllerName=""){
			$html = '';
			if($preController!=''){
				$html .='<li><a href="'.base_url($preController).'">'.$preControllerName.'</a></li>';
			}
			$html .='<li class="active">'.$presentController.'</li>';
			return $html;
		}

		function getCallDetilas($callid){
		
			// Your Account Sid and Auth Token from twilio.com/user/account
			$sid = "AC1ef62a708c745394b63e63422aa936f2";
			$token = "781550d6e0f1f4b45fa73f292b9d9979";
			$client = new Client($sid, $token);

			// Get an object from its sid. If you do not have a sid,
			// check out the list resource examples on this page
			$call = $client->calls($callid)->fetch();

			return $call;

		}

		function sendSMS($phoneNumber,$message){
			$sid = "AC1ef62a708c745394b63e63422aa936f2";
			$token = "781550d6e0f1f4b45fa73f292b9d9979";
			$client = new Client($sid, $token);

				$client->messages->create(
				    $phoneNumber,
				    array(
				        'from' => '+1 587-803-2829',
				        'body' => $message,
				        //'mediaUrl' => "https://c1.staticflickr.com/3/2899/14341091933_1e92e62d12_b.jpg",
				    )
				);
			return true;
		}

    function sendMsg($mobileNbr,$message){
                $today  	= date("d-m-Y");
                $mobile 	= $mobileNbr;
                $message 	= $message;

                $username 	= "rkfarmstrans";
                $password 	= "bhopal1234";
                $url 		= "http://173.45.76.227/send.aspx";
                $senderid 	= "RBFARM";
                $method 	= "POST";
                $username	= urlencode($username);
                $password	= urlencode($password);
                $message	= urlencode($message);
                $domain		= "$url?username=$username&pass=$password&route=trans1&senderid=$senderid&numbers=$mobile&message=$message";
                $opts = array(
                    'http'=>array(
                        'method'=>"$method",
                        'content' =>"username=$username&pass=$password&route=trans1&senderid=$senderid&numbers=$mobile&message=$message",
                        'header'=>"Accept-language: en\r\n" .
                            "Cookie: foo=bar\r\n"
                    )
                );

                $context 	= stream_context_create($opts);
                $fp 		= fopen("$domain", "r", false, $context);
                $response = @stream_get_contents($fp);
                return $response;
                fpassthru($fp);
                fclose($fp);
            }


		function memberLoginCheckRedirect()
		{
			$member_type = $this->obj->nsession->userdata('member_session_membertype');
			if($member_type==1){
				redirect(base_url('retailer/dashboard'));
				return true;
			}else if($member_type==2){
				redirect(base_url('supplier/dashboard'));
				return true;
			}
			return true;
		}
		/* @bhanu 31-03-2017  Function Libraray */

		function getCountry(){
			return $this->obj->db->select('*')->get('countries')->result_array();
		}
		function getState($country_id){
			if($country_id==''){
				return $this->obj->db->select('*')->get('states')->result_array();
			}else{
				return $this->obj->db->select('*')->where('country_id',$country_id)->get('states')->result_array();
			}
			
		} 
		function getCity($state_id){
			return $this->obj->db->select('*')->where('state_id',$state_id)->get('cities')->result_array();
		}
		function getCounty($state_id){
			return $this->obj->db->select('*')->where('state_id',$state_id)->get('county')->result_array();
		}
		// --------------------------Convert Number to Words------------------------------------//

		function convertNumberToWords($number) {

		    $hyphen      = '-';
		    $conjunction = ' and ';
		    $separator   = ', ';
		    $negative    = 'negative ';
		    $decimal     = ' point ';
		    $dictionary  = array(
		        0                   => 'zero',
		        1                   => 'one',
		        2                   => 'two',
		        3                   => 'three',
		        4                   => 'four',
		        5                   => 'five',
		        6                   => 'six',
		        7                   => 'seven',
		        8                   => 'eight',
		        9                   => 'nine',
		        10                  => 'ten',
		        11                  => 'eleven',
		        12                  => 'twelve',
		        13                  => 'thirteen',
		        14                  => 'fourteen',
		        15                  => 'fifteen',
		        16                  => 'sixteen',
		        17                  => 'seventeen',
		        18                  => 'eighteen',
		        19                  => 'nineteen',
		        20                  => 'twenty',
		        30                  => 'thirty',
		        40                  => 'fourty',
		        50                  => 'fifty',
		        60                  => 'sixty',
		        70                  => 'seventy',
		        80                  => 'eighty',
		        90                  => 'ninety',
		        100                 => 'hundred',
		        1000                => 'thousand',
		        1000000             => 'million',
		        1000000000          => 'billion',
		        1000000000000       => 'trillion',
		        1000000000000000    => 'quadrillion',
		        1000000000000000000 => 'quintillion'
		    );

		    if (!is_numeric($number)) {
		        return false;
		    }

		    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
		        // overflow
		        trigger_error(
		            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
		            E_USER_WARNING
		        );
		        return false;
		    }

		    if ($number < 0) {
		        return $negative . $this->convertNumberToWords(abs($number));
		    }

		    $string = $fraction = null;

		    if (strpos($number, '.') !== false) {
		        list($number, $fraction) = explode('.', $number);
		    }

		    switch (true) {
		        case $number < 21:
		            $string = $dictionary[$number];
		            break;
		        case $number < 100:
		            $tens   = ((int) ($number / 10)) * 10;
		            $units  = $number % 10;
		            $string = $dictionary[$tens];
		            if ($units) {
		                $string .= $hyphen . $dictionary[$units];
		            }
		            break;
		        case $number < 1000:
		            $hundreds  = $number / 100;
		            $remainder = $number % 100;
		            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
		            if ($remainder) {
		                $string .= $conjunction . $this->convertNumberToWords($remainder);
		            }
		            break;
		        default:
		            $baseUnit = pow(1000, floor(log($number, 1000)));
		            $numBaseUnits = (int) ($number / $baseUnit);
		            $remainder = $number % $baseUnit;
		            $string = $this->convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
		            if ($remainder) {
		                $string .= $remainder < 100 ? $conjunction : $separator;
		                $string .= $this->convertNumberToWords($remainder);
		            }
		            break;
		    }

		    if (null !== $fraction && is_numeric($fraction)) {
		        $string .= $decimal;
		        $words = array();
		        foreach (str_split((string) $fraction) as $number) {
		            $words[] = $dictionary[$number];
		        }
		        $string .= implode(' ', $words);
		    }

		    return ucfirst($string);
		}
	function NVPToArray($NVPString)
	{
		$proArray = array();
		while(strlen($NVPString))
		{
			// name
			$keypos= strpos($NVPString,'=');
			$keyval = substr($NVPString,0,$keypos);
			// value
			$valuepos = strpos($NVPString,'&') ? strpos($NVPString,'&'): strlen($NVPString);
			$valval = substr($NVPString,$keypos+1,$valuepos-$keypos-1);
			// decoding the respose
			$proArray[$keyval] = urldecode($valval);
			$NVPString = substr($NVPString,$valuepos+1,strlen($NVPString));
		}
		return $proArray;
	}
	
	function mail_template($to,$subject,$body){
		
		$serverYear=date('Y');
		$global_email = $this->getGlobalInfo('global_contact_email');
		$global_email_sign = $this->getGlobalInfo('global_email_signature');
		$global_site_name = $this->getGlobalInfo('global_site_name');

		$emailTo = $to;
		$emailFrom = $global_email;
		
		$mailbody = '<table width="100%" border="0" cellspacing="10" cellpadding="10" bordercolor="#000000" style="border-collapse:collapse; margin:0 auto; border:#C4C4C4 1px solid;">
					<tr>
						<td style="background:#3466BF; border-bottom: 1px solid #C4C4C4; height: 100px;">
							<center><img style="padding:10px 0 10px 20px;" src='.base_url('/').'public/images/logo.png></center>
						</td>
					</tr>
					<tr>'.$body.'</tr>
					<tr>
						<td style="background:#C00000;"><p style="color: #ffffff; font-family: Arial,Helvetica,sans-serif; font-size: 11px; padding: 6px 0;text-align: center;"> '.$global_site_name.' &copy; Copyright 2016-'.$serverYear.'. All Rights Reserved.</p></td>
					</tr>
				</table>';
		
		$to = $to;
		$subject = $subject;
		$from = $emailFrom;
		 
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		 
		// Create email headers
		$headers .= 'From: '.$from."\r\n".
			'X-Mailer: PHP/' . phpversion();
		 
		 
		// Sending email
		if(mail($to, $subject, $mailbody, $headers)){
			return true;
		} else{
			return false;
		}
		
		
	}

	function mail_template_for_content($to,$subject,$body,$from=''){
		
		$serverYear=date('Y');
		$global_email = $this->getGlobalInfo('global_contact_email');
		$global_email_sign = $this->getGlobalInfo('global_email_signature');
		$global_site_name = $this->getGlobalInfo('global_site_name');

		$emailTo = $to;
		if($from!=''){
			$emailFrom = $global_email;
		}else{
			$emailFrom = $from;
		}
		
		
		$mailbody = '<table width="100%" border="0" cellspacing="10" cellpadding="10" bordercolor="#000000" style="border-collapse:collapse; margin:0 auto; border:#C4C4C4 1px solid;">
					<tr>
						<td style="background:#3466BF; border-bottom: 1px solid #C4C4C4; height: 100px;">
							<center><img style="padding:10px 0 10px 20px;" src='.base_url('/').'public/images/logo.png></center>
						</td>
					</tr>
					<tr>'.$body.'</tr>
					<tr>
						<td style="background:#C00000;"><p style="color: #ffffff; font-family: Arial,Helvetica,sans-serif; font-size: 11px; padding: 6px 0;text-align: center;"> '.$global_site_name.' &copy; Copyright 2016-'.$serverYear.'. All Rights Reserved.</p></td>
					</tr>
				</table>';
		
		$to = $to;
		$subject = $subject;
		$from = $emailFrom;
		 
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		 
		// Create email headers
		$headers .= 'From: '.$from."\r\n".
			'X-Mailer: PHP/' . phpversion();
		 
		 
		// Sending email
		if(mail($to, $subject, $mailbody, $headers)){
			return true;
		} else{
			return false;
		}
		
		
	}

	function checkContentAlreadyExist($table,$search_key,$search_value,$check_type,$contentId){
		$returnData = $this->obj->db->select('id')->where($search_key,$search_value)->get($table)->result_array();
		if($check_type=='add'){
			if(count($returnData)<=0){
				return true;
			}else{
				return false;
			}
		}else if($check_type=='edit'){
			if(count($returnData)<=0){
				return true;
			}else if(count($returnData)==1){
				if($returnData[0]['id']==$contentId){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	}
	function getMemberBasicInfo($member_id){
		return $this->obj->db->select('first_name,last_name,email')->where(array('id'=>$member_id))->get('member')->row_array();
	}
	function sendNotificationAndMail($member_id,$notification,$notificationData,$lang){
		$notificationStatus = $this->obj->db->select('notification')->where('member_id',$member_id)->get('member_notification')->row_array();
		$notificationStatusArray = json_decode($notificationStatus['notification']);
		if(!in_array($notification,$notificationStatusArray)){
			$this->obj->db->insert('notification_data',$notificationData);
			$memberData = $this->getMemberBasicInfo($member_id);
			$to = $memberData['email'];
			$subject=$lang=='en'?"Renting Street Notification":"出租街道通知";
			if($lang=='en'){
				$body="<tr><td>Hi,</td></tr>
					<tr><td>".$notificationData['message']."</td></tr>";
			}else{
				$body="<tr><td>你好,</td></tr>
					<tr><td>".$notificationData['message']."</td></tr>";
			}
			$this->mail_template($to,$subject,$body);
			$need_notify = 1;
		}else{
			$need_notify  = 0;
		}
		return $need_notify;
	}
	function getCategories($lang){
		if($lang=='en'){
			return $this->obj->db->select('id,title_en title,icon')->where(array('level'=>0,'parent'=>0))->order_by('title_en','ASC')->get('ad_category')->result_array();
		}else{
			return $this->obj->db->select('id,title_ch title,icon')->where(array('level'=>0,'parent'=>0))->order_by('title_en','ASC')->get('ad_category')->result_array();
		}
		
	}
	function sendNotificationByAdsCategory($parent_id,$owner_id,$ad_pid,$lang){
		$this->obj->db->select('member.id,member.first_name,member.last_name,member.email');
		$this->obj->db->join('member','member.id=member_more.member_id');
		$this->obj->db->where('member.is_active',1);
		$this->obj->db->where("FIND_IN_SET('$parent_id',member_more.ad_category) !=", 0);
		$resultSet = $this->obj->db->get('member_more')->result_array();
		if(count($resultSet)>0){
			foreach($resultSet as $result){
				$notificationData = array(
					'pid' => $ad_pid,
					'renter_id'=>$result['id'],
					'owner_id'=>$owner_id,
					'need_notify'=>2,
					'message'=>$lang=='en'?"New ads has been posted.":"新廣告已發布。",
					'created'=>date('Y-m-d H:i:s')
				);
				$this->obj->db->insert('notification_data',$notificationData);
			}
		}
	}
	function getCMSContent($page_name){
		$this->obj->db->select('*');
		$this->obj->db->from('contents');
		$this->obj->db->where(array('page_name'=>$page_name));
		//pr($this->obj->db->get()->row_array());
		return $this->obj->db->get()->row_array();
	}
	function memberInfo($member_id){
		$this->obj->db->select('*');
		$this->obj->db->from('member');
		$this->obj->db->where(array('id'=>$member_id));
		return $this->obj->db->get()->row_array();
	}

	function getUserIP()
	{
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}

		return $ip;
	}
	function geoCheckIP($ip)
	{
	   //check, if the provided ip is valid
	   if(!filter_var($ip, FILTER_VALIDATE_IP))
	   {
			   throw new InvalidArgumentException("IP is not valid");
	   }

	   //contact ip-server
	   $response=@file_get_contents('http://www.netip.de/search?query='.$ip);
	   if (empty($response))
	   {
			   throw new InvalidArgumentException("Error contacting Geo-IP-Server");
	   }

	   //Array containing all regex-patterns necessary to extract ip-geoinfo from page
	   $patterns=array();
	   $patterns["domain"] = '#Domain: (.*?)&nbsp;#i';
	   $patterns["country"] = '#Country: (.*?)&nbsp;#i';
	   $patterns["state"] = '#State/Region: (.*?)<br#i';
	   $patterns["town"] = '#City: (.*?)<br#i';

	   //Array where results will be stored
	   $ipInfo=array();

	   //check response from ipserver for above patterns
	   foreach ($patterns as $key => $pattern)
	   {
			   //store the result in array
			   $ipInfo[$key] = preg_match($pattern,$response,$value) && !empty($value[1]) ? $value[1] : 'not found';
	   }

	   return $ipInfo;
	}

	function httpPostForFile($url,$params,$headers)
	{
		$postData = '';
	   //create name value pairs seperated by &
	   foreach($params as $k => $v) 
	   { 
	      $postData .= $k . '='.$v.'&'; 
	   }
	   $postData = rtrim($postData, '&');
	 
	    $ch = curl_init();  
	 
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($ch,CURLOPT_HEADER, false); 
	    curl_setopt($ch, CURLOPT_POST, count($postData));
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
	 	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	    $output=curl_exec($ch);
	 
	    curl_close($ch);
	    return $output;

	    /*$filename = $_FILES['file']['name'];
		 $filedata = $_FILES['file']['tmp_name'];
		 $filesize = $_FILES['file']['size'];
		 if ($filedata != '')
		 {
		 $headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
		 $postfields = array("filedata" => "@$filedata", "filename" => $filename);
		 $ch = curl_init();
		 $options = array(
		 CURLOPT_URL => $url,
		 CURLOPT_HEADER => true,
		 CURLOPT_POST => 1,
		 CURLOPT_HTTPHEADER => $headers,
		 CURLOPT_POSTFIELDS => $postfields,
		 CURLOPT_INFILESIZE => $filesize,
		 CURLOPT_RETURNTRANSFER => true
		 ); // cURL options
		 curl_setopt_array($ch, $options);
		 curl_exec($ch);
		 if(!curl_errno($ch))
		 {
		 $info = curl_getinfo($ch);
		 if ($info['http_code'] == 200)
		 $errmsg = "File uploaded successfully";
		 }
		 else
		 {
		 $errmsg = curl_error($ch);
		 }
		 curl_close($ch);
		 }*/

	 
	}

	function httpPost($url,$params)
	{
		$postData = '';
	   //create name value pairs seperated by &
	   foreach($params as $k => $v) 
	   { 
	      $postData .= $k . '='.$v.'&'; 
	   }
	   $postData = rtrim($postData, '&');
	 
	    $ch = curl_init();  
	 
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($ch,CURLOPT_HEADER, false); 
	    curl_setopt($ch, CURLOPT_POST, count($postData));
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
	 
	    $output=curl_exec($ch);
	 
	    curl_close($ch);
	    return $output;
	 
	}

	function httpGet($url)
	{
	    $ch = curl_init();  
	 
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	//  curl_setopt($ch,CURLOPT_HEADER, false); 
	 
	    $output=curl_exec($ch);
	 
	    curl_close($ch);
	    return $output;
	}
	
	function acc_permission_r($contentId,$name,$for){
		$this->obj->db->select('*');
		$this->obj->db->from('staff');
		$this->obj->db->where(array('staff_id'=>$contentId));
		$data2= $this->obj->db->get()->row_array();
		
		$jsonvalue=json_decode($data2['permission'],true);
		$x=(int)$jsonvalue[$name][$for];
		//$x=1;
		return $x;
	}
	
	function acc_permission_r_cms($contentId,$name,$for){
		$this->obj->db->select('*');
		$this->obj->db->from('staff');
		$this->obj->db->where(array('staff_id'=>$contentId));
		$data2= $this->obj->db->get()->row_array();
		
		$jsonvalue=json_decode($data2['permission'],true);
		$x=(int)$jsonvalue['$name'][$for];
		//$x=1;
		return $x;
	}



	function acc_permission33($contentId){
       $this->obj->db->select('*');
		$this->obj->db->from('staff');
		$this->obj->db->where(array('staff_id'=>$contentId));
		$data2= $this->obj->db->get()->row_array();
		
		$jsonvalue=json_decode($data2['permission'],true);
		//pr($data['jsonvalue']);die();
		$x=(int)$jsonvalue['category'][0];
		return $x;
	}

	function acc_permission_r_variation($contentId){
		$this->obj->db->select('*');
		 $this->obj->db->from('staff');
		 $this->obj->db->where(array('staff_id'=>$contentId));
		 $data2= $this->obj->db->get()->row_array();
		 
		 $jsonvalue=json_decode($data2['permission'],true);
		 //pr($data['jsonvalue']);die();
		 $x=(int)$jsonvalue['variation_attribute'][0];
		 return $x;
	 }
}

// END Functions Class

/* End of file functions.php */
/* Location: ./system/libraries/functions.php */