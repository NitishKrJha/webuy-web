<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**

 * CodeIgniter

 *

 * An open source application development framework for PHP 5.1.6 or newer

 *

 * @package		CodeIgniter

 * @author		ExpressionEngine Dev Team

 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.

 * @license		http://codeigniter.com/user_guide/license.html

 * @link		http://codeigniter.com

 * @since		Version 1.0

 * @filesource

 */



// ------------------------------------------------------------------------



/**

 * Common Functions

 *

 * Loads the base classes and executes the request.

 *

 * @package		CodeIgniter

 * @subpackage	codeigniter

 * @category	Common Functions

 * @author		ExpressionEngine Dev Team

 * @link		http://codeigniter.com/user_guide/

 */



// ------------------------------------------------------------------------



/**

* Determines if the current version of PHP is greater then the supplied value

*

* Since there are a few places where we conditionally test for PHP > 5

* we'll set a static variable.

*

* @access	public

* @param	string

* @return	bool	TRUE if the current version is $version or higher

*/

	function is_php($version = '5.0.0')

	{

		static $_is_php;

		$version = (string)$version;



		if ( ! isset($_is_php[$version]))

		{

			$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;

		}



		return $_is_php[$version];

	}



// ------------------------------------------------------------------------



/**

 * Tests for file writability

 *

 * is_writable() returns TRUE on Windows servers when you really can't write to

 * the file, based on the read-only attribute.  is_writable() is also unreliable

 * on Unix servers if safe_mode is on.

 *

 * @access	private

 * @return	void

 */

	function is_really_writable($file)

	{

		// If we're on a Unix server with safe_mode off we call is_writable

		if (DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == FALSE)

		{

			return is_writable($file);

		}

		

		// For windows servers and safe_mode "on" installations we'll actually

		// write a file then read it.  Bah...

		if (is_dir($file))

		{

			$file = rtrim($file, '/').'/'.md5(mt_rand(1,100).mt_rand(1,100));



			if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)

			{

				return FALSE;

			}



			fclose($fp);

			@chmod($file, DIR_WRITE_MODE);

			@unlink($file);

			return TRUE;

		}

		elseif (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)

		{

			return FALSE;

		}



		fclose($fp);

		return TRUE;

	}



// ------------------------------------------------------------------------



/**

* Class registry

*

* This function acts as a singleton.  If the requested class does not

* exist it is instantiated and set to a static variable.  If it has

* previously been instantiated the variable is returned.

*

* @access	public

* @param	string	the class name being requested

* @param	string	the directory where the class should be found

* @param	string	the class name prefix

* @return	object

*/

	function &load_class($class, $directory = 'libraries', $prefix = 'CI_')

	{

		static $_classes = array();



		// Does the class exist?  If so, we're done...

		if (isset($_classes[$class]))

		{

			return $_classes[$class];

		}



		$name = FALSE;



		// Look for the class first in the native system/libraries folder

		// thenin the local application/libraries folder

		foreach (array(BASEPATH, APPPATH) as $path)

		{

			if (file_exists($path.$directory.'/'.$class.EXT))

			{

				$name = $prefix.$class;



				if (class_exists($name) === FALSE)

				{

					require($path.$directory.'/'.$class.EXT);

				}



				break;

			}

		}



		// Is the request a class extension?  If so we load it too

		if (file_exists(APPPATH.$directory.'/'.config_item('subclass_prefix').$class.EXT))

		{

			$name = config_item('subclass_prefix').$class;



			if (class_exists($name) === FALSE)

			{

				require(APPPATH.$directory.'/'.config_item('subclass_prefix').$class.EXT);

			}

		}



		// Did we find the class?

		if ($name === FALSE)

		{

			// Note: We use exit() rather then show_error() in order to avoid a

			// self-referencing loop with the Excptions class

			exit('Unable to locate the specified class: '.$class.EXT);

		}



		// Keep track of what we just loaded

		is_loaded($class);



		$_classes[$class] = new $name();

		return $_classes[$class];

	}



// --------------------------------------------------------------------



/**

* Keeps track of which libraries have been loaded.  This function is

* called by the load_class() function above

*

* @access	public

* @return	array

*/

	function &is_loaded($class = '') // add & by nitish

	{

		static $_is_loaded = array();



		if ($class != '')

		{

			$_is_loaded[strtolower($class)] = $class;

		}



		return $_is_loaded;

	}



