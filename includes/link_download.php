<?php
include("db_functions.inc.php");
include("config.inc.php");
include("outputfile.php");
$get_id = intval($_GET['id']);
$select_link = yasDB_select("SELECT `id`, `file` FROM `downgames` WHERE `id` = $get_id");
if($select_link->num_rows == 1) {
	$result = $select_link->fetch_array(MYSQLI_ASSOC);
	yasDB_update("UPDATE `downgames` SET `downloadtimes` = `downloadtimes` + 1 WHERE id = {$result['id']}");
	output_file($result['file'], basename($result['file']));
}
$select_link->close();
?>