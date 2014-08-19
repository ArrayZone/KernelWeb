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
class db {

	static $connections = array();
	static $data = array();
	
	static function connect($resource, $type, $host, $dbname, $username = "", $password = "", $port = 3306) {
		$type = strtolower($type).'_db';
		
		$db = new $type;
		$db->connect($type, $host, $dbname, $username, $password, $port);
		
		return self::$connections[$resource] = $db;
	}
	
	static function createcommand($query = '', $resource = '') {
		return self::$connections[$resource]->createcommand($query);
		//self::$connections[$resource];
	}
		
	/*static function select(array $query = array(), $resource = '' ) {
		return self::$connections[$resource]->select($query);
		//self::$connections[$resource];
	}
	
	static function from(array $query = array(), $resource = '' ) {
		return self::$connections[$resource]->from($query);
		//self::$connections[$resource];
	}
	
	static function where(array $query = array(),$resource = '' ) {
		return self::$connections[$resource]->where($query);
		//self::$connections[$resource];
	}
	
	static function limit($limit_start = 0, $limit_count = '', $resource = '' ) {
		return self::$connections[$resource]->limit($limit_start, $limit_count );
		//self::$connections[$resource];
	}*/
	
	static function execute(array $array = array(), $resource = '') {
		if (self::$connections['select'] == ''){
			return $this->createcommand->execute($array);
		}
	}
}
?>