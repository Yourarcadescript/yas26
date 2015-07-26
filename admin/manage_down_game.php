<div id="center-column">
<div class="top-bar">
				<a href="index.php?act=uploadgames" title="Upload Games" class="button">ADD GAME</a>
				<h1>Cpanel - Downloadable Games</h1>
				<div class="breadcrumbs"><a href="index.php?act=addmedia" title="Ad Media">Add Media</a> / <a href="index.php?act=addcode" title="Ad Code">Add Code</a> / <a href="index.php?act=uploadgames" title="Upload Games">Upload Games</a> / <a href="index.php?act=brokenfiles" title="Broken Files">Broken Files</a></div>
			</div><br />
		  <div class="select-bar">
		    <label>
		    <h3>Manage Downloadable Games</h3>
		    </label>
		  </div>
<?php
if(!empty($_GET['edit'])) {

	$query = yasDB_select("SELECT * FROM downgames WHERE id = '{$_GET['edit']}'",false);
	if($query->num_rows == 0) {
		echo '<center>You cannot edit a game that doesnt exist.</center>';
	} else {
		$row = $query->fetch_array(MYSQLI_ASSOC);?>		
	<div class="table">
		    <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
			<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
			<form name="edit" method="post" action="index.php?act=managedowngame">
			<table class="listing form" cellpadding="0" cellspacing="0">
	        <tr>
	        <th class="full" colspan="2">Edit - <?php echo $row['title'];?></th>
			</tr>
			<tr>
			<td class="first" width="172"><strong>Title</strong></td>
			<td class="last"><input type="text" name="title" value="<?php echo $row['title'];?>"  /></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Description</strong></td>
			<td class="last"><textarea name="description" style="width: 300px; height: 100px;"/><?php echo $row['description'];?></textarea></td>
		    </tr>
			<tr>
			<td class="first"><strong>Submit</strong></td>
			<td class="last"><input type="hidden" name="id" value="<?php echo $row['id'];?>" />
			<input type="hidden" name="m" value="<?php echo $_GET['m'];?>"/>
			<input type="submit" class=button" name="edit" value="Edit!" />
			</td>
			</tr>
			</table>
			</div>
			</form>
			<?php	
    }	
	$query->close();
	
} elseif(isset($_POST['edit'])) {
	if(empty($_POST['title'])) {
		echo '<center>One or more fields was left empty.<br />';
		echo '<a href="index.php?act=managedowngame&edit=' . $_POST['id'] . '">Click here to go back</a></center>';
	} else {
		yasDB_update("UPDATE downgames SET title = '{$_POST['title']}', description = '{$_POST['description']}' where id = '{$_POST['id']}'",false);
		echo '<p align="center">File Successfully edited!<br />';
	 if (!empty($_POST['m'])) {
			echo '<center><a href="index.php?act=managedowngame">Click here to proceed</a></p></center>';
		}
	}
} elseif(!empty($_GET['delete'])) {
	$query = yasDB_select("SELECT id, file, thumbnail FROM downgames WHERE id = '{$_GET['delete']}'",false);
	if($query->num_rows == 0) {
		echo '<center>You cannot delete a game that does not exist!<br /><a href="index.php?act=managedowngame">Click here to go back</a></center>';
	} else {
		$row = $query->fetch_array(MYSQLI_ASSOC);
		@unlink($row['file']);
		@unlink($row['thumbnail']);
		$query->close();
		
		yasDB_delete("delete from downgames where id = '{$_GET['delete']}'",false);
		echo '<p style="text-align:center;">Game successfully deleted from Download Our Games section.<br />';
		echo '<a href="index.php?act=managedowngame">Click here to proceed</a></p>';
	}
} elseif(isset($_POST['deletechecked'])) {
    $count = count($_POST['checkbox']);
    ?>
    <script type = "text/javascript">
        var response = confirm("Are you sure you want to delete <?php echo $count;?> game(s)?");
        if (!response) {
            window.location.href = '<?php echo $setting['siteurl'];?>admin/index.php?act=managedowngame&remove=notta';
            
        }
    </script>
    <?php
    foreach ($_POST['checkbox'] as $box) {
        $query = yasDB_select("SELECT id, file, thumbnail FROM downgames WHERE id = '$box'",false);
        if($query->num_rows == 0) {
            echo '<center>You cannot delete a game that does not exist!<br />';
            echo '<a href="index.php?act=managedowngame">Click here to go back</a></center>';
        } else {
            if ($_GET['remove'] != 'notta') {
                $row = $query->fetch_array(MYSQLI_ASSOC);
                @unlink('../ourgames/img/' . $row['file']);
                @unlink('../ourgames/games/' . $row['thumbnail']);
                $query->close();            
                yasDB_delete("delete from downgames where id = '$box'",false);
                ?>
                <meta http-equiv="refresh" content="0;URL=<?php echo $setting['siteurl'];?>admin/index.php?act=managedowngame" />
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
	$result = yasDB_select("SELECT count(id) FROM downgames");
	$query_data = $result->fetch_array(MYSQLI_NUM);;
	$numrows = $query_data[0];
	$result->close();
	
	$rows_per_page = 10;
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
	$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;?>
<div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing" cellpadding="0" cellspacing="0">
					<tr>
						<th class="first">ID</th>
						<th>Thumb</th>
						<th>Game Title</th>
						<th>Description</th>
						<th>Down</th>
						<th>D.Mochi</th>
						<th>Edit</th>
						<th class="last">Delete</th>
					</tr>
<?php
	$query = yasDB_select("SELECT * FROM `downgames` ORDER BY `id` DESC ");
	if($query->num_rows==0) {
    } else { 
		while($row = $query->fetch_array(MYSQLI_ASSOC)) { ?>
		<form name="deleteform" method="post" action="">
			    <tr>
				<td class="first style1" width="80px"><?php echo $row['id'];?></td>
				<td><img src="<?php echo $row['thumbnail'];?>" style="width: 60px; height: 50px;" /></td>
				<td><?php echo $row['title'];?></td>
				<td><?php echo $row['description'];?></td>
				<td><?php echo $row['downloadtimes'];?></td>	
				<td><?php echo $row['mochidownloads'];?></td>
				<td ><a href="index.php?act=managedowngame&edit=<?php echo $row['id'];?>&m=1"><font color="#cc0000">Edit</font></a></td>
				<td class="last"><font color=red><a href="index.php?act=managedowngame&delete=<?php echo $row['id'];?>" onclick="return confirm(\'Are you sure you want to delete this game?\')"><font color="#cc0000">Delete</font></a><br/><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $row['id'];?>"></td>
				</tr>
		<?php
		}
		$query->close();
	}
?>
</table></div>
<div style="text-align:right;">
        <input name="deletechecked" class="button" type="submit" id="deletechecked" value="Delete Checked">
        </form>
    </div>
<div style="text-align:center;padding-bottom:10px;">	
<?php
     if ($pageno == 1) {
	   echo ' FIRST PREV ';
	} else {
	   echo ' <a href="index.php?act=managedowngame&page=1">FIRST</a> ';
	   $prevpage = $pageno-1;
	   echo ' <a href="index.php?act=managedowngame&page=' . $prevpage . '">PREV</a> ';
	} 
	echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
	if ($pageno == $lastpage) {
	   echo ' NEXT LAST ';
	} else {
	   $nextpage = $pageno+1;
	   echo ' <a href="index.php?act=managedowngame&page=' . $nextpage . '">NEXT</a> ';
	   echo ' <a href="index.php?act=managedowngame&page=' . $lastpage . '">LAST</a> ';
	}
?>
</div>
<?php
}
?>
</div>