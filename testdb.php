<h1>Pruebas de base de datos</h1>
<?php
include 'extensions/db/dbscheme.class.php';
include 'extensions/db/mysql_db.plugin.php';
include 'extensions/db/db.php';

/*db::connect('name1', 'mysql', 'localhost', 'pruebas', 'root', 'test');
db::createcommand('SELECT * FROM alumnos', 'name1')->execute();
*/


$db = new mysql_db();
$db->connect('localhost', 'pruebas', 'root', 'test');
$t = $db->createcommand('SELECT * FROM alumnos', 'name1');


while ($row = mysql_fetch_assoc($t)) {
	print_r($row);
	echo '<hr />';
}


echo '<h2>Other</h2>';
$db2 = new mysql_db();
$db2->connect('localhost', 'arrayzone', 'root', 'test');
$t = $db2->createcommand('SELECT * FROM az_users', 'name1');
while ($row = mysql_fetch_assoc($t)) {
	print_r($row);
	echo '<hr />';
}

/*echo '<h2>Another</h2>';
$t = $db->select(array('*'))->from(array('cargos'));
while ($row = mysql_fetch_assoc($t)) {
	print_r($row);
	echo '<hr />';
}*/