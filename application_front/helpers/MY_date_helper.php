<?php 
   //this function formates date according to format argument passed
   function formate_date($data,$time=false,$format='')
   {
     if($format == '')
	 {
	   
	   $format = 'Y-m-d';
	   
	   if($time == true)
	   {
	     $format .= ' g:i a';
	   }
	 } 
	
	 $formated_date = date($format,strtotime($data)); 
     return $formated_date;
   } 
    	 define('GLOBAL_DATE_FORMAT','dd/mm/yy');
		 
		 function user_date_format($value){
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
			
			}
			
			function db_date_format($value){
			
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
			
			}
?>
