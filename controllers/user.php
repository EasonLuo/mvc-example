<?php
require_once 'models/user.php';
class UserController {
	
	function loginform(){
		require 'views/loginform.php';
	}
	
	function register(){
		$user = new User();
		$user->register();
	}
	
	function login(){
		$username = trim($_POST['username']);
		
		$password = trim($_POST['password']);
		$user = new User();
		if($user->login(array('username'=>$username,'password'=>$password))){
			//require("views/profile.php");
			header("Location:http://localhost/mvc/index.php?action=user.profile");
		}else{
			header("Location:http://localhost/mvc/index.php?action=user.loginform&errorcode=1");
		}
		
	}
	
	function profile(){
		require 'views/profile.php';
	}
}