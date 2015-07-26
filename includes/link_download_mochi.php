<?php
include("db_functions.inc.php");
include("config.inc.php");
$get_id = intval($_GET['id']);
$select_link = yasDB_select("SELECT * FROM downgames WHERE id='$get_id'");
if($select_link->num_rows == 1) {
	$result = $select_link->fetch_array(MYSQLI_ASSOC);
	yasDB_update("UPDATE downgames SET `mochidownloads`=`mochidownloads`+1 WHERE id='{$result['id']}'");
	header("location: ".$result['mochi']);
}
$select_link->close();
?>