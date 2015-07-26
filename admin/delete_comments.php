<?php
include ("../includes/db_functions.inc.php");
foreach ($_POST['checkbox'] as $box) {
    if(!is_numeric($box)) exit;
	$box = intval($box);
	$query = yasDB_select("SELECT id FROM comments WHERE id = ".$box,false);
    if($query->num_rows == 0) {
        $row = $query->fetch_array(MYSQLI_ASSOC);
        $query->close();            
        yasDB_delete("DELETE FROM comments WHERE id = ".$box,false);
    } 
}
?>