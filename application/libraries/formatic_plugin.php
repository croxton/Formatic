<?php

/**
 * Formatic Plugin Class
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton, mcroxton@hallmark-design.co.uk
 * @version 	1.0.0  17th May 2010
 */

abstract class Formatic_plugin {
	
	protected $CI;

	public function __construct() {
		$this->CI =& get_instance();
	}
	
	abstract public function run($f, $param);
}