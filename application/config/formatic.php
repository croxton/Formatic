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
|	['fields_config'] 			The directory where you wish to store form field schema files
|	['row_tpl'] 				The default form field row template
|	['row_options_tpl'] 		The default radio/checkbox options row template
|	['outer_options_tpl'] 		The default radio/checkbox outer template (encloses a group of options)
|	['error_row_tpl'] 			The default error row template
|	['error_outer_tpl'] 		The default error outer template
|	['fieldset_tpl'] 			The default fieldset template
|	['css_error_class'] 		The CSS error class applied to error fields
|	['css_id_prefix']			String to prefix the CSS id for each field (helps to avoid CSS conflicts)
|	['required_field']			String to replace into the row_tpl to indicate a required field

|	['field_option_plugins'] 	The plugin directory where you want to store field option generation functions
|	['field_type_plugins'] 		The plugin directory where you want to store field type generation functions
|	['field_callbacks'] 		The plugin directory where you want to store field callback functions
|	['display_widgets'] 		The plugin directory where you want to store display widgets
*/

$config['fields_config'] 		= 'config/fields';
$config['row_tpl'] 				= 'forms/row';
$config['row_options_tpl'] 		= 'forms/row_options';
$config['outer_options_tpl'] 	= 'forms/outer_options';
$config['row_error_tpl'] 		= 'forms/row_error';
$config['outer_error_tpl'] 		= 'forms/outer_error';
$config['fieldset_tpl'] 		= 'forms/fieldset';
$config['css_error_class'] 		= 'error';
$config['css_id_prefix'] 		= 'frm-';
$config['required_field'] 		= '*';

$config['field_option_plugins'] = 'field_options';
$config['field_type_plugins'] 	= 'field_types';
$config['field_callbacks'] 		= 'field_callbacks';
$config['display_widgets'] 		= 'display_widgets';

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
							array('tiny_mce/tiny_mce.js','', FALSE, FALSE)
					),	
		'editor_css' => 'wysiwyg.css',
		
		'plugins'	=> 'safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template',
		
		'theme'		=> 'advanced',
		
		'options'	=>'
					// Theme options
					theme_advanced_blockformats : "p,h1,h2,h3,h4,h5,h6,code",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resize_horizontal : false,
					theme_advanced_resizing : true,

					theme_advanced_buttons1 : "undo,redo,|,bold,italic,underline,strikethrough,sub,sup,|,bullist,numlist,|,outdent,indent,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,iespell",
					
					theme_advanced_buttons2 : "formatselect,styleselect,|,link,unlink,anchor,image,media,charmap,|,print,|,removeformat, visualaid, cleanup,code",
					
					theme_advanced_buttons3 : "search,cut,copy,paste,pastetext,pasteword,|,tablecontrols,|,fullscreen",
					
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
$config['googlemap_api_key'] = 'Your-key-here';

$config['googlemap'] = array(
		
		'js' 		=> array(
							array('http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=' . $config['googlemap_api_key'] . '&amp;sensor=false','', FALSE, FALSE),
							array('http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=' . $config['googlemap_api_key'],'', FALSE, FALSE),
							array('googlemap.js','', FALSE, FALSE)
					),		
		'css'		=> 	array('googlemap.css','screen','', FALSE, FALSE),
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
		'public_key'  => 'Your-key-here',
		'private_key' => 'Your-key-here',
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
		'captcha_fonts_path' 		=> 'captcha/fonts/5.ttf',
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
		'default_thumb'  	=> 'default.jpg',
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
							array('jquery.select-chain.js','', FALSE, FALSE)
					)
		);
		
/*
|--------------------------------------------------------------------------
| Datepicker Field Type Configuration
|
| Requires jQuery 1.3.x+
|--------------------------------------------------------------------------
*/
$config['datepicker'] = array(
		'js' 		=> array(
							array('date.js', '', FALSE, FALSE),
							array('jquery.datePicker.js', '', FALSE, FALSE)
					),		
		'css'		=> 	array('jquery.datePicker.css', 'screen', '', FALSE, FALSE)
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
							array('currency/jquery.formatCurrency.min.js', '', FALSE, FALSE)
					),
		'i18n'			 => 'currency/i18n/',
		'region'		 => 'en-GB',
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
							array('jslider/jquery.dependClass.js', '', FALSE, FALSE),
							array('jslider/jquery.slider.js', '', FALSE, FALSE)
					),		
		'css'		=> 	array(
							array('jslider/jslider.css', 'screen', '', FALSE, FALSE),
							array('jslider/jslider.ie6.css', 'screen', '', FALSE, FALSE, 'IE'),
							array('jslider/jslider.plastic.css', 'screen', '', FALSE, FALSE),
							array('jslider/jslider.plastic.ie6.css', 'screen', '', FALSE, FALSE, 'IE'),
							
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
							array('jquery.bgiframe.min.js','', FALSE, FALSE),
							array('jquery.multiSelect.js','', FALSE, FALSE),
					),		
		'css'		=> 	array('jquery.multiSelect.css','screen','', FALSE, FALSE),
		'template'	=>	'forms/row'
		);
		
/*
|--------------------------------------------------------------------------
| Alternate Multi Select Field Type Configuration
|
| Requires jQuery 1.3.x+ and jQuery UI
|--------------------------------------------------------------------------
*/

$config['alternate_multiselect'] = array(
		'js' 		=> array(
							array('jquery.asmselect.js','', FALSE, FALSE),
					),		
		'css'		=> 	array('jquery.asmselect.css','screen','', FALSE, FALSE),
		'template'	=>	'forms/row'
		);
/* End of file formatic.php */
/* Location: ./application/config/formatic.php */