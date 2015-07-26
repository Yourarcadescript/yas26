<?php
@ob_start();
@session_start();
include("includes/db_functions.inc.php");
include("includes/config.inc.php");
$ref = $_SERVER['HTTP_REFERER'];
if(isset($_SESSION['user']) || isset($_SESSION['userid'])) {
	$memberid = intval($_SESSION['userid']);
	yasDB_delete("DELETE FROM membersonline WHERE memberid='$memberid'");
	session_unset();
	session_destroy();
	setcookie("user", '', time()-172800);
	header ("Location: ".$setting['siteurl']);
	exit();
} else {
	header ("Location: ".$setting['siteurl']."index.php?act=register");
}
@ob_end_flush();
exit();
?>