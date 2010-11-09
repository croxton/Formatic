<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Datepicker class
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

class Datepicker extends Formatic_plugin {
	
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
			$formatic->get_plugin_config(__CLASS__), 
			$formatic->get_field_config($f)
		);
		
		// create a standard text input
		$data = array(
						'name'        => $f,
              			'id'          => $config['id'],
              			'value'       => set_value($f, isset($_POST[$f]) ? $_POST[$f] : $config['default'])
					);			
		$data = isset($config['attr']) ? $data + $config['attr'] : $data;
		$r = call_user_func('form_input', $data);
		
		$config['startDate'] = isset($config['startDate']) ? $config['startDate'] : '01/01/1750';
		
		// add the js call
		$js = <<<JAVASCRIPT
		<script type="text/javascript">
		//<![CDATA[ 
			$(function() {
				$('#{$config['id']}').datePicker({
					startDate:'{$config['startDate']}'
				});
				$('.content-sidebar #{$config['id']}').dpSetPosition($.dpConst.POS_TOP, $.dpConst.POS_RIGHT);
			});
		//]]>
		</script>
JAVASCRIPT;

		return $r.$js;
	}
}