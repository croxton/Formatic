<?php	if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Formatic Configuration
| -------------------------------------------------------------------

| Global configuration settings that apply to all Formatic forms.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['fields_dir'] 				The directory where you store form field schema files
|	['plugins_dir'] 			The directory where you store Formatic plugin files
|	['field_options_dir'] 		The directory where you want to store field option plugins
|	['field_types_dir'] 		The directory where you want to store field type plugins
|	['field_callbacks_dir'] 	The directory where you want to store field callback plugins
|	['display_widgets_dir'] 	The directory where you want to store display widget plugins

|	['row_tpl'] 				The default form field row template
|	['row_options_tpl'] 		The default radio/checkbox options row template
|	['outer_options_tpl'] 		The default radio/checkbox outer template (encloses a group of options)
|	['error_row_tpl'] 			The default error row template
|	['error_outer_tpl'] 		The default error outer template
|	['fieldset_tpl'] 			The default fieldset template
|	['css_error_class'] 		The CSS error class applied to error fields
|	['css_field_id_prefix']		String to prefix the CSS id for each form control (helps to avoid CSS conflicts)
|	['css_label_id_prefix']		String to prefix the CSS id for each wrapping label (helps to avoid CSS conflicts)
|	['required_field']			String to replace into the row_tpl to indicate a required field

|	['asset_bridge']			Asset library bridge class, for loading js/css assets used by plugins.
|	['consistent_options']		Boolean. Use a consistent format for options arrays supplied for any form control: 
								array(value1 => name1, value2 => name2).
								This allows you to easily switch between field controls types, and is recommended.
								Set to false to use the same formats that apply to individual controls in the CI Form Helper
*/

$config['fields_dir'] 			= 'formatic/fields';
$config['plugins_dir'] 			= 'formatic/plugins';
$config['field_options_dir'] 	= 'field_options';
$config['field_types_dir'] 		= 'field_types';
$config['field_callbacks_dir'] 	= 'field_callbacks';
$config['display_widgets_dir'] 	= 'display_widgets';

$config['row_tpl'] 				= 'forms/row';
$config['row_options_tpl'] 		= 'forms/row_options';
$config['outer_radio_tpl'] 		= 'forms/outer_radio';
$config['outer_checkbox_tpl'] 	= 'forms/outer_checkbox';
$config['row_error_tpl'] 		= 'forms/row_error';
$config['outer_error_tpl'] 		= 'forms/outer_error';
$config['inline_error_tpl'] 	= 'forms/inline_error';
$config['fieldset_tpl'] 		= 'forms/fieldset';
$config['css_error_class'] 		= 'error';
$config['css_field_id_prefix']	= 'frm-';
$config['css_label_id_prefix']	= 'lbl-';
$config['required_field'] 		= '*';

$config['asset_bridge']			= 'Formatic_stuff_bridge';
$config['consistent_options']	= true;


/*
| -------------------------------------------------------------------
| Formatic plugins configuration
| -------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| TinyMCE Field Type Configuration
|
| Requires jQuery 1.3.x+
|--------------------------------------------------------------------------
*/
$config['tinymce'] = array(
		
		// assets that need to be loaded - can pass a string or an array of parameters
		// see ./application/config/carabiner.php
		'js' 		=> array(
							array('tiny_mce/tiny_mce.js')
					),	
		'editor_css' => '/_assets/css/wysiwyg.css',
		
		'plugins'	=> 'safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template',
		
		'theme'		=> 'advanced',
		
		'options'	=>'
					// Theme options
					theme_advanced_blockformats : "p,h2,h3,h4,code",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resize_horizontal : false,
					theme_advanced_resizing : true,

					theme_advanced_buttons1 : "bold,italic,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,iespell,removeformat,cleanup,code,fullscreen",
					
					theme_advanced_buttons2 : "formatselect,|,link,unlink,image,charmap,|,search,cut,copy,paste,pastetext,pasteword",
					
					theme_advanced_buttons3 : "",
					
					invalid_elements: "span,font",
					
					// Drop lists for link/image/media/template dialogs
					template_external_list_url : "lists/template_list.js",
					external_link_list_url : "lists/link_list.js",
					external_image_list_url : "lists/image_list.js",
					media_external_list_url : "lists/media_list.js"
					',
		);

/*
|--------------------------------------------------------------------------
| GoogleMap Field Type Configuration
|
| Requires jQuery 1.3.x+
|--------------------------------------------------------------------------
*/
$config['googlemap_api_key'] = 'your-key-here';

$config['googlemap'] = array(
		
		'js' 		=> array(
							// note: access to map api must be over http://
							array('http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=' . $config['googlemap_api_key'] . '&amp;sensor=false'),
							// note: access to Google's AJAX API can be over https://
							array('https://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=' . $config['googlemap_api_key']),
							array('googlemap.js')
					),		
		'css'		=> 	array('googlemap.css','screen'),
		'template'	=>	'forms/row_googlemap',
		
		'api_key'	=> $config['googlemap_api_key'],
		'map_lat'	=> 39.368,
		'map_lng'	=> -1.406,
		'map_zoom'	=> 12,
		'map_width'	=> 472,
		'map_height'=> 360
		);

