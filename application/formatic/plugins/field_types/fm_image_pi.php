<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * FM Image class
 *
 * Formatic field plugin
 *
 * @package		Formatic
 * @license 	MIT Licence (http://opensource.org/licenses/mit-license.php) 
 * @author  	Mark Croxton
 * @copyright  	Mark Croxton, hallmarkdesign (http://www.hallmark-design.co.uk)
 * @version 	1.0.0
 */

class Fm_image extends Formatic_plugin {
			
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
			$formatic->get_plugin_config('fm_image'), 
			$formatic->get_field_config($f)
		);
		
		$field_value = set_value($f, $formatic->set_value($f) !='' ? $formatic->set_value($f) : @$config['default']);
	
		// create a file input field
		$settings = array(
			'name'  	=> $f,
	        'id'    	=> $config['id'],
	       	'value'		=> set_value($f, $formatic->set_value($f) !='' ? $formatic->set_value($f) : @$config['default']),
			'class'     => ltrim(@$config['attr']['class'].' image')
			);
		$settings = isset($config['attr']) ? $settings+$config['attr'] : $settings;

		$r = form_upload($settings);
		
		$thumb = '<img src="/'.$config['upload_location'].$field_value.'" alt="" />';
		
		$view_data = array(
			"preview_id" 	=> 'preview_'.$f,
			"preview_class" => $config['preview_class'],
			"thumb"			=> $thumb
		);
		$formatic->set_template_config($f, $view_data);
	
		$js = <<<JAVASCRIPT
		<script type="text/javascript">
		//<![CDATA[ 
			$(document).ready(function(){
				$('#{$config['id']}').change(function(){
					$('#{$view_data['preview_id']}').html('');
				});
			});
		//]]>
		</script>
JAVASCRIPT;

		return $js.$r;
	}
}