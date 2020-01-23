<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	function currencyConverter($price,$format=true,$currency='USD')
	{
		$CI =& get_instance();
		
	 	$sql = "SELECT CRM.currency_rate as currency_rate, CM.currency_format as currency_format
	 			FROM  currency_master CM LEFT JOIN `currency_rate_master` CRM ON CM.id = CRM.currency_id 
				WHERE  CM.currency_code='".$currency."'";
		$recordSet = $CI->db->query($sql);
	
		if ($recordSet->num_rows() > 0)
        {
           $row = $recordSet->row_array();
           
           if($row['currency_rate'] <> '')
            $rate =  round($price/$row['currency_rate'],2);
           else 
           	 $rate = $price;
  
           if($format == true)
		  	 $str =  str_replace('##',$rate,$row['currency_format']);
		   else 
		   	$str =  $rate;
			   
		   return $str;
        }
		else
		{
			return "";
		}
		
	}

	function convertToINR($price,$currency='')
	{
		$CI =& get_instance();
		
		$currency = $CI->nsession->userdata('currency_code');
		
		if($currency=='')
			$currency='USD';
			
		if($currency<>'INR')
		{		
		 	$sql = "SELECT CRM.currency_rate as currency_rate, CM.currency_format as currency_format
		 			FROM  currency_master CM LEFT JOIN `currency_rate_master` CRM ON CM.id = CRM.currency_id 
					WHERE  CM.currency_code='".$currency."'";
			$recordSet = $CI->db->query($sql);
		
			if ($recordSet->num_rows() > 0)
	        {
	           $row = $recordSet->row_array();
			   $rate =  round($price*$row['currency_rate'],2);
			   
			   return $rate;
	        }
			else
			{
				return $price;
			}
		}
	}
	