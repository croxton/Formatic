<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Sample form field configuration
|--------------------------------------------------------------------------
*/

$config = array(
	
	'office_name' => array(
		'label'	 	=> 'Office name',
		'rules'  	=> 'trim|required|min_length[4]|max_length[255]|xss_clean',
		'type'   	=> 'input',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'255', 'size'=>'30' )
	),	
	
	'address1' => array(
		'label'	 	=> 'Address 1',
		'rules'  	=> 'trim|required|min_length[4]|max_length[255]|xss_clean',
		'type'   	=> 'input',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'255', 'size'=>'30' )
	),	
	
	'address2' => array(
		'label'	 	=> 'Address 2',
		'rules'  	=> 'trim|min_length[4]|max_length[255]|xss_clean',
		'type'   	=> 'input',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'255', 'size'=>'30' )
	),
	
	'city' => array(
		'label'	 	=> 'Town / City',
		'rules'  	=> 'trim|required|min_length[4]|max_length[255]|xss_clean',
		'type'   	=> 'input',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'255', 'size'=>'30' )
	),
	
	'state' => array(
		'label'	 	=> 'State / County / Province',
		'rules'  	=> 'trim|min_length[4]|max_length[255]|xss_clean',
		'type'   	=> 'input',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'255', 'size'=>'30' )
	),
	
	'zip' => array(
		'label'	 	=> 'Zip / Postcode',
		'rules'  	=> 'trim|min_length[4]|max_length[255]|xss_clean',
		'type'   	=> 'input',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'255', 'size'=>'30' )
	),
	
	'country_iso' => array(
		'label'	 	=> 'Country',
		'rules'  	=> 'alpha|trim|min_length[2]|max_length[2]|xss_clean',
		'type'   	=> 'select',
		'options'	=> 'related[countries:iso:country_name]',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'2')
	),
	
	'region_id' => array(
		'label'	 	=> 'Geographical Region',
		'rules'  	=> 'integer|required|trim|xss_clean',
		'type'   	=> 'select',
		'options'	=> 'related[regions:id:region_name]',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'11' )
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
					'-3'  => '-2',
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
	
	'location' => array(
		'label'	 	=> 'Search for your location',
		'rules'  	=> 'trim|xss_clean',
		'type'   	=> 'googlemap',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'11', 'size'=>'30' )
	),
	
	'show_marker' => array(
		'label'		=> 'Show map marker?',
		'rules'  	=> 'alpha|max_length[1]',
		'options' 	=> array( 'Yes' => 'Y', 'No' => 'N'),
		'type'   	=> 'radio',
		'default'	=> 'Y',
		'groups'	=> 'edit|add'
	),
	
	'phone' => array(
		'label'	 	=> 'Telephone',
		'rules'  	=> 'trim|required|phonenumber|max_length[100]|xss_clean',
		'type'   	=> 'input',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'100', 'size'=>'30' )
	),
	
	'fax' => array(
		'label'	 	=> 'Fax',
		'rules'  	=> 'trim|phonenumber|max_length[100]|xss_clean',
		'type'   	=> 'input',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'100', 'size'=>'30' )
	),
	
	'email'	=> array(
		'label'	 	=> 'Email',
		'rules'  	=> 'trim|required|valid_email|xss_clean',
		'type'   	=> 'input',
		'groups'	=> 'edit|add',
		'attr'		=> array( 'class' => 'txt', 'maxlength'=>'100', 'size'=>'30' )
	),	
	
	'contacts[]' => array(
		'label'		=> 'Principle contact 1',	
		'rules'  	=> 'required|xss_clean',
		'type'   	=> 'alternate_multiselect',
		'groups'	=> 'edit',
		'options' 	=> 'related_model[user_office:list_users_in_office:(please select)]',
	),
	
	'opening_times' => array(
		'label'		=> 'Monday',
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
	
);

/* End of file example.php */
/* Location: ./application/config/fields/example.php */