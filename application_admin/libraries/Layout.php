<?php  

if (!defined('BASEPATH')) exit('No direct script access allowed');



class Layout

{

    

    var $obj;

    var $layout;

    

    function __construct($layout = "layout_login")

    {

        $this->obj =& get_instance();

        $this->layout = $layout;

		define('FILE_UPLOAD_BASE_URL',config_item('file_upload_base_url'));

		define('CSS_IMAGES_JS_BASE_URL',config_item('css_images_js_base_url'));

    }



    function setLayout($layout)

    {

      $this->layout = $layout;

    }

    

    function view($view, $data=null, $return=false)

    {

		$loadedData = array();

        $loadedData['content_for_layout'] = $this->obj->load->view($view,$data,true);

        

        if($return)

        {

            $output = $this->obj->load->view($this->layout, $loadedData, true);

            return $output;

        }

        else

        {

            $this->obj->load->view($this->layout, $loadedData, false);

        }

    }

	

	

	function multiple_view($view_arr = array(), $data_arr= array(), $return=false)

    {

		$loadedData = array();

        

		foreach($view_arr as $key => $view)

		{

			$data = null;

			

			if(isset($data_arr[$key]))

			{

				$data = $data_arr[$key];

			}

			

			if($view <> "")

			{

				$loadedData['content_for_layout_'.$key] = $this->obj->load->view($view,$data,true);				

			}

		}

        

        if($return)

        {

            $output = $this->obj->load->view($this->layout, $loadedData, true);

            return $output;

        }

        else

        {

            $this->obj->load->view($this->layout, $loadedData, false);

        }

    }

}

?>