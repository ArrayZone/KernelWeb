<?php 
/**
 * @name DB Abstract Class
 * @author ArrayZone
 * @package KernelWeb
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category core
 */
abstract class dbscheme {
	public $name;
	
	/**
	 * @name DBScheme Connect Function
	 * @tutorial Create the connection referenced to the $type database and save in the $connections array
	 * @param string $name
	 * @param string $type
	 * @param string $host
	 * @param string $dbname
	 * @param string $username
	 * @param string $password
	 * @param number $port
	 */
	abstract public function connect($host, $dbname, $username, $password, $port);
	
	/**
	 * @name DBScheme CreateCommand Function
	 * @tutorial Must be defined always. Define a start of a DB Command. If its called with a QueryBuilder format, do a querybuilder call, otherwise, query the argument
	 * @param string $query SQL or NO-SQL sentence between quotes
	 */
	abstract public function createcommand($query);
	
	/**
	 * db QueryBuilder: Make SQL creation easier
	 */
	/**
	 * Query Builder Select Function. 
	 * Inputs the SELECT sentence on the Query
	 * @param array $array
	 */
	abstract public function select(array $array = array());
	
	/**
	 * Query Builder From Function.
	 * Input the FROM sentence on the Query
	 * @param array $array
	 */
	abstract public function from(array $array = array());
	
	/**
	 * Query Builder Where Function
	 * Input the WHERE sentence on the Query
	 * @param array $array
	 * @return 
	 */
	abstract public function where(array $array = array());
	
	/**
	 * Executes the Query.
	 * If the query is inserted with the CreateCommand function, 
	 * executes the query directly. if QueryBuilder its used,
	 * executes it building before execute.
	 * @param array $array
	 */
	abstract public function execute(array $array = array());
	
	/**
	 * Verify the Status of the server.
	 * If the connection is made, return true, else return false
	 * @return boolean
	 */
	static function verifyconnection(){
		//TODO: "ping" a la base de datos.
	}
	
}
?>