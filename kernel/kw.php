<?php /**
 * @name KernelWeb
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category Core
 * 
 * Description:
 * This is the main class of kernel, it controll the main features.
 * You can use any function using kw::functionName();
 */

class kw {
	
	static $debug = false;
	static $dir; // This will be autoassigned once load "index.php". It specify ABSOLUTE URL to kernel web
	static $app_dir; // This too will autoassigned once load "index.php" Specify ABSOLUTE URL to Main APP (server, NOT http)
	static $config; // It contain all configuration from "config.php". It is autoloaded everytime
	static $lang;
	
	/**
	 * Check if plugin exist and include it. DON'T USE IT MANUALLY
	 * Plugins autoenabled have a .en in plugins file
	 * If file exists, require it.
	 */
	static function init($plugin) {
		$fl = kw::$dir.'extensions/'.$plugin.'/'.$plugin.'.php';
        if (is_file($fl)) {
        	include_once $fl ; 
	        return true;
        }
        return false;
	}
	static function addModel($model) {
		$fl = kw::$app_dir.'model/'.$model.'.php';
		if (is_file($fl)) {
			include_once $fl;
			return true;
		}
	}
	
	static function readConfig($conf) {
		require kw::$app_dir . 'private/config/'.$conf.'.conf.php';
	}
	/**
	 * @name start
	 * This function load main functions kernel and include controllers, models and others
	 */
	static function start($def_module, $def_controller, $def_action) {
		// Cargamos el idioma
		self::loadlang();
		
		// preparamos una URL con los valores recibidos y utilizando los de defecto si nos falta alguno importante (o todos)
		$module = kw::get('m', $def_module);
		$controller = kw::get('c', $def_controller);
		$action = kw::get('a', $def_action);
		
		$file = kw::$app_dir . 'private/modules/' . $module . '/controllers/' . $controller . '.php';
		
		// Intentamos cargar el archivo si existe, si no, cargaremos el de error
		// TODO: Que cargue el de error
		if (is_file($file)) include $file;
		else {
			$file = kw::$app_dir . 'private/modules/' . $module . '/controllers/errors.php';
			// El archivo no existe... ¿tampoco el modulo?
			if (is_file($file)) {
				require $file;
				$controller = 'errors';
				$action = 'e404';
			} else {
				// Error fatal, redirigimos a lo que en teoria es el modulo por defecto
				header('location: ' . rewriter($def_module, $def_controller, $def_action));
				die();
			}
		}
		
		// Cargamos la configuracion propia de la aplicacion (es mas rapido hacerlo manualmente que a traves de la funcion)
		require kw::$app_dir . 'private/config/app.conf.php';
		require kw::$app_dir . 'private/config/config.conf.php';
		
		// Preparing names to launch actions
		$c = $controller . 'Controller';
		$a = $action . 'Action';
		
		// Ahora ejecutamos la clase (controller) y la accion escogida (action)
		// Ademas especificamos la vista por defecto ($action)
		// Se puede especificar manualmente en cada action con "$this->view"
		$app = new $c($module, $controller, $action);
		$app->$a(); // Cargamos el controlador
		$app->loadView(); // Cargamos la vista
	}
	
	
	static function filterText($text) {
		return $text;
	}
	
	/**
	 * Check if $_GET[$name] exist and return its value
	 * If GET not defined, return $def (default)
	 *
	 * @param string $name Index ($_GET[name]) 
	 * @param string $def Default value if index GET is not defined
	 * @return string 
	 */
	static function get($name, $def = '') {
		// Guardamos una pequeña cache de los gets ya leidos para evitar cargas
		static $get = array();
		
		// Leemos los GET
		if (!isset($get[$name])) {
			$get[$name] = isset($_GET[$name]) ? self::filterText($_GET[$name]) : $def;
			//unset($_GET[$name]); // Delete $_GET require more memory RAM than non delete
		}
		return $get[$name];
	}
	
	/**
	 * Check if $_POST[$name] exist and return its value
	 * If POST not defined, return $def (default)
	 *
	 * @param string $name Index ($_POST[name]) 
	 * @param string $def Default value if index POST is not defined
	 * @return string 
	 */
	static function post($name, $def = '') {
		// Guardamos una pequeña cache de los posts ya leidos para evitar cargas
		static $post = array();
		
		// Leemos los POST
		if (!isset($post[$name])) {
			$post[$name] = isset($_POST[$name]) ? self::filterText($_POST[$name]) : $def;
			//unset($_POST[$name]);
		}
		return $post[$name];
	}
	
