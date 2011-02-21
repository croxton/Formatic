<?php
/**
 * Formatic Stuff Bridge Class
 *
 * Bridges Dan Horrigan's Stuff asset manager with Formatic
 * https://github.com/dhorrigan/codeigniter-stuff
 *
 * @package		Formatic
 * @license 	MIT Licence (http://opensource.org/licenses/mit-license.php) 
 * @author  	Mark Croxton
 * @copyright  	Mark Croxton, hallmarkdesign (http://www.hallmark-design.co.uk)
 * @version 	1.0.1
 */

// --------------------------------------------------------------------------

/**
 * Formatic Stuff Bridge Class
 */
class Formatic_stuff_bridge extends Formatic_asset_bridge {
	
	protected $CI;
		
	function __construct()
	{
		// load the Carabiner library
		if ($CI =& get_instance())
		{
			$CI->load->library('stuff');
			
			// assign a reference to the super object
			$this->CI = $CI;
		}
	}
	
	/**
	 * JS
	 *
	 * @param	String of the path to development version of the JS file.  Could also be an array, or array of arrays.
	 * @param	String of the group name with which the asset is to be associated. NOT REQUIRED
	 * @param	String of the path to production version of the JS file. NOT REQUIRED
	 * @param	Boolean flag whether the file is to be combined. NOT REQUIRED
	 * @param	Boolean flag whether the file is to be minified. NOT REQUIRED
	 * @return	void
	 */	
	public function js($file, $group="main", $prod_file="", $combine=FALSE, $minify=FALSE)
	{			
		if (is_array($file))
		{
			$assets = $file;
				
			// array, or array of arrays
			if(!is_array($file[0]))
			{
				$assets = array($file);
			}

			foreach($assets as $asset){	
				$dev_file 	= $asset[0];
				$group 		= (isset($asset[1])) ? $asset[1] : 'main';
				// Stuff encodes ampersands as entities, so lets replace them here
				$dev_file = str_replace('&amp;', '&', $dev_file);
				Stuff::js($dev_file, array(), $group);
			}
		}
		else
		{
			// string
			Stuff::js($file, array(), $group);
		}
	}
	
	/**
	 * CSS
	 *
	 * @param	String of the path to development version of the CSS file.  Could also be an array, or array of arrays.
	 * @param	String of the media type of the CSS file. NOT REQUIRED
	 * @param	String of the group name with which the asset is to be associated. NOT REQUIRED
	 * @param	String of the path to production version of the JS file. NOT REQUIRED
	 * @param	Boolean flag whether the file is to be combined. NOT REQUIRED
	 * @param	Boolean flag whether the file is to be minified. NOT REQUIRED
	 * @return	void
	 */	
	public function css($file, $media="screen", $group="main", $prod_file='', $combine=FALSE, $minify=FALSE)
	{	
		if (is_array($file))
		{
			$assets = $file;
			
			// array, or array of arrays
			if(!is_array($file[0]))
			{
				$assets = array($file);
			}

			foreach($assets as $asset){
				$dev_file 	= $asset[0];
				$group 		= (isset($asset[2])) ? $asset[2] : 'main';
				Stuff::css($dev_file, array(), $group);
			}
		}
		else
		{
			// string
			Stuff::css($file, array(), $group);
		}
	}
	
	/**
	 * Render
	 *
	 * @param	Array of groups to render
	 * @return	string
	 */	
	public function render($groups=array())
	{	
		$output = '';
		
		if (empty($groups))
		{
			$groups = array('main');
		}
		foreach ($groups as $group) 
		{
			$output .= Stuff::render($group);
		}
		
		return $output;
	}	
}