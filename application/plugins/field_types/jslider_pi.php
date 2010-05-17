<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Jslider class
 *
 * Formatic field plugin
 *
 * JQuery Slider form field
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */

class Jslider extends Formatic_plugin {
	
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
			$formatic->get_plugin_config('jslider'), 
			$formatic->get_field_config($f)
		);

		// load the skin assets
		/*
		$formatic->load_asset('css', array($config['skin_dir'].'jslider.'.$config['skin'].'.css', '', FALSE, FALSE));
		
		// asset for IE6 support
		$formatic->load_asset('css', array($config['skin_dir'].'jslider.'.$config['skin'].'.css', '', FALSE, FALSE, 'IE'));
		*/
		
		// create a standard text input
		$data = array(
						'name'        => $f,
              			'id'          => $config['id'],
              			'value'       => set_value($f, isset($_POST[$f]) ? $_POST[$f] : $config['default'])
					);			
		$data = isset($config['attr']) ? $data + $config['attr'] : $data;
		$r = call_user_func('form_input', $data);
		
		// add the js call	
		$js = '
		<script type="text/javascript">
		//<![CDATA[ 
			$("#'.$config['id'].'").slider({ 
				from: '.$config['from'].', 
				to: '.$config['to'].', 
				step: '.$config['step'].', 
				round: '.$config['round'].', 
				limits: '.$config['limits'].', 
				step: '.$config['step'].', 
				dimension: "'.$config['dimension'].'", 
				skin: "'.$config['skin'].'",'.
				($config['calculate'] !== '' ? "calculate: {$config['calculate']}," : "").
				($config['onstatechange'] !== '' ? "onstatechange: {$config['onstatechange']}," : "").
				($config['callback'] !== '' ? "callback: {$config['callback']}," : "").'
				scale: ['.$config['scale'].'], 
				heterogeneity: ['.$config['heterogeneity'].'] 
			});
		//]]>
		</script>
		';

		return $r.$js;
	}
}