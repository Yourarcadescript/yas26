<?php
session_start();
include ("db_functions.inc.php");
include ("config.inc.php");
if (ISSET($_SESSION['userid'])) {
	if (ISSET($_GET['addavatar'])) {
		$avatarfile = yasDB_clean($_GET['addavatar']);
		$userid = yasDB_clean($_SESSION['userid']);
		yasDB_update("UPDATE user SET useavatar=1, avatarfile='".$avatarfile."' WHERE id=".$userid) or die("Could not update avatar to the database.");
	}
} 
?>