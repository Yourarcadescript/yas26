<?php
include("includes/db_functions.inc.php");
$get_url = yasDB_clean($_GET['id']);
yasDB_update("UPDATE links SET `out`=`out`+1 WHERE id='$get_url'");
exit;
?>