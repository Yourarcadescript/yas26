<div id="center-column">
<div class="top-bar">
				<a href="index.php?act=uploadgames" title="Upload Games" class="button">ADD GAME</a>
				<h1>Cpanel - Categories</h1>
				<div class="breadcrumbs"><a href="index.php?act=general" title="Settings">Settings</a> / <a href="index.php?act=links">Links</a></div>
			</div><br />
		  <div class="select-bar">
		    <label>
		    <h3>Manage Categories</h3>
		    </label>
		  </div>
<?php
if(!empty($_GET['edit'])) {
	$query = yasDB_select("SELECT * FROM categories WHERE id = '{$_GET['edit']}'",false);
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
			<input type="hidden" name="act" value="categories"/>
			<table class="listing form" cellpadding="0" cellspacing="0">
	        <tr>
	        <th class="full" colspan="2">Edit - Category</th>
			</tr>
			<tr>
			<td class="first" width="172"><strong>Name</strong></td>
			<td class="last">
			<?php if ($row['id'] > 10) { ?>
				<input type="text" name="name" value="<?php echo $row['name'];?>" /></td>
			<?php } else {
				?> <label style="font-weight:700;"><?php echo $row['name'];?></label>
				<input type="hidden" name="name" value="<?php echo $row['name'];?>" />
			<?php } ?>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Active</strong></td>
			<td class="last">Active:<br/>
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
			<tr class="bg">
			<td class="first"><strong>Parent category</strong></td>
			<td class="last">
			<select name="parent">
            <option value ="<?php echo $row['parent'];?>" /><?php echo $row['parent'];?></option>
            <option value ="yes">yes</option>
            <option value ="no">no</option>
            </select>
			</td>
			</tr>
            <tr>
			<td class="first"><strong>Show on home page</strong></td>
			<td class="last"><select name="home">
        <option value ="<?php echo $row['home'];?>" /><?php echo $row['home'];?></option>
        <option value ="yes">yes</option>
        <option value ="no">no</option>
        </select></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>If not parent,id of the parent category</strong></td>
			<td class="last"><input type="text" name="pid" size=3 value="<?php echo $row['pid'];?>"  /></td>
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
} elseif(isset($_POST['newcategory'])) {
	$newid = intval($_POST['newcategory']);
	$oldid = intval($_POST['oldcategory']);
	$return = yasDB_update("UPDATE games SET category=$newid WHERE category=$oldid");
	if($return === false) {
		echo "Error converting games to the new category. Please check your dberror_log.txt for more details.<br/>Deleting of category cancelled.";
	} else {
		$catreturn = yasDB_delete("DELETE FROM categories WHERE id=$oldid");
		if ($catreturn === false) {
			echo "Error deleteing category. Please check your dberror_log.txt for more details.<br/>Deleting of category aborted.";
		} else {
			echo '<center>Category Successfully deleted!<br/><br/>';
			echo '<a href="index.php?act=categories">Click here to proceed</a></center>'; 
		}
	}
} elseif(isset($_GET['check'])) {
	if(empty($_GET['name'])) {
		echo '<center>One or more fields was left empty.<br />';
		echo '<a href="index.php?act=categories&edit=' . $_GET['id'] . '">Click here to go back</a></center>';
	} else {
		yasDB_update("UPDATE categories SET name = '{$_GET['name']}', active = '{$_GET['active']}', `order` = '{$_GET['order']}', `parent` = '{$_GET['parent']}', `home` = '{$_GET['home']}', `desc` = '{$_GET['desc']}', `pid` = '{$_GET['pid']}' where id = '{$_GET['id']}'",false);
		echo '<center>Category Successfully edited!<br/><br/>';
		echo '<a href="index.php?act=categories">Click here to proceed</a></center>';
	}
} elseif(!empty($_GET['delete'])) {
	$query = yasDB_select("SELECT id FROM categories WHERE id = ".intval($_GET['delete']),false);
	$query2 = yasDB_select("SELECT COUNT(id) AS count FROM games WHERE category=".intval($_GET['delete']));
	$tcount = $query2->fetch_array(MYSQLI_ASSOC);
	if($query->num_rows == 0) {
		echo '<center>You cannot delete a category that does not exist!<br />';
		echo '<a href="index.php?act=categories">Click here to go back</a></center>';
		$query->close();
	} elseif($tcount['count'] > 0) {
		?><center>Games are attached to this category. Please choose a category for these games.<br />
			<?php
			$result = yasDB_select("SELECT id, name FROM categories WHERE active='yes'");
			?>
			<form id="newcategories" method="post" action="">
			<select name="newcategory">
			<?php
				while($categoryitem = $result->fetch_array(MYSQLI_ASSOC)) {
					if($categoryitem['id'] != intval($_GET['delete'])) {
						?><option value="<?php echo $categoryitem['id'];?>"><?php echo $categoryitem['name'];?></option><?php
					}
				}
			?>
			</select>
			<input type="hidden" name="oldcategory" value="<?php echo intval($_GET['delete']);?>"><br/><br/>
			<input type="submit" value="Submit">
			</form>
		<?php
	} else {
		$query->close();		
		yasDB_delete("DELETE FROM categories WHERE id = '{$_GET['delete']}'");
		echo '<center>Category successfully deleted.<br />';
		echo '<a href="index.php?act=categories">Click here to proceed</a></center>';
	}
} elseif(isset($_POST['deletechecked'])) {
    $count = count($_POST['checkbox']);
    ?>
    <script type = "text/javascript">
        var response = confirm("Are you sure you want to delete <?php echo $count;?> categorie(s)?");
        if (!response) {
            window.location.href = '<?php echo $setting['siteurl'];?>admin/index.php?act=categories&remove=notta';
            
        }
    </script>
    <?php
    foreach ($_POST['checkbox'] as $box) {
        $query = yasDB_select("SELECT id FROM categories WHERE id = '$box'",false);
        if($query->num_rows == 0) {
            echo '<center>You cannot delete this categorie that does not exist!<br />';
            echo '<a href="index.php?act=categories">Click here to go back</a></center>';
        } else {
            if ($_GET['remove'] != 'notta') {
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $query->close();            
                yasDB_delete("DELETE FROM categories WHERE id = '$box'",false);
                ?>
                <meta http-equiv="refresh" content="0;URL=<?php echo $setting['siteurl'];?>admin/index.php?act=categories" />
                <?php
            }
        } 
	}
} elseif(isset($_POST['add'])) {
	$query = yasDB_select("SELECT id FROM `categories` where name = '{$_POST['name']}'",false);
	if($query->num_rows != 0) {
		$query->close();		
		echo '<center>Sorry, that category already exists.<br/>';
		echo '<a href="index.php?act=categories">Click here to go back</a></center>';
	} else {
		$query->close();		
		$query = yasDB_select("SELECT `order`*1 AS `neworder` FROM `categories` ORDER BY `neworder` DESC LIMIT 1");
		$order = $query->fetch_array(MYSQLI_NUM);
		$neworder = $order[0];
		$neworder++;
		yasDB_insert("INSERT INTO categories (`id`,`name`,`active`,`order`) VALUES ('', '{$_POST['name']}', 'yes', '$neworder')");
		echo '<center>Category Successfully added!<br />';
		echo '<a href="index.php?act=categories">Click here to proceed!</a></center>';
	}
} else { 
if (isset($_GET['page'])) {
		$pageno = $_GET['page'];
	} else {
		$pageno = 1;
	} 
	$result = yasDB_select("SELECT count(id) FROM categories",false);
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
	$query = yasDB_select("select * from categories $limit",false);
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
						<th>Parent</th>
						<th>Sub Cat of (id)</th>
						<th>Show on home page</th>
						<th width="100px">Meta description</th>
						<th width="40px"></th>
						<th class="last" width="50px"></th>
					</tr>
	<?php
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		?>
		<tr><form name="deleteform" method="post" action="">
		<td class="first style1" style="height:45px;"><?php echo $row['id'];?></td>
		<td style="overflow:hidden;height:45px;"><?php echo $row['name'];?></td>
		<td style="height:45px;"><?php echo $row['active'];?></td>
		<td style="height:45px;"><?php echo $row['order'];?></td>
		<td style="height:45px;"><?php echo $row['parent'];?></td>
		<td style="height:45px;"><?php echo $row['pid'];?></td>
		<td style="height:45px;"><?php echo $row['home'];?></td>
		<td style="overflow:hidden;height:45px;"><?php echo $row['desc'];?></td>
		<td style="height:45px;"><a href="index.php?act=categories&edit=<?php echo $row['id'];?>"><font color="#cc0000">Edit</font></a></td>
		<td class="last" style="height:45px;">
		<?php
		if ($row['id'] > 10) { ?>
		<a href="index.php?act=categories&delete=<?php echo $row['id'];?>" onclick="return confirm(\'Are you sure you want to delete this category?\')"><font color="#cc0000">Delete</font></a><br/><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $row['id'];?>">
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
			echo ' <a href="index.php?act=categories&page=1">FIRST</a> ';
			$prevpage = $pageno-1;
			echo ' <a href="index.php?act=categories&page=' . $prevpage . '">PREV</a> ';
		} 
		echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
		if ($pageno == $lastpage) {
			echo ' NEXT LAST ';
		} else {
			$nextpage = $pageno+1;
			echo ' <a href="index.php?act=categories&page=' . $nextpage . '">NEXT</a> ';
			echo ' <a href="index.php?act=categories&page=' . $lastpage . '">LAST</a> ';
		} ?> 
	</div>
<?php	
}
}
?>
</div>