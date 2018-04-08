<?php

require_once 'class.database.php';

class MySql {
	
	// Sends the query to the connection
	public function Query($sql) {
		$this->_result = $this->_link->query($sql) or die(mysqli_error($this->_result));
		$this->_numRows = mysqli_num_rows($this->_result);
	}
	
	// Inserts into databse
	public function UpdateDb($sql) {
		$this->_result = $this->_link->query($sql) or die(mysqli_error($this->_result));
		return $this->_result;
	}


	// Return the number of rows
	public function NumRows() {
		return $this->_numRows;
	}

	// Check if row exists
	public static function checkRowExists($sql) {
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$result = $mysqli->query($sql);
		if (!$result) {
			$res = array(
					'success'	=>	'0',
					'error'		=>	$mysqli->error,
					'message'	=>	'invalid query or no result'
					);
			return $res;
			exit();
		}
		$rows   = $result->num_rows;
		$error = mysqli_error($mysqli);
		// $mysqli->close();

		if($error == NULL) {
			if ($rows>0) {
				$res = array(
					'success'	=>	'1',
					'error'		=>	NULL,
					'message'	=>	'row exists'
					);
			} else {
				$res = array(
					'success'	=>	'0',
					'error'		=>	$error,
					'message'	=>	'no row exists'
					);
			}
		} else {
			$res = array(
					'success'	=>	'0',
					'error'		=>	$error,
					'message'	=>	'execution error'
					);
		}

		return $res;
		exit();
	}

	// Check if row exists
	public static function countRows($sql) {
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$result = $mysqli->query($sql);
		$rows   = $result->num_rows;
		return $rows;
	}

	// insert data in table
	public static function insertData($tablename,$dataArray) {

		$sql = "INSERT INTO `".$tablename."`(";
		foreach ($dataArray as $key=>$value){
			$sql .= "`" . $key . "`,";
		}
		$sql  = trim($sql,",");
		$sql .= ") VALUES (";
		foreach($dataArray as $key=>$value){
			$sql .= $value . ",";
		}
		$sql  = trim($sql,",");
		$sql .= ");";

		// return $sql;


		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$result = $mysqli->query($sql);
		$error = mysqli_error($mysqli);
		$id = $mysqli->insert_id;

		if( $id != 0 ){

			$res = array(
					'success'	=>	'1',
					'id'		=>	$id,
					'error'		=>	NULL,
					);
		} else {
			$res = array(
					'success'	=>	'0',
					'error'		=>	$error,
					);
		}

		return $res;
	}

	// update data in table
	public static function updateData($tablename,$dataArray,$condition) {
		$sql = "UPDATE `".$tablename."` SET ";
		foreach($dataArray as $key=>$value) {
			$sql .= "`".$key."`=".$value.",";
		}
		$sql  = trim($sql,",");
		$sql .= " WHERE ";
		foreach($condition as $key=>$value) {
			$sql .= "`".$key."`=".$value." AND ";
		}
		$sql  = substr($sql, 0, -4);
		
		$sql .= ";";

		// return $sql;

		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$result = $mysqli->query($sql);
		if (!$result) {
			$res = array(
					'success'	=>	'0',
					'error'		=>	$mysqli->error,
					'message'	=>	'invalid query'
					);
			return $res;
			exit();
		}
		$error = mysqli_error($mysqli);

		if($error == NULL) {
			if (!empty($result)) {
				$res = array(
					'success'	=>	'1',
					'error'		=>	NULL,
					'message'	=>	'row updated successfully'
					);
			} else {
				$res = array(
					'success'	=>	'0',
					'error'		=>	$error,
					'message'	=>	'no row updated'
					);
			}
		} else {
			$res = array(
					'success'	=>	'0',
					'error'		=>	$error,
					'message'	=>	'execution error'
					);
		}

		return $res;
		exit();


		/*if(!empty($result))
			return TRUE;
		return FALSE;*/
	}

	// update data in table
	public static function deleteData($tablename,$condition) {
		$sql = "DELETE FROM `".$tablename."` WHERE ";
		foreach($condition as $key=>$value) {
			$sql .= "`".$key."`=".$value." AND ";
		}
		$sql  = substr($sql, 0, -4);
		
		$sql .= ";";

		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$error = mysqli_error($mysqli);

		if ($mysqli->query($sql) === TRUE) {

			$res = array(
					'success'	=>	'1',
					'error'		=>	NULL,
					);
		} else {
			$res = array(
					'success'	=>	'0',
					'error'		=>	$error,
					);
		}

		return $res;
	}

	// execute a query defined
	public static function Execute($sql) {
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$result = $mysqli->query($sql);

		if($result)
			return TRUE;
		return FALSE;
	}

	public static function fetchRow($sql) {
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		$result = $mysqli->query($sql);
		if (!$result) {
			$res = array(
					'success'	=>	'0',
					'error'		=>	$mysqli->error,
					'message'	=>	'invalid query'
					);
			return $res;
			exit();
		}
		$numrow = $result->num_rows;
		$error = mysqli_error($mysqli);


		if($result->num_rows > 0){
			$rows = $result->fetch_assoc();
			$res = array(
					'success'	=>	'1',
					'data'		=> 	$rows,
					'error'		=>	NULL,
					);
		} else {
			$res = array(
					'success'	=>	'0',
					'data'		=> 	NULL,
					'error'		=>	$error,
					);
		}

		return $res;

	}


	public static function fetchAllRows($sql) {
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		
		$result = $mysqli->query($sql);
		if (!$result) {
			$res = array(
					'success'	=>	'0',
					'error'		=>	$mysqli->error,
					'message'	=>	'invalid query'
					);
			return $res;
			exit();
		}
		$numrow = $result->num_rows;
		$error = mysqli_error($mysqli);
		if($result->num_rows > 0){
			$res = array(
					'success'	=>	'1',
					'data'		=> 	$result,
					'error'		=>	NULL,
					);
			
		} else {
			$res = array(
					'success'	=>	'0',
					'data'		=> 	$result,
					'error'		=>	$error,
					);
		}

		return $res;
	}

	// Fetchs the rows and return them
	public function Rows() {
		$rows = array();
		
		for($x = 0; $x < $this->NumRows(); $x++) {
			$rows[] = mysqli_fetch_assoc($this->_result);
		}
		return $rows;
	}
	
	// Used by other classes to get the connection
	public function GetLink() {
		return $this->_link;
	}
	
	// Securing input data
	public function SecureInput($value) {
		return mysqli_real_escape_string($this->_link, $value);
	}
	
}




?>