<?php
/** 
 * a database helper
 * @author Eason Luo (trueluo1987@gmail.com)
 * @since 18-6-2014
 */
class Database {
	private $db;
	
	/**
	 * create a pdo connection
	 *
	 * @param array $config  if $config is not given, use the default config file:db.ini      	
	 */
	function __construct($config = array()) {
		$dsn = "mysql:host=" . $config ['server'] . ";" . "dbname=" . $config ['database'];
		$this->db = new PDO ( $dsn, $config ['username'], $config ['password'], array (
				PDO::ATTR_PERSISTENT => true 
		) );
	}

	
	/**
	 * insert records by preparedstatement with question mark
	 *
	 * @param string $table        	
	 * @param array $args        	
	 * @return boolean
	 */
	public function insertByMark($table, $args) {
		$keys = array_keys ( $args );
		$columns = implode(',',$keys);
		$vals = [];
		foreach ( $args as $key => $val ) {
			$vals[] = '?';
		}
		$str = implode(',', $vals);
		$sql = "insert into $table ( $columns 	) values ( $str )";
		$pstmt = $this->db->prepare ( $sql );
		$index = 1;
		foreach ( $args as $key => $val ) {
			$pstmt->bindValue ( $index ++, $val );
		}
		$rs = $pstmt->execute ();
    // print_r($pstmt->errorInfo());    
		return $rs;
	}
	
	/**
	 *
	 * @param string $table        	
	 * @param array $args        	
	 * @return boolean
	 */
	public function delete($table, $args) {
		$keys = array_keys ( $args );
		if (count ( $keys ) === 0) {
			return false;
		}
		$id = $keys [0];
		$value = $args [$id];
		$sql = "delete from " . $table . " where " . $id . "=:" . $id;
		$pstmt = $this->db->prepare ( $sql );
		$pstmt->bindParam ( ":" . $id, $value );
		$rs = $pstmt->execute ();
		$pstmt = null;
		return $rs;
	}
	
	/**
	 * update record specified by keys with args
	 *
	 * @param string $table        	
	 * @param array $keys        	
	 * @param array $args        	
	 * @return boolean
	 */
	public function update($table, $keys, $args) {
		$key = array_keys ( $keys );
		if (count ( $key ) === 0) {
			return false;
		}
		$columns = array_keys ( $args );
		$values = array ();
		foreach ( $columns as $column ) {
			$values [] = $column . "=:" . $column;
		}
		$values = join ( ",", $values );
		$id = $key [0];
		$sql = "update " . $table . " set " . $values . " where " . $id . "=:$id";
		$pstmt = $this->db->prepare ( $sql );
		foreach ( $columns as $column ) {
			$pstmt->bindParam ( ":" . $column, $args [$column] );
		}
		$pstmt->bindParam(":$id", $keys[$id]);
		$rs = $pstmt->execute ();
		$pstmt = null;
		return $rs;
	}
	
	/**
	 * load single record by primary key
	 *
	 * @param string $table        	
	 * @param array $args        	
	 * @return object
	 */
	public function load($table, $args) {
		$key = array_keys ( $args );
		if (count ( $key ) === 0) {
			return null;
		}
		$id = $key [0];
		$sql = "select * from " . $table . " where " . $id . " = :" . $id;
		$pstmt = $this->db->prepare ( $sql );
		$pstmt->bindParam ( ":" . $id, $args [$id] );
		if (! $pstmt->execute ()) {
			return null;
		}
		$arr = $pstmt->fetchAll ( PDO::FETCH_ASSOC );
		$pstmt = null;
		return $arr [0];
	}
	
	/**
	 * execute a sql statment with specific args
	 * @param unknown $sql
	 * @param unknown $args
	 * @return boolean
	 */
	public function execute($sql, $args) {
		$pstmt = $this->db->prepare ( $sql );
		$index = 1;
		foreach ( $args as $key => $val ) {
			$pstmt->bindValue ( $index ++, $val );
		}
		$rs = $pstmt->execute ();
		return $rs;
	}
	
	/**
	 * general query
	 *
	 * @param string $sql        	
	 * @param array $args        	
	 * @return assoc array
	 */
	public function query($sql, $args = array()) {
		$pstmt = $this->db->prepare ( $sql );
		$index = 1;
		foreach ( $args as $key => $val ) {
			$pstmt->bindValue ( $index ++, $val );
		}
		if ($pstmt->execute ()) {
			$arr = $pstmt->fetchAll ( PDO::FETCH_ASSOC );
		}
		$pstmt = null;
		return $arr;
	}
	
	/**
	 * start a database transaction
	 * @return boolean
	 */
	public function beginTransaction() {
		return $this->db->beginTransaction ();
	}
	
	/**
	 * commit a datatbase transaction if started
	 * @return boolean
	 */
	public function commit() {
		return $this->db->commit ();
	}
	
	/**
	 * rollback a database transaction if failed
	 * @return boolean
	 */
	public function rollBack() {
		return $this->db->rollBack ();
	}
	
	/**
	 * return the last insert primary key by auto increasing
	 */
	public function lastId() {
		$rs = $this->query ( "select last_insert_id() last_id " );
		return $rs [0] ['last_id'];
	}

}
?>