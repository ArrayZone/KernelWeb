<?php 
/*
 * FUNCTION ENCODE
 */

// Limit unlimited
set_time_limit(-1);

include '../Stringer/Stringer.php'; // Dependence to work

// Creating and configuring the decoder
// THIS IS ONLY AN EXAMPLE, IT WON'T WORK BECAUSE THE DIRECTORY TEST NOT EXIST
$t = new Base64Encoder();
$t->excludeFiles = array('.', '..', 'Thumbs.db'); // Discard this files (. and .. is important)
$t->foldersEncrypt = array('epanel', 'controladores', 'view', 'webservice');
$t->foldersSkip = array('img', 'cache', 'uploads'); // Folders to ignore


// ENCOODING COMPLETE DIRECTORY
$t->source = 'site/';
$t->dest = 'obfuscated/';
//$t->execute();


//DECODING COMPLETE DIRECTORY
$t->source = 'obfuscated/';
$t->dest = 'desobfuscated/';
//$t->execute('decode');
?>