<?php
require_once dirname(__FILE__).'/../db/db.php';
class Model {
	
	protected $props;
	
	protected $table;
	
	protected $db;
	function __construct($table){
		$this->table = $table;
		$this->props = [];
		$this->db = new Database([
				'server'=>'localhost',
				'dbname'=>'mvc',
				'username'=>'root',
				'password'=>'root'
		]);
	}
	
	protected function save($data=array()){
		return $this->db->insertByMark($this->table, $data);
	}
}