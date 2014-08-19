<?php
include 'import.php';


// Creating conection to MySQL
$db = mysql_connect("localhost", "3_mnf_user_foro", ".9l_95ZgazX.");
$res = mysql_select_db("3_mnf_foro", $db);


// File to read:
$file = 'db.txt';

// Conversion
$t = new odtToDB();
//$t->resourceDB = $db;
//$t->exportFile = 'test.sql';
$t->import('db.txt');
