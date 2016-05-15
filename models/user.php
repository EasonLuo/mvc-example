<?php
require_once 'model.php';
class User extends Model {
	function __construct(){
		parent::__construct('tb_users');
		//$this->table = 'tb_users';
		$this->props[] = 'username';
		$this->props[] = 'password';
		$this->props[] = 'email';
		$this->props[] = 'status';
		$this->props[] = 'created_time';
		$this->props[] = 'last_login_time';
	}

	function register(){
	}
	
	function login($arr){
		$username = $arr['username'];
		$password = $arr['password'];
		$user = $this->db->query("select * from tb_users where username = ?", [$username]);
		if($user->password === md5($password)){
			session_start();
			$_SESSION['login'] = $user;
			return true;
		}
		return false;
	}
}