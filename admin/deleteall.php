<?php
include ("../includes/db_functions.inc.php");
foreach ($_POST['checkbox'] as $box) {
    if(!is_numeric($box)) exit;
	$box = intval($box);
	$query = yasDB_select("SELECT `id`, `file`, `thumbnail`, `thumbnail_200`, `source`, `sourceid` FROM `games` WHERE `id` = $box",false);
    if($query->num_rows != 0) {
        $row = $query->fetch_array(MYSQLI_ASSOC);
        @unlink('../' . $row['file']);
        @unlink('../' . $row['thumbnail']);
        if ($row['thumbnail'] != '') { @unlink('../' . $row['thumbnail_200']); }
		$query->close();            
        yasDB_delete("DELETE FROM `games` WHERE id = '$box'",false);
		switch ($row['source']) {
			case 'MOCHI':
				yasDB_update("UPDATE `mochigames` SET `isinstalled` = 0 WHERE `id` = {$row['sourceid']}");
				break;
			case 'FGD':
				yasDB_update("UPDATE `fgdfeed` SET `installed` = 0 WHERE `id` = {$row['sourceid']}");
				break;
			case 'PLAYTOMIC':
				yasDB_update("UPDATE `playtomicfeed` SET `isinstalled` = 0 WHERE `id` = {$row['sourceid']}");
				break;
			case 'FOG':
				yasDB_update("UPDATE `fogfeed` SET `installed` = '0' WHERE `id` = {$row['sourceid']}");
				break;
			case 'KONGREGATE':
				yasDB_update("UPDATE `kongregate` SET `installed` = '0' WHERE `id` = {$row['sourceid']}");
				break;			
		}
	}
}
?>