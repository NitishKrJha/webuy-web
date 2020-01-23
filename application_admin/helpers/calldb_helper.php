<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

		function callpro($procedure){
			$CI =& get_instance();
			$CI->load->database(); 
	
			$recordSet = $CI->db->query($procedure);
			$CI->db->freeDBResource($CI->db->conn_id);
			if($recordSet->num_rows() > 0){
				return $recordSet->result_array();
			}else{
				return array();
			}
		}