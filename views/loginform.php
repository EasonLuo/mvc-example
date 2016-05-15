<form action="index.php?action=user.login" method="POST">
Username<input type="text" name="username">
Password<input type="password" name="password">
<input type="submit" value="Login">
<div style="color:red;">
<?php 
if($_GET['errorcode']==1){
	echo "Username or password not mathced.";
}
?>
</div>
</form>