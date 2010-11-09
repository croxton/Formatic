<?php
/**
 * Formatic Carabiner Bridge Class
 *
 * Bridges Tony Dewan's Carabiner asset manager with Formatic
 * https://github.com/tonydewan/Carabiner
 *
 * @license 	Creative Commons Attribution-Share Alike 3.0 Unported
 * @package		Formatic
 * @author  	Mark Croxton
 * @version 	1.0.0
 */

// --------------------------------------------------------------------------

/**
 * Formatic Carabiner Bridge Class
 */
class Formatic_carabiner_bridge extends Formatic_asset_bridge {
	
	protected $CI;
		
	function __construct()
	{
		// load the Carabiner library
		if ($CI =& get_instance())
		{
			$CI->load->library('carabiner');
			
			// assign a reference to the super object
			$this->CI = $CI;
		}
	}
	
	/**
	 * Load JS
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
		/*
		$assets may be in the form...
		
		array(
				array('script_uri', 'group_name', 'production_file', 'combine', 'minify'),
				array('jslider/script.js', 'main', 'jslider/script.pack.js', TRUE, TRUE),
				array('jslider/ie_specific_script.js', 'IE')
		)	
		
		...or...
		
		array('jslider/script.js', 'main')
		array('jslider/script.js')
		
		...or simply...
		
		'jslider/script.js'
		*/

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
				$prod_file 	= (isset($asset[2])) ? $asset[2] : '';
				$combine	= (isset($asset[3])) ? $asset[3] : FALSE;
				$minify		= (isset($asset[4])) ? $asset[4] : FALSE;

				$this->CI->carabiner->js($dev_file, $prod_file, $combine, $minify, $group);
			}
		}
		else
		{
			// string
			$this->CI->carabiner->js($file, $prod_file, $combine, $minify, $group);
		}
	}
	
	/**
	 * Load CSS
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
				$media 		= (isset($asset[1])) ? $asset[1] : 'screen';
				$group 		= (isset($asset[2])) ? $asset[2] : 'main';
				$prod_file 	= (isset($asset[3])) ? $asset[3] : '';
				$combine	= (isset($asset[4])) ? $asset[4] : FALSE;
				$minify		= (isset($asset[5])) ? $asset[5] : FALSE;
				
				$this->CI->carabiner->css($dev_file, $media, $prod_file, $combine, $minify, $group);
			}
		}
		else
		{
			// string
			$this->CI->carabiner->css($file, $media, $prod_file, $combine, $minify, $group);
		}
	}
	
}