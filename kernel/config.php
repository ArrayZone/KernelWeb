<?php /**
 * @name Configuration
 * @author ArrayZone
 * @package KernelWeb
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category core
 * 
 *
	 * Log avaible levels
	 * Log 
	 * -1 - DISABLED (NOT RECOMMENDED)
	 *  0 - FATAL
	 *  1 - ERROR
	 *  2 - WARNING (production mode)
	 *  3 - INFO
	 *  4 - DEBUG (Developer mode)
	 *  5 - TRACE
	 *  
 */

 /*
  * user configs
  */
 kw::$debug = true; 		// Kernel Web debug mode. Disable on production mode

/*
 * Main configurations
 * You can re-configure only some parts of here in $app/private/config/app.conf.php
 */
kw::$app_dir = getcwd().'/';
kw::$config = array(
	'name'=>'KernelWeb',
	'version'=>'A.0.3',
	'rewrite'=>true,
	'url_base'=>((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') ? 'https' : 'http') . '://'.$_SERVER['HTTP_HOST'] . 
		( (isset($_SERVER['SERVER_PORT']) and !in_array($_SERVER['SERVER_PORT'], array('80', '443'))) ? ':' . $_SERVER['SERVER_PORT'] : '' ) 
		. (($_SERVER['SCRIPT_NAME'] != '/index.php') ? dirname($_SERVER['SCRIPT_NAME']) : '') . '/',
	'languages'=> array('es-ES','ca-ES', 'en-EN'),
	'language_available_on_database' => false,
	'log'=>array(
		'base' => array(
			'level'=>2,
			'file'=>kw::$dir.'system/logging/kernelweb.txt',
		),
	),
	/*'db'=>array(	
	    'local'=>array(
	    	'type'=>'mysql',
	    	'dbname'=>'testdb',
	    	'host'=>'127.0.0.1',
	        'user'=>'root',
	        'password'=>'test',
	    ),
		'dbname'=>array(
			'type'=>'mysql',
			'dbname'=>'testdb',
			'host'=>'127.0.0.1',
			'user'=>'root',
			'password'=>'test',
		),
	),*/
);

?>