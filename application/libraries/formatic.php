<?php

// load form validation
$CI =& get_instance();
$CI->load->library('form_validation');

// load plugin abstract class
require_once APPPATH.'libraries/formatic_plugin'.EXT;

/**
 * Formatic Class
 *
 * Form automation class
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton, mcroxton@hallmark-design.co.uk
 * @version 	1.0.0  17th May 2010
 */

// --------------------------------------------------------------------------

/**
 * Formatic Class
 */
class Formatic extends CI_Form_validation {
	
	public $CI;
	protected $formatic_prefs;
	static protected $plugins = array();
		
	function __construct($rules = array())
	{
		// call the parent class constructor
		parent::CI_Form_validation($rules);
		
		// load & assign CodeIgniter libraries
		$this->_load_libraries();
		
		// store a reference to variables in the current scope
		$this->cached_vars =& $this->load->_ci_cached_vars;
		
		// load helpers
		$this->load->helper(array('form', 'formatic'));
		
		// load languages
		$this->lang->load('formatic');
		
		// load config
		$this->config->load('formatic', TRUE, TRUE);
		
		// Get config settings by reference
		$prefs =& $this->config->config['formatic'];
		
		foreach (array_keys($prefs) as $key)
		{
			// Only if they're not already set
			if (empty($this->formatic_prefs->{$key}))
			{
				$this->formatic_prefs->{$key} =& $prefs[$key];
			}
		}
		unset ($prefs);
	}
	
	private function _register_plugin($plugin, $file) 
	{
    	if(!isset(self::$plugins[$plugin]))
		{
			$this->load->plugin($file);
			self::$plugins[] = $plugin;
		}
  	}

	private function _unregister_plugin($plugin) 
	{
		if(isset(self::$plugins[$plugin]))
		{
			unset(self::$plugins[$plugin]);
		} 
		else 
		{
			throw new Exception('No such plugin');
		}
  	}

	private function __call($plugin, $args) 
	{
    	if (in_array($plugin, self::$plugins)) 
		{
			$plugin = ucfirst($plugin);
			$plugin_obj =& new $plugin();
      		return call_user_func_array(array(&$plugin_obj, 'run'), $args);
    	}
		else
		{
			throw new Exception('Method not found');
		}
  	}
	
	// --------------------------------------------------------------------

	/**
	 * Loads Libraries
	 *
	 * Loads and assigns CodeIgniter system libraries to Formatic by reference.
	 *
	 * @access	private
	 * @return	void
	 */
	private function _load_libraries()
	{
		if ($CI =& get_instance())
		{
			// load required libraries
			#$CI->load->library('form_validation');
			$CI->load->library('parser');
			$CI->load->library('carabiner');

			// assign loaded libraries
			#$this->form_validation = $CI->form_validation;
			$this->parser = $CI->parser;
			
			// assign system libraries (already loaded)
			$this->lang = $CI->lang;
			$this->load = $CI->load;
			$this->db = $CI->db;
			$this->config = $CI->config;
			$this->assets = $CI->carabiner;
			
			// assign a reference to the super object
			$this->CI = $CI;
		}
		
	}
	
	/**
	 * Load fields
	 *
	 * Load field config file
	 *
	 * @access	private
	 * @param	string	the config file name
	 * @return	boolean	if the file was loaded correctly
	 */	
	private function _load_fields($file = '')
	{
		$file = ($file == '') ? 'shared' : str_replace(EXT, '', $file);
	
		if (in_array($file.'_fields', $this->config->is_loaded, TRUE))
		{
			return TRUE;
		}

		if ( ! file_exists(APPPATH.$this->formatic_prefs->fields_config.'/'.$file.EXT))
		{
			show_error('The configuration file '.$file.EXT.' does not exist.');
		}
	
		include(APPPATH.$this->formatic_prefs->fields_config.'/'.$file.EXT);

		if ( ! isset($config) OR ! is_array($config))
		{
			show_error('Your '.$file.EXT.' file does not appear to contain a valid configuration array.');
		}

		$this->config->config[$file.'_fields'] = $config;

		$this->config->is_loaded[] = $file.'_fields';
		unset($config);
		
		log_message('debug', 'Config file loaded: '.$this->formatic_prefs->fields_config.'/'.$file.EXT);
		
		return TRUE;
	}
	
	/**
	 * Load field plugin
	 *
	 * @access	public
	 * @param	string 	$type 	The plugin field function - option|type
	 * @param	string	$plugin The plugin to load
	 * @return	array
	 */	
	public function load_field_plugin($type='option', $plugin, $field=NULL, $run=TRUE)
	{
		// Strip the parameter (if exists) from the plugin
		// Plugins can contain parameters like this: related[firms:id:name]
		$param = FALSE;
		if (preg_match("/(.*?)\[(.*?)\]/", $plugin, $match))
		{
			$plugin	= trim($match[1]);
			$param	= trim($match[2]);
		}
		
		// explode parameter string into an array of arguments
		if (!!$param)
		{
			$param = explode(':',$param);
		}
		
		switch ($type)
		{
			case 'option':
				$file = $this->formatic_prefs->field_option_plugins.'/'.$plugin;
				break;
			case 'type': default :
				$file = $this->formatic_prefs->field_type_plugins.'/'.$plugin;
				break;
		}

		// register plugin
		$this->_register_plugin($plugin, $file);
		
		// run it ?
		if ($run)
		{
			return $this->{$plugin}($field, $param);
		}
	}
	
	/**
	 * Display - load and run display plugin
	 *
	 * @access	public
	 * @param	string	$plugin The plugin to load
	 * @param	array	$param 	Arguments to pass to the display widget
	 * @return	array
	 */	
	public function display($plugin, $param=array())
	{
		$file = $this->formatic_prefs->display_widgets.'/'.$plugin;	
		
		// register plugin and run it
		$this->_register_plugin($plugin, $file);
		
		// get plugin configuration by reference
		$config =& $this->formatic_prefs->{$plugin};
		
		#print_r($config);
		
		// load dependent assets
		if (!isset($config['assets_loaded']))
		{
			if (isset($config['js']))
			{
				$this->load_asset('js', $config['js']); 
			}
			if (isset($plugin['css']))
			{
				$this->load_asset('css', $config['css']); 
			}
			// flag that assets have been loaded
			$config['assets_loaded'] = TRUE;
		}
		// run the plugin and return	
		return $this->{$plugin}(null, $param);
	}
	
