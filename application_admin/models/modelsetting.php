<?php

class ModelSetting extends CI_Model {

	function __construct()
    {
        parent::__construct();
    }


	function getGlobalData()
	{
		$sql="SELECT * FROM global_config ";
		$recordSet = $this->db->query($sql);

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
					$rs[$recordSet->fields['field_key']] = $recordSet->fields;
				}
        }
		return $rs;
	}


	//=========================================//
	#	Update Global Settings for the Website	#
	//=========================================//
	function updateGlobalSetting($data)
	{
		/***************** Site Information *****************/
		$sql = "UPDATE global_config SET field_value = '".$data['global_site_name']."' WHERE field_key='global_site_name' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_meta_title']."' WHERE field_key='global_meta_title' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_meta_keywords']."' WHERE field_key='global_meta_keywords' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_meta_description']."' WHERE field_key='global_meta_description' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_phone_number']."' WHERE field_key='global_phone_number' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_web_admin_headquarters_address']."' WHERE field_key='global_web_admin_headquarters_address' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_web_admin_secondary_address']."' WHERE field_key='global_web_admin_secondary_address' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_paypal_business_email']."' WHERE field_key='global_paypal_business_email' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_paypal_mode']."' WHERE field_key='global_paypal_mode' ";
		$recordSet = $this->db->query($sql);

		/***************** Social Site Link *****************/
		$sql = "UPDATE global_config SET field_value = '".$data['global_facebook_url']."' WHERE field_key='global_facebook_url' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_twitter_url']."' WHERE field_key='global_twitter_url' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_youtube_url']."' WHERE field_key='global_youtube_url' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_google_plus_url']."' WHERE field_key='global_google_plus_url' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_instagram_url']."' WHERE field_key='global_instagram_url' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_playstore_url']."' WHERE field_key='global_playstore_url' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_appstore_url']."' WHERE field_key='global_appstore_url' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_linkedin_url']."' WHERE field_key='global_linkedin_url' ";
		$recordSet = $this->db->query($sql);

		/***************** Email Information *****************/
		$sql = "UPDATE global_config SET field_value = '".$data['global_web_admin_name']."' WHERE field_key='global_web_admin_name' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_webadmin_email']."' WHERE field_key='global_webadmin_email' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".addslashes($data['global_email_signature'])."' WHERE field_key='global_email_signature' ";
		$recordSet = $this->db->query($sql);
		$sql = "UPDATE global_config SET field_value = '".$data['global_contact_email']."' WHERE field_key='global_contact_email' ";
		$recordSet = $this->db->query($sql);

		/***************** List Settings *****************/
		$sql = "UPDATE global_config SET field_value = '".$data['global_product_page_count']."' WHERE field_key='global_product_page_count' ";
		$recordSet = $this->db->query($sql);
		
		/***************** Extra Settings *****************/
		$sql = "UPDATE global_config SET field_value = '".$data['global_credit_price']."' WHERE field_key='global_credit_price' ";
		$recordSet = $this->db->query($sql);

		$sql = "UPDATE global_config SET field_value = '".$data['global_footer_details']."' WHERE field_key='global_footer_details' ";
		$recordSet = $this->db->query($sql);

		$sql = "UPDATE global_config SET field_value = '".$data['global_tax_name']."' WHERE field_key='global_tax_name' ";
		$recordSet = $this->db->query($sql);

		$sql = "UPDATE global_config SET field_value = '".$data['global_tax_value']."' WHERE field_key='global_tax_value' ";
		$recordSet = $this->db->query($sql);

		$sql = "UPDATE global_config SET field_value = '".$data['global_gratuity_value']."' WHERE field_key='global_gratuity_value' ";
		$recordSet = $this->db->query($sql);


		$sql = "UPDATE global_config SET field_value = '".$data['global_google_analytics_code']."' WHERE field_key='global_google_analytics_code' ";
		$recordSet = $this->db->query($sql);

		if($data['global_sub_banner']!=''){
			$sql = "UPDATE global_config SET field_value = '".$data['global_sub_banner']."' WHERE field_key='global_sub_banner' ";
			$recordSet = $this->db->query($sql);
		}

		return true;
	}

	function getContactUsList(&$config,&$start,&$param)
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
			$sortType    	= $this->nsession->get_param('ADMIN_POSTEDADS','sortType','DESC');
			$sortField   	= $this->nsession->get_param('ADMIN_POSTEDADS','sortField','id');
			$searchField 	= $this->nsession->get_param('ADMIN_POSTEDADS','searchField','');
			$searchString 	= $this->nsession->get_param('ADMIN_POSTEDADS','searchString','');
			$searchText  	= $this->nsession->get_param('ADMIN_POSTEDADS','searchText','');
			$searchFromDate = $this->nsession->get_param('ADMIN_POSTEDADS','searchFromDate','');
			$searchToDate  	= $this->nsession->get_param('ADMIN_POSTEDADS','searchToDate','');
			$searchAlpha  	= $this->nsession->get_param('ADMIN_POSTEDADS','searchAlpha','');
			$searchMode  	= $this->nsession->get_param('ADMIN_POSTEDADS','searchMode','STRING');
			$searchDisplay  = $this->nsession->get_param('ADMIN_POSTEDADS','searchDisplay',20);
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
		
		$this->nsession->set_userdata('ADMIN_POSTEDADS', $sessionDataArray);
		//==============================================================
		if(isset($sessionDataArray['searchField'])){
			$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
		}
		$recordSet = $this->db->get('contact_us');
		
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

		if($page > 0 && $page < $config['total_rows'])
			$start = $page;
			$this->db->select('contact_us.*');
			if(isset($sessionDataArray['searchField'])){
				$this->db->like($sessionDataArray['searchField'],$sessionDataArray['searchString'],'both');
			}
		$this->db->order_by($sortField,$sortType);
		$this->db->limit($config['per_page'],$start);
		$recordSet = $this->db->get('contact_us');
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

}

?>