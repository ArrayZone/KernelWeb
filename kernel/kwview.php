<?php
class KWView {
	
	/**
	 * @name templatePart
	 * This function load a file located in $appdir/public/templates/$tempalte/$file
	 * @param string $file File to include
	 * @param string $cat_cache Category of cache
	 * @param string $name_cache Name of cache
	 * @param number $tcache Time in seconds maxium lifetime cache 
	 */
	static function templatePart($file, $cat_cache = null, $name_cache = null, $tcache = null) {
		// If it would use cache
		if ($name_cache) {
			$cat_cache = kw::ses('lang') . '/' . $cat_cache;
			// If not have cache (so it will not loaded)
			if (!Cache::load('html', $cat_cache, $name_cache, $tcache)) {
				// Generate it
				ob_start();
					require kw::$app_dir . 'public/templates/' . app::$template . '/'.$file.'.php';
					cache::buildFromString('html', $cat_cache, $name_cache, ob_get_contents() );
					// Once end, it send information to client
				ob_end_flush();
			}
		} else {
			// No cache, load directly
			require kw::$app_dir . 'public/templates/' . app::$template . '/'.$file.'.php';
		}
	}
	
	/**
	 * @name loadData
	 * This function load a file located in $appdir/private/modules/$module/$file.php
	 * The loaded file must RETURN something (like array or string), NOT echo (if you do this, use ob_start and return buffer)
	 * @param string $data
	 */
	static function loadData($file) {
		return require kw::$app_dir . 'private/modules/' . kw::get('m') . '/data/'.$file.'.php';
	}
	
	/**
	 * @name loadUrl This function generate an URL from array 
	 * @param string $name Text to show
	 * @param array $vals Array with information:
	 * 	mod = module,
 	 *	cont = controller,
 	 *	act = action,
 	 *	get = GET extra
 	 *	target = type of target in href,
 	 *	href = direct HREF (this is ignored if are "cont" or "act"
	 * @param string $classes Classes to show
	 * @return string URL
	 */
	static function loadUrl($name, $vals, $classes = '') {
		$r = '<a class="'.$classes.'"';
		
		// If have URL
		if (isset($vals['href'])) {
			$r .= ' href="';
			if (is_array($vals['href'])) {
				$mod = (isset($vals['href']['mod']) ? $vals['href']['mod'] : '');
				$cont = (isset($vals['href']['cont']) ? $vals['href']['cont'] : '');
				$act = (isset($vals['href']['act']) ? $vals['href']['act'] : '');
				$get = (isset($vals['href']['get']) ? $vals['href']['get'] : '');
				
				$r .= rewriter($mod, $cont, $act, $get);
			} else {
				$r .= $vals['href'];
			}
			$r .= '"';
		}
		
		
		if (isset($vals['target'])) $r .= ' target="' . $vals['target'] . '"';
		
		return $r .= '>' . $name . '</a>';
	}
}