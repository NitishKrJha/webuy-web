<?php

class ModelPermission extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
    }
	
	function add_permission($data)

	{
		echo"hi vap";
		$this->db->insert('staff',$data);
		//echo $this->db->last_query(); die();
		$insert_id = $this->db->insert_id();
		
		if($insert_id)
		{
			return $insert_id;
		}
		else
		{
			return false;
		}
	}
}
?>