<?php
session_start();
include ("db_functions.inc.php");

if (isset($_SESSION['userid'])) {
	$gameid = yasDB_clean($_GET['gid']);
	$userid = yasDB_clean($_SESSION['userid']);
	$query = yasDB_select("SELECT `gameid` FROM `favourite` WHERE  `userid` = $userid && `gameid` = $gameid");
	if ($query->num_rows == 0) {
		yasDB_insert("INSERT INTO `favourite` (userid, gameid) VALUES ($userid, $gameid)");
	}
}
?>