// ------------------------------------------------------------------------



/**

* Loads the main config.php file

*

* This function lets us grab the config file even if the Config class

* hasn't been instantiated yet

*

* @access	private

* @return	array

*/

	function &get_config($replace = array())

	{

		static $_config;



		if (isset($_config))

		{

			return $_config[0];

		}



		// Fetch the config file

		if ( ! file_exists(APPPATH.'config/config'.EXT))

		{

			exit('The configuration file does not exist.');

		}

		else

		{

			require(APPPATH.'config/config'.EXT);

		}



		// Does the $config array exist in the file?

		if ( ! isset($config) OR ! is_array($config))

		{

			exit('Your config file does not appear to be formatted correctly.');

		}



		// Are any values being dynamically replaced?

		if (count($replace) > 0)

		{

			foreach ($replace as $key => $val)

			{

				if (isset($config[$key]))

				{

					$config[$key] = $val;

				}

			}

		}



		$_config[0] =& $config;
                return $_config[0]; 

	}



// ------------------------------------------------------------------------



/**

* Returns the specified config item

*

* @access	public

* @return	mixed

*/

	function config_item($item)

	{

		static $_config_item = array();



		if ( ! isset($_config_item[$item]))

		{

			$config =& get_config();



			if ( ! isset($config[$item]))

			{

				return FALSE;

			}

			$_config_item[$item] = $config[$item];

		}



		return $_config_item[$item];

	}



// ------------------------------------------------------------------------



/**

* Error Handler

*

* This function lets us invoke the exception class and

* display errors using the standard error template located

* in application/errors/errors.php

* This function will send the error page directly to the

* browser and exit.

*

* @access	public

* @return	void

*/

	function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')

	{

		$_error =& load_class('Exceptions', 'core');

		echo $_error->show_error($heading, $message, 'error_general', $status_code);

		exit;

	}



// ------------------------------------------------------------------------



/**

* 404 Page Handler

*

* This function is similar to the show_error() function above

* However, instead of the standard error template it displays

* 404 errors.

*

* @access	public

* @return	void

*/

	function show_404($page = '', $log_error = TRUE)

	{

		$_error =& load_class('Exceptions', 'core');

		$_error->show_404($page, $log_error);

		exit;

	}



// ------------------------------------------------------------------------



/**

* Error Logging Interface

*

* We use this as a simple mechanism to access the logging

* class and send messages to be logged.

*

* @access	public

* @return	void

*/

	function log_message($level = 'error', $message, $php_error = FALSE)

	{

		static $_log;



		if (config_item('log_threshold') == 0)

		{

			return;

		}



		$_log =& load_class('Log');

		$_log->write_log($level, $message, $php_error);

	}



// ------------------------------------------------------------------------



/**

 * Set HTTP Status Header

 *

 * @access	public

 * @param	int		the status code

 * @param	string

 * @return	void

 */

	function set_status_header($code = 200, $text = '')

	{

		$stati = array(

							200	=> 'OK',

							201	=> 'Created',

							202	=> 'Accepted',

							203	=> 'Non-Authoritative Information',

							204	=> 'No Content',

							205	=> 'Reset Content',

							206	=> 'Partial Content',



							300	=> 'Multiple Choices',

							301	=> 'Moved Permanently',

							302	=> 'Found',

							304	=> 'Not Modified',

							305	=> 'Use Proxy',

							307	=> 'Temporary Redirect',



							400	=> 'Bad Request',

							401	=> 'Unauthorized',

							403	=> 'Forbidden',

							404	=> 'Not Found',

							405	=> 'Method Not Allowed',

							406	=> 'Not Acceptable',

							407	=> 'Proxy Authentication Required',

							408	=> 'Request Timeout',

							409	=> 'Conflict',

							410	=> 'Gone',

							411	=> 'Length Required',

							412	=> 'Precondition Failed',

							413	=> 'Request Entity Too Large',

							414	=> 'Request-URI Too Long',

							415	=> 'Unsupported Media Type',

							416	=> 'Requested Range Not Satisfiable',

							417	=> 'Expectation Failed',



							500	=> 'Internal Server Error',

							501	=> 'Not Implemented',

							502	=> 'Bad Gateway',

							503	=> 'Service Unavailable',

							504	=> 'Gateway Timeout',

							505	=> 'HTTP Version Not Supported'

						);



		if ($code == '' OR ! is_numeric($code))

		{

			show_error('Status codes must be numeric', 500);

		}



		if (isset($stati[$code]) AND $text == '')

		{

			$text = $stati[$code];

		}



		if ($text == '')

		{

			show_error('No status text available.  Please check your status code number or supply your own message text.', 500);

		}



		$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;



		if (substr(php_sapi_name(), 0, 3) == 'cgi')

		{

			header("Status: {$code} {$text}", TRUE);

		}

		elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')

		{

			header($server_protocol." {$code} {$text}", TRUE, $code);

		}

		else

		{

			header("HTTP/1.1 {$code} {$text}", TRUE, $code);

		}

	}



