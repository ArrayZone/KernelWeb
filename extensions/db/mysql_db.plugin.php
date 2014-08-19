<?php 
/**
 * @name MySQL Plugin
 * @author ArrayZone
 * @package KernelWeb
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category plugin
 */
//http://www.phpclasses.org/browse/file/41026.html
class mysql_db extends dbscheme{
	private $connection;
	public $data;
	public $createcommand;

	/**
	 * Makes the connection to a database
	 * @param mixed $link User link
	 * @param string $user
	 * @param string $password
	 */
	public function connect($host, $dbname, $username, $password, $port = 3306) {
		$con = mysql_connect($host.':'.$port, $username, $password, true);
		mysql_select_db($dbname, $con);
		$this->connection = $con;
		//return $this->connection = new PDO($type.':host='.$host.';port='.$port.';dbname='.$dbname, $username, $password);
	}
	
	/**
	 * @name MySQL - CreateCommand
	 * Must be defined always. Define a start of a DB Command.
	 * If its called with a QueryBuilder format, do a querybuilder call,
	 * otherwise, query the argument.
	 * @param string $query
	 * @return PDOStatement
	 */
	public function createcommand($query) {
		return mysql_query($query, $this->connection);
	}
	
	public function select(array $array = array()) {
		return 'SELECT '.implode(', ', $array);
	}
	
    public function from(array $array = array()) {
    	return ' FROM '.implode(', ', $array);
	}
	
	public function where(array $array = array()) {
	    return ' WHERE '.implode(', ', $array);
	}
	
	public function limit($limit_start = 0, $limit_count = '') {
		$r = ' LIMIT '.$limit_start;
		if ($limit_count != '') $r .= ',' . $limit_count;
	}
	
	
	/**
	 * @see dbscheme::execute()
	 */
	public function execute(array $array = array()) {
		return $this->createcommand->execute($array);
	}
	
}
?>