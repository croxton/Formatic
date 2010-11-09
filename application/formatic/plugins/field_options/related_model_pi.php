<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Related class
 *
 * Formatic field plugin
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */

class Related_model extends Formatic_plugin {
	
	/**
	 * Run
	 *
	 * @access	public
	 * @param	string	$f The field name
	 * @param	array	$param An array of parameters
	 * @return	string
	 */
	function run($f, $param)
	{
		$model 	 	 	= isset($param[0]) ? $param[0] : NULL;
		$model_method 	= isset($param[1]) ? $param[1] : '';
		$default 		= isset($param[2]) ? $param[2] : '';
		$arg			= isset($param[3]) ? $param[3] : 1;
		
		
		// get last segment of the current uri and pass to the model
		$model_arg	= end($this->CI->uri->segments); 
	
		$r = array();
		if (!empty($default))
		{
			$r += array("0" => $default);
		}
	
		if (!is_null($model))
		{
			// load it
      		$this->CI->load->model($model);
			
			// remove any directory paths to the model
			if (strstr($model,'/'))
			{
				$model = explode('/', $model);
				$model = end($model);
			}
			if ($arg)
			{
				$result = $this->CI->$model->{$model_method}($model_arg);
			}
			else
			{
				$result = $this->CI->$model->{$model_method}();
			}
		}
		
		// make sure we have an array
		if (is_array($result)) 
		{
			$r += $result;
		}

		return $r;
	}
}
