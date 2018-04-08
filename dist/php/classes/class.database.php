<?php

require('common.php');


/*
* Mysql database class - only one connection alowed
*/
class Database {

	private $db_host = "localhost";
	private $db_user = "root";
	private $db_pass = "";
	private $db_name = "the_pet_network";

	private $_connection;
	private static $_instance; //The single instance
	

	/*
	Get an instance of the Database
	@return Instance
	*/
	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	// Constructor
	private function __construct() {
		$this->_connection = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
	
		// Error handling
		if(mysqli_connect_error()) {
			trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
				 E_USER_ERROR);
		}
	}

	// Magic method clone is empty to prevent duplication of connection
	private function __clone() { }

	// Get mysqli connection
	public function getConnection() {
		return $this->_connection;
	}
}
?>