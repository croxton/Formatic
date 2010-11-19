<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * An array of paths that will be searched for assets.  Each asset is a
 * RELATIVE path from the base_url WITH a trailing slash:
 *
 *     array('assets/')
 */
$config['stuff_paths'] = array('_assets/');

/**
 * URL to your CodeIgniter root. Typically this will be your base URL,
 * WITH a trailing slash:
 *
 *     config_item('base_url')
 */

$config['stuff_url'] = config_item('base_url');

/**
 * Asset Sub-folders
 *
 * Names for the img, js and css folders (inside the asset path).
 *
 * Examples:
 *
 *     img/
 *     js/
 *     css/
 *
 * This MUST include the trailing slash ('/')
 */
$config['stuff_img_dir'] = 'img/';
$config['stuff_js_dir'] = 'js/';
$config['stuff_css_dir'] = 'css/';

/* End of file stuff.php */