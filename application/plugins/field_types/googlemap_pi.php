<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Googlemap Class
 *
 * Formatic field plugin
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */

class Googlemap extends Formatic_plugin {
	
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
		
		$r = $js ='';
	
		// get config data for the plugin and field and merge
		// so that field config values supercede plugin ones
		$config = array_merge(
			$formatic->get_plugin_config('googlemap'), 
			$formatic->get_field_config($f)
		);
	
		// field data value order of precedence: config values, default value (populated from view vars), $_POSTed value
		$field_data = isset($config['map_lat']) && isset($config['map_lng']) && isset($config['map_zoom']) ? $config['map_lat'].','.$config['map_lng'].','.$config['map_zoom'] : '';
		$field_data = $config['default'] !== '' ? $config['default'] : $field_data;
		$field_data = isset($_POST[$f]) ? $_POST[$f] : $field_data;
	
		$map_data = explode(',', $field_data);
		
		$map = array();
		
		if (count($map_data) >= 3)
		{
			$map = array(
				'map_lat' 	=> $map_data[0],
		      	'map_lng' 	=> $map_data[1],
		      	'map_zoom' 	=> $map_data[2],
		      	'pin_lat' 	=> isset($map_data[3]) ? $map_data[3] : $map_data[0],
		  		'pin_lng' 	=> isset($map_data[4]) ? $map_data[4] : $map_data[1]
			);
		}

		// add variables to the view, namespaced to this field [$f]
		// these will be replaced into the row_googlemap template by $formatic->make_fields()
		$view_data = array(
			"map_id" 				=> $f . '_container',
			"map_class"				=> (isset($config['class']) && $config['class'] != '') ? $config['class'] : 'googlemap',
			"map_width"				=> $config['map_width'],
			"map_height"			=> $config['map_height'],
			"map_find_class"		=> 'googlemap-address-lookup',
			"address_input_id"		=> $f . '_address_input',
			"address_input_class"	=> 'googlemap-address-input',
			"address_submit_id"		=> $f . '_address_submit',
			"address_submit_class"	=> 'googlemap-address-submit',
			"address_submit_value"	=> $formatic->lang->line('googlemap_find'),	
			"map_noscript"			=> $formatic->lang->line('googlemap_noscript'),		
		);
	
		// create a hidden field to store the map coordinates
		$r = form_hidden($f, $field_data);
		
		// set values in the googlemap row template that wraps this field
		$formatic->set_template_config($f, $view_data);

		// map options
		$ui_zoom 			= isset($config['ui_zoom']) 		? $config['ui_zoom'] 			: 12;
		$ui_scale 			= isset($config['ui_scale']) 		? $config['ui_scale'] 			: 1;
		$ui_overview 		= isset($config['ui_overview']) 	? $config['ui_overview'] 		: 1;
		$ui_map_type      	= isset($config['ui_map_type'])    	? $config['ui_map_type']   		: 1;
		$map_drag 			= isset($config['map_drag']) 		? $config['map_drag'] 			: 1;
		$map_click_zoom 	= isset($config['map_click_zoom']) 	? $config['map_click_zoom']		: 1;
		$map_scroll_zoom	= isset($config['map_scroll_zoom'])	? $config['map_scroll_zoom']	: '';
		$pin_drag 			= isset($config['pin_drag']) 		? $config['pin_drag'] 			: 1;
		$background			= isset($config['background'])		? $config['background']			: '';
		$map_types        	= isset($config['map_types'])		? $config['map_types']       	: 'normal|satellite|hybrid|physical';

		// build the javascript
		$js = "\n".'<script type="text/javascript">'."\n";

		if (self::$init)
		{

		$js .=<<<JAVASCRIPT
			if (typeof(CI_GMap) == 'undefined' || ( ! CI_GMap instanceof Object)) CI_GMap = new Object();
			if (typeof(CI_GMap.google_maps) == 'undefined' || ( ! CI_GMap.google_maps instanceof Array)) CI_GMap.google_maps = new Array();
JAVASCRIPT;

			// make sure this doesn't run again
			self::$init = FALSE;
		}

		$js .= <<<JAVASCRIPT
			CI_GMap.google_maps.push({
				init : {
			   		api_key: '{$config['api_key']}',
			   		map_field: '{$f}',
			   		map_container: '{$view_data['map_id']}',
			   		map_lat: {$map['map_lat']},
			   		map_lng: {$map['map_lng']},
			   		map_zoom: {$map['map_zoom']},
			   		pin_lat: {$map['pin_lat']},
			   		pin_lng: {$map['pin_lng']},
			   		address_input: '{$view_data['address_input_id']}',
			   		address_submit: '{$view_data['address_submit_id']}'
				},
				options : {
					ui_zoom: '{$ui_zoom}',
					ui_scale: '{$ui_scale}',
					ui_overview: '{$ui_overview}',
					ui_map_type: '{$ui_map_type}',
					map_drag: '{$map_drag}',
					map_click_zoom: '{$map_click_zoom}',
					map_scroll_zoom: '{$map_scroll_zoom}',
					pin_drag: '{$pin_drag}',
					background: '{$background}',
					map_types: '{$map_types}'
				}
			});
			</script>
JAVASCRIPT;
		
		return $r.$js;
	}
}