<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Formatic helper
 *
 * @package		Formatic
 * @license 	MIT Licence (http://opensource.org/licenses/mit-license.php) 
 * @author  	Mark Croxton
 * @copyright  	Mark Croxton, hallmarkdesign (http://www.hallmark-design.co.uk)
 * @version 	1.0.0
 */

/**
 * To object
 *
 * Converts a multidimensional array to an object
 *
 * @access	public
 * @param	array
 * @return	object
 */	
if ( ! function_exists('to_object'))
{
	function to_object($data) {
	    return is_array($data) ? (object) array_map(__FUNCTION__, $data) : $data;
	}
}

/**
 * Render Errors
 *
 * Returns all the errors associated with a form submission.
 *
 * @access	public
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('render_errors'))
{
	function render_errors($tpl_row = NULL, $tpl_outer = NULL, $extra=array())
	{
		if (FALSE === ($OBJ =& _get_formatic_object()))
		{
			return '';
		}
		return $OBJ->render_errors($tpl_row, $tpl_outer, $extra);
	}
}

/**
 * form_errors
 *
 * Returns true if there are errors with the current form
 *
 * @access	public
 * @return	boolean
 */
if ( ! function_exists('form_errors'))
{
	function form_errors()
	{
		if (FALSE === ($OBJ =& _get_formatic_object()))
		{
			return '';
		}
		return $OBJ->form_errors();
	}
}

/**
 * Renders a form fieldset.
 * 
 * @param string $legend [optional] The fieldset <legend>
 * @param array $fields An associative array that defines the form.
 * @param array $populate An associative array that contains form values.
 * @param string $groups [optional] The field group(s) to include in the rendered fieldset
 * @param array $attributes [optional] An associative array that defines attributes for the fieldset tag.
 * @return Rendered String.
 */
if ( ! function_exists('render_fieldset'))
{
	function render_fieldset($legend='', $fields, $groups='', $populate=array(), $tpl=NULL, $tpl_row=NULL, $attributes=array())
	{
		if (FALSE === ($OBJ =& _get_formatic_object()))
		{
			return '';
		}
		return $OBJ->render_fieldset($legend, $fields, $groups, $populate, $tpl, $tpl_row, $attributes);
	}
}


/**
 * Renders a form row.
 * 
 * @param string $field The field to render.
 * @param string $tpl The HTML to render the field into.
 * @param string $id The form element css id.
 * @param string $label The form element label text.
 * @param string $label The row css class.
 * @return Rendered String.
 */
	
if ( ! function_exists('render_row'))
{
	function render_row($field='', $tpl = '', $id='', $label='', $row_class='', $required='')
	{
		if (FALSE === ($OBJ =& _get_formatic_object()))
		{
			return '';
		}
		return $OBJ->render_row($field, $tpl, $id, $label, $row_class, $required);
	}
}

/**
 * Renders a hidden fields for CSRF prevention
 * 
 * @return String.
 */

if ( ! function_exists('form_token'))
{
	function form_token() {
		
		if (FALSE === ($OBJ =& _get_formatic_object()))
		{
			return '';
		}
		
		if ($OBJ->CI->config->config['formatic']['is_EE'] == FALSE)
		{	
		    // Get the token from the csrf class
		    $tokenArray = $OBJ->get_token();    
		    if(!$tokenArray) {
		        // Token is bad. Create a new one
		        $tokenArray = $OBJ->create_token();    
		    }
    
		    // Return token hidden form field strings
		    $input_formID = form_input(array('name'=>'formid', 'id'=>'formid', 'value'=>$tokenArray['formID'], 'type'=>'hidden'));
		    $input_token  = form_input(array('name'=>'token', 'id'=>'token', 'value'=>$tokenArray['token'], 'type'=>'hidden'));
    
		    // Visible form fields for testing. Should not be used in production
		    //$input_formID = form_input(array('name'=>'formid', 'id'=>'formid', 'value'=>$tokenArray['formID'], 'type'=>'input'));
		    //$input_token  = form_input(array('name'=>'token', 'id'=>'token', 'value'=>$tokenArray['token'], 'type'=>'input'));
			return "\n $input_formID \n $input_token\n";
		}
		else
		{
			// Using EE, so we'll use EE's built in CSRF protection
			$input_siteID = form_input(array('name'=>'site_id', 'value'=>$OBJ->CI->config->item('site_id'), 'type'=>'hidden'));
			$input_token  = form_input(array('name'=>'XID', 'value'=>'{XID_HASH}', 'type'=>'hidden'));
			
			return "\n $input_siteID \n $input_token\n";
		}
	}
}

/**
 * Render links to formatic assets
 * 
 * @param array $field The field to render.
 * @return Rendered String.
 */
	
if ( ! function_exists('render_assets'))
{
	function form_assets($groups=array())
	{
		if (FALSE === ($OBJ =& _get_formatic_object()))
		{
			return '';
		}
		return $OBJ->assets->render($groups);
	}
}
	
	
// ------------------------------------------------------------------------

/**
 * Formatic Object
 *
 * Determines what the formatic class was instantiated as, fetches
 * the object and returns it.
 *
 * @access	private
 * @return	mixed
 */
if ( ! function_exists('_get_formatic_object'))
{
	function &_get_formatic_object()
	{
		$CI =& get_instance();

		// We set this as a variable since we're returning by reference
		$return = FALSE;

		if ( ! isset($CI->load->_ci_classes) OR  ! isset($CI->load->_ci_classes['formatic']))
		{
			return $return;
		}

		$object = $CI->load->_ci_classes['formatic'];

		if ( ! isset($CI->$object) OR ! is_object($CI->$object))
		{
			return $return;
		}

		return $CI->$object;
	}
}

/* End of file formatic.php */
/* Location: ./application/helpers/formatic.php */