<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Compact MultiSelect class
 *
 * Formatic field plugin
 *
 * Implements the multiSelect jquery plugin  
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */

class Compact_multiselect extends Formatic_plugin {
	
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
			$formatic->get_plugin_config('Compact_multiselect'), 
			$formatic->get_field_config($f)
		);
		
		// create a multiselect menu
		// note: need to ensure id is same as name without '[]'
		$css_id = str_replace('[]', '', trim($f));
		$attr = array('id' => $css_id);
		$attr = isset($config['attr']) ? $attr+$config['attr'] : $attr;
		$attr_str = $formatic->field_attributes($attr);
		
		if (count($_POST)>0)
		{	
			$field_value = $formatic->get_data($f);
		} 
		else
		{
			$field_value = $config['default'];
		}

		$r = call_user_func('form_multiselect', 
			$f,
			@is_array($config['options']) ? $config['options'] : $formatic->load_field_plugin('option', $config['options']),
			$field_value,
			$attr_str
		);	

		$js = <<<JAVASCRIPT
		<script type="text/javascript">
		//<![CDATA[ 
			$(function() {
			    $("#{$css_id}").multiSelect();
			});
		//]]>
		</script>
JAVASCRIPT;

		return $r.$js;
	}
}