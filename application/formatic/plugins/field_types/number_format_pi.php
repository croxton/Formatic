<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Number format class
 *
 * Formatic field plugin
 *
 * Populate a select field dynamically via AJAX, based on the 
 *
 * @package		Formatic
 * @license 	MIT Licence (http://opensource.org/licenses/mit-license.php) 
 * @author  	Mark Croxton
 * @copyright  	Mark Croxton, hallmarkdesign (http://www.hallmark-design.co.uk)
 * @version 	1.0.0
 */

class Number_format extends Formatic_plugin {
	
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
			$formatic->get_plugin_config('number_format'), 
			$formatic->get_field_config($f)
		);

		// currency symbol
		$region = '';
		
		if (isset($config['symbol']))
		{
			$region = "symbol: '{$config['symbol']}',";
		}
		elseif(isset($config['region']))
		{
			// load the requested currency asset for this field
			$formatic->load_asset('js', array($config['i18n'].'jquery.formatCurrency.'.$config['region'].'.js'));
			$region = "region: '{$config['region']}',";	
		}
		
		// decimal places
		$decimal_places = isset($config['decimal_places']) ? $config['decimal_places'] : 2;
		
		// create a standard text input
		$data = array(
						'name'        => $f,
              			'id'          => $config['id'],
              			'value'       => set_value($f, isset($_POST[$f]) ? $_POST[$f] : $config['default'])
					);			
		$data = isset($config['attr']) ? $data + $config['attr'] : $data;
		$r = call_user_func('form_input', $data);
		
		// add the js call	
		$js = <<<JAVASCRIPT
		<script type="text/javascript">
		//<![CDATA[ 
			
			$(function() {	
				
				// onload
				$('#{$config['id']}').formatCurrency({ {$region} colorize: true, negativeFormat: '-%s%n', roundToDecimalPlace: {$decimal_places} });
				
				// capture form submit
				$('#{$config['id']}').closest('form').submit(function() {
					// convert number to float for database use
					$('#{$config['id']}').val($('#{$config['id']}').asNumber());
				});
				// Format while typing, limit to 2 decimal places
				$('#{$config['id']}').blur(function() {
					$(this).formatCurrency({ {$region} colorize: true, negativeFormat: '-%s%n', roundToDecimalPlace: {$decimal_places} });
				})
				.keyup(function(e) {
					var e = window.event || e;
					var keyUnicode = e.charCode || e.keyCode;
					if (e !== undefined) {
						switch (keyUnicode) {
							case 27: this.value = ''; break; // Esc
							case 37: break; // cursor left
							case 38: break; // cursor up
							case 39: break; // cursor right
							case 40: break; // cursor down
							case 78: break; // N (Opera 9.63+ maps the "." from the number key section to the "N" key too!) (See: http://unixpapa.com/js/key.html search for ". Del")
							case 110: break; // . number block (Opera 9.63+ maps the "." from the number block to the "N" key (78) !!!)
							case 190: break; // .
							default: $(this).formatCurrency({ {$region} colorize: true, negativeFormat: '-%s%n', roundToDecimalPlace: -1 });
						}
					}
				});		
			});
		//]]>
		</script>
JAVASCRIPT;

		return $r.$js;
	}
}