	/**
	 * Load field callback
	 *
	 * @access	private
	 * @param	string	the plugin file name
	 * @return	array
	 */	
	private function _load_field_callback($plugin, $field, $param=NULL, $postdata, $run=TRUE)
	{
		$file = $this->formatic_prefs->field_callbacks.'/'.$plugin;
		
		// explode parameter string into an array of arguments
		if (!!$param)
		{
			$param = explode(':',$param);
		}

		// register plugin
		$this->_register_plugin($plugin, $file);
		
		// run it ?
		if ($run)
		{
			return $this->{$plugin}($field, $param, $postdata);
		}
	}
	
	/**
	 * Loads assets
	 *
	 * Loads assets required by form field plugins specified in config
	 *
	 * @access	private
	 * @return	void
	 */
	private function _load_assets($fields)
	{		
		foreach ($fields as $field => $value)
		{	
			if (preg_match("/(.*?)\[(.*?)\]/", $value['type'], $match))
			{
				$value['type']= trim($match[1]);
			}	
			if (isset($this->formatic_prefs->{$value['type']}))
			{
				$plugin =& $this->formatic_prefs->{$value['type']};
				
				if (!isset($plugin['assets_loaded']))
				{
					if (isset($plugin['js']))
					{
						$this->load_asset('js', $plugin['js']); 
					}
					if (isset($plugin['css']))
					{
						$this->load_asset('css', $plugin['css']); 
					}
				
					// flag that assets have been loaded
					$plugin['assets_loaded'] = TRUE;
				}
			}
		}
	}
	
	/**
	 * Load asset
	 *
	 * Loads an asset using the assigned asset library
	 *
	 * @access	public
	 * @return	void
	 */
	public function load_asset($type, $file)
	{
		$this->assets->{$type}($file); 
	}
	
	/**
	 * get_plugin_config
	 *
	 * Returns configuration values for the given plugin
	 *
	 * @access	public
	 * @param	$plugin The plugin name
	 * @return	array
	 */
	public function get_plugin_config($plugin)
	{	
		return isset($this->formatic_prefs->{$plugin}) ? $this->formatic_prefs->{$plugin} : array();
	}
	
	/**
	 * set_field_config
	 *
	 * Sets configuration values for the given field
	 *
	 * @access	public
	 * @param	$field The plugin name
	 * @return	array
	 */
	public function set_field_config($field, $values)
	{	
		$this->formatic_prefs->{'_field_'.$field} = $values;
	}
	
	/**
	 * get_field_config
	 *
	 * Returns configuration values for the given field (can only be used by plugins)
	 *
	 * @access	public
	 * @param	$field The plugin name
	 * @return	array
	 */
	public function get_field_config($field)
	{	
		return isset($this->formatic_prefs->{'_field_'.$field}) ? $this->formatic_prefs->{'_field_'.$field} : array();
	}
	
	/**
	 * set_template_config
	 *
	 * sets template values for the given field, these are replaced into the field row template
	 *
	 * @access	public
	 * @param	$field The plugin name
	 * @return	array
	 */
	public function set_template_config($field, $values)
	{	
		$this->formatic_prefs->{'_field_'.$field}['view'] = $values;
	}
	
	/**
	 * get_template_config
	 *
	 * gets configuration values for the given field
	 *
	 * @access	public
	 * @param	$field The plugin name
	 * @return	array
	 */
	public function get_template_config($field)
	{	
		return isset($this->formatic_prefs->{'_field_'.$field}['view']) ? $this->formatic_prefs->{'_field_'.$field}['view'] : array();
	}

	// --------------------------------------------------------------------
	// Form rendering
		
