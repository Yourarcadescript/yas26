<?php
if (isset($_POST['ajax']) && $_POST['ajax'] == 'true') {
	include_once("../includes/db_functions.inc.php");
	if (isset($_POST['checkbox'])) {
		foreach ($_POST['checkbox'] as $box) {
			$parts = explode(',', $box);
			$query1 = yasDB_select("SELECT `order` FROM `gameque` WHERE `source` = '".yasDB_clean($parts[0])."' AND `sourceid` = ".intval($parts[1]));
			$deleteResult = yasDB_delete("DELETE FROM `gameque` WHERE `source` = '".yasDB_clean($parts[0])."' AND `sourceid` = ".intval($parts[1]));
			$order = $query1->fetch_array();
			yasDB_update("UPDATE `gameque` SET `order` = `order` - 1 WHERE `order` > {$order[0]}");
		}
	}
	elseif (isset($_POST['move'])) {
		$action = explode(',', $_POST['move']);
		switch($action[0]) {
			case 'top':
				if ($action[1] != 1) {
					$query1 = yasDB_select("SELECT `source`, `sourceid` FROM `gameque` WHERE `order` = " . intval($action[1]));
					$old = $query1->fetch_array();
					yasDB_update("UPDATE `gameque` SET `order` = `order` + 1 WHERE `order` < " . intval($action[1]));
					yasDB_update("UPDATE `gameque` SET `order` = 1 WHERE `source` = '{$old[0]}' AND `sourceid` = {$old[1]}");					
				}
				break;
			case 'bottom':
				$result = yasDB_select("SELECT `order` FROM `gameque` ORDER BY `order` DESC LIMIT 1");
				$max = $result->fetch_array();
				if ($action[1] < $max[0]) {
					$query1 = yasDB_select("SELECT `source`, `sourceid` FROM `gameque` WHERE `order` = " . intval($action[1]));
					$old = $query1->fetch_array();
					yasDB_update("UPDATE `gameque` SET `order` = `order` - 1 WHERE `order` > " . intval($action[1]));
					yasDB_update("UPDATE `gameque` SET `order` = {$max[0]} WHERE `source` = '{$old[0]}' AND `sourceid` = {$old[1]}");					
				}
				break;
				break;
			case 'up':
				$newaction = intval($action[1] - 1);
				$query1 = yasDB_select("SELECT `source`, `sourceid` FROM `gameque` WHERE `order` = " . $newaction);
				if ($query1) {
					$query2 = yasDB_select("SELECT `source`, `sourceid` FROM `gameque` WHERE `order` = " . intval($action[1]));
					$new = $query2->fetch_array();
					$old = $query1->fetch_array();
					yasDB_select("UPDATE `gameque` SET `order` = `order` - 1 WHERE `source` = '{$new[0]}' AND `sourceid` = {$new[1]}");
					yasDB_select("UPDATE `gameque` SET `order` = `order` + 1 WHERE `source` = '{$old[0]}' AND `sourceid` = {$old[1]}");
				}
				break;
			case 'down':
				$newaction = intval($action[1] + 1);
				$query1 = yasDB_select("SELECT `source`, `sourceid` FROM `gameque` WHERE `order` = " . $newaction);
				if ($query1) {
					$query2 = yasDB_select("SELECT `source`, `sourceid` FROM `gameque` WHERE `order` = " . intval($action[1]));
					$new = $query2->fetch_array();
					$old = $query1->fetch_array();
					yasDB_select("UPDATE `gameque` SET `order` = `order` +1 WHERE `source` = '{$new[0]}' AND `sourceid` = {$new[1]}");
					yasDB_select("UPDATE `gameque` SET `order` = `order` - 1 WHERE `source` = '{$old[0]}' AND `sourceid` = {$old[1]}");
				}
				break;
		}
	}
	exit;
}
if (isset($_GET['delete'])) {
	$ident = explode(',', $_GET['delete']);
	$query1 = yasDB_select("SELECT `order` FROM `gameque` WHERE `source` = '".yasDB_clean($ident[0])."' AND `sourceid` = ".intval($ident[1]));
	$order = $query1->fetch_array();
	$deleteResult = yasDB_delete("DELETE FROM `gameque` WHERE `source` = '".yasDB_clean($ident[0])."' AND `sourceid` = ".intval($ident[1]));
	yasDB_update("UPDATE `gameque` SET `order` = `order` - 1 WHERE `order` > {$order[0]}");
}
?>
<script language="JavaScript">
function Check(chk) {
	if(document.deleteform.Check_All.value=="Check All"){
		for (i = 0; i < chk.length; i++) {
			chk[i].checked = true;
		}
		document.deleteform.Check_All.value="UnCheck All";
	} else {
		for (i = 0; i < chk.length; i++) {
			chk[i].checked = false;
		}
		document.deleteform.Check_All.value="Check All";
	}
}
function deleteChecked(chked) {
	//
	var boxArray = new Array();
	var a = 0;
	for (i = 0; i < chked.length; i++) {
		if (chked[i].checked === true) {
			boxArray[a] = chked[i].value;
			a++;
		}
	}
	var response = confirm("Are you sure you want to remove " + a + " game(s)?");
	if (response) {
		$.post('managegamequeue.php', {'checkbox[]': boxArray, ajax: 'true' }, function(data){ location.reload(true); });		
	}
}
function moveGame(moveAction) {
	$.post('managegamequeue.php', {move: moveAction, ajax: 'true' }, function(data){ location.reload(true); });
	//alert(moveAction);
}
</script>
<div id="center-column">
<div class="top-bar">
				<a href="index.php?act=uploadgames" title="Upload Games" class="button">ADD GAME</a>
				<h1>Cpanel - Games</h1>
				
			</div><br />
		  <div class="select-bar">
		    <label>
		    <h3>Manage GameQueue</h3>
		    </label>
		  </div>
	<div style="clear:both;"></div>
	
	<?php
	$query = yasDB_select("SELECT * FROM gameque ORDER BY `order` ASC");
	if($query->num_rows==0) {
		echo '<div style="text-align:center;font-size:12px;"><br/><h1>Your GameQueue is empty</h1><br/>Load your GameQueue with games from the feed management pages.</div>';
	} else { 
		?>
		<form name="deleteform" method="post" action="">
	<input name="deletechecked" class="button" type="button" id="deletechecked" value="Delete Checked" style="float:right;margin-left:5px;" onClick="return deleteChecked(document.deleteform.checkbox)"/>
	<input type="button" class="button" name="Check_All" value="Check All" onClick="Check(document.deleteform.checkbox)" style="float:right;"/>
	<br/><br/>
    <div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing" cellpadding="0" cellspacing="0">
			<tr>
			<th class="first" width="125px"></th>
			<th>Title</th>
			<th width="125px">Source Feed</th>
			<th class="last"></th>
			</tr>
		<?php
		while($row = $query->fetch_array(MYSQLI_ASSOC)) {
			?><tr>
			<td class="first style1" width="125px" style="height:125px;" ><img src="<?php echo $row['thumb'];?>" style="width: 100px; height: 100px;" /></td>
			<td><?php echo $row['title'];?></td>
			<td><?php echo strtoupper($row['source']); ?></td>
			<td class="last" style="height:125px;">
			<select name="move" size="1" onChange="moveGame(this.value)">
				<option value="">Move in Queue</option>
				<option value="up,<?php echo $row['order'];?>">Up</option>
				<option value="down,<?php echo $row['order'];?>">Down</option>
				<option value="top,<?php echo $row['order'];?>">Top</option>
				<option value="bottom,<?php echo $row['order'];?>">Bottom</option>
				
			</select><br/>
			<a href="index.php?act=managegamequeue&delete=<?php echo $row['source'].','.$row['sourceid'];?>" onclick="return confirm(\'Are you sure you want to remove this game?\')"><img src="img/delete.png" height="25" width="25" alt="delete game" title="delete" style="padding-top:10px;"/></a><input name="checkbox" type="checkbox" id="checkbox[]" value="<?php echo $row['source'].','.$row['sourceid'];?>" style="vertical-align: top; margin: 18px 0 0 15px;"></td>
			</tr>
<?php			
		}
	
	$query->close();
	?>
</table>
</div> </form>

<div style="text-align:center;padding-bottom:10px;">	
<?php
     if ($pageno == 1) {
	   echo ' FIRST PREV ';
	} else {
	   echo ' <a href="index.php?act=managegamequeue&page=1">FIRST</a> ';
	   $prevpage = $pageno-1;
	   echo ' <a href="index.php?act=managegamequeue&page=' . $prevpage . '">PREV</a> ';
	} 
	echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
	if ($pageno == $lastpage) {
	   echo ' NEXT LAST ';
	} else {
	   $nextpage = $pageno+1;
	   echo ' <a href="index.php?act=managegamequeue&page=' . $nextpage . '">NEXT</a> ';
	   echo ' <a href="index.php?act=managegamequeue&page=' . $lastpage . '">LAST</a> ';
	}
?>
</div>
<?php } ?>
</div>