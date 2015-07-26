<div id="center-column">
<div class="top-bar">
				<a href="index.php?act=uploadgames" title="Upload Games" class="button">ADD GAME</a>
				<h1>Cpanel - Games</h1>
				<div class="breadcrumbs"><a href="index.php?act=addmedia" title="Ad Media">Add Media</a> / <a href="index.php?act=addcode" title="Ad Code">Add Code</a> / <a href="index.php?act=uploadgames" title="Upload Games">Upload Games</a> / <a href="index.php?act=brokenfiles" title="Broken Files">Broken Files</a></div>
			</div><br />
		  <div class="select-bar">
		    <label>
		    <h3>Manage Forum Post</h3>
		    </label>
		  </div>
<?php
$qry = yasDB_select("SELECT id from forumposts");
$gameids = array();
while ($game = $qry->fetch_array(MYSQLI_NUM)) {
	$gameids[] = $game[0];
}
if(!empty($_GET['edit'])) {
	$query = yasDB_select("SELECT * FROM forumposts WHERE id = '{$_GET['edit']}'",false);
	if($query->num_rows == 0) {
		echo 'You cannot edit a post that doesnt exist.';
		$query->close();

	} else {
		$row = $query->fetch_array(MYSQLI_ASSOC);
		$query->close();
		$catName = array();
		$catId = array();
		$i = 0;
		$query = yasDB_select("SELECT id, name FROM forumcats",false);
		while($names = $query->fetch_array(MYSQLI_BOTH)) {
			$catName[$i] = $names['name'];
			$catId[$i] = $names['id'];
			$i++;
		}
		$query->close();
		?>
		<div class="table">
		    <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
			<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
			<form name="edit" method="post" action="index.php?act=manageposts">
			<table class="listing form" cellpadding="0" cellspacing="0">
	        <tr>
	        <th class="full" colspan="2">Edit - <?php echo $row['id'];?></th>
			</tr>
			<tr>
			<td class="first"><strong>ID</strong></td>
			<td class="last"><?php echo $row['id'];?></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Message</strong></td>
			<td class="last"><textarea name="text" /><?php echo $row['text'];?></textarea></td>
		    </tr>
			<tr>
			<td class="first"><strong>Date</strong></td>
			<td class="last"><input type="text" name="date" value="<?php echo $row['date'];?>" /></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Topic ID</strong></td>
			<td class="last"><input type="text" name="topic" value="<?php echo $row['topic'];?>"  /></td>
			</tr>
            <tr>
			<td class="first"><strong>User</strong></td>
			<td class="last"><input type="text" name="name" value="<?php echo $row['name'];?>"  /></td>
			</tr>
			<tr class="bg">
			<td class="first"></td>
			<td class="last"><input type="hidden" name="id" value="<?php echo $row['id'];?>"/>
		<input type="hidden" name="m" value="<?php echo $_GET['m'];?>"/>
		<input type="submit" class="button" name="edit" value="Edit!"/>&nbsp;
		<input type="reset" class="button" name="reset" value="Reset"/></td>
			</tr>
			</table>
			</div>
			</form>
	<?php
	}

} elseif(isset($_POST['edit'])) {
	if(empty($_POST['id']) || empty($_POST['text'])) {
		echo '<center>One or more fields was left empty.<br />';
		echo '<a href="index.php?act=manageposts&edit=' . $_POST['id'] . '">Click here to go back</a></center>';
	} else {
		yasDB_update("UPDATE forumposts SET id = '". yasDB_clean($_POST['id'])."', text = '".yasDB_clean($_POST['text'])."', date = '".yasDB_clean($_POST['date'])."',topic = '".yasDB_clean($_POST['topic'])."', name = '".yasDB_clean($_POST['name'])."' where id = {$_POST['id']}",false);
		$result = yasDB_select("SELECT id FROM forumposts WHERE id = {$_POST['id']}");
		if ($_POST['featured'] == 'yes') {
			if ($result->num_rows == 0) {
				yasDB_insert("INSERT INTO forumposts (id) VALUES({$_POST['id']})");
			}
		}
		elseif ($_POST['featured'] == 'no') {
			yasDB_delete("DELETE FROM forumposts WHERE id = {$_POST['id']}");
		}
		echo '<p align="center">Post Successfully Edited!<br />';
		if (!empty($_POST['m'])) {
			echo '<center><a href="index.php?act=manageposts">Click here to proceed</a></p></center>';
		} else {
			echo '<center><a href="index.php?act=brokenfiles">Click here to proceed</a></p></center>';
		}
	}
} elseif(!empty($_GET['delete'])) {
	$query = yasDB_select("SELECT id, name FROM forumposts WHERE id = '{$_GET['delete']}'",false);
	if($query->num_rows == 0) {
		echo '<center>You cannot delete a post that does not exist!<br />';
		echo '<a href="index.php?act=manageposts">Click here to go back</a></center>';
	} else {
		$row = $query->fetch_array(MYSQLI_ASSOC);
		yasDB_update("UPDATE `user` set posts = posts -1 WHERE username = '{$row['name']}'"); // add a post to the user
		yasDB_update("UPDATE `user` set totalposts = totalposts -1 WHERE username = '{$row['name']}'"); // add a post to the user Total
		$query->close();

		yasDB_delete("delete from forumposts where id = '{$_GET['delete']}'",false);
		echo '<center><p style="text-align:center;">Post successfully deleted.<br />';
		echo '<a href="index.php?act=manageposts">Click here to proceed</a></p></center>';
	}
} elseif(isset($_POST['deletechecked'])) {
    $count = count($_POST['checkbox']);
    ?>
    <script type = "text/javascript">
        var response = confirm("Are you sure you want to delete <?php echo $count;?> post(s)?");
        if (!response) {
            window.location.href = '<?php echo $setting['siteurl'];?>admin/index.php?act=manageposts&remove=notta';

        }
    </script>
    <?php
    foreach ($_POST['checkbox'] as $box) {
        $query = yasDB_select("SELECT id, name FROM forumposts WHERE id = '$box'",false);
        if($query->num_rows == 0) {
            echo '<center>You cannot delete a post that does not exist!<br />';
            echo '<a href="index.php?act=manageposts">Click here to go back</a></center>';
        } else {
            if ($_GET['remove'] != 'notta') {
                $row = $query->fetch_array(MYSQLI_ASSOC);
				yasDB_update("UPDATE `user` set posts = posts -1 WHERE username = '{$row['name']}'"); // add a post to the user
				yasDB_update("UPDATE `user` set totalposts = totalposts -1 WHERE username = '{$row['name']}'"); // add a post to the user Total
                $query->close();
                yasDB_delete("delete from forumposts where id = '$box'",false);
                ?>
                <meta http-equiv="refresh" content="0;URL=<?php echo $setting['siteurl'];?>admin/index.php?act=manageposts" />
                <?php
            }
        }
    }
} else {
	if (isset($_GET['page'])) {
	   $pageno = $_GET['page'];
	} else {
	   $pageno = 1;
	}
	$result = yasDB_select("SELECT count(id) FROM forumposts");
	$query_data = $result->fetch_array(MYSQLI_NUM);;
	$numrows = $query_data[0];
	$result->close();

	$rows_per_page = 20;
	$lastpage = ceil($numrows/$rows_per_page);
	$pageno = (int)$pageno;
	if ($lastpage < 1) {
		$lastpage = 1;
	}
	if ($pageno < 1) {
	   $pageno = 1;
	} elseif ($pageno > $lastpage) {
	   $pageno = $lastpage;
	}
	$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
	?><div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing" cellpadding="0" cellspacing="0">
					<tr>
						<th class="first">ID</th>
						<th width="204px">Message</th>
						<th>Date</th>
						<th>Topic ID</th>
						<th>User</th>
						<th>Edit</th>
						<th class="last">Delete</th>
					</tr>
<?php
	$query_cats = yasDB_select("SELECT name FROM forumcats");
	$cats = array();
	$i = 0;
	while ($catRow = $query_cats->fetch_array(MYSQLI_ASSOC)) {;
		$cats[$i] = $catRow['name'];
		$i++;
	}
	$query_cats->close();
	$query = yasDB_select("SELECT * FROM forumposts ORDER BY `id` DESC $limit");
	if($query->num_rows==0) {
    } else {
		?><form name="deleteform" method="post" action=""><?php
		while($row = $query->fetch_array(MYSQLI_ASSOC)) {
			?>
			<tr>
			<td class="first style1"><?php echo $row['id'];?></td>
			<td><?php echo $row['text'];?></td>
			<td><?php echo $row['date'];?></td>
			<td><?php echo $cats[$row['topic']-1];?></td>
			<td><?php echo $row['name'];?></td>
			<td ><a href="index.php?act=manageposts&edit=<?php echo $row['id'];?>&m=1"><font color="#cc0000">Edit</font></a></td>
			<td class="last"><a href="index.php?act=manageposts&delete=<?php echo $row['id'];?>" onclick="return confirm(\'Are you sure you want to delete this game?\')"><font color="#cc0000">Delete</font></a><br/><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $row['id'];?>"></td>
			</tr>
<?php
		}
	}
	unset ($cats);
	$query->close();
	?>
</table>
</div>
<div style="text-align:right;">
        <input name="deletechecked" class="button" type="submit" id="deletechecked" value="Delete Checked">
        </form>
    </div>
<div style="text-align:center;padding-bottom:10px;">
<?php
     if ($pageno == 1) {
	   echo ' FIRST PREV ';
	} else {
	   echo ' <a href="index.php?act=manageposts&page=1">FIRST</a> ';
	   $prevpage = $pageno-1;
	   echo ' <a href="index.php?act=manageposts&page=' . $prevpage . '">PREV</a> ';
	}
	echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
	if ($pageno == $lastpage) {
	   echo ' NEXT LAST ';
	} else {
	   $nextpage = $pageno+1;
	   echo ' <a href="index.php?act=manageposts&page=' . $nextpage . '">NEXT</a> ';
	   echo ' <a href="index.php?act=manageposts&page=' . $lastpage . '">LAST</a> ';
	}
?>
</div>
<?php
}
?>
</div>