	/**
	 * Check if $_REQUEST[$name] ($_GET, $_POST and finally $_COOKIE) exist and return its value
	 * If REQUEST not defined, return $def (default)
	 *
	 * @param string $name Index ($_REQUEST[name]) 
	 * @param string $def Default value if index REQUEST is not defined
	 * @return string 
	 */
	static function req($name, $def) {
		// Guardamos una pequeña cache de los posts ya leidos para evitar cargas
		static $req = array();
		
		// Leemos los REQUEST
		if (!isset($req[$name])) {
			$req[$name] = isset($_REQUEST[$name]) ? self::filterText($_REQUEST[$name]) : $def;
			//unset($_REQUEST[$name]);
		}
		return $req[$name];
	}
	
	/**
	 * Check if $_SESSION[$name] exist and return its value
	 * If session not defined, return $def (default)
	 * 
	 * @param string $name Session Index name 
	 * @param string $def Default value if Session not exist OR value to write
	 * @param string $write If is true, then save the session
	 * @return string 
	 */
	static function ses($name, $def = null, $write = false) {
		if ($write) return $_SESSION[$name] = $def;
		return isset($_SESSION[$name]) ? $_SESSION[$name] : $def;
	}
	
	/**
	 * Log to a file
	 *  log result is: [LEVEL] DATE [CATEGORY] MESSAGE
	 * @param string $message
	 * @param string $type
	 * @param string $category
	 */
	static function log($message, $level = 'info', $category = 'log') {
		$logline = array($message, $level, $category, date("YYYY/MM/dd H:i:s"));
		foreach (kw::$config['log'] as $log) {
			If ($log['level'] >= $logline[1]) {
				$line = $logline[3]." ".$logline[2]." ".$logline[1]." ".$logline[0];
				$fp = fopen(kw::$dir.$log['file'], "ab");
				fwrite($fp,$line);
				fclose($fp);
			}
		}
	}
	
	/**
	 * @name loadLang This function select the preferred language to the user (if him not select any)
	 */
	static function loadlang(){
		// Intentamos leer el idioma del get
		$lang = kw::get('l', null);
		if (in_array($lang, kw::$config['languages'])) {
			// Language is correct, save it
			kw::ses('lang', $lang, true);
		} else {

			// Si el idioma no esta disponible lo intentamos coger de la SESSION
			$lang = null; //= kw::ses('lang', null);
			
			if($lang == null) {
				// Como no hay un idioma predefinido, intentamos cargar el que mas le convenga al usuario
				// en funcion a la configuracion de su navegador
				// Si no encontramos ninguno relacionado utilizaremos el primero del array
				// Despues lo guardamos en la SESSION
				$lang = Language::prefered_language(kw::$config['languages']);
				
				kw::ses('lang', $lang, true);
					
				// Metodo alternativo (requiere PECL)
				//http_negotiate_language( kw::$config['languages'], $userlang );
			}
		}
		
		// Setting language
		kw::$lang = $lang;
	}
	
	
	/**
	 * @name t (translate) This function show a translated text
	 *  It selfinclude the language files
	 *  All files have to be located in $app/private/modules/$module/lang/$lang-$LOCALE/$category (controller or something)
	 * @param string $text Specify type of file (the name)
	 * @param string $category
	 * @param string $params
	 */
	static function t($text, $category = 'generic', $params = array()) {
		static $texts = array();
		// Loading main language file
		//include (kw::$dir . "system/language/".$lang.".php");
		
		if (isset($texts[$category][$text])) {
			//if exist the text to translate
			return $texts[$category][$text];
		} elseif (!isset($texts[$category])) {
			// main category is not included
			$file = kw::$app_dir.'private/modules/'.kw::get('m').'/lang/'. kw::$lang .'/'.$category.'.php';
			if (is_file($file)) {
				// File exists
				$texts[$category] = include $file;
				return (isset($texts[$category][$text]) ? $texts[$category][$text] : $text);
			} else {
				// File not exists, so it will be empty
				$texts[$category] = array();
			}
		} 
		
		return $text;
	}
	
	/**
	 * @name te (translate extension) This function show a translated text OF AN EXTENSION
	 *  It selfinclude the language files
	 *  All files have to be located in kw::$dir/extensions/$EXTENSION/lang/$lang-$LOCALE.php
	 * @param string $text Specify type of file (the name)
	 * @param string $extensionName
	 * @param string $params
	 */
	static function te($text, $extension = '', $params = array()) {
		static $texts = array();
	
		if (isset($texts[$extension][$text])) {
			//if exist the text to translate
			return $texts[$extension][$text];
		} elseif (!isset($texts[$extension])) {
			// main category is not included
			$file = kw::$dir.'extensions/'.$extension.'/lang/'. kw::$lang . '.php';
			if (is_file($file)) {
				// File exists
				$texts[$extension] = include $file;
				return (isset($texts[$extension][$text]) ? $texts[$extension][$text] : $text);
			} else {
				// File not exists, so it will be empty
				$texts[$extension] = array();
			}
		}	

		return $text;
	}
}
?>