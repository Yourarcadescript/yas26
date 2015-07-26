<?php
session_start();
if (isset($_SESSION['admin']) && $_SESSION['admin'] == "logged") {
	include ("../includes/db_functions.inc.php");
	$type = yasDB_clean($_POST['type']);
	$id = intval($_POST['queid']);
	$action = yasDB_clean($_POST['action']);
	$title = yasDB_clean($_POST['title']);
	$thumb = yasDB_clean($_POST['thumb']);
	if ($action == 'que') {
		$check = yasDB_select("SELECT `source` FROM `gameque` WHERE `source` = '$type' AND `sourceid` = $id");
		if ($check->num_rows > 0) {
			echo "Game already in Queue";
			exit;
		}
		$maxresult = yasDB_select("SELECT `order` FROM `gameque` ORDER BY `order` DESC LIMIT 1");
		if ($maxresult->num_rows == 0) {
			$order = 1;
		} else {
			$max = $maxresult->fetch_array();
			$order = $max[0] + 1;
		}
		$result = yasDB_insert("INSERT INTO `gameque` (source, sourceid, title, thumb, `order`) VALUES ('$type', $id, '$title', '$thumb', $order)");
		if ($result) {
			echo "Game successfully added to scheduled game queue.";
		} else {
			echo "Database error, unable to queue game.";
		}
	} else {
		$orderresult = yasDB_select("SELECT `order` FROM `gameque` WHERE `source` = '$type' AND `sourceid` = $id");
		$order = $orderresult->fetch_array();
		$result = yasDB_delete("DELETE FROM `gameque` WHERE `source` = '$type' AND `sourceid` = $id LIMIT 1");
		yasDB_update("UPDATE `gameque` SET `order` = `order` - 1 WHERE `order` > {$order[0]}");
		if ($result) {
			echo "Game successfully removed from install queue.";
		} else {
			echo "Database error, unable to remove game from queue.";
		}
	}
}	
?>