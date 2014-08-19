<?php /**
 * @name odtToDB for Kernel Web
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category plugin
 * 
 * Description: This script can convert an exported "ODT" file with PHP My Admin to SQL file (or import directly to data base)
 * It only work if you paste all ODT file in TXT FILE!!
 * Is recomended create table structure of database previously because ODT files not contain information about Foreing Keys and Indexs...
 * 
 * This is not really a plugin (not now), is a casual script.
 * 
 * You can edit this file freely to import to another type of database (is very easy, only read the coments trhough the file)
 * 
 * NOTE: Import to MySQL is very slow, but convert to another file is very fast (if you wish do some test).
 * NOTE2: DON'T IMPORT DIRECTLY TO A PRODUCTION DATABASE, do test in other database
 */

class odtToSQL {
	/**
	 * @param string $stringStructure Specify the text to start to read a table
	 */
	public $stringStructure = 'Estructura de tabla para la tabla ';
	
	/**
	 * @param string $stringData Specify the text to start to read the data
	 */
	public $stringData = 'Volcado de datos para la tabla ';
	
	/**
	 * @param string $columnDelimiter Column separator 
	 */
	public $columnDelimiter = '	';
	
	/**
	 * @param resource/null $resourceDB If is specified, it will execute a MySQL Query
	 */ 
	public $resourceDB = null;
	
	/**
	 * @param string exportFile If is specified, it will save the querys in file
	 */ 
	public $exportFile;
	
	/**
	 * @param number extendedInsert Specify maxium extended insert, if 1 it not do extended
	 */
	public $extendedInsert = 1;
	
	/**
	 * @param resource $fileOpened Used to know if a file is open
	 */
	private $fileOpened;
	
