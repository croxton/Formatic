<?php

/**
 * Formatic Plugin Class
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */

abstract class Formatic_plugin {
	
	protected $CI;

	public function __construct() {
		$this->CI =& get_instance();
	}
	
	abstract public function run($f, $param);
}