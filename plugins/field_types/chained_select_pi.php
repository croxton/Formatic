<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Chained Select class
 *
 * Formatic field plugin
 *
 * Populate a select field dynamically via AJAX, based on the 
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */

class Chained_select extends Formatic_plugin {
	
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
			$formatic->get_plugin_config('chained_select'), 
			$formatic->get_field_config($f)
		);
		
		$type			= isset($param[0]) ? $param[0] : 'select';
		$target 		= isset($param[1]) ? $param[1] : 'parent';
		$ajax_script	= isset($param[1]) ? $param[2] : 'http://your.server/app';
		
		// create an empty select or multislect menu
		$attr = array('id' => $config['id']);
		$attr = isset($config['attr']) ? $attr+$config['attr'] : $attr;
		$attr_str = $formatic->field_attributes($attr);
		$field_value = $formatic->get_data($f); // will always return an array
		
		switch ($type)
		{
			case 'select' : case 'dropdown' :

				$r = call_user_func('form_dropdown', 
					$f,
					@is_array($config['options']) ? $config['options'] : array(),
					count($field_value)>0 ? $field_value : $config['default'],
					$attr_str
				);
				break;
			
			case 'multiselect' :

				$r = call_user_func('form_multiselect', 
					$f,
					@is_array($config['options']) ? $config['options'] : array(),
					count($field_value)>0 ? $field_value : $config['default'],
					$attr_str
				);	
				break;	
			break;	
		}
		
		$chosen = json_encode($field_value);

		$js = <<<JAVASCRIPT
		<script type="text/javascript">
		//<![CDATA[ 
			$(function() {
				$("select[name='{$target}']").selectChain({
			    	target: $("#{$config['id']}"),
			    	url: '{$ajax_script}',
				    type: 'post',
					data: { ajax: true },
					chosen: {$chosen}
			  	});
			});
		//]]>
		</script>
JAVASCRIPT;


		return $r.$js;
	}
}