	/**
	 * @name import
	 * @param string $file Filename to import (it must be txt)
	 * @param resource $resourceDB DB MySQL Resource to import 
	 */
	public function import($file) {
		set_time_limit(-1); // Changing time limit
		
		$maxExtended = $this->extendedInsert;
		
		// To avoid problems with end line files
		ini_set("auto_detect_line_endings", true);
		
		if (!$f = $this->fileOpen($file)) {
			return false;
		}

		try {
			// File is open... start all processes
			$action = ''; // This contain the current action reading (table information or data)
			$delimiter = $this->columnDelimiter;
			$tableStruct = $this->stringStructure;
			$dataStruct = $this->stringData;
			
			$structLen = strlen($tableStruct);
			$dataLen = strlen($dataStruct);
			
			$query = ''; // This will contain the query to execute
			$table = ''; // Current table
			$header = ''; // Header to table data
			$tot_col = 0; // Total columns (-1)
			
			$clear = true; // If is true, it NOT add ',' when add new line to create table
			
			$total = 0; // Total insert added to the query, if it is biggest than 40 it execute the query extended
			
			// Read all lines
			while (!feof($f)) {
				// Read current line
				$line = fgets($f);

				// new action? (in the first key)

				// If is numeric, sure is data, so skip it
				if (!is_numeric($line)) {
					// Some conicidence?
					if (strpos($line, $tableStruct) !== FALSE) {
						// End last insert query
						if ($query != '') $this->execute($this->startQuery() . $table . ' '. $header  .' VALUES ' . PHP_EOL . $query);
						
						// Is a table, so start to made it
						$action = 'structure';
						$table = trim(substr($line, $structLen));
						$clear = true;
						
						// starting query
						$query = 'CREATE TABLE IF NOT EXISTS `' . $table . '` (' . PHP_EOL;

						// Skiping this line and the header
						fgets($f);
						continue;
					}  elseif (strpos($line, $dataStruct) !== FALSE) {
						// Is start to data
						$this->execute($query);
			
						$action = 'data';
						$table = trim(substr($line, $dataLen));
						$query = '';
					
						// Getting header (convert string to header type)
						$total = 0;
						$line = fgets($f);
						$header = '(`' . str_replace($delimiter, '`,`', trim($line)) . '`)';
						$tot_col = substr_count($header, ',') - 1;
						continue;
					}
				}

				// DATA GENERATOR
				if ($action != '') {
					// Extracting information from line
					$data = explode($delimiter, $line);
					switch ($action) {
						case 'data':
							if (!isset($data[$tot_col])) continue;
							
							if ($total++ > 0) $query .= '), ';
							
							$query .= '(';
							// Process data
							$c = 0;
							foreach ($data as $t) {
								// , if is needed
								if ($c > 0) $query .= ', ';
								else ++$c;
								
								if (!is_numeric($t)) {			
									// THIS CONVERSION IS OPTIONAL, IF YOU DON'T NEED COMMENT IT (used for SMF)
									// First convert all \ to \\ to scape the caracter
									// Finally convert ' to \' to can scape all '
									$query .= '\''.str_replace('\'', '\\\'',  str_replace('\\', '\\\\', trim(utf8_decode($t)))).'\'';
									
									// ALTERNATIVE (USED FOR WORDPRESS)
									//$query .= '\''.str_replace('\'', '\\\'',   trim(utf8_decode($t))).'\'';
								} else {
									$query .= $t;
								}
							}
			
							// Extended insert...
							if ($total >= $maxExtended) {
								$this->execute($this->startQuery() .  $table . ' '. $header .' VALUES ' . PHP_EOL . $query);
								$query = '';
								$total = 0;
							}
			
							break;
						case 'structure':
							// Process table creation
			
							// Adding , and end line
							if (!$clear) $query .= ',' . PHP_EOL;
							else $clear = false;
			
							// Preparing query line
							$query .= '`'.$data[0].'` '.
									$data[1].' ';
									if ($data[2] == 'No') {
										// It can be null
										$query .= 'NULL ';
										if ($data[3] != '' and $data[1] != 'longtext') {
											if ($data[3] != 'CURRENT_TIMESTAMP') $query .= 'DEFAULT \''.$data[3].'\'';
											else $query .= 'DEFAULT '.$data[3];
										}
									} else{
										$query .= 'NOT NULL ';
										if ($data[3] != '' and $data[3] != 'NULL' and $data[1] != 'longtext') $query .= 'DEFAULT \''.$data[3].'\'';
									}
							break;
					}
				}		
			}
			
			
			// END QUERY
			if ($query != '') {
				// Querys to do
				switch ($action) {
					case 'data':
						$this->execute($this->startQuery() . $table . ' '. $header . ' VALUES ' . PHP_EOL . $query);
						break;
					case 'structure':
						$this->execute($query);
						break;
				}
			}
			
		} catch (Exception $e) {
			print_r($e);
		}
		fclose($f);

	}
	
	/**
	 * This function made the first part of insert sentence
	 * @name startQuery
	 * @return string
	 */
	private function startQuery() {
		return 'REPLACE INTO ';
	}
	
	/**
	 * Open a file if exist
	 * @name fileOpen
	 * @param string $file File to open
	 */
	private function fileOpen($file) {
		
		if (is_file($file)) {
			try {
				$fl = fopen($file, 'r');
				return $fl;			
			} catch (Exception $e) {
				print_r($e);
				die();
			}
		} else {
			echo 'The file '.$file.' not exist.';
			return false;
		}
	}
	
	/**
	 * This function execute/save the query
	 * @name execute
	 * @param string $query Query to exxecute
	 */
	private function execute($query) {
		static $file = '';
		static $link= '';
		
		// Initializing static vars
		if ($link === '') {
			
			if ($this->exportFile) {
				//unlink($this->exportFile);
				$file = fopen($this->exportFile, 'w+');
				$this->fileOpened = $file;
			} else {
				$file = null;
			}
			
			
			$link = ($this->resourceDB) ? $this->resourceDB : null;
		}
		
		
		if ($query != '') {
			// Add end to query
			$query .= PHP_EOL . ');' . PHP_EOL . PHP_EOL;
			
			// If it will be executed...
			if ($link) mysql_query($query, $link);
			if (mysql_error($link)) {
				echo 'Error on query:<br />'. $query.'<hr />';
				echo mysql_error($link);
				die();
			}
			// Write if is needed
			if ($file) {
				
				fwrite($file, $query);
			}	
		}
		
	}
	
	function __destroy() {
		if ($this->fileOpened) fclose($this->fileOpened);
	}
}
