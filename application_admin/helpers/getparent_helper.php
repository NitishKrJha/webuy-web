<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	function parentList($parent)
	{
		$CI =& get_instance();
		
	 	$sql = "SELECT AllParentId from admin where id=".$parent;
		$recordSet = $CI->db->query($sql);
	
		if ($recordSet->num_rows() > 0)
        {
           $row = $recordSet->row_array();
           
           if($row['AllParentId'] != ''){
            $str = $row['AllParentId'].','.$parent;
		   }else{
			   $str =  $parent;
		   }
			   
		   return $str;
        }
		else
		{
			return "";
		}
		
	}
        

