<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
/**
 * Fm captcha Class
 *
 * Formatic field plugin
 *
 * @package		Formatic
 * @license 	MIT Licence (http://opensource.org/licenses/mit-license.php) 
 * @author  	Mark Croxton
 * @copyright  	Mark Croxton, hallmarkdesign (http://www.hallmark-design.co.uk)
 * @version 	1.0.1
 */

class Fm_captcha extends Formatic_plugin {	

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
		$formatic =& $this->CI->formatic;
	
		$formatic->load->helper('captcha');
	
		$config = array_merge(
			$formatic->get_plugin_config('fm_captcha'), 
			$formatic->get_field_config($f)
		);

		$cap = create_captcha(array(
			'img_path'		=> $config['captcha_img_path'],
			'img_url'		=> $config['captcha_img_url'],
			'font_path'		=> $config['captcha_fonts_path'],
			'font_size'		=> $config['captcha_font_size'],
			'img_width'		=> $config['captcha_width'],
			'img_height'	=> $config['captcha_height'],
			'show_grid'		=> $config['captcha_grid'],
			'expiration'	=> $config['captcha_expire'],
		));

		// Save captcha params in session
		// Take account of Session class differences in EE
		if (method_exists($this->CI->session, 'set_userdata'))
		{
			$this->CI->session->set_userdata(array(
				'captcha_word' => $cap['word'],
				'captcha_time' => $cap['time'],
			));
		}
		else
		{
			// EE, so we'll use a cookie, taking precaution to salt and hash the captcha word
			if (!$config['captcha_case_sensitive'])
			{
				$cap['word'] = sha1(strtolower($cap['word']).$config['captcha_cookie_salt']);
			}
			else
			{
				$cap['word']= sha1($cap['word'].$config['captcha_cookie_salt']);
			}
			$this->CI->functions->set_cookie('fm_captcha', serialize(array(
				'captcha_word' => $cap['word'],
				'captcha_time' => $cap['time'],
			)), 1800); // 30 minutes
		}
	
		$r =  $cap['image'];
	
		// create an input field for the recaptcha response
		$settings = array(
			'name'		=> $f,
	        'id'    	=> $f,
			'value'		=> set_value($f, isset($_POST[$f]) ? $_POST[$f] : @$config['default'])
		);
		$settings = isset($config['attr']) ? $settings+$config['attr'] : $settings;
	
		$r .= form_input($settings);
	
		// return html
		$view_data = array(
			"captcha_html" => $r 
		);
		
		$formatic->set_template_config($f, $view_data);
	}
}