// --------------------------------------------------------------------



/**

* Exception Handler

*

* This is the custom exception handler that is declaired at the top

* of Codeigniter.php.  The main reason we use this is to permit

* PHP errors to be logged in our own log files since the user may

* not have access to server logs. Since this function

* effectively intercepts PHP errors, however, we also need

* to display errors based on the current error_reporting level.

* We do that with the use of a PHP error template.

*

* @access	private

* @return	void

*/

	function _exception_handler($severity, $message, $filepath, $line)

	{

		 // We don't bother with "strict" notices since they tend to fill up

		 // the log file with excess information that isn't normally very helpful.

		 // For example, if you are running PHP 5 and you use version 4 style

		 // class functions (without prefixes like "public", "private", etc.)

		 // you'll get notices telling you that these have been deprecated.

		if ($severity == E_STRICT)

		{

			return;

		}



		$_error =& load_class('Exceptions', 'core');



		// Should we display the error? We'll get the current error_reporting

		// level and add its bits with the severity bits to find out.

		if (($severity & error_reporting()) == $severity)

		{

			$_error->show_php_error($severity, $message, $filepath, $line);

		}



		// Should we log the error?  No?  We're done...

		if (config_item('log_threshold') == 0)

		{

			return;

		}



		$_error->log_exception($severity, $message, $filepath, $line);

	}



	// --------------------------------------------------------------------



	/**

	 * Remove Invisible Characters

	 *

	 * This prevents sandwiching null characters

	 * between ascii characters, like Java\0script.

	 *

	 * @access	public

	 * @param	string

	 * @return	string

	 */

	function remove_invisible_characters($str)

	{

		static $non_displayables;



		if ( ! isset($non_displayables))

		{

			// every control character except newline (dec 10), carriage return (dec 13), and horizontal tab (dec 09),

			$non_displayables = array(

										'/%0[0-8bcef]/',			// url encoded 00-08, 11, 12, 14, 15

										'/%1[0-9a-f]/',				// url encoded 16-31

										'/[\x00-\x08]/',			// 00-08

										'/\x0b/', '/\x0c/',			// 11, 12

										'/[\x0e-\x1f]/'				// 14-31

									);

		}



		do

		{

			$cleaned = $str;

			$str = preg_replace($non_displayables, '', $str);

		}

		while ($cleaned != $str);



		return $str;

	}



	function pr($arr,$e=1)

	{

		if(is_array($arr))

		{

			echo "<pre>";

			print_r($arr);

			echo "</pre>";		

		}

		else

		{

			echo "<br>Not an array...<br>";

			echo "<pre>";

			var_dump($arr);

			echo "</pre>";

			

		}

		if($e==1)

		{

			exit();

		}

		else

		{

			echo "<br>";

		}

			

	}





	function inputEscapeString($str,$Type='DB',$htmlEntitiesEncode = true)

	{

		if($Type === 'DB')

		{

			if(get_magic_quotes_gpc()===0)

			{

				$str = addslashes($str);

			}

		}

		elseif($Type === 'FILE')

		{

			if(get_magic_quotes_gpc()===1)

			{

				$str = stripslashes($str);	

			}

		}

		else

		{

			$str = $str;

		}

		

		if($htmlEntitiesEncode === true)

		{

			$str = htmlentities($str);

			//$str = htmlspecialchars($str);

		}

		return $str;

	}

		

		

	function outputEscapeString($str,$Type = 'INPUT', $htmlEntitiesDecode = true )

	{

		if(get_magic_quotes_runtime()==1)

		{

			$str = stripslashes($str);	

		}

		

		if($htmlEntitiesDecode === true)

		{

			$str = html_entity_decode($str);

		}

		

		if($Type == 'INPUT')

		{

			//$str = htmlentities($str);

			$str = htmlspecialchars($str);

		}

		elseif($Type == 'TEXTAREA')

		{

			$str = $str;

		}

		elseif($Type == 'HTML')

		{

			$str = nl2br($str);

		}

		else

		{

			$str = $str;

		}

		

		return $str;

	}

	

	if ( ! function_exists('file_upload_absolute_path'))

	{

		function file_upload_absolute_path()

		{

			$CI =& get_instance();

			return $CI->config->slash_item('file_upload_absolute_path');

		}

	}

	

	

	

	if ( ! function_exists('file_upload_base_url'))

	{

		function file_upload_base_url()

		{

			$CI =& get_instance();

			return $CI->config->slash_item('file_upload_base_url');

		}

	}

	

	

	if ( ! function_exists('server_absolute_path'))

	{

		function server_absolute_path()

		{

			$CI =& get_instance();

			return $CI->config->slash_item('server_absolute_path');

		}

	}

	

	if ( ! function_exists('front_base_url'))

	{

		function front_base_url()

		{

			$CI =& get_instance();

			return $CI->config->slash_item('front_base_url');

		}

	}

	

	if ( ! function_exists('css_images_js_base_url'))

	{

		function css_images_js_base_url()

		{

			$CI =& get_instance();

			return $CI->config->slash_item('css_images_js_base_url');

		}

	}

	

	if ( ! function_exists('tableOrdering'))

	{

		function tableOrdering($title,$field,$sortfield='',$sorttype='ASC')

		{

			$CI =& get_instance();

			$image_path = css_images_js_base_url();

			if($sortfield==$field && $sorttype=='ASC'){

				$returnOption = '<a href="javascript:tableOrdering(\''.$field.'\',\'DESC\');" title="Click to sort by this column" class="textHeader">'.$title.'<i class="fa fa-sort-amount-asc"></i></a>';

			}elseif($sortfield==$field && $sorttype=='DESC'){

				$returnOption = '<a href="javascript:tableOrdering(\''.$field.'\',\'ASC\');" title="Click to sort by this column" class="textHeader">'.$title.'<i class="fa fa-sort-amount-desc"></i></a>';

			}else{

				$returnOption = '<a href="javascript:tableOrdering(\''.$field.'\',\'DESC\');" title="Click to sort by this column" class="textHeader">'.$title.'</a>';

			}

			return $returnOption;

		}

	}

	

	if( ! function_exists('make_thumb')){

		function make_thumb($img_name,$filename,$new_w,$new_h,$ext)

		{

			//get image extension.

			

			//creates the new image using the appropriate function from gd library

			

			if(!strcmp(".jpg",$ext) || !strcmp(".jpeg",$ext) || !strcmp(".JPG",$ext) || !strcmp(".JPEG",$ext))

			$src_img=@imagecreatefromjpeg($img_name);

			

			if(!strcmp(".gif",$ext) || !strcmp(".GIF",$ext))

			$src_img=@imagecreatefromgif($img_name);

			

			if(!strcmp(".bmp",$ext) || !strcmp(".BMP",$ext))

			$src_img=@imagecreatefromwbmp($img_name);

		

			

			if(!strcmp(".png",$ext) || !strcmp(".PNG",$ext))

			$src_img=@imagecreatefrompng($img_name);

			

			if($src_img){

			//gets the dimmensions of the image

			$old_x=imagesx($src_img);

			$old_y=imagesy($src_img);

			

			// next we will calculate the new dimmensions for the thumbnail image

			// the next steps will be taken:

			// 1. calculate the ratio by dividing the old dimmensions with the new ones

			// 2. if the ratio for the width is higher, the width will remain the one define in WIDTH variable

			// and the height will be calculated so the image ratio will not change

			// 3. otherwise we will use the height ratio for the image

			// as a result, only one of the dimmensions will be from the fixed ones

			$ratio1=$old_x/$new_w;

			$ratio2=$old_y/$new_h;

			if($ratio1>$ratio2) {

				$thumb_w=$new_w;

				$thumb_h=$old_y/$ratio1;

			}

			else {

				$thumb_h=$new_h;

				$thumb_w=$old_x/$ratio2;

			}

			

			// we create a new image with the new dimmensions

			$dst_img=imagecreatetruecolor($thumb_w,$thumb_h);

			

			/*********************************************************************************************************************************

			if($transparency) 

			{*/

				if($ext==".png") {

					imagealphablending($dst_img, false);

					$colorTransparent = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);

					imagefill($dst_img, 0, 0, $colorTransparent);

					imagesavealpha($dst_img, true);

				} 

				elseif($ext==".gif") {

					$trnprt_indx = imagecolortransparent($src_img);

					if ($trnprt_indx >= 0) {

						//its transparent

						$trnprt_color = imagecolorsforindex($src_img, $trnprt_indx);

						$trnprt_indx = imagecolorallocate($dst_img, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);

						imagefill($dst_img, 0, 0, $trnprt_indx);

						imagecolortransparent($dst_img, $trnprt_indx);

					}

				}

			/*} 

			else 

			{

				Imagefill($dst_img, 0, 0, imagecolorallocate($dst_img, 255, 255, 255));

			}

			*********************************************************************************************************************************/

			

			// resize the big image to the new created one

			imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

			

			// output the created image to the file. Now we will have the thumbnail into the file named by $filename

			if(!strcmp(".png",$ext))

				imagepng($dst_img,$filename);

			else

				imagejpeg($dst_img,$filename);

			

			if(!strcmp(".gif",$ext))

				imagegif($dst_img,$filename);

			if(!strcmp(".bmp",$ext))		

				imagewbmp($dst_img,$filename);	

			

				

			

			//destroys source and destination images.

			imagedestroy($dst_img);

			imagedestroy($src_img);

			

			return true;

			}else{

				return false;

			}

		}

	}

	

	if( ! function_exists('getProductImages')){

	function getProductImages($product_code)

	{

		$results = array($product_code.'.jpg');



		$directory = file_upload_absolute_path().'product_image_gallery/';

		$handler = opendir($directory);

		

		// open directory and walk through the filenames

		while ($file = readdir($handler)) {

		

		    // if file isn't this directory or its parent, add it to the results

		    if ($file != "." && $file != "..") {

		

		        // check with regex that the file format is what we're expecting and not something else

		        if(preg_match('#'.$product_code.'\_[^\s]#', $file)) {

		

		            // add to our file array for later use

		            $results[] = $file;

		        }

		    }

		}

		

		return $results;

	}

	

	

}		



