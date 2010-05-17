<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Recaptcha Class
 *
 * Formatic field plugin
 *
 * Requires recaptchalib located at http://code.google.com/p/recaptcha/downloads/list?q=label:phplib-Latest.
 * Rename recaptchalib.php to recaptcha_helper.php and put it in your helpers fold
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */
	
class Recaptcha extends Formatic_plugin {
	
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
		
		$formatic->load->helper('recaptcha');
		$config = array_merge(
			$formatic->get_plugin_config('recaptcha'), 
			$formatic->get_field_config($f)
		);
	
		// create an input field for the recaptcha response
		$settings = array(
			'name'		=> $f,
	        'id'    	=> $f,
			'value'		=> set_value($f, isset($_POST[$f]) ? $_POST[$f] : @$config['default'])
		);
		$settings = isset($config['attr']) ? $settings+$config['attr'] : $settings;
	
		$field = form_input($settings);

		// Add custom theme so we can get only image
		$field .= "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$recaptcha = recaptcha_get_html($config['public_key']);

		$view_data = array(
			"field" 	   => $field,
			"recaptcha"    => $recaptcha,
		);
		$formatic->set_template_config($f, $view_data);
	}
}