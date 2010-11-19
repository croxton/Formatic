<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formatic_example extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		// load the 'example' field configuration file
		// typically this file should be named after the controller
		$this->formatic->load('example', TRUE);
	}

	function index()
	{
		$view_data = array();
		
		// load form field config file 'example', and get a subset of fields in the group 'edit'
		// (leave second parameter blank to get all fields)
		$this->formatic->form('example', 'edit');
		
		/* --------------------------------------------------------------------
		// you can also load an array of fields directly, eg: 
		
		$fields = array(
			'not_used' => array(
				'label'	 	=> 'Name',
				'rules'  	=> 'trim|min_length[4]|max_length[255]|xss_clean',
				'type'   	=> 'input',
				'groups'	=> 'edit|add',
				'attr'		=> array( 'class' => 'txt', 'maxlength'=>'255', 'size'=>'30' )
			)
		);
		$this->formatic->form($fields);
		*/
		
		/* --------------------------------------------------------------------
		// or you can also build a custom $fields array from loaded config file(s), e.g.:
		
		// load a config file
		$this->formatic->load('example', TRUE);
		
		// get a subset of fields (tagged with 'edit') from the 'example' config file
		$fields = $this->formatic->group('edit', 'example');
		
		// add one or more fields individually
		$fields += $this->formatic->fields('additional_field_1', 'example');
		$fields += $this->formatic->fields(array('additional_field_1', 'additional_field_2'), 'example');

		// set up the form
		$this->formatic->form($fields);
		-------------------------------------------------------------------- */
		
		// run validation
		if ($this->formatic->validate()) 
		{
			// success - get all the submitted data...
			$view_data['valid_data'] = $this->formatic->get_data();
			
			/* --------------------------------------------------------------------
			// ... or get individual fields like this...
			$subset  = $this->formatic->get_data('field');
			
			// ...or get an array of fields...
			$subset  = $this->formatic->get_data('field1', 'field2');
			
			//...or get a tagged group of fields like this
			$subset  = $this->formatic->get_data('', 'group1'));
			
			//...or get multiple tagged groups of fields like this
			$subset  = $this->formatic->get_data('', array('group1', 'group2'));
			-------------------------------------------------------------------- */
			
			/* --------------------------------------------------------------------
			// create a custom error message for a field on the fly
			
			// e.g.: match at least 2 words for name
			if (!preg_match ("/\w+\s+\w+/ ", $submitted_data['name']))
			{ 
				$this->formatic->set_form_error('name', 'The Name field must contain at least 2 words.');	
			}
			-------------------------------------------------------------------- */
			
			/* --------------------------------------------------------------------
			// example of moving and resizing an uploaded image using move_image()
			$photo  = $this->formatic->get_data('photo');
			if (is_array($photo))
			{
				$image_uri = $this->formatic->move_image(
					$photo, 
					$filename_prefix, 
					'insert-file-path-here',
					200, 200, TRUE   
				);
			}
			
			// example of moving an uploaded file using move_file()
			$file  = $this->formatic->get_data('file');
			if (is_array($file))
			{
				$file_uri = $this->formatic->move_file(
					$file, 
					$filename_prefix, 
					'insert-file-path-here' 
				);
			}
			-------------------------------------------------------------------- */
			
		}
		
		// $form_data could be an array of form values retrieved from a model
		$form_data = array();
		
		// e.g., let's popluate the 'name' field
		$form_data['name'] = 'John Smith';
		
		// e.g., select contact 1  and 3 in the 'Contacts' field
		$form_data['contacts[]'] = array(0, 2);
		
		// generate the form field html and populate with values in $data array
		$this->formatic->generate($form_data);
		
		// send to view
		$this->load->view('formatic_view_example', $view_data);
	}
}

/* End of file formatic_example.php */
/* Location: ./application/controllers/formatic_example.php */