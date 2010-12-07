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
			$formatic->get_plugin_config('datepicker'), 
			$formatic->get_field_config($f)
		);
		
		// only validate if not empty
		if (!empty($postdata))
		{
			// get date
			$dd 	= (int) substr($postdata, strpos($config['format'], 'dd'),   2);
			$mm 	= (int) substr($postdata, strpos($config['format'], 'mm'),   2);
			$yyyy 	= (int) substr($postdata, strpos($config['format'], 'yyyy'), 4);
		
	        if(!$dd OR !$mm OR !$yyyy)
	        {
	            $formatic->set_form_error($f, $formatic->lang->line('datepicker_invalid_date'));
	            return false;
	        }
	        else
	        {
	            if(!checkdate($mm, $dd, $yyyy))
	            {
	                $formatic->set_form_error($f, $formatic->lang->line('datepicker_invalid_date'));
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