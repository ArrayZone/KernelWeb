<?php
include 'paginator.php';

/*
 * To see all params to configure, please, open "paginator.php"
 * You can paginate ANYTHING you like (not only tables from database)
 */
// Its recomended have a count of all records of a table to count (if you use SQL or something)
// and pass directly total records

/*
 * EXAMPLE PART 1: Loading total records from database:
 * $records = mysql_num_rows(mysql_query("SELECT count(*) as tot FROM mytable WHERE YOUR_FILTER_OPTIONAL));
 */

/*
 * EXAMPLE PART 2: Paginating records 
 */
$test = new Paginator(); // Init pagination
//$test->total_records = $records['tot']; // Records loaded from query
$test->total_records = 50; // Total records
$test->class = 'pagination'; // Specify div class
$test->specific_get = array();
$test->paginate(); // refresh and update all calcs and settings


echo $test->show(); // Showing all links

/*
 * EXAMPLE PART 3: loading results from database:
 * $records = mysql_query("SELECT * FROM mytable WHERE YOUR_FILTER_OPTIONAL $test->limit");
 * 
 * while ($row = mysql_fetch_assoc($records)) {
 * 	echo $row['yourField'];
 * }
 */
echo '<br />';

echo 'First record of current page: '.$test->first_record.'<br />';
echo 'LIMIT to DB: '.$test->limit.'<br />';