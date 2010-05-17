<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Check captcha Class
 *
 * Formatic field plugin
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */

class Check_captcha extends Formatic_plugin {
	
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
			$formatic->get_plugin_config('fm_captcha'), 
			$formatic->get_field_config($f)
		);
	
		// retrieve session vars
		$time = $this->CI->session->userdata('captcha_time');
		$word = $this->CI->session->userdata('captcha_word');
	
		// unset session vars
		$this->CI->session->unset_userdata('captcha_time');
		$this->CI->session->unset_userdata('captcha_word');

		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		if ($now - $time > $config['captcha_expire']) 
		{
			$formatic->set_form_error('captcha', $formatic->lang->line('captcha_expired'));
			return FALSE;
		} 
		elseif (($config['captcha_case_sensitive'] AND
				$postdata != $word) OR
				strtolower($postdata) != strtolower($word)) 
		{
			$formatic->set_form_error('captcha', $formatic->lang->line('incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}
}