if ( ! function_exists('DisplayAlphabet'))

{

	function DisplayAlphabet()

	{

		$str="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";

		$str=split(",",$str);

		for($i=0; $i < sizeof($str); $i++)

		{

			echo "<a href=\"#\" class='link' onClick=\"javascript:search_alpha('".$str[$i]."')\">$str[$i]</a>&nbsp;&nbsp;&nbsp;";

		}

	}

}



	if ( ! function_exists('resetPosition'))

	{

		function resetPosition($table,$field, $pk,$field_value)

		{

			$CI =& get_instance();

			$pre = $CI->functions->getPreviousRecord($table,$field, $pk, $field_value);

			$next = $CI->functions->getNextRecord($table,$field, $pk, $field_value);

			$image_path = css_images_js_base_url();

			$returnOption = '<div style="float:right;cursor:pointer;">';

			if($next)

				$returnOption.= '<img src="'.$image_path.'images/sortdown.gif" onclick="javascript:recordPosition(\''.$field_value.'\',\'down\');" title="Click to decrease Position" />'; 

			if($pre && $next)

				$returnOption.= ' | '; 

			if($pre)

				$returnOption.= '<img src="'.$image_path.'images/sortup.gif" onclick="javascript:recordPosition(\''.$field_value.'\',\'up\');" title="Click to increase Position"/>';

			$returnOption.= '</div>';

			return $returnOption;

		}		

	}

/* End of file Common.php */

/* Location: ./system/core/Common.php */