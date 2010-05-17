<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

// ------------------------------------------------------------------------
// Fieldtypes


// ------------------------------------------------------------------------
// Options





/* End of file formatic.php */
/* Location: ./application/helpers/formatic.php */