<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Sample form field configuration
|--------------------------------------------------------------------------
*/

$config = array(
	
	'name' => array(
		'label'	 	=> 'Name',
		'rules'  	=> 'required|trim|min_length[4]|max_length[255]|xss_clean',
		'type'   	=> 'input',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'255', 'size'=>'30' )
	),

	'timezone' => array(
		'label'	 	=> 'Time zone (+-GMT)',
		'rules'  	=> 'required|trim|xss_clean|max_length[3]',
		'type'   	=> 'select',
		'options'	=> array(
					'-12' => '-12',
					'-11' => '-11',
					'-10' => '-10',
					'-9'  => '-9',
					'-8'  => '-8',
					'-7'  => '-7',
					'-6'  => '-6',
					'-5'  => '-5',
					'-4'  => '-4',
					'-3'  => '-3',
					'-2'  => '-2',
					'-1'  => '-1',
					'0'	  => '0',
					'+1'  => '+1',
					'+2'  => '+2',
					'+3'  => '+3',
					'+4'  => '+4',
					'+5'  => '+5',
					'+6'  => '+6',
					'+7'  => '+7',
					'+8'  => '+8',
					'+9'  => '+9',
					'+10' => '+10',
					'+11' => '+11',
					'+12' => '+12'
					),
		'default'	=> '+0',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'3' )
	),
	
	'map' => array(
		'label'	 	=> 'Search for your location',
		'rules'  	=> 'trim|xss_clean',
		'type'   	=> 'googlemap',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'11', 'size'=>'30' )
	),
	
	'show_marker' => array(
		'label'		=> 'Show map marker?',
		'rules'  	=> 'alpha|max_length[1]',
		'options' 	=> array( 'Y' => 'Yes', 'N' => 'No'),
		'type'   	=> 'radio',
		'default'	=> 'Y',
		'groups'	=> 'edit|add'
	),
	
	'description' => array(
		'label'	 	=> 'Description',
		'rules'  	=> 'trim|xss_clean',
		'type'   	=> 'tinymce',
		'groups'	=> 'edit|admin',
		'attr'		=> array( 'class' => 'txt', 'rows'=>'20', 'cols'=>'60' )
	),
	
	'contacts[]' => array(
		'label'		=> 'Contacts',	
		'rules'  	=> 'required|xss_clean',
		'type'   	=> 'compact_multiselect',
		'groups'	=> 'edit|add',
		'options' 	=> 'related_model[Formatic_model_example:get_contacts::0]',
	),
	
	'categories[]' => array(
		'label'		=> 'Categories',	
		'rules'  	=> 'xss_clean',
		'type'   	=> 'checkbox',
		'groups'	=> 'edit|add',
		'options' 	=> array( '1' => 'Category 1', '2' => 'Category 2'),
	),	
		
	'industries[]' => array(
		'label'	 	=> 'Industries',
		'rules'  	=> 'xss_clean',
		'type'   	=> 'alternate_multiselect',
		'options'	=> 'related_model[Formatic_model_example:get_contacts::0]',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'title' => '(please select)' )
	),
	
	'opening_times' => array(
		'label'		=> 'Opening times',
		'rules'  	=> 'xss_clean|max_length[9]',
		'type'   	=> 'jslider',
		'groups'	=> 'edit|add',
		'from'		=> '480',
  		'to' 		=> '1140',
  		'step' 		=> '15',
  		'dimension' => '',
  		'scale'		=> "'8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00'",
  		'limits' 	=> 'false',
  		'calculate'	=> 'function( value ){
    						var hours = Math.floor( value / 60 );
    						var mins = ( value - hours*60 );
    						return (hours < 10 ? "0"+hours : hours) + ":" + ( mins == 0 ? "00" : mins );
  						}',
		'default'	=> '480;480',
		'attr'		=> array('maxlength'=>'9', 'size'=>'9' )
	),
	
	'photo' => array(
		'label'	 	=> 'Photo',
		'rules'  	=> 'file_required|file_size_max[2048KB]|file_allowed_type[image]',
		'type'   	=> 'fm_image',
		'groups'	=> 'edit|add',
		'template'	=> 'forms/row_photo',
		'attr'		=> array( 'class' => 'file tooltip', 'maxlength'=>'255', 'size'=>'30', 'title'=>'File types: JPEG, GIF, PNG. Max file size: 2MB.'),
		'upload_location'  	=> 'uploads/images/',
		'default'  	=> 'logo_default.png',
	),	
	
	'file' => array(
		'label'		=> 'File',	
		'rules'  	=> 'file_size_max[3072KB]|file_allowed_type[document]',
		'type'   	=> 'upload',
		'groups'	=> 'edit',
		'template'	=> 'forms/row_file',
		'attr'		=> array( 'class' => 'file tooltip', 'maxlength'=>'255', 'size'=>'30', 'title'=>'File types: Abobe PDF or Microsoft Word (RTF, DOC, DOCX). Max file size: 3MB.'),
	),
	
	'date' => array(
		'label'		=> 'Date',
		'rules'  	=> 'callback_check_date',
		'type'   	=> 'datepicker',
		'class'		=> 'datepicker',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'10', 'size'=>'10' )
	),
	
	/*
	'recaptcha_response_field' => array(
		'label'	 	=> 'Confirmation code',
		'rules' 	=> 'trim|xss_clean|required|callback_check_recaptcha',
		'type'   	=> 'recaptcha',
		'template'	=> 'forms/row_recaptcha',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'id'=>'recaptcha_response_field', 'maxlength'=>'100', 'size'=>'30' )
	),
	*/
	
	'captcha' => array(
		'label'	 	=> 'Captcha code',
		'rules'  	=> 'trim|xss_clean|required|callback_check_captcha',
		'type'   	=> 'fm_captcha',
		'template'	=> 'forms/row_captcha',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'id'=>'captcha', 'maxlength'=>'8', 'size'=>'30' )
	),
	
	'additional_field_1' => array(
		'label'	 	=> 'Additional 1',
		'rules'  	=> 'trim|min_length[4]|max_length[255]|xss_clean',
		'type'   	=> 'input',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'255', 'size'=>'30' )
	),
	
	'additional_field_2' => array(
		'label'	 	=> 'Additional 2',
		'rules'  	=> 'trim|min_length[4]|max_length[255]|xss_clean',
		'type'   	=> 'input',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'255', 'size'=>'30' )
	),
	
);

/* End of file example.php */
/* Location: ./application/formatic/fields/example.php */