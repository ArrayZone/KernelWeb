<?php /**
 * @name Cache for Kernel Web
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category plugin
 * 
 * Description: This script do a cache of PHP and HTML files, permanently and temporaly
 * If you wish change the directories you may change it before start to use it ($html_dir and $php_dir)
 * If you will do a cache of PHP file and it wouldn't have to do anything with php, you may save the cache in HTML
 * 
 */

class Cache {
	static $html_dir = 'cache/';
	static $php_dir = 'cache_php/';
	static $lastLoad = '';

	/**
	 * @name genDirName Generate the name of directory to use
	 * @param string $type Type of cache (php/html)
	 * @param string $category Name of subdir to categorize
	 * @return string Dir name route
	 */
	static function genDirName($type, $category) {
		$url = (($type == 'php') ? self::$php_dir : self::$html_dir); 
		if ($category != '') $url .= $category . '/';

		return $url; 
	}
	
	/**
	 * @name buildFromFunction
	 * @param string $type PHP/HTML
	 * @param string $category Subdir folder/s to organize cache
	 * @param string $name File name
	 * @param string $callFunction Function which will be called
	 * @param array $functionParameters Parameters to the function (in order)
	 * @param string $method Method to save file (w, w+, a, a+, ...)
	 * @return boolean
	 */
	static function buildFromFunction($type, $category, $name, $callFunction, $functionParameters = array(), $method = 'w') {
		$success = false;
		if (function_exists($callFunction)) {
			ob_start();
				try {
					// Obtaining data from function
					echo call_user_func_array($callFunction, $functionParameters);
					
					// Saving data
					$success = self::buildFromString($type, $category, $name, ob_get_contents(), $method);
				} catch (Exception $e) {
					echo 'Cache::BuildFromFunction says: ',  $e->getMessage(), "\n";
					die();
				} 
			ob_end_clean();
		} else {
			echo 'Function ' , $callFunction , ' is not available to do cache.';
			die();
		}
		
		return $success;
	}
	
	/**
	 * @name buildFromString This function save cache file
	 * @param string $type PHP/HTML
	 * @param string $category Subdir folder to organize cache
	 * @param string $name File name
	 * @param string $method Method to save file (w, w+, a, a+, ...)
	 * @return boolean
	 */
	static function buildFromString($type, $category, $name, $data, $method = 'w') {
		$dir = self::genDirName($type, $category);
		$file = $name . '.' . $type;
		
		$sucess = false;
		
		umask(0000);
		
		// Creating dir and changing permisions
		if (!is_dir($dir)) mkdir($dir, 0777, true);
		else chmod ($dir, 0777);
		
		// Changing permissions to file if exist
		if (is_file($dir . $file)) chmod($dir . $file, 0777);
		
		// Try to open file and write content
		try {
			$fl = fopen($dir . $file, $method);
			if (!fwrite($fl, $data)) {
				echo 'I can\'t save cache in ' , $category , '/' , $name , '. Test directory permissions';
			} else {
				$success = true;
			}
		} catch (Exception $e) {
			echo 'Cache::buildFromString says: ',  $e->getMessage(), "\n";
		}
		if (is_resource($fl)) fclose ($fl);
		
		// Saving the last file created
		self::$lastLoad = $dir . $file;
		
		return $success;
	}
	
	
	/**
	 * @name load This function include cache file
	 * @param string $type
	 * @param string $category
	 * @param string $name
	 * @param number $maxTime Time in SECONDS to life. NULL = unlimited
	 * 	if the time will end, it will self delete the file and return "false" 
	 * @return boolean
	 */
	static function load($type, $category, $name, $maxTime = null) {
		$file = self::genDirName($type, $category) . $name . '.' . $type;
		
		if (is_file($file)) {
			// Testing cache life
			if ($maxTime !== null) {
				if (isset(app::$cache) and !app::$cache) $maxTime = 0;
				$maxDate = filemtime($file) + $maxTime;

				// The file will be deleted
				if ($maxDate < time()) {
					self::deleteFile($type, $category, $name);
					return false;
				}
			}
			
			// Including file
			include ($file);
			return true;
		}
		return false;
	}
	
	/**
	 * @name loadLast Load the last builded cache
	 * You will use this when "load()" return false and you created the new content (for example)
	 */
	static function loadLast() {
		include self::$lastLoad;
	}
	
	/**
	 * @name delete It deletes a cache file
	 * @param string $type
	 * @param string $category
	 * @param string $name
	 * @return boolean Return true if all pass OK
	 */
	static function deleteCategory($type, $category) {
		$dir = self::genDirName($type, $category);
	
		// TODO: delteCategory
		if (is_dir($dir)) {
			deleteDirectoryOldDate($dir, false, true, true, true);
		}
	
		return false;
	}
	
	
	/**
	 * @name delete It deletes a cache file
	 * @param string $type
	 * @param string $category
	 * @param string $name
	 * @return boolean Return true if all pass OK
	 */
	static function deleteFile($type, $category, $name) {
		$file = self::genDirName($type, $category, $name) . $name . '.' . $type;
		
		if (!is_file($file) or unlink($file)) return true;
		
		return false;
	}
	
}