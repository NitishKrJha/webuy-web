<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation{



function is_unique_edit($str, $field)

{

	//$this->obj =& get_instance();	

list($table, $field) = explode('.', $field);

list($field, $id) = explode("##",$field);

	
$res = $this->CI->db->query('SHOW COLUMNS FROM '.$table)->result_array();

foreach($res as $row) {
  if ($row['Extra'] == 'auto_increment')
    $autoIncrementId = $row['Field'];
  if ($row['Key'] == 'PRI')
    $primaryKey = $row['Field'];
}



$this->CI->form_validation->set_message('is_unique_edit','The %s is not available');



if (isset($this->CI->db))

{

$sql =	"select ".$autoIncrementId." from ".$table." where ".$field."='".$str."' AND ".$autoIncrementId."!='".$id."'";

$query =$this->CI->db->query($sql);

// $this->CI->db->where('email', $str)->get($table);

return $query->num_rows() === 0;

}



        return FALSE;

}





function is_exist($str, $field)

{

	

	

list($table, $field) = explode('.', $field);



$this->CI->form_validation->set_message('is_exist','The %s is not registered yet');



if (isset($this->CI->db))

{

$query = $this->CI->db->where($field, $str)->get($table);

return $query->num_rows() !== 0;

}



        return FALSE;

}







}

?>