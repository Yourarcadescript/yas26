<div id="center-column">
<div class="top-bar">
				<a href="index.php?act=uploadgames" title="Upload Games" class="button">ADD GAME</a>
				<h1>Cpanel - Forum Categories</h1>
				<div class="breadcrumbs"><a href="index.php?act=general" title="Settings">Settings</a> / <a href="index.php?act=links">Links</a></div>
			</div><br />
		  <div class="select-bar">
		    <label>
		    <h3>Manage Forum Categories</h3>
		    </label>
		  </div>
<?php
if(!empty($_GET['edit'])) {
	$query = yasDB_select("SELECT * FROM forumcats WHERE id = '{$_GET['edit']}'",false);
	if($query->num_rows == 0) {
		echo '<center>You cannot edit a category that doesnt exist.</center>';
	} else {
		$row = $query->fetch_array(MYSQLI_ASSOC);
		$query->close();

		?>
		<div class="table">
		    <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
			<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
			<form name="update" method="get" action="index.php">
			<input type="hidden" name="act" value="manageforumcats"/>
			<table class="listing form" cellpadding="0" cellspacing="0">
	        <tr>
	        <th class="full" colspan="2">Edit - Category</th>
			</tr>
			<tr>
			<td class="first" width="172"><strong>Name</strong></td>
			<td class="last">
			<?php if ($row['id']) { ?>
				<input type="text" name="name" value="<?php echo $row['name'];?>" /></td>
			<?php } else {
				?> <label style="font-weight:700;"><?php echo $row['name'];?></label>
				<input type="hidden" name="name" value="<?php echo $row['name'];?>" />
			<?php } ?>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Active</strong></td>
			<td class="last">
		<select name="active">
        <option value ="<?php echo $row['active'];?>" /><?php echo $row['active'];?></option>
        <option value ="yes">yes</option>
        <option value ="no">no</option>
        </select></td>
		    </tr>
			<tr>
			<td class="first"><strong>Display Order</strong></td>
			<td class="last"><input type="text" name="order" size=3 value="<?php echo $row['order'];?>"  /></td>
			</tr>
			<tr>
			<td class="first"><strong>Meta description</strong></td>
			<td class="last"><input type="text" name="desc" value="<?php echo $row['desc'];?>" size="40" /></td>
			</tr>
			<tr class="bg">
			<td class="first"></td>
			<td class="last"><input type="hidden" name="id" value="<?php echo $row['id'];?>" /><input type="submit" class="button" name="check" value="Edit!" />
		<input type="reset" class="button" value="Reset" /></td>
			</tr>
		</table>
			</div>
			</form>
	<?php
	}
} elseif(isset($_GET['check'])) {
	if(empty($_GET['name'])) {
		echo '<center>One or more fields was left empty.<br />';
		echo '<a href="index.php?act=manageforumcats&edit=' . $_GET['id'] . '">Click here to go back</a></center>';
	} else {
		yasDB_update("UPDATE forumcats SET name = '{$_GET['name']}', active = '{$_GET['active']}', `order` = '{$_GET['order']}', `desc` = '{$_GET['desc']}' where id = '{$_GET['id']}'",false);
		echo '<center>Category Successfully edited!<br/><br/>';
		echo '<a href="index.php?act=manageforumcats">Click here to proceed</a></center>';
	}
} elseif(!empty($_GET['delete'])) {
	$query = yasDB_select("SELECT id FROM forumcats WHERE id = '{$_GET['delete']}'",false);
	if($query->num_rows == 0) {
		echo '<center>You cannot delete a category that does not exist!<br />';
		echo '<a href="index.php?act=manageforumcats">Click here to go back</a></center>';
		$query->close();

	} else {
		$query->close();
		yasDB_delete("DELETE FROM forumcats WHERE id = '{$_GET['delete']}'");
		echo '<center>Category successfully deleted.<br />';
		echo '<a href="index.php?act=manageforumcats">Click here to proceed</a></center>';
	}
} elseif(isset($_POST['deletechecked'])) {
    $count = count($_POST['checkbox']);
    ?>
    <script type = "text/javascript">
        var response = confirm("Are you sure you want to delete <?php echo $count;?> categorie(s)?");
        if (!response) {
            window.location.href = '<?php echo $setting['siteurl'];?>admin/index.php?act=manageforumcats&remove=notta';

        }
    </script>
    <?php
    foreach ($_POST['checkbox'] as $box) {
        $query = yasDB_select("SELECT id FROM forumcats WHERE id = '$box'",false);
        if($query->num_rows == 0) {
            echo '<center>You cannot delete this categorie that does not exist!<br />';
            echo '<a href="index.php?act=manageforumcats">Click here to go back</a></center>';
        } else {
            if ($_GET['remove'] != 'notta') {
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $query->close();
                yasDB_delete("DELETE FROM forumcats WHERE id = '$box'",false);
                ?>
                <meta http-equiv="refresh" content="0;URL=<?php echo $setting['siteurl'];?>admin/index.php?act=manageforumcats" />
                <?php
            }
        }
	}
} elseif(isset($_POST['add'])) {
	$query = yasDB_select("SELECT id FROM `forumcats` where name = '{$_POST['name']}'",false);
	if($query->num_rows != 0) {
		$query->close();
		echo '<center>Sorry, that category already exists.<br/>';
		echo '<a href="index.php?act=manageforumcats">Click here to go back</a></center>';
	} else {
		$query->close();
		$query = yasDB_select("SELECT `order`*1 AS `neworder` FROM `forumcats` ORDER BY `neworder` DESC LIMIT 1");
		$order = $query->fetch_array(MYSQLI_NUM);
		$neworder = $order[0];
		$neworder++;
		yasDB_insert("INSERT INTO forumcats (`id`,`name`,`active`,`order`) VALUES ('', '{$_POST['name']}', 'yes', '$neworder')");
		echo '<center>Category Successfully added!<br />';
		echo '<a href="index.php?act=manageforumcats">Click here to proceed!</a></center>';
	}
} else {
if (isset($_GET['page'])) {
		$pageno = $_GET['page'];
	} else {
		$pageno = 1;
	}
	$result = yasDB_select("SELECT count(id) FROM forumcats",false);
	$query_data = $result->fetch_array(MYSQLI_NUM);
	$numrows = $query_data[0];
	$result->close();

	$rows_per_page = 15;
	$lastpage = ceil($numrows/$rows_per_page);
	$pageno = (int)$pageno;
	if ($pageno > $lastpage) {
	   $pageno = $lastpage;
	}
	if ($pageno < 1) {
	   $pageno = 1;
	}
	$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
	$query = yasDB_select("select * from forumcats $limit",false);
	if($query->num_rows == 0) {
		echo '<center>No categories in the database!</center>';
	} else {?>
	<br/><center>
	<form name="add" method="post" action="">
	Name: <input type="text" name="name" /> <input type="submit" class="button" value="Add Category" name="add">
	</form></center><br />

	<div class="table">
		    <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
			<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
			<table class="listing" cellpadding="0" cellspacing="0">
			<tr>
						<th class="first" width="30px">ID</th>
						<th>Category</th>
						<th width="40px">Active</th>
						<th width="30px">Order</th>
						<th width="200px">Meta description</th>
						<th width="40px">Edit</th>
						<th class="last" width="50px">Delete</th>
					</tr>
	<?php
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		?>
		<tr><form name="deleteform" method="post" action="">
		<td class="first style1" style="height:45px;"><?php echo $row['id'];?></td>
		<td style="overflow:hidden;height:45px;"><?php echo $row['name'];?></td>
		<td style="height:45px;"><?php echo $row['active'];?></td>
		<td style="height:45px;"><?php echo $row['order'];?></td>
		<td style="overflow:hidden;height:45px;"><?php echo $row['desc'];?></td>
		<td style="height:45px;"><a href="index.php?act=manageforumcats&edit=<?php echo $row['id'];?>"><font color="#cc0000">Edit</font></a></td>
		<td class="last" style="height:45px;">
		<?php
		if ($row['id']) { ?>
		<a href="index.php?act=manageforumcats&delete=<?php echo $row['id'];?>" onclick="return confirm(\'Are you sure you want to delete this category?\')"><font color="#cc0000">Delete</font></a><br/><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $row['id'];?>">
		<?php } ?>
		</td>
		</tr>

	<?php
	}
	$query->close();

	?>
	</table>
	</div>
	<div style="text-align:right;padding-right:20px;padding-top:10px;margin-bottom:4px;">
        <input name="deletechecked" type="submit" class="button" id="deletechecked" value="Delete Checked">
        </form>
    </div>
	<br /><div style="text-align:center;padding-bottom:10px;">
		<?php
		if ($pageno == 1) {
			echo ' FIRST PREV ';
		} else {
			echo ' <a href="index.php?act=manageforumcats&page=1">FIRST</a> ';
			$prevpage = $pageno-1;
			echo ' <a href="index.php?act=manageforumcats&page=' . $prevpage . '">PREV</a> ';
		}
		echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
		if ($pageno == $lastpage) {
			echo ' NEXT LAST ';
		} else {
			$nextpage = $pageno+1;
			echo ' <a href="index.php?act=manageforumcats&page=' . $nextpage . '">NEXT</a> ';
			echo ' <a href="index.php?act=manageforumcats&page=' . $lastpage . '">LAST</a> ';
		} ?>
	</div>
<?php
}
}
?>
</div>