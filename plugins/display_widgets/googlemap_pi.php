<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Googlemap class
 *
 * Formatic display widget
 *
 * Outputs a Google Map
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */
class Googlemap extends Formatic_plugin {
	
	protected static $init = TRUE;
	
    function run($f, $param) {
	
		$formatic =& $this->CI->formatic;
		
		$param = array_merge(
			$formatic->get_plugin_config('googlemap'), 
			$param
		);
		
		// map options
		if (!isset($param['ui_zoom'])) 		 	$param['ui_zoom'] 		  = 12;
		if (!isset($param['ui_scale'])) 		$param['ui_scale'] 		  = 1;
		if (!isset($param['ui_overview']))  	$param['ui_overview'] 	  = 1;
		if (!isset($param['ui_map_type']))     	$param['ui_map_type']     = 1;
		if (!isset($param['map_drag'])) 		$param['map_drag'] 		  = 1;
		if (!isset($param['map_click_zoom']))  	$param['map_click_zoom']  = 1;
		if (!isset($param['map_scroll_zoom'])) 	$param['map_scroll_zoom'] = '';
		if (!isset($param['pin_drag']))  		$param['pin_drag'] 		  = 1;
		if (!isset($param['background'])) 	 	$param['background']	  = '';
		if (!isset($param['map_types'])) 		$param['map_types']       = 'normal|satellite|hybrid|physical';
	
		// build the inline javascript
		$param['js'] = "\n".'<script type="text/javascript">'."\n";

		if (self::$init)
		{
		$param['js'] .=<<<JAVASCRIPT
			if (typeof(CI_GMap) == 'undefined' || ( ! CI_GMap instanceof Object)) CI_GMap = new Object();
			if (typeof(CI_GMap.google_maps) == 'undefined' || ( ! CI_GMap.google_maps instanceof Array)) CI_GMap.google_maps = new Array();
JAVASCRIPT;

			// make sure this doesn't run again
			self::$init = FALSE;
		}
		$param['js'] .= <<<JAVASCRIPT
			CI_GMap.google_maps.push({
				init : {
			   		api_key: '{$param['api_key']}',
			   		map_field: '',
			   		map_container: '{$param['map_id']}',
			   		map_lat: {$param['map_lat']},
			   		map_lng: {$param['map_lng']},
			   		map_zoom: {$param['map_zoom']},
			   		pin_lat: {$param['map_lat']},
			   		pin_lng: {$param['map_lng']},
			   		address_input: '',
			   		address_submit: ''
				},
				options : {
					ui_zoom: '{$param['ui_zoom']}',
					ui_scale: '{$param['ui_scale']}',
					ui_overview: '{$param['ui_overview']}',
					ui_map_type: '{$param['ui_map_type']}',
					map_drag: '{$param['map_drag']}',
					map_click_zoom: '{$param['map_click_zoom']}',
					map_scroll_zoom: '{$param['map_scroll_zoom']}',
					pin_drag: '{$param['pin_drag']}',
					background: '{$param['background']}',
					map_types: '{$param['map_types']}'
				}
			});
			</script>
JAVASCRIPT;

		// render and return
		return $this->CI->load->view($param['template'], $param, true);
    }
} 