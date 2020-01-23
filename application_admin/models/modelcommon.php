<?php
class ModelCommon extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
	function insertData($tbl_name,$data){
		$result=$this->db->insert($tbl_name,$data);
		return $this->db->insert_id();
	}

	function updateData($tbl_name,$data,$check){
		$result=$this->db->update($tbl_name,$data,$check);
		return true;
	}

	function getSingleData($tbl_name,$where){
		return $this->db->get_where($tbl_name,$where)->row_array();
	}

	public function creatThumbImage($filename='',$source_path,$target_path){
		//$source_path = file_upload_absolute_path() .$source_path. '/profile_pic/merchants/' . $filename;
	    //$target_path = file_upload_absolute_path() .$destination_path. '/profile_pic/merchants/';
	    $config_manip = array(
	        'image_library' => 'gd2',
	        'source_image' => $source_path,
	        'new_image' => $target_path,
	        'maintain_ratio' => TRUE,
	        'create_thumb' => TRUE,
	        'thumb_marker' => '_thumb',
	        'width' => 150,
	        'height' => 150
	    );
	    $this->load->library('image_lib', $config_manip);
	    if (!$this->image_lib->resize()) {
	        //echo $this->image_lib->display_errors();
	        $this->image_lib->clear();
	    	return false;
	    }else{
	    	$imgDetailArray=explode('.',$filename);
 			$thumbimgname=$imgDetailArray[0].'_thumb';
 			$this->image_lib->clear();
  			return $thumbimgname.'.'.$imgDetailArray[1]; 	
	    }
	}

    public function send_email($to,$template_name,$data){
        $this->load->library('email');
        $this->email->set_mailtype("html");
        $this->email->from($this->config->item('webmaster_email'), $this->config->item('website_name'));
        $this->email->reply_to($this->config->item('webmaster_email'), $this->config->item('website_name'));
        $this->email->to($to);
        $this->email->subject(sprintf($data['subject'], $this->config->item('website_name')));
        $this->email->message($this->load->view('email/'.$template_name,$data, TRUE));
        $this->email->send();
        return $this->email->print_debugger();
    }
}