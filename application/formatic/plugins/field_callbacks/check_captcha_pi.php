<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Check captcha Class
 *
 * Formatic field plugin
 *
 * @package		Formatic
 * @license 	MIT Licence (http://opensource.org/licenses/mit-license.php) 
 * @author  	Mark Croxton
 * @copyright  	Mark Croxton, hallmarkdesign (http://www.hallmark-design.co.uk)
 * @version 	1.0.1
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

		if (method_exists($this->CI->session, 'set_userdata'))
		{
			// retrieve session vars
			$time = $this->CI->session->userdata('captcha_time');
			$word = $this->CI->session->userdata('captcha_word');
	
			// unset session vars
			$this->CI->session->unset_userdata('captcha_time');
			$this->CI->session->unset_userdata('captcha_word');
		}
		else
		{
			// retrieve from cookie
			$cookie_data = unserialize($this->CI->input->cookie('fm_captcha'));
			$time = $cookie_data['captcha_time'];
			$word = $cookie_data['captcha_word'];
			
			// $word is hashed, so we need to hash $postdata to compare on equal terms
			if (!$config['captcha_case_sensitive'])
			{
				$postdata = sha1(strtolower($postdata).$config['captcha_cookie_salt']);
			}
			else
			{
				$postdata = sha1($postdata.$config['captcha_cookie_salt']);
			}
			
			// expire cookie
			$this->CI->functions->set_cookie('fm_captcha', '', 0);	
		}

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