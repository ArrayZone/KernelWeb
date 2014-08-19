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
	public function connect($type, $host, $dbname, $username, $password, $port) {
		return $this->connection = new PDO($type.':host='.$host.';port='.$port.';dbname='.$dbname, $username, $password);
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
		return $this->connection->query($query);
	}
	
	public function select(array $array = array()) {
		return 'SELECT '.implode(', ', $array);
	}
	
    public function from(array $array = array()) {
    	return 'FROM '.implode(', ', $array);
	}
	
	public function where(array $array = array()) {
	    return 'WHERE '.implode(', ', $array);
	}
	
	/**
	 * @see dbscheme::execute()
	 */
	public function execute(array $array = array()) {
		return $this->createcommand->execute($array);
	}
	
}
?>