/*
|--------------------------------------------------------------------------
| Recaptcha Field Type Configuration
|--------------------------------------------------------------------------
*/
$config['recaptcha'] = array(
		'css'		  =>  array('recaptcha.css', 'screen'),
		'public_key'  => 'your-key-here',
		'private_key' => 'your-key-here',
		'use_ssl'	  => true
		);

/*
|--------------------------------------------------------------------------
| fm_captcha Field Type Configuration
|
| 'captcha_path' = Directory where the catpcha will be created.
| 'captcha_fonts_path' = Font in this directory will be used when creating captcha.
| 'captcha_font_size' = Font size when writing text to captcha. Leave blank for random font size.
| 'captcha_grid' = Show grid in created captcha.
| 'captcha_expire' = Life time of created captcha before expired, default is 3 minutes (180 seconds).
| 'captcha_case_sensitive' = Captcha case sensitive or not.
|--------------------------------------------------------------------------
*/
$config['fm_captcha'] = array(		
		'captcha_path' 				=> 'captcha/',
		'captcha_fonts_path' 		=> 'system/fonts/texb.ttf',
		'captcha_width'				=> 200,
		'captcha_height'			=> 50,
		'captcha_font_size' 		=> 14,
		'captcha_grid' 				=> FALSE,
		'captcha_expire' 			=> 180,
		'captcha_case_sensitive' 	=> FALSE,
		);		

/*
|--------------------------------------------------------------------------
| fm_image Field Type Configuration
|
| Requires jQuery 1.3.x+
|--------------------------------------------------------------------------
*/
$config['fm_image'] = array(
		'upload_location'  	=> 'uploads/images/',
		'default_thumb'  	=> 'default_user.jpg',
		'preview_class' 	=> 'preview',
		);
		
/*
|--------------------------------------------------------------------------
| Chained Select Field Type Configuration
|
| Requires jQuery 1.3.x+
|--------------------------------------------------------------------------
*/
$config['chained_select'] = array(
		'js' 		=> array(
							array('jquery.select-chain.js')
					)
		);
		
/*
|--------------------------------------------------------------------------
| Datepicker Field Type Configuration
|
| Requires jQuery 1.3.x+
|--------------------------------------------------------------------------
*/
// Format string must include dd, mm and yyyy (can be any order)
// For localization of calendar pop-up include the desired date_LOCAL.js file: 
// https://github.com/vitch/jquery-methods/

$config['datepicker'] = array(
		'js' 		=> array(
							array('date.js'),
							array('jquery.datePicker.js')
					),		
		'css'		=> 	array('jquery.datePicker.css', 'screen'),
		'format'	=> 'mm/dd/yyyy'
		);

/*
|--------------------------------------------------------------------------
| Number format Field Type Configuration
|
| Requires jQuery 1.3.x+
|--------------------------------------------------------------------------
*/
$config['number_format'] = array(
		'js' 		=> array(
							array('currency/jquery.formatCurrency.min.js')
					),
		'i18n'			 => 'currency/i18n/',
		'region'		 => 'en-US',
		'decimal_places' => 0
		);
		
/*
|--------------------------------------------------------------------------
| JSlider Field Type Configuration
|
| Requires jQuery 1.4.x+
|--------------------------------------------------------------------------
*/
$config['jslider'] = array(
		'js' 		=> array(
							array('jslider/jquery.dependClass.js'),
							array('jslider/jquery.slider.js')
					),		
		'css'		=> 	array(
							array('jslider/jslider.css', 'screen'),
							array('jslider/jslider.ie6.css', 'screen', 'IE6'),
							array('jslider/jslider.plastic.css', 'screen'),
							array('jslider/jslider.plastic.ie6.css', 'screen', 'IE6'),
							
						),	
		'skin_dir'	=> 'jslider/',		
		'skin'		=> 'plastic',
		'from'		=> '0',
		'to'		=> '100',
		'step'		=> '1',
		'round'		=> '0',
		'limits'	=> 'true',
		'dimension'	=> '&nbsp;%',
		'scale'		=> "",
		'heterogeneity'	=>	"",
		'calculate'		=> '',
		'onstatechange'	=> '',
		'callback'		=> ''
		);
				
/*
|--------------------------------------------------------------------------
| Many Multi Select Field Type Configuration
|
| Requires jQuery 1.3.x+
|--------------------------------------------------------------------------
*/

$config['compact_multiselect'] = array(
		'js' 		=> array(
							array('jquery.bgiframe.min.js'),
							array('jquery.multiSelect.js'),
					),		
		'css'		=> 	array('jquery.multiSelect.css','screen'),
		'template'	=>	'forms/row'
		);
		
/*
|--------------------------------------------------------------------------
| Alternate Multi Select Field Type Configuration
|
| Requires jQuery 1.3.x+ and jQuery UI core
|--------------------------------------------------------------------------
*/

$config['alternate_multiselect'] = array(
		'js' 		=> array(
							array('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js'),
							array('jquery.asmselect.js'),
					),		
		'css'		=> 	array('jquery.asmselect.css','screen'),
		'template'	=>	'forms/row'
		);
/* End of file formatic.php */
/* Location: ./application/config/formatic.php */