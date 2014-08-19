<?php /**
 * @name InternetExplorer for Kernel Web
 * @version 1.1
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category plugin
 * 
 * Description: This script show special metas and message if you use InternetExplorer
 */

class InternetExplorer {
	
	/**
	 * @name metas This function revise if the current Navigator is Internet Explorer and if is true, it show generic JS and metas to explorer
	 */
	static function metas() {
		// SI usamos internet explorer, mostrara una meta que forzara a usar IE8 (y no la retro-compatibilidad)
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
			// http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js
			echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<!--[if lt IE 9]>
				<script src="'.app::$tdir.'js/html5shiv.js"></script>
				<script src="'.app::$tdir.'js/respond.min.js"></script>
			<![endif]-->';
		}
	}
	
	/**
	 * @name warning This function revise if the current Navigator is Internet Explorer and if is true, show a message to upgrade if is older version
	 */
	static function warning () {
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) {
			echo '<!--[if lte IE 9]>
				<div class="warning">'.kw::t('Please, update your Internet Explorer version or change to:', 'kw/InternetExplorer').' <a href="http://www.mozilla.org/es-ES/firefox/new/" target="_blank">Mozilla Firefox</a> o <a href="http://www.google.com/intl/es_es/chrome/" target="_blank">Google Chrome</a></div>
			<![endif]-->';
		}
	}
}
?>