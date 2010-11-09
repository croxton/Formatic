<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Check date Class
 *
 * Formatic field plugin
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */

class Check_date extends Formatic_plugin {
	
	/**
	 * Run
	 *
	 * @access	public
	 * @param	string	$f The field name
	 * @param	string	$postdata
	 * @param	array	$param An array of parameters
	 * @return	bool
	 */
	public function run($f, $param, $postdata=NULL)
	{
		$formatic =& $this->CI->formatic;
	
		$config = array_merge(
			$formatic->get_plugin_config(__CLASS__), 
			$formatic->get_field_config($f)
		);
		
		// only validate if not empty
		if (!empty($postdata))
		{
			$date = explode("/", $postdata);
		
	        if(count($date) <= 2)
	        {
	            $formatic->set_form_error($f, 'Date must be in dd/mm/yyyy format.');
	            return false;
	        }
	        else
	        {
	            if(!checkdate((int)$date[1], (int)$date[0], (int)$date[2]))
	            {
	                $formatic->set_form_error($f, 'Date must be valid.');
	                return false;
	            }
	            else
				{ 
					return true; 
				}
	        }
		}
		else
		{
			return true; 
		}
	}
}