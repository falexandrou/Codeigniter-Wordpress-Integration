<?php
/**
 * Wordpress CodeIgniter Include file
 *
 * This is a plugin to integrate codeigniter into your wordpress sites. 
 * Makes it easy to share functions and views between the two
 *
 * @category	Libraries
 * @author		David McReynolds @ Daylight Studio
 * @link		http://bitbucket.org/daylight/ci-wordpress-helper/wiki/Home
 * @license		http://www.gnu.org/licenses/gpl.html
 */
// --------------------------------------------------------------------


//CAN'T BE IN A FUNCTION BECAUSE CI NEEDS TO BE IN GLOBAL SCOPE!!!
if (!isset($wp_ci['index'])) $wp_ci['index'] = './../index.php';
if (!isset($wp_ci['uri'])) $wp_ci['uri'] = FALSE;
if (!isset($wp_ci['return'])) $wp_ci['return'] = FALSE;

$GLOBALS['wp_ci_is_wp'] = TRUE;

$wp_ci['index'] = realpath($wp_ci['index']);

if (empty($wp_ci['index']))
{
	 _e('Error finding the CodeIgniter bootstrap index file');
	exit;
}
$_SERVER['PATH_INFO'] = $wp_ci['uri'];
$_SERVER['REQUEST_URI'] = $wp_ci['uri'];

ob_start();
@include($wp_ci['index']);

if (!function_exists('get_instance'))
{
	_e('Error loading CodeIgniter');
	exit;
}
$GLOBALS['CI'] =& get_instance();

$GLOBALS['wp_ci_output'] = ob_get_contents();
ob_end_clean();

// now include wp_ci_helper functions
require_once(APPPATH.'helpers/wordpress_helper.php');

if($wp_ci['return'])
{
	$output;
}
else
{
	echo $GLOBALS['wp_ci_output'];
}


?>
