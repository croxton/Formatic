<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Check recaptcha Class
 *
 * Formatic field plugin
 *
 * Requires recaptchalib located at http://code.google.com/p/recaptcha/downloads/list?q=label:phplib-Latest.
 * Rename recaptchalib.php to recaptcha_helper.php and put it in your helpers fold
 *
 * @package		Formatic
 * @license 	MIT Licence (http://opensource.org/licenses/mit-license.php) 
 * @author  	Mark Croxton
 * @copyright  	Mark Croxton, hallmarkdesign (http://www.hallmark-design.co.uk)
 * @version 	1.0.0
 */

class Check_recaptcha extends Formatic_plugin {
	
	/**
	 * Run
	 *
	 * @access	public
	 * @param	string	$f The field name
	 * @param	string	$postdata
	 * @param	array	$param An array of parameters
	 * @return	bool
	 */
	function run($f, $param, $postdata=NULL)
	{
		$formatic =& $this->CI->formatic;
		
		$formatic->load->helper('recaptcha');	
		$config = $formatic->get_plugin_config('recaptcha');

		$resp = recaptcha_check_answer($config['private_key'],
				$_SERVER['REMOTE_ADDR'],
				$_POST['recaptcha_challenge_field'],
				$postdata);

		if (!$resp->is_valid) 
		{
			$formatic->set_form_error($f, $formatic->lang->line('incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}
}