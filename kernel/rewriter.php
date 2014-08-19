<?php /**
 * @name KernelWeb & CMS: Index
 * @author ArrayZone
 * @package KernelCMS
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category core
 * 
 * Description:
 * This script create the main function to show URLs depending if rewrite is on or off
 * It load only one time the functions trhough "IF", with this method it not have to test every time
 * "if" for each URL
 */

/**
 * @name rewriter
 * @description This function write a URL depending if rewrite is on or not
 * @param string $controller Controller name
 * @param string action Action of Controller
 * @param string get Optional GET (without initial "?")
 * @return string URL absolute of script
 */
if (isset(kw::$config['rewrite']) and kw::$config['rewrite']) {
	// Function when rewrite is on
	function rewriter($module = '', $controller = '', $action = '', $get = '', $lang = '') {
		// Base URL
		$r = kw::$config['url_base'];
		
		// Actions
		$r .= kw::ses('lang') . '/';
		$r .= (($module != '') ? $module : kw::get('m')) . '/';
		$r .= (($controller != '') ? $controller : kw::get('c')) . '/';
		$r .= (($action  != '') ? $action : kw::get('a')) . '/';
		
		// Final GET
		if ($get != '') $r .= '?' . $get;
		
		return $r;
	}
	
} else {
	// Rewrite is off? No problem	
	function rewriter($module = '', $controller = '', $action = '', $get = '', $lang = '') {
		// Base URL
		$r = kw::$config['url_base'];

		// Actions
		$r .= '?lang=' . kw::get('lang');
		$r .= '&m=' . (($module != '') ? $module : kw::get('m'));
		$r .= '&c=' . (($controller != '') ? $controller : kw::get('c'));
		$r .= '&a=' . (($action  != '') ? $action : kw::get('a'));
	
		// Final GET
		if ($get != '') $r .= '&' . $get;
		
		return $r;
	}

}


/**
 * @name showChangeLanguage
 * @param array $langs List with all languages
 */
function showChangeLanguage($langs, $flagsDir = null) {
	foreach ($langs as $lang) {
		if ($lang != kw::$lang) {
			echo '<a href="' , kw::$config['url_base'] , $lang , '/">' , (($flagsDir != null) ? '<img src="'.$flagsDir . $lang . '.png" alt="'.$lang.'" />' : $lang) , '</a>';
		}
			
	}
}