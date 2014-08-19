<?php /**
 * @name filter for Kernel Web
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category plugin
 * 
 * Description: This script return string filtered
 */

class Filter {
	/**
	 * @name deleteRepeatedChar This function remove all char repeated
	 * EX: If we will remove "-", if you have "this---is-my--text" it will return "this-is-my-text" 
	 * @param string $string String to repalce
	 */
	static function deleteRepeatedSpaces ($string, $charToRemove = ' ') {
		return preg_replace('/'.$charToRemove.$charToRemove.'/', '', $string);
	}
	
	/**
	 * @name genericText this function clean any string to text plain
	 * @param string $string String to filter
	 * @param boolean/string $removeWhitespaces If is true, it remove whitespaces. If is specified, reeplace by specific string 
	 * @param boolean $specials If is true, remove all characters except: letters, numbers, "_" and "whitespaces"
	 * @param string $specific_preg If is specified, replace too the specific preg
	 * @return string Text filtered
	 */
	static function genericText($string, $removeWhitespaces = false, $specials = false, $specific_preg = '') {
		// Converting tildes to nontildes
		$string = strtr($string, 
				'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ‗אבגדהוזחטיךכלםמןנסעףפץצרשתû‎‎‏ÿRr', 
				'AAAAAAACEEEEIIIIDNOOOOOOUUUUYBSaaaaaaaceeeeiiiidnoooooouuuyybyRr');
	
		// Deleting duplicated spaces
		$string = self::deleteRepeatedSpaces($string);

		if ($removeWhitespaces) {
			// Remove white spaces?
			if ($removeWhitespaces !== TRUE) $string = preg_replace('([ ])', $removeWhitespaces, $string); // Reeplacing whitespaces by $removeWhitespaces string
			else $string = preg_replace('([ ])', '', $string); // Delete all spaces
		}
		
		
		// Remove special chars?
		if ($specials) {
			// prepare specific filter
			//$filter = '([^A-Za-z0-9_\- ])';
			if ($removeWhitespaces !== FALSE) $filter = '([^A-Za-z0-9_'.$removeWhitespaces.'])';
			elseif ($removeWhitespaces === FALSE) $filter = '([^A-Za-z0-9_ ])';
			else $filter = '([^A-Za-z0-9_])';
			
			$string = preg_replace($filter, '', $string); //Eliminamos el resto de suciedad del codigo
		}
		
		if ($specific_preg != '') $string = preg_replace($specific_preg, '', $string);
		
		return $string;
	}
	
	
	/**
	 * @name file this function filter email address
	 * @param string $string File name to filter
	 * @return string Email filtered
	 */
	static function email($string) {
		// Filter the string cleaning tildes and whitespaces
		return self::genericText($string, true, false, '([^A-Za-z0-9_@.\-])');
	}
	
	/**
	 * @name file this function filter file name
	 * @param string $string File name to filter
	 * @param string/boolean $changeSpaces String replace spaces, if is true, only delete spaces 
	 * @return string File name filtered
	 */
	static function file($string, $changeSpaces = '') {
		// Filter the string cleaning tildes and whitespaces
		return self::genericText($string, $changeSpaces, false, '([^A-Za-z0-9_\-.])');
	}
}