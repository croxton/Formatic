<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
/**
 * Fm captcha Class
 *
 * Formatic field plugin
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */

class Fm_recaptcha extends Formatic_plugin {	

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
	
		$formatic->load->plugin('captcha');
	
		$config = array_merge(
			$formatic->get_plugin_config('recaptcha'), 
			$formatic->get_field_config($f)
		);

		$cap = create_captcha(array(
			'img_path'		=> './'.$config['captcha_path'],
			'img_url'		=> base_url().$config['captcha_path'],
			'font_path'		=> './'.$config['captcha_fonts_path'],
			'font_size'		=> $config['captcha_font_size'],
			'img_width'		=> $config['captcha_width'],
			'img_height'	=> $config['captcha_height'],
			'show_grid'		=> $config['captcha_grid'],
			'expiration'	=> $config['captcha_expire'],
		));

		// Save captcha params in session
		$this->CI->session->set_userdata(array(
			'captcha_word' => $cap['word'],
			'captcha_time' => $cap['time'],
		));
	
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