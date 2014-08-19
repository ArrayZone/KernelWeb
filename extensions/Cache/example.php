<?php 
include 'cache.php';

cache::$html_dir = 'test/html/';
cache::$php_dir = 'test/php/';

function test($string1 = '', $string2 = '') {
	echo $string1 ."<br />\n";
	echo $string2 ."<br />\n";
}

echo 'Saving data...';
echo '<hr />';

// Saving cache from function
cache::buildFromFunction('html', 'gen', 'first', 'test', array('kernel','web'));

// Saving cache directly from var
$myData = 'All data that you need';
cache::buildFromString('html', 'gen', 'second', $myData);

// Saving PHP
/*cache::buildFromString('php', 'gen', 'FirstPHP', '<?php echo "See arrayzone.com?"; ?>');*/



// LOADING
echo '<h1>Loading data</h1>';
		
// Load simple html
cache::load('html', 'gen', 'first');


// Load PHP (with 50 min maximum of life)
if (!cache::load('php', 'gen', 'FirstPHP', 1)) {
	// Saving PHP again
	cache::buildFromString('php', 'gen', 'FirstPHP', '<?php echo "See arrayzone.com?"; ?>');
	
	// And loading the last builded
	cache::loadLast();
}
?>