<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * TinyMCE class
 *
 * Formatic field plugin
 *
 * Works with TinyMCE > 3.2.7 http://tinymce.moxiecode.com/.
 *
 * @package		Formatic
 * @license 	MIT Licence (http://opensource.org/licenses/mit-license.php) 
 * @author  	Mark Croxton
 * @copyright  	Mark Croxton, hallmarkdesign (http://www.hallmark-design.co.uk)
 * @version 	1.0.0
 */

class Tinymce extends Formatic_plugin {
	
	protected static $init = TRUE;
			
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
		$js ='';
	
		$config = array_merge(
			$formatic->get_plugin_config('tinymce'), 
			$formatic->get_field_config($f)
		);
	
		// create a textarea
		$settings = array(
			'name'  	=> $f,
	        'id'    	=> $config['id'],
	       	'value'		=> set_value($f, $formatic->set_value($f) !='' ? $formatic->set_value($f) : @$config['default']),
			'class'     => ltrim(@$config['class'].' tinymce')
			);
		$settings = isset($config['attr']) ? $settings+$config['attr'] : $settings;

		$r = form_textarea($settings);
	
		if (self::$init)
		{
			$js = <<<JAVASCRIPT
			<script type="text/javascript">
			//<![CDATA[ 
				tinyMCE.init({
					mode : "specific_textareas",
					editor_selector : "tinymce",
					theme   	: "{$config['theme']}",
					plugins 	: "{$config['plugins']}",
					content_css : "{$config['editor_css']}",

					{$config['options']}
				});
			//]]>
			</script>
JAVASCRIPT;

		self::$init = FALSE;
		}

		return $r.$js;
	}
}