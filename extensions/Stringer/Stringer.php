<?php  /**
 * @name Stringer for Kernel Web
 * @version 1.1
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category plugin
 * 
 * This class manipulate strings
 */

class Stringer {
	/**
	 * @name getStringBetween
	 * This function get a string between two strings
	 * @param string $haystack String to read
	 * @param string $start String since start to read (this string not included)
	 * @param string $end String to read (this string not included)
	 * @param int $offset If specified, search will start this number of characters counted from the beginning of the string.
	 * @param string $default Default text if it can't retrieve the text.
	 * @return string 
	 */
	static function getStringBetween($haystack, $start, $end, $offset = 0, $default = '')  {
		// Initial position
		$posi =  strpos($haystack, $start) + strlen($start);
	
		if ($posi !== false) {
			// Exist the start

			// End position
			$pose = ($end != '') ? (strpos($haystack, $end, $posi) - $posi): '';
		
			if ($pose !== false) 	{
				// Exist the end
				
				// Get the text between
				return substr($haystack, $posi, $pose);
			}
		}
		return '';
	}
}
?>