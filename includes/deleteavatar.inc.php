<?php
session_start();
require_once ("db_functions.inc.php");
require_once ("config.inc.php");
if (isset($_SESSION['userid'])) {
	$af = yasDB_clean($_GET['af']);
	$userid = yasDB_clean($_SESSION['userid']);
	yasDB_delete("DELETE FROM `avatars` WHERE `userid` = '$userid' and `avatar` = '$af'");
	unlink("../avatars/" . $af);
	$query = yasDB_select("SELECT `avatarfile` FROM `user` WHERE `id` = '$userid'");
	$useraf = $query->fetch_array(MYSQLI_ASSOC);
	if ($af == $useraf['avatarfile']) {
		yasDB_update("UPDATE user SET avatarfile = 'useruploads/noavatar.JPG' WHERE id = $userid");
		echo 'useruploads/noavatar.JPG';
	} else {
		echo $useraf['avatarfile'];
	}
}