	/**
	 * Make fields
	 *
	 * Renders group(s) of fields
	 * 
	 * @access public
	 * @param  array $fields The fields to render.
	 * @param  array $populate The field data.
	 * @param  string $groups The fieldgroups to render.
	 * @param  array $tpl_row The view(s) to render each field into.
	 * @param  bool $return_object Return as an array or object.
	 * @return mixed Object or Array.
	 */
	public function make_fields($fields=array(), $groups='', $populate=array(), $tpl_row=NULL,  $render = FALSE)
	{	
		$field_html = array();
		$rendered_fields = '';
		
		// get locally scoped view variables or use the $populate array?
		if (is_array($populate) && count($populate) > 0)
		{
			$view_data =& $populate;
		}
		else
		{
			$view_data =& $this->cached_vars;	
		}
		
		// row template(s)
		if (!is_null($tpl_row) && $tpl_row !== '')
		{
			$tpl_row = $this->load->view($tpl_row, '', TRUE);
		}
		else
		{
			// null - load default row template view
			$tpl_row = $this->load->view($this->formatic_prefs->row_tpl, '', TRUE);
		}
		
		// default options row template - don't load unless needed!
		$tpl_row_options = '';
		
		// load default outer options template
		$tpl_outer_options = $this->load->view($this->formatic_prefs->outer_options_tpl, '', TRUE);
		
		// get groups
		if ($groups !== '')
		{
			$groups = str_replace(" ","",$groups);
			$groups = explode('|', $groups);
		}
	
		foreach ($fields as $f => $v)
		{
			if ($groups !== '' && isset($v['groups']))
			{
				$search  = str_replace(" ","",$v['groups']);
				$search  = explode('|', $search );
				
				if (!count(array_intersect($search, $groups)) > 0)
				{
					// this field is not in the requested group(s)
					// so skip the rest of this foreach loop
					continue; 
				}	
			}

			// set up defaults
			$field_html[$f]['field'] 	 = ''; // rendered field html
			$field_html[$f]['error'] 	 = ''; // field error message
			$field_html[$f]['label'] 	 = ''; // field label css id
			$field_html[$f]['row'] 	     = ''; // rendered field row
			
			// the field css id
			$v['id'] = $field_html[$f]['id'] = isset($v['attr']['id']) ? $v['attr']['id'] : $this->formatic_prefs->css_id_prefix.$f;
			
			// remove '[]' from the css id
			if (substr(trim($v['id']), -2) == '[]')
			{
				$v['id'] = str_replace('[]','',$v['id']);
			}
			
			// the field row class
			$field_html[$f]['row_class'] = isset($v['class']) ? $v['class'] : '' ; // field css class
			
			// set default values of fields from config
			$v['default'] = isset($v['default']) ? $v['default'] : '';

			// re-populate fields from $populate array or view variables
			if (isset($view_data[$f]))
			{	
				$v['default'] = $view_data[$f];
			}
			
			// add the field config to the formatic prefs master array
			//$this->formatic_prefs->{'_field_'.$f} = $v;
			$this->set_field_config($f, $v);

			switch ($v['type'])
			{
				case 'input': case 'upload' : case 'password' :	
					$data = array(
						'name'        => $f,
              			'id'          => $v['id'],
              			'value'       => set_value($f, isset($_POST[$f]) ? $_POST[$f] : $v['default'])
					);
					$data = isset($v['attr']) ? $data+$v['attr'] : $data;
					$field_html[$f]['field'] = call_user_func('form_'.$v['type'], $data);
					break;
				
				case 'textarea' :
					$data = array(
						'name'        => $f,
              			'id'          => $v['id'],
              			'value'       => set_value($f, $this->set_value($f) !== '' ? $this->set_value($f) : $v['default'])
					);
					$data = isset($v['attr']) ? $data+$v['attr'] : $data;
					$field_html[$f]['field'] = form_textarea($data);
					break;
				
				case 'dropdown' : case 'select' :

					$attr = array('id' => $v['id']);
					$attr = isset($v['attr']) ? $attr+$v['attr'] : $attr;
					$attr_str = $this->field_attributes($attr);
					
					if (isset($_POST[$f]))
					{	
						//$field_value = $this->get_data($f);
						$field_value = $_POST[$f];
					} 
					else
					{
						$field_value = $v['default'];
					}

					$field_html[$f]['field'] = call_user_func('form_dropdown', 
						$f,
						@is_array($v['options']) ? $v['options'] : $this->load_field_plugin('option', $v['options']),
						$field_value,
						$attr_str
					);	
					break;
					
				case 'multiselect' :
					$attr = array('id' => $v['id']);
					$attr = isset($v['attr']) ? $attr+$v['attr'] : $attr;
					$attr_str = $this->field_attributes($attr);
					
					if (isset($_POST[$f]))
					{	
						//$field_value = $this->get_data($f);
						$field_value = $_POST[$f];
					} 
					else
					{
						$field_value = $v['default'];
					}				
					
					$field_html[$f]['field'] = call_user_func('form_'.$v['type'], 
						//$f.'[]',
						$f,
						@is_array($v['options']) ? $v['options'] : $this->load_field_plugin('option', $v['options']),
						$field_value,
						$attr_str
					);	
					break;	
					
				case 'hidden' :
					$field_html[$f]['field'] = form_hidden( 
						$f,
						(isset($_POST[$f]) ? $_POST[$f] : $v['default'])
					);
					break;
					
				case 'checkbox' : case 'radio' :
					if (isset($v['options']))
					{
						$field_html[$f]['field'] ='';
						
						// load options plugin if $options is a string
						$options = @is_array($v['options']) ? $v['options'] : $this->load_field_plugin('option', $v['options']);
						
						//$name = count($options)>1 && $v['type'] === 'checkbox' ? $f.'[]' : $f;
						
						// count for appending to id value for each option
						$count = 1;
						
						foreach($options as $option => $value)
						{	
							$row ='';
							
							// set default
							$is_checked = FALSE;
							
							if (!count($_POST) >0) 
							{
								if (is_array($v['default']))
								{
									$is_checked = @in_array($option, $v['default']) ? TRUE : FALSE;
								}
								else
								{
									$is_checked = ($value == $v['default'] ? TRUE : FALSE);
								}
							}
		
							// overwrite the selected values if form has been posted
							// posted field keys will lack the '[]', so remove
							$field_key = $f;
							if (substr(trim($f), -2) == '[]')
							{
								$field_key = str_replace('[]','',$f);
							} 
							
							if (isset($_POST[$field_key]))
							{		
								if (is_array($_POST[$field_key]))
								{
									$is_checked = in_array($value, $_POST[$field_key]) ? TRUE : FALSE;
								}
								else
								{
									$is_checked = ($value == $_POST[$field_key] ? TRUE : FALSE);
								}
							}
							
							$data = array(
									'name'        => $f,
	              					'value'       => $value,
									'checked'	  => $is_checked
							);
							
							// add any attributes
							$data = isset($v['attr']) ? $data+$v['attr'] : $data;
							
							// id for each checkbox/radio option
							$data['id'] =  $v['id'].$count;
							
							// build the form control
							$row = call_user_func('form_'.$v['type'], $data);
							
							$ph = array('{id}', '{control}', '{option}');
							$replace = array($data['id'], $row, $option);
							
							// load options row template if configured	
							if (isset($v['template_options_row']))
							{
								$row_options_tpl = $this->load->view($v['template_options_row'], '', TRUE);
							}
							else
							{
								// use default options row template
								if ($tpl_row_options == '')
								{
									// only load the default row template the first time it is requested
									$tpl_row_options = $this->load->view($this->formatic_prefs->row_options_tpl, '', TRUE);
								}
								$row_options_tpl = $tpl_row_options;
							}
							
							// format row by replacing into row options template
							$row = str_replace($ph, $replace, $row_options_tpl)."\n";

							// add to html
							$field_html[$f]['field'] .= $row;
							
							//increment count
							$count ++;
						}
					}
					break;

				default :
					// try to load a field_type plugin of the requested type
					$field_html[$f]['field'] = $this->load_field_plugin('type', $v['type'], $f);
			}
			
			// add some useful info for form labels etc
			$label = @$v['label'];
			
			// get the tranlated line if available;
			if (strncmp($label, 'lang:', 5) ==  0)
			{
				$label = @$this->lang->line(ltrim($label, 'lang:'));
			}
			$field_html[$f]['label'] = $label;
			
			// is this field required?
			if (strpos($v['rules'], 'required') !== FALSE)
			{
				$field_html[$f]['required'] = $this->formatic_prefs->required_field;
			}
			else $field_html[$f]['required'] = '';
			
			// get error for this field
            $field_html[$f]['error'] = @$this->_error_array[$f];

			if (strlen($field_html[$f]['error']) > 0)
			{
				$field_html[$f]['row_class'] = $this->formatic_prefs->css_error_class.' '.$field_html[$f]['row_class'];
			}
			
			// format row class if we need it
			if ($field_html[$f]['row_class'] !== '')
			{
				$field_html[$f]['row_class'] = ' class="'.$field_html[$f]['row_class'].'"';
			}
			
			if ($v['type'] == 'checkbox' || $v['type'] == 'radio')
			{
				// use outer options template for these field types
				$tpl = $tpl_outer_options;
				// since each option has an id count appended, we want the id to point to first option
				$field_html[$f]['id'] .= '1';
			}
			else
			{
				// use standard row template for these field types
				$tpl = $tpl_row;
			}
			
			// map to individual row template(s) view if set in either the main config or overridden in the field config	
			$config_template = isset($this->formatic_prefs->{$v['type']}['template']) ? $this->formatic_prefs->{$v['type']}['template'] : '';
			$config_template = isset($v['template']) ? $v['template'] : $config_template;
			
			if ($config_template !== '')
			{
				$replace = $view_data;
				
				// if any variables have been namespaced to this field by field plugins, add them to the replace array
				$template_config = $this->get_template_config($f);
				
				if (count($template_config) > 0)
				{
					// make sure the view data for this field takes precedence over any other config values.
					$replace = array_merge($replace, $template_config);
				}

				// replace placeholders in the row template
				$tpl = $this->parser->parse($config_template, $replace, TRUE);	
			}
			
			// map to row template
			if ($tpl !== '')
			{
				$field_html[$f]['row'] = $this->render_row($field_html[$f]['field'], $tpl, $v['id'], $field_html[$f]['label'], $field_html[$f]['row_class'], $field_html[$f]['required']);
			}
			
			// are we rendering the fields or returning an object?
			if ($render)
			{
				if ($tpl !== '')
				{
					$rendered_fields .= $field_html[$f]['row'];
				} 
				else
				{
					$rendered_fields .= $field_html[$f]['field'];
				}
				
				unset($field_html[$f]);
			}
			
			// remove [] from key names in the rendered html array
			// so that we can cast to an object sucessfully
			if (substr(trim($f), -2) == '[]')
			{
				$new_f = str_replace('[]','',$f);
				$field_html[$new_f] = $field_html[$f];
				unset($field_html[$f]);
			}
		}

		// cast to an object and return - uses to_object in formatic_helper
		if (!$render)
		{
			return to_object($field_html);
		}
		// else return rendered field html
		else
		{
			return $rendered_fields;
		}
	}
	
