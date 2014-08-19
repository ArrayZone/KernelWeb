<?php /**
 * @name KernelWeb & CMS
 * @author ArrayZone
 * @package KernelWeb - System
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category configuration
 * 
 * Here you can configure the main settings of "system" to interact with KernelWeb easily
 */

class sysconf {
	/*
	 * $enableSystem If is true, it will accept petitions to System
	 */
	static $enableSystem = true;
	
	/*
	 * $users Contain an array with all users and him passwords (in sha1)
	 * This users only used to enter in "system" when access is
	 * $enableSystem must be TRUE 
	 */
	static $users = array(
		'admin' => 'd033e22ae348aeb5660fc2140aec35850c4da997' // test
	);
	
}