<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
* Excel library for Code Igniter applications
* Based on: Derek Allard, Dark Horse Consulting, www.darkhorse.to, April 2006
* Tweaked by: Moving.Paper June 2013
*/
class Export{
    
    function to_excel($array, $filename) {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');
		//echo '<pre>' ; print_r($array->result_array());exit;
         //Filter all keys, they'll be table headers
        $h = array();
        foreach($array->result_array() as $row){
            foreach($row as $key=>$val){
                
                if(!in_array($key, $h)){
                    if($key!="tid" && $key!="sell_order_id" && $key!="equipment_id" && $key!="package_id" && $key!="order_type"  && $key!="tagent_id" && $key!="present_SP" && $key!="site" && $key!="add_on" && $key!="line_type" && $key!="audited_by"  && $key!="id" && $key!="agent_id" && $key!="parent_id" && $key!="parent_all_id" && $key!="tfid"  && $key!="colrCode"){
                      $h[] = $key;   
                   }
                }
                
            }
        }
                
                echo '<table><tr>';
                foreach($h as $key) {
					if($key=='sname'){ $key = 'Status'; }
					if($key=='optradio'){ $key = 'account_type'; }
                    $key = ucwords(str_replace("_"," ",$key));
					
                    echo '<th>'.$key.'</th>';
                    
                }
                echo '</tr>';
                
                foreach($array->result_array() as $row){
                    echo '<tr>';
                    foreach($row as $key=>$val){
                        if($key!="tid" && $key!="sell_order_id" && $key!="equipment_id" && $key!="package_id" && $key!="order_type"  && $key!="tagent_id" && $key!="present_SP" && $key!="site" && $key!="add_on" && $key!="line_type" && $key!="audited_by" && $key!="id" && $key!="agent_id" && $key!="parent_id" && $key!="parent_all_id" && $key!="tfid"  && $key!="colrCode" ){
                            //echo $key.'<br>';
                            if($key=="start_date" || $key=="end_date" || $key=="ldate" || $key=="dead_date" || $key=="sale_date" || $key=="activity_date" || $key=="contract_end" || $key=="lp_contract_end"|| $key=="contractEnd"|| $key=="dateOfBirth"){
                                $val=$this->user_date_format($val);
                            }elseif($key=="transfer_date" || $key=="post_date"){
                                $val=$this->convertTransferDate($val);
                            }else{
                                $val=$val;
                            }
                            $this->writeRow($val);
                        }
                    }
                }
                echo '</tr>';
                echo '</table>';
                
            
        }
    function writeRow($val) {
                echo '<td>'.utf8_decode($val).'</td>';              
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
				$retunVal = $userdate[2].'-'.$userdate[1].'-'.$userdate[0];
			}
			if($defineddateformat=='mm/dd' || $defineddateformat=='mm/dd/yy'){
				$retunVal = $userdate[1].'-'.$userdate[2].'-'.$userdate[0];
			}
			if($defineddateformat=='yy/mm' || $defineddateformat=='yy/mm/dd'){
				$retunVal = $userdate[0].'-'.$userdate[1].'-'.$userdate[2];
			}
			return $retunVal;
                    }else{
			return $value;
                    }
			
    }
    function convertTransferDate($valueDate){
        $newVal = explode(" ",$valueDate);
        $dateOnly = $this->user_date_format($newVal[0]);
        return $dateOnly." ".$newVal[1];
    }
    

}
?>