	/**
	 * Field attributes
	 *
	 * Converts an array to a string of html attributes
	 *
	 * @param array $attr The array of html attributes
	 * @access	public
	 * @return	string
	 */
	public function field_attributes($attr=array())
	{
		$r = '';
		while (list($key, $val) = each($attr)) 
		{
    		$r .= $key.'='.'"'.$val.'" ';
		}
		return rtrim($r);
	}
	
	/**
     * set custom error
     * 
	 * sets an error for the specified form field
 	 *
     * @access    public
     * @param     str
     * @return    void
     */    
    public function set_form_error($key, $error)
	{
		if (!isset($this->_error_array[$key]))
		{
			$this->_error_array[$key] = $error;
		}
	}
	
	/**
	 * get form errors
	 *
	 * Returns an array of validation errors
	 *
	 * @access	public
	 * @return	array
	 */
	public function get_form_errors()
	{
		if (count($this->_error_array) > 0)
		{
			return $this->_error_array;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Render errors
	 *
	 * @param string $tpl_row The error row view
	 * @param string $tpl_outer The error outer view
	 * @param array $extra Array of additional error messages to append
	 * @access	public
	 * @return	string
	 */
	public function render_errors($tpl_row = NULL, $tpl_outer = NULL, $extra=array())
	{
		$error_message = '';
		$errors = @$this->_error_array;
		
		if(is_null($tpl_row))
		{
			$tpl_row = $this->formatic_prefs->row_error_tpl;
		}
		
		if(is_null($tpl_outer))
		{
			$tpl_outer = $this->formatic_prefs->outer_error_tpl;
		}
		
		// load views
		$tpl_row   = $this->load->view($tpl_row, '', TRUE);
		$tpl_outer = $this->load->view($tpl_outer, '', TRUE);

		// any extra errors passed in the function call
		$errors = array_merge($extra, $errors);
		
		// map to row template
		foreach ($errors as $error)
		{
			if ($error !== '')
			{
				$error_message .= str_replace('{row}',trim($error), $tpl_row)."\n";
			}
		}
		
		// map to outer template
		if ($error_message !== '')
		{
			$error_message = str_replace('{errors}', $error_message, $tpl_outer);
		}
		
		return $error_message;
	}

	/**
	 * Renders a form row.
	 * 
	 * @param string $field The field to render.
	 * @param string $tpl The HTML to render the field into.
	 * @param string $id The form element css id.
	 * @param string $label The form element label text.
	 * @param string $label The row css class.
	 * @return string
	 */
	public function render_row($field='', $tpl = '', $id='', $label='', $row_class='', $required='')
	{
		$ph = array('{field}', '{id}', '{label}', '{row_class}', '{required}');
		$row_data = array($field, $id, $label, $row_class, $required);
		return str_replace($ph,$row_data,$tpl)."\n";
	}


	/**
	 * Renders a form fieldset.
	 * 
	 * @param string $legend [optional] The fieldset <legend>
	 * @param array $fields An associative array that defines the form.
	 * @param array $populate An associative array that contains form values.
	 * @param string $groups [optional] The field group(s) to include in the rendered fieldset
	 * @param array $attributes [optional] An associative array that defines attributes for the fieldset tag.
	 * @return string
	 */
	public function render_fieldset($legend='', $fields, $groups='', $populate=array(), $tpl=NULL, $tpl_row=NULL, $attributes=array())
	{	
		
		$tpl = is_null($tpl) ? $this->formatic_prefs->fieldset_tpl : $tpl;
		
		// render the field rows
		$rows = $this->make_fields($fields, $groups, $populate, $tpl_row, TRUE);
		
		// render into fieldset template
		$view_data = $attributes + array(
			'legend' => $legend,
			'rows'   => $rows
		);
		
		return $this->cleanup($this->parser->parse($tpl,$view_data,TRUE));
	}
	
	/**
	 * Cleanup
	 * 
	 * @param string  The string to clean up.
	 * @return string
	 */
	public function cleanup($content)
	{		
		// remove any unused {tags}
		$pattern = '/{[a-zA-Z_-]+}/Usi';
		return preg_replace($pattern,'',$content);
	}
	
	// --------------------------------------------------------------------
	// Validation
	
	/**
     * make_rules
     * 
	 * creates validation rules from a multidimensional array
 	 *
     * @access    public
     * @param     array
     * @return    void
     */    
    public function make_rules($fields, $groups='')
	{
		if ($groups !== '')
		{
			$groups = str_replace(" ","",$groups);
			$groups = explode('|', $groups);
		}
		
		foreach ($fields as $key => $val)
		{
			if ($groups == '')
			{	
				// add validation to all fields
				$this->set_rules($key, $val['label'], $val['rules']);
			}
			else
			{
				// only validate fields in specified group(s)
				if (isset($val['groups']))
				{
					$haystack = str_replace(" ","",$val['groups']);
					$haystack = explode('|', $haystack);
				
					if (count(array_intersect($haystack, $groups)) > 0)
					{
						$this->set_rules($key, $val['label'], $val['rules']);
					}
				}	
			}	
		}
	}
	
	/**
     * get_data
     * 
	 * retrieves user submitted data from form
	 * can narrow to group(s) or fields(s) in search array
 	 *
     * @access    public
     * @param     array
     * @param     boolean
     * @return    array
     */    
    public function get_data($fields, $groups='')
	{
		$data = array();
	
		if ($groups !== '' && is_array($fields))
		{	
			// we're searching within a formatic fields config array for one or more groups
			
			if (!is_array($groups))
			{
				$groups = str_replace(" ", "", $groups);
				$groups = explode('|', $groups);
			}
			
			// get the fields that match the specified group names
			foreach ($fields as $key => $val)
			{
				// make sure we really do have a multidimensional array
				if (is_array($val))
				{
					$field_value = $this->set_value($key);
			
					if ($field_value !=='' || is_array($field_value))
					{
						// field must be assigned to one or more groups or we ignore it
						if (isset($fields[$key]['groups']))
						{
							// only return fields in specified group(s)
							$haystack = str_replace(" ","",$fields[$key]['groups']);
							$haystack = explode('|', $haystack);
			
							if (count(array_intersect($haystack, $groups)) > 0)
							{
								$data[$key] = $field_value;
							}	
						}
					}
				}
			}
		}
		else
		{	
			// we're searching for a list of fieldnames
			
			if (!is_array($fields))
			{
				$fields = str_replace(" ", "", $fields);
				$fields = explode('|', $fields);
			}
			
			// get the values for the specified fields
			foreach ($fields as $key => $val)
			{
				// if this is a multidiemnsional array then the key will be $key
				$field_key = is_array($val) ? $key : $val;
				
				// get the value for this field
				$field_value = $this->set_value($field_key);
				
				if ($field_value !=='' || is_array($field_value))
				{
					$data[$field_key] = $field_value;
				}
			}
		}
		
		if (count($data) == 1)
		{
			// only one field requested, so return just the field value itself
			return end($data);
		}
		else
		{
			return $data;
		}		
	}
	
	// --------------------------------------------------------------------
	// Configuration
	
	/**
	 * Fetch config file group(s)
	 *
	 * @access	private
	 * @param	string	group(s) requested, delimited by |
	 * @param	string	the index name
	 * @return	array
	 */
	private function _get_group($groups='', $index = '')
	{	
		$pref = array();
		
		if ($index == '')
		{	
			return FALSE;
		}
		else
		{
			if ( ! isset($this->config->config[$index.'_fields']))
			{
				return FALSE;
			}
			
			$config = $this->config->config[$index.'_fields'];		
			
			if ($groups == '')
			{
				// no group specified so return all fields
				$pref = $config;
			}
			else
			{
				$groups = str_replace(" ","",$groups);
				$groups = explode('|', $groups);
				
				// look for items that match the requested group(s)
				foreach ($config as $item => $val)
				{
					if (isset($val['groups']))
					{
						$haystack = str_replace(" ","",$val['groups']);
						$haystack = explode('|', $haystack);
						
						if (count(array_intersect($haystack, $groups)) > 0)
						{
							$pref[$item] = $val;
						}
					}
				}
			}
		}
		return $pref;
	}
	
	/**
	 * Get Fields
	 *
	 * @access	private
	 * @param	array	fields(s) requested
	 * @param	string	the index name
	 * @return	array
	 */
	private function _get_fields($fields=array(), $index = '')
	{	
		$pref = array();
		
		if ($index == '')
		{	
			return FALSE;
		}
		else
		{
			if ( ! isset($this->config->config[$index.'_fields']))
			{
				return FALSE;
			}
			
			$config = $this->config->config[$index.'_fields'];		
			
			if (count($fields) < 1)
			{
				// no fields specified so return all fields
				$pref = $config;
			}
			else
			{
				// look for items that match the requested field keys(s)
				foreach ($config as $item => $val)
				{
					if (in_array($item, $fields))
					{
						$pref[$item] = $val;
					}
				}
			}
		}
		return $pref;
	}
	
	// --------------------------------------------------------------------
	// public wrappers for internal functions
	
	/**
	 * Validate
	 *
	 * @access	public
	 * @return	bool
	 */
	public function validate()
	{
		return $this->run();
	}
	
	/**
	 * Load
	 *
	 * @access	public
	 * @return	array
	 */
	public function load($fields)
	{
		return $this->_load_fields($fields);
	}
	
	/**
	 * Group
	 *
	 * @access	public
	 * @return	array
	 */
	public function group($groups='', $index = '')
	{
		// grab a subset of the loaded fields
		$prefs = $this->_get_group($groups, $index);

		// load any required assets for this group
		$this->_load_assets($prefs);
		
		return $prefs;
	}
	
	/**
	 * Fields
	 *
	 * @access	public
	 * @return	array
	 */
	public function fields($fields='', $index = '')
	{
		// just one field?
		if (!is_array($fields))
		{
			$fields = array($fields);
		}
		
		// grab a subset of the loaded fields
		$prefs = $this->_get_fields($fields, $index);

		// load any required assets for this group
		$this->_load_assets($prefs);
		
		return $prefs;
	}
	
	
	// --------------------------------------------------------------------
	// special file/image handling
	
	public function set_rules($field, $label = '', $rules = '')
    {
        if(count($_POST)===0 AND count($_FILES) > 0)//it will prevent the form_validation from working
        {
            //add a dummy $_POST
            $_POST['DUMMY_ITEM'] = '';
            parent::set_rules($field,$label,$rules);
            unset($_POST['DUMMY_ITEM']);
        }
        else
        {
            //we are safe just run as is
            parent::set_rules($field,$label,$rules);
        }    
    }
    
    public function run($group='')
    {
        $rc = FALSE;
        if(count($_POST)===0 AND count($_FILES)>0) //does it have a file only form?
        {
            //add a dummy $_POST
            $_POST['DUMMY_ITEM'] = '';
            $rc = parent::run($group);
            unset($_POST['DUMMY_ITEM']);
        }
        else
        {
            //we are safe just run as is
            $rc = parent::run($group);
        }
        
        return $rc;
    }

    public function file_upload_error_message($error_code)
    {
        switch ($error_code)
        {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }     
    
    public function _execute($row, $rules, $postdata = NULL, $cycles = 0)
    {
		
        log_message('DEBUG','called Formatic::_execute ' . $row['field']);
        //changed based on
        //http://codeigniter.com/forums/viewthread/123816/P10/#619868
        
		if(isset($_FILES[$row['field']]))
        {
			// it is a file so process as a file
            log_message('DEBUG','processing as a file');
            $postdata = $_FILES[$row['field']];
            
            //before doing anything check for errors
            if($postdata['error'] !== UPLOAD_ERR_OK && $postdata['error'] !== UPLOAD_ERR_NO_FILE)
            {
                $this->_error_array[$row['field']] = $this->file_upload_error_message($postdata['error']);
                return FALSE;
            }

            $_in_array = FALSE;        
        
            // If the field is blank, but NOT required, no further tests are necessary
            $callback = FALSE;
            if ( ! in_array('file_required', $rules) AND $postdata['size']==0)
            {
                // Before we bail out, does the rule contain a callback?
                if (preg_match("/(callback_\w+)/", implode(' ', $rules), $match))
                {
                    $callback = TRUE;
                    $rules = (array('1' => $match[1]));
                }
                else
                {
                    return;
                }
            }
       	}
		else
		{
			// If the $_POST data is an array we will run a recursive call
			if (is_array($postdata))
			{ 
				foreach ($postdata as $key => $val)
				{
					$this->_execute($row, $rules, $val, $cycles);
					$cycles++;
				}
			
				return;
			}

			// If the field is blank, but NOT required, no further tests are necessary
			$callback = FALSE;
			if ( ! in_array('required', $rules) AND is_null($postdata))
			{
				// Before we bail out, does the rule contain a callback?
				if (preg_match("/(callback_\w+)/", implode(' ', $rules), $match))
				{
					$callback = TRUE;
					$rules = (array('1' => $match[1]));
				}
				else
				{
					return;
				}
			}
		
			// Isset Test. Typically this rule will only apply to checkboxes.
			if (is_null($postdata) AND $callback == FALSE)
			{
				if (in_array('isset', $rules, TRUE) OR in_array('required', $rules))
				{
					// Set the message type
					$type = (in_array('required', $rules)) ? 'required' : 'isset';
			
					if ( ! isset($this->_error_messages[$type]))
					{
						if (FALSE === ($line = $this->lang->line($type)))
						{
							$line = 'The field was not set';
						}							
					}
					else
					{
						$line = $this->_error_messages[$type];
					}
				
					// Build the error message
					$message = sprintf($line, $this->_translate_fieldname($row['label']));

					// Save the error message
					$this->_field_data[$row['field']]['error'] = $message;
				
					if ( ! isset($this->_error_array[$row['field']]))
					{
						$this->_error_array[$row['field']] = $message;
					}
				}
					
				return;
			}

		}
		
		/* --------------------------------------------
		/*  Adapted from parent class
		/* --------------------------------------------*/

		// Cycle through each rule and run it
		foreach ($rules As $rule)
		{
			// don't do this to file fields
			if(!isset($_FILES[$row['field']]))
        	{
				$_in_array = FALSE;
	
				// We set the $postdata variable with the current data in our master array so that
				// each cycle of the loop is dealing with the processed data from the last cycle
				if ($row['is_array'] == TRUE AND is_array($this->_field_data[$row['field']]['postdata']))
				{
					// We shouldn't need this safety, but just in case there isn't an array index
					// associated with this cycle we'll bail out
					if ( ! isset($this->_field_data[$row['field']]['postdata'][$cycles]))
					{
						continue;
					}
	
					$postdata = $this->_field_data[$row['field']]['postdata'][$cycles];
					$_in_array = TRUE;
				}
				else
				{
					$postdata = $this->_field_data[$row['field']]['postdata'];
				}
			}

			// Is the rule a callback?			
			$callback = FALSE;
			if (substr($rule, 0, 9) == 'callback_')
			{
				$rule = substr($rule, 9);
				$callback = TRUE;
			}
	
			// Strip the parameter (if exists) from the rule
			// Rules can contain a parameter: max_length[5]
			$param = FALSE;
			if (preg_match("/(.*?)\[(.*?)\]/", $rule, $match))
			{
				$rule	= $match[1];
				$param	= $match[2];
			}
	
			// Call the function that corresponds to the rule
			if ($callback === TRUE)
			{
				
				if ( ! method_exists($this->CI, $rule))
				{ 	
					// Try to load and run a field plugin
					$result = $this->_load_field_callback($rule, $row['field'], $param, $postdata);
				}
				else
				{
					// Run the function and grab the result
					$result = $this->CI->$rule($postdata, $param);
				}

				// Re-assign the result to the master data array
				if ($_in_array == TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
				}
				else
				{
					$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
				}
	
				// If the field isn't required and we just processed a callback we'll move on...
				if ( ! in_array('required', $rules, TRUE) AND $result !== FALSE)
				{
					continue;
				}
			}
			else
			{			
				if ( ! method_exists($this, $rule))
				{
					// If our own wrapper function doesn't exist we see if a native PHP function does. 
					// Users can use any native PHP function call that has one param.
					if (function_exists($rule))
					{
						$result = $rule($postdata);
									
						if ($_in_array == TRUE)
						{
							$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
						}
						else
						{
							$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
						}
					}
								
					continue;
				}
				
				$result = $this->$rule($postdata, $param);

				if ($_in_array == TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
				}
				else
				{
					$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
				}
			}
					
			// Did the rule test negatively?  If so, grab the error.
			if ($result === FALSE)
			{				
				if ( ! isset($this->_error_messages[$rule]))
				{
					if (FALSE === ($line = $this->lang->line($rule)))
					{
						$line = 'Unable to access an error message corresponding to your field name.';
					}						
				}
				else
				{
					$line = $this->_error_messages[$rule];
				}
		
				// Is the parameter we are inserting into the error message the name
				// of another field?  If so we need to grab its "field label"
				if (isset($this->_field_data[$param]) AND isset($this->_field_data[$param]['label']))
				{
					$param = $this->_field_data[$param]['label'];
				}
		
				// Build the error message
				$message = sprintf($line, $this->_translate_fieldname($row['label']), $param);

				// Save the error message
				$this->_field_data[$row['field']]['error'] = $message;
		
				if ( ! isset($this->_error_array[$row['field']]))
				{
					$this->_error_array[$row['field']] = $message;
				}
		
				return;
			}
		}
		// <-- end adaption from from original class
    }
    
    
    /**
    * Future function. To return error message of choice.
    * It will use $msg if it cannot find one in the lang files
    * 
    * @param string $msg the error message
    */
    public function set_error($msg)
    {
        $this->lang->load('upload');
        return ($this->lang->line($msg) == FALSE) ? $msg : $this->lang->line($msg);
    }
    
    /**
    * tests to see if a required file is uploaded
    * 
    * @param mixed $file
    */
    public function file_required($file)
    {
        if($file['size']===0)
        {
            $this->set_message('file_required','Uploading a file for %s is required.');
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
    * tests to see if a file is within expected file size limit
    * 
    * @param mixed $file
    * @param mixed $max_size
    */
    public function file_size_max($file,$max_size)
    {
        $max_size_bit = $this->let_to_bit($max_size);
        if($file['size']>$max_size_bit)
        {
            $this->set_message('file_size_max',"%s is too big. (max allowed is $max_size)");
            return FALSE;
        }
        return true;
    }
    
    /**
    * tests to see if a file is bigger than minimum size
    * 
    * @param mixed $file
    * @param mixed $min_size
    */
    public function file_size_min($file,$min_size)
    {
        $max_size_bit = $this->let_to_bit($max_size);
        if($file['size']<$min_size_bit)
        {
            $this->set_message('file_size_min',"%s is too small. (Min allowed is $max_size)");
            return FALSE;
        }
        return true;
    }    
    
    /**
    * tests the file extension for valid file types
    * 
    * @param mixed $file
    * @param mixed $type
    */
    public function file_allowed_type($file,$type)
    {
	
        //is type of format a,b,c,d? -> convert to array
        $exts = explode(',',$type);
                
        //is $type array? run self recursively
        if(count($exts)>1)
        {
            foreach($exts as $v)
            {
                $rc = $this->file_allowed_type($file,$v);
                if($rc===TRUE)
                {
                    return TRUE;
                }
            }
        }
        
        //is type a group type? image, application, word_document, code, zip .... -> load proper array
        $ext_groups = array();
        $ext_groups['image'] = array('jpg','jpeg','gif','png');
        $ext_groups['application'] = array('exe','dll','so','cgi');
        $ext_groups['php_code'] = array('php','php4','php5','inc','phtml');
        $ext_groups['document'] = array('rtf','doc','docx','pdf');
        $ext_groups['compressed'] = array('zip','gzip','tar','gz');
        
        if(array_key_exists($exts[0],$ext_groups))
        {
            $exts = $ext_groups[$exts[0]];
        }
        
        //get file ext
        $file_ext = strtolower(strrchr($file['name'],'.'));
        $file_ext = substr($file_ext,1);
        
        if(!in_array($file_ext,$exts))
        {
            $this->set_message('file_allowed_type',"%s should be $type.");
            return false;        
        }
        else
        {
            return TRUE;
        }
    }
    
    public function file_disallowed_type($file,$type)
    {
        $rc = $this->file_allowed_type($file,$type);
        if(!$rc)
        {
            $this->set_message('file_disallowed_type',"%s cannot be $type.");
        }

        return $rc;
    }   
       
    //http://codeigniter.com/forums/viewthread/123816/P20/
   /**
    * given an string in format of ###AA converts to number of bits it is assignin
    * 
    * @param string $sValue
    * @return integer number of bits
    */
    public function let_to_bit($sValue)
    {
        // Split value from name
        if(!preg_match('/([0-9]+)([ptgmkb]{1,2}|)/ui',$sValue,$aMatches))
        { // Invalid input
            return FALSE;
        }
        
        if(empty($aMatches[2]))
        { // No name -> Enter default value
            $aMatches[2] = 'KB';
        }
        
        if(strlen($aMatches[2]) == 1)
        { // Shorted name -> full name
        $aMatches[2] .= 'B';
        }
        
        $iBit   = (substr($aMatches[2], -1) == 'B') ? 1024 : 1000;
        // Calculate bits:
        
        switch(strtoupper(substr($aMatches[2],0,1)))
        {
            case 'P':
                $aMatches[1] *= $iBit;
            case 'T':
                $aMatches[1] *= $iBit;
            case 'G':
                $aMatches[1] *= $iBit;
            case 'M':
                $aMatches[1] *= $iBit;
            case 'K':
                $aMatches[1] *= $iBit;
            break;
        }

        // Return the value in bits
        return $aMatches[1];
    }    
    
   /**
    * returns false if image is bigger than the dimensions given
    * 
    * @param mixed $file
    * @param array $dim
    */
    public function file_image_maxdim($file,$dim)
    {
        log_message('debug','Formatic:file_image_maxdim ' . $dim);
        $dim = explode(',',$dim);
        
        if(count($dim)!==2)
        {
            //bad size given
            $this->set_message('file_image_maxdim','%s has invalid rule expected similar to 150,300 .');
            return FALSE;
        }
        
        log_message('debug','Formatic:file_image_maxdim ' . $dim[0] . ' ' . $dim[1]);
        
        //get image size
        $d = $this->get_image_dimension($file['tmp_name']);
        
        log_message('debug',$d[0] . ' ' . $d[1]);
        
        if(!$d)
        {
            $this->set_message('file_image_maxdim','%s dimensions was not detected.');
            return FALSE;        
        }
                
        if($d[0] < $dim[0] && $d[1] < $dim[1])
        {
            return TRUE;
        }
    
        $this->set_message('file_image_maxdim','%s image size is too big.');
        return FALSE;
    }
    
   /**
    * returns false if the image is smaller than given dimension
    * 
    * @param mixed $file
    * @param array $dim
    */
    public function file_image_mindim($file,$dim)
    {
        $dim = explode(',',$dim);
        
        if(count($dim)!==2)
        {
            //bad size given
            $this->set_message('file_image_mindim','%s has invalid rule expected similar to 150,300 .');
            return FALSE;
        }
        
        //get image size
        $d = $this->get_image_dimension($file['tmp_name']);
        
        if(!$d)
        {
            $this->set_message('file_image_mindim','%s dimensions was not detected.');
            return FALSE;        
        }
        
        log_message('debug',$d[0] . ' ' . $d[1]);
        
        if($d[0] > $dim[0] && $d[1] > $dim[1])
        {
            return TRUE;
        }
    
        $this->set_message('file_image_mindim','%s image size is too big.');
        return FALSE;
    }
    
   /**
    * attempts to determine the image dimension
    * 
    * @param mixed $file_name path to the image file
    * @return array
    */
    public function get_image_dimension($file_name)
    {
        log_message('debug',$file_name);
        if (function_exists('getimagesize'))
        {
            $D = @getimagesize($file_name);

            return $D;
        }
        
        return FALSE;
    }

   /**
    * move an uploaded file
    * 
    * @param array $file the file data
    * @param string $name the file name
    * @param destination $name the file path to move to
    * @return array
    */
	public function move_file($file=array(), $name, $destination)
	{
      	$file_ext = strtolower(strrchr($file['name'],'.'));
      	$file_ext = substr($file_ext,1);
		$new_file = $name.'.'.$file_ext;

		// we've already validated the file so we just need to move it
		if (move_uploaded_file($file['tmp_name'], $destination.$new_file))
		{
			return $new_file;
		}
		return false;
	}
	
   /**
    * move and resize an uploaded image
    * 
    * @param array $file the file data
    * @param string $name the file name
    * @param destination $name the file path to move to
    * @param integer $width the max width of resized image
    * @param integer $height the max height of resized image
    * @param boolean $ratio maintain aspect ratio
    * @return array
    */
	public function move_image($file=array(), $name, $destination, $width=80, $height=80, $ratio=TRUE)
	{		
      	$file_ext = strtolower(strrchr($file['name'],'.'));
      	$file_ext = substr($file_ext,1);
		$new_file = $name.'.'.$file_ext;

		$config['image_library'] = 'gd2';
		$config['source_image'] = $file['tmp_name'];
		$config['new_image'] = $destination.$new_file;
		$config['maintain_ratio'] = $ratio;
		$config['width'] = $width;
		$config['height'] = $height;

		$this->CI->load->library('image_lib', $config);

		if ($this->CI->image_lib->resize())
		{
			$this->CI->image_lib->clear();
			return $new_file;
		}
		return false;
	}

	// <- END - file/image handling
	// --------------------------------------------------------------------
}
