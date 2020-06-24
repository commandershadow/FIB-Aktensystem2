<?php
class logDB extends PDO {
	
	private $dbConn = null;
	private $userID = null;
	#private $lifetime = 0; //Anzahl der Tage bis INSERT UPDATE Einträge gelöscht werden 	0 = nie
	#private $lifetimeCrit = 0; //Anzahl der Tage bis DELETE Einträge gelöscht werden		0 = nie
	
	// Lädt die Datenbankklasse und stellt eine verbindung her
	public final function __construct() {
		$dbhost=DB_SERVER;
		$dbuser=DB_USERNAME;
		$dbpass=DB_PASSWORD;
		$dbname=DB_DATABASE;
		try {
			$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass); 
			$dbConnection->exec("set names utf8");
			$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->dbConn = $dbConnection;
			$this->userID = $_SESSION['uid'];
			return true;
		}
		catch (PDOException $e) {
			#die($e->getMessage());
			return false;
		}
	}
	
	// Load a single Row from a table
	// @param $q: query in form "* FROM TABLE WHERE LIMIT"
	// @return: the executed query in Object or Array when $Array = true
	// NO LOGGING REQUIRED
	public final function singleQuery($q, $array = false) {
		$query = $this->dbConn->query("SELECT ".$q);
		if($array) $result = $query->fetch(PDO::FETCH_ASSOC);
		else $result = $query->fetch(PDO::FETCH_OBJ);
		return $result;
	}
	
	// Load a multiple Rows from a table
	// @param $q: query in form "* FROM TABLE WHERE LIMIT"
	// @return: the executed query in Array cont Objects or Arrays when $array = true
	// NO LOGGING REQUIRED	
	public final function multiQuery($q, $array = false) {
		$query = $this->dbConn->query("SELECT ".$q);
		$returnArray = array();
		if($array)
			while($result = $query->fetch(PDO::FETCH_ASSOC)) {
				$returnArray[] = $result;
			}
		else 
			while($result = $query->fetch(PDO::FETCH_OBJ)) {
				$returnArray[] = $result;
			}
		return $returnArray;
	}
	
	// Insert data into a table
	// @param $table: Table to insert
	// @param $data: array(rowName => 'value');
	// Creating Logfile WHO inserted the data as UID
	public final function insert($table, $data) {
		$index = '';
		$content = '';
		// Merge all keys and values with an ,
		foreach($data as $key => $value) {
			$index .= $key.',';
			$content .= '\''.$value.'\',';
		}
		// Remove the last , that is too much
		$index = substr($index,0,-1);
		$content = substr($content,0,-1);
		// execute the query
		$this->dbConn->query("INSERT INTO ".$table."(".$index.") VALUES(".$content.")");
		// Add log entry
		$this->createLogFile("INSERT",$table,$data);
	}
	
	// UPDATE data in a table
	// @param $table: Table to insert
	// @param $data: array(rowName => 'value');
	// @param $where array(rowName => 'value');
	// Creating Logfile WHO updated the data as UID
	public final function update($table, $data, $where) {
		// Merge all keys and values with an ,
		$setValue = '';
		$qKey = '';
		$wQuery = '';
		foreach($data as $key => $value) {
			$setValue .= $key.' = \''.$value.'\',';
			$qKey .= $table.'.'.$key.',';
		}
		foreach($where as $key => $value) {
			if(strlen($wQuery) > 0) 
				$wQuery .= ' AND ';
			else 
				$wValue = $value;
			$wQuery .= $key." = '".$value."'";
		}
		$setValue = substr($setValue,0,-1);
		$qKey = substr($qKey,0,-1);
		// Fetch old Data for comparison
		$oldData = $this->singleQuery("* FROM ".$table." WHERE ".$wQuery,true);
		// Update DB entry
		$this->dbConn->query("UPDATE ".$table." SET ".$setValue." WHERE ".$wQuery);
		// Add log Entry
		$logArray = array();
		foreach($data as $key => $value) {
			if(strcmp($data[$key],$oldData[$key]) !== 0)
				$logArray[$key] = array($oldData[$key],$value);
		}
		if(sizeof($logArray) > 0)
			$this->createLogFile("UPDATE",$table,$logArray,$wValue);
	}
	
	// DELETE data from a table
	// @param $table: Table to delete from
	// @param $ident: Array of WHERE param (key = id)
	// Creating Logfile WHO deleted the data as UID
	public final function delete($table, $ident = null) {
		if($ident == null || !is_array($ident)) return null;
		$wParam = '';
		foreach($ident as $key => $id) {
			if(strlen($wParam) > 0) 
				$wParam .= ' AND ';
			$wParam .= $key." = '".$id."'";
		} 
		$oldData = $this->singleQuery("* FROM ".$table." WHERE ".$wParam,true);
		#unset($oldData[$key]);
		$this->dbConn->query("DELETE FROM ".$table." WHERE ".$wParam);
		$this->createLogFile("DELETE",$table,$oldData,$id);
		echo implode( $wParam ); 
	}
	
	// Count all rows from a table
	// @param $table: Table to count from
	// @param $where = null: count WHERE clause
	public final function countRow($table, $where = null) {
		if($where != null) $wQ = ' '.$where; else $wQ = '';
		return current($this->dbConn->query("SELECT COUNT(*) FROM ".$table.$wQ)->fetch(PDO::FETCH_ASSOC));
	}
	
	// Internal function that creates a log entry in the Table
	private final function createLogFile($command,$table,$data,$id = 0) {
		// serialize the given data
		$data = serialize($data);
		$this->dbConn->query("INSERT INTO Log(logUserID,
											  logDate,
											  logTable,
											  logTableID,
											  logType,
											  logParam)
									   VALUES('".$this->userID."',
											  '".time()."',
											  '".$table."',
											  '".$id."',
											  '".$command."',
											  '".addslashes($data)."')");
	} 
}   

function alter( $datum )
{
  $geburtstag = new DateTime($datum);
  $heute = new DateTime(date('Y-m-d'));
  $differenz = $geburtstag->diff($heute);
 
  return $differenz->format('%y');
  }
?>