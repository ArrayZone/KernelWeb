<?php /**
 * @name KernelWeb & CMS: Index
 * @author ArrayZone
 * @package KernelWeb - System
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category core
 */

// Force show all errors (comment this when you finish the development)
error_reporting(E_ALL);


//include '../KernelWeb/index.php'; // Starting Kernel Web, this is the critical point of your app
include '../main.php';

// To test load time
// If PHP version is lower than 5.4 it would start count time now, else it get time since petition was send
// If you like disable this, comment this line and the last line "ServerStatus::calcTime();"
ServerStatus::calcTime();

// Execute Kernel Loader (with default controller and default action)
// it will load all pages
// You can specify what thing will be loaded if is not specified (for example, the default module)
kw::start('main', 'index', 'index');


// End test time
ServerStatus::calcTime();
// This is all your index.php file!!! What do you expect?
?>