<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Related class
 *
 * Formatic field plugin
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */

class Related extends Formatic_plugin {
	
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
		$db_table 	 = isset($param[0]) ? $param[0] : NULL;
		$db_col_id 	 = isset($param[1]) ? $param[1] : 'id';
		$db_col_name = isset($param[2]) ? $param[2] : 'name';
		$order_by 	 = isset($param[3]) ? $param[3] : $db_col_name;
		$default 	 = isset($param[4]) ? $param[4] : '';
	
		$r = array();
		if (!empty($default))
		{
			$r += array("0" => $default);
		}
	
		if (!is_null($db_table))
		{
			$this->CI->db->select($db_col_id.' AS id, '.$db_col_name.' AS name', FALSE);
			$this->CI->db->from($db_table);
			$this->CI->db->order_by($order_by);
			$query = $this->CI->db->get();
			if ($query->num_rows() > 0) 
			{
				foreach ($query->result() as $row)
				{
					$r[$row->id] = $row->name;
				}
			}
		}
		return $r;
	}
}
