<?php
session_start();
include ("db_functions.inc.php");
if (isset($_GET['gid'])) {
	$gameid = yasDB_clean($_GET['gid']);
	$userid = yasDB_clean($_SESSION['userid']);
	yasDB_insert("DELETE FROM `favourite` WHERE userid = $userid and gameid = $gameid") or die("Could not delete favorite from the database.");
}
?>