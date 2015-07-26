<div id="center-column">
<div class="top-bar">
				<a href="index.php?act=uploadgames" title="Upload Games" class="button">ADD GAME</a>
				<h1>Cpanel - Forum Topics</h1>
				<div class="breadcrumbs"><a href="index.php?act=general" title="Settings">Settings</a> / <a href="index.php?act=links">Links</a></div>
			</div><br />
		  <div class="select-bar">
		    <label>
		    <h3>Manage Forum Topics</h3>
		    </label>
		  </div>
<?php
$qry = yasDB_select("SELECT id from forumtopics");
$gameids = array();
while ($game = $qry->fetch_array(MYSQLI_NUM)) {
	$gameids[] = $game[0];
}
if(!empty($_GET['edit'])) {
	$query = yasDB_select("SELECT * FROM forumtopics WHERE id = '{$_GET['edit']}'",false);
	if($query->num_rows == 0) {
		echo 'You cannot edit a Topic that doesnt exist.';
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
			<form name="edit" method="post" action="index.php?act=managetopics">
			<table class="listing form" cellpadding="0" cellspacing="0">
	        <tr>
	        <th class="full" colspan="2">Edit - <?php echo $row['subject'];?></th>
			</tr>
			<tr>
			<td class="first" width="172"><strong>ID</strong></td>
			<td class="last"><?php echo $row['id'];?></td>
			</tr>
			<tr>
			<td class="first" width="172"><strong>Title</strong></td>
			<td class="last"><input type="text" name="subject" value="<?php echo $row['subject'];?>"  /></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Description</strong></td>
			<td class="last"><textarea name="text" /><?php echo $row['text'];?></textarea></td>
			</tr>
			<tr>
			<td class="first"><strong>Category</strong></td>
			<td class="last"><select name="cat">
		<option value="<?php echo $row['cat'];?>"><?php echo $catName[$row['cat']-1];?></option>
		<?php
		$len = count($catName);
		for($i=0;$i<$len;$i++) {
		?><option value="<?php echo $catId[$i];?>"><?php echo $catName[$i];?></option>
		<?php } ?></select></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Author</strong></td>
			<td class="last"><input type="text" name="name" value="<?php echo $row['name'];?>"  /></td>
			</tr>
			<tr>
			<td class="first"><strong>Date</strong></td>
			<td class="last"><input type="text" name="date" value="<?php echo $row['date'];?>"  /></td>
			</tr>
			<tr>
			<td class="first"><strong>Views</strong></td>
			<td class="last"><input type="text" name="views" value="<?php echo $row['views'];?>"  /></td>
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
	if(empty($_POST['subject']) || empty($_POST['text'])) {
		echo '<center>One or more fields was left empty.<br />';
		echo '<a href="index.php?act=managetopics&edit=' . $_POST['id'] . '">Click here to go back</a></center>';
	} else {
		yasDB_update("UPDATE forumtopics SET subject = '". yasDB_clean($_POST['subject'])."', views = '{$_POST['views']}', date = '{$_POST['date']}', name = '{$_POST['name']}', text = '{$_POST['text']}', cat = '".yasDB_clean($_POST['cat'])."' where id = {$_POST['id']}",false);
		$result = yasDB_select("SELECT id FROM forumtopics WHERE id = {$_POST['id']}");
		if ($_POST['featured'] == 'yes') {
			if ($result->num_rows == 0) {
				yasDB_insert("INSERT INTO forumtopics (id) VALUES({$_POST['id']})");
			}
		}
		elseif ($_POST['featured'] == 'no') {
			yasDB_delete("DELETE FROM forumtopics WHERE id = {$_POST['id']}");
		}
		echo '<p align="center">Topic Successfully edited!<br />';
		if (!empty($_POST['m'])) {
			echo '<center><a href="index.php?act=managetopics">Click here to proceed</a></p></center>';
		} else {
			echo '<center><a href="index.php?act=brokenfiles">Click here to proceed</a></p></center>';
		}
	}
} elseif(!empty($_GET['delete'])) {
	$query = yasDB_select("SELECT id,name FROM forumtopics WHERE id = '{$_GET['delete']}'",false);
	if($query->num_rows == 0) {
		echo '<center>You cannot delete a Topic that does not exist!<br />';
		echo '<a href="index.php?act=managetopics">Click here to go back</a></center>';
	} else {
		$row = $query->fetch_array(MYSQLI_ASSOC);
		$query->close();

		yasDB_delete("delete from forumtopics where id = '{$_GET['delete']}'",false);
		yasDB_update("UPDATE `user` set topics = topics -1 WHERE name = '{$row['name']}'"); // deletes a topic from user total
	    yasDB_update("UPDATE `user` set totalposts = totalposts -1 WHERE username = '{$row['name']}'"); // deletes a topic from user total post count
		echo '<center><p style="text-align:center;">Topic successfully deleted.<br />';
		echo '<a href="index.php?act=managetopics">Click here to proceed</a></p></center>';
	}
} elseif(isset($_POST['deletechecked'])) {
    $count = count($_POST['checkbox']);
    ?>
    <script type = "text/javascript">
        var response = confirm("Are you sure you want to delete <?php echo $count;?> topic(s)?");
        if (!response) {
            window.location.href = '<?php echo $setting['siteurl'];?>admin/index.php?act=managetopics&remove=notta';

        }
    </script>
    <?php
    foreach ($_POST['checkbox'] as $box) {
        $query = yasDB_select("SELECT id, name FROM forumtopics WHERE id = '$box'",false);
        if($query->num_rows == 0) {
            echo '<center>You cannot delete a topic that does not exist!<br />';
            echo '<a href="index.php?act=managetopics">Click here to go back</a></center>';
        } else {
            if ($_GET['remove'] != 'notta') {
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $query->close();
                yasDB_delete("delete from forumtopics where id = '$box'",false);
				yasDB_update("UPDATE `user` set topics = topics -1 WHERE name = '{$row['name']}'"); // deletes a topic from user total
				yasDB_update("UPDATE `user` set totalposts = totalposts -1 WHERE username = '{$row['name']}'"); // deletes a topic from user total post count
                ?>
				<meta http-equiv="refresh" content="0;URL=<?php echo $setting['siteurl'];?>admin/index.php?act=managetopics" />
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
	$result = yasDB_select("SELECT count(id) FROM forumtopics");
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
						<th class="first">Id</th>
						<th width="102px">Topic</th>
						<th width="102px">Category</th>
						<th>Author</th>
						<th>Views</th>
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
	$query = yasDB_select("SELECT * FROM forumtopics ORDER BY `id` DESC $limit");
	if($query->num_rows==0) {
    } else {
		?><form name="deleteform" method="post" action=""><?php
		while($row = $query->fetch_array(MYSQLI_ASSOC)) {
			?>
			<tr>
			<td><?php echo $row['id'];?></td>
			<td><?php echo $row['subject'];?></td>
			<td><?php echo $cats[$row['cat']-1];?></td>
			<td><?php echo $row['name'];?></td>
			<td><?php echo $row['views'];?></td>
			<td ><a href="index.php?act=managetopics&edit=<?php echo $row['id'];?>&m=1"><font color="#cc0000">Edit</font></a></td>
			<td class="last"><a href="index.php?act=managetopics&delete=<?php echo $row['id'];?>" onclick="return confirm(\'Are you sure you want to delete this topic?\')"><font color="#cc0000">Delete</font></a><br/><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $row['id'];?>"></td>
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
	   echo ' <a href="index.php?act=managetopics&page=1">FIRST</a> ';
	   $prevpage = $pageno-1;
	   echo ' <a href="index.php?act=managetopics&page=' . $prevpage . '">PREV</a> ';
	}
	echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
	if ($pageno == $lastpage) {
	   echo ' NEXT LAST ';
	} else {
	   $nextpage = $pageno+1;
	   echo ' <a href="index.php?act=managetopics&page=' . $nextpage . '">NEXT</a> ';
	   echo ' <a href="index.php?act=managetopics&page=' . $lastpage . '">LAST</a> ';
	}
?>
</div>
<?php
}
?>

</div>