<?php /**
 * @name KernelWeb
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category Core
 * 
 * Description:
 * This script start all Kernel Web Services to work correctly.
 * Youre script only need have this file included to work
 */

// Starting session
session_start();

// Loading the directory location
$dir = dirname(__FILE__).'/';

// Loading the Kernel, configuration is included 
require_once($dir.'kernel/kw.php');

// Main KernelWeb Variables
kw::$dir = $dir; // Kernel main auto-settings - Kernel Web directory


// Main controllers
require $dir.'kernel/config.php';
require $dir.'kernel/kwcontroller.php';
require $dir.'kernel/kwview.php';
require $dir.'kernel/rewriter.php';

/* 
 * AutoLoader Creator
 * If we try to use a non loaded class, the framework try to start it.
 * It's possible to add as must as loaders as we want, only one pe
 */
spl_autoload_register("kw::init");
spl_autoload_register("kw::addModel");


// Forcing close session... It delete the session...
//if (session_status() != PHP_SESSION_NONE) session_write_close();
?>