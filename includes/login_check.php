<?php
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST") {
	include("db_functions.inc.php");
	include("config.inc.php");
	$username = yasDB_clean($_POST["username"]);
	$password = md5($_POST["password"]);
	$rememberme = $_POST["remember"];
	$result = yasDB_select("select * from user where username='$username' LIMIT 1",false);
	$rows = $result->fetch_array(MYSQLI_ASSOC);
	if (!$result || $result->num_rows == 0) {
		echo "Wrong password or username.";
		exit();
	}
	$result->close();
	if($rows['endban'] > time()) {
		$banend = date("F j, Y g:i a", $rows['endban']);
		echo 'Sorry, this user is banned until '.$banend;
		exit();
	}
	if($rows['endban'] < time()) {
		yasDB_update("UPDATE `user` SET `endban` = '0' WHERE username = '$username'",false);
	}
	$loginok = '';
	if($rows["password"] == $password && $rows["username"] == $username) { 
		$loginok = TRUE;
	}
	else {
		$loginok = FALSE;
	}
	if ($loginok === TRUE){
		$ref = $_SERVER['HTTP_REFERER'];
		if ($rememberme == "remember"){
			setcookie("user", $username, time()+86400); // cookie lasts 24 hours
		}
		$_SESSION['user'] = $username;		 
		$_SESSION['userid'] = $rows['id'];
		$now = time(); 
		$query = yasDB_select("SELECT `id` FROM `membersonline` WHERE `memberid` = '{$rows['id']}'");
		if ($query->num_rows==0) {
			yasDB_insert("INSERT INTO `membersonline` (id, memberid, timeactive) VALUES ('', '{$rows['id']}', '$now')",false);
		}
		else {
			yasDB_update("UPDATE `membersonline` SET timeactive='$now' WHERE `memberid`='{$rows['id']}'");
		}
		$query->close();
		echo 'Logging in...';
		exit();
	}
	else  {
		echo 'Login failed.';
	}
}
?>