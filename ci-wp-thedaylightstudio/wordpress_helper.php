<?php
/**
 * Wordpress Helper
 *
 * This helper is bridge between wordpress files and codeigniter.  Each 
 * method is designed to bring content from codeiginter to wordpress related
 * files. 
 *
 * @category	Libraries
 * @author		David McReynolds @ Daylight Studio
 * @link		http://bitbucket.org/daylight/ci-wordpress-helper/wiki/Home
 * @license		http://www.gnu.org/licenses/gpl.html
 */

 
 // --------------------------------------------------------------------
 
/**
 * Returns wordpress content through codeigniter index file
 *
 * @access	public
 * @param	string	full path to codeigniter index file
 * @param	string	class and method being called in uri format
 * @param	boolean	true to echo, false to return
 * @return	string
 */	
if (!function_exists('wp_ci_initiliaze'))
{
	function wp_ci_initiliaze($ci_index = null, $controller_method = '/', $echo = TRUE)
	{
		$GLOBALS['wp_ci_is_wp'] = TRUE;
		if (empty($ci_index))
		{
			$ci_index = realpath('../../../../index.php');
			
			$_SERVER['PATH_INFO'] = $controller_method;
			$_SERVER['REQUEST_URI'] = $controller_method;
		}
		
		ob_start();
		@include($ci_index);
		$GOBALS['CI'] =& get_instance();
		$output = ob_get_contents();
		ob_end_clean();
		
		if($echo)
		{
			echo $output;
			return;
		}
		
		return $output;
	}
}

// --------------------------------------------------------------------

/**
 * Boolean check to see call accesses wordpress content
 *
 * @access	public
 * @return	string
 */	
function is_wp()
{
	return ($GLOBALS['wp_ci_is_wp']);
}

// --------------------------------------------------------------------

/**
 * Loads codeigniter config.php content into global config array
 *
 * @access	public
 * @param	string	config file name
 * @return	array
 */
function wp_ci_load_config($file = 'config')
{
	if (empty($GLOBALS['config']))
	{
		$GLOBALS['config'] = array();
		@include(APPPATH.'/config/'.$config.'.php');
	}
	
	$GLOBALS['config'] = array_merge($GLOBALS['config'], $config);
	
	return $GLOBALS['config'];
}

// --------------------------------------------------------------------

/**
 * Get config item content by passing key
 *
 * @access	public
 * @param	string	config item key
 * @return	mixed
 */
function wp_ci_config_item($item)
{
	return (isset($GLOBALS['config'][$item])) ? $GLOBALS['config'][$item] : FALSE;
}

// --------------------------------------------------------------------

/**
 * Print codeigniter view file in wordpress content
 *
 * @access	public
 * @param	string	view file name
 * @param	array	array of variables to be rendered
 * @param	boolean	true to return, false to return 
 * @return	mixed
 */
function wp_ci_load_view($view, $vars = array(), $return = FALSE)
{
	extract($vars);
	$file = APPPATH.'/views/'.$view.'.php';
	$contents = file_get_contents($file);
	
	$CI =& get_instance();
	$check_funcs = array('site_url(', '$this->');
	$replace_funcs = array('site_url_wp_safe(', '$CI->');
	
	$contents = str_replace($check_funcs, $replace_funcs, $contents);
    $contents = eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', $contents)).'<?php ');
	
	if ($return === TRUE)
	{
		return $contents;
	}
	echo $contents;
}

// --------------------------------------------------------------------

/**
 * Load codeigniter helper file in wordpress content
 *
 * @access	public
 * @param	string	helper prefix name
 * @return	void
 */
function wp_ci_load_helper($helper)
{
	@require_once(APPPATH.'/helpers/'.$helper.'_helper.php');
}

// --------------------------------------------------------------------

/**
 * Load codeigniter library file in wordpress content
 *
 * @access	public
 * @param	string	library file name
 * @return	void
 */
function wp_ci_load_library($library)
{
	@require_once(APPPATH.'/libraries/'.$library.'.php');
}

// --------------------------------------------------------------------

/**
 * Return full qualified url with uri passed as argument
 *
 * @access	public
 * @param	string	uri to be added to base domain
 * @return	string
 */
function site_url_wp_safe($uri = null)
{
	$CI = get_instance();
	
	if (is_array($uri))
	{
		$uri = implode('/', $uri);
	}

	if ($uri == '')
	{
		return $CI->config->slash_item('base_url').$CI->config->item('index_page');
	}
	else
	{
		$suffix = ($CI->config->item('url_suffix') == FALSE) ? '' : $CI->config->item('url_suffix');
		return $CI->config->slash_item('base_url').$CI->config->slash_item('index_page').preg_replace("|^/*(.+?)/*$|", "\\1", $uri).$suffix;
	}
}	

/* End of file wordpress_helper.php */
/* Location: ./application/helpers/wordpress_helper.php */