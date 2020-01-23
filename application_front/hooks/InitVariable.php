<?php
class InitVariable 
{
	
	function getGlobalData()
	{

		$CI =& get_instance();		
		$sql="SELECT * FROM global_config ";
		$recordSet = $CI->db->query($sql);
		
		$rs = false;
		if ($recordSet->num_rows() > 0)
        {
           	$rs = array();
			$isEscapeArr = array();
			foreach ($recordSet->result_array() as $row)
				{
					foreach($row as $key=>$val){
						if(!in_array($key,$isEscapeArr)){
							$recordSet->fields[$key] = outputEscapeString($val,'TEXTAREA');
						}
					}
					/*echo strtoupper($recordSet->fields['field_key']).$recordSet->fields['field_value']."<br/>";*/
					define(strtoupper($recordSet->fields['field_key']),$recordSet->fields['field_value']);
				}
        }
        //die();
		return true;
				
	}
	
}
?>