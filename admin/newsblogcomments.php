<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Blog Comments</h1>
<div class="breadcrumbs"><a href="index.php?act=comments" title="Comments">Comments</a> / <a href="index.php?act=memberscomment" title="Members Comments">Members Comments</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Manage News Blog Comments</h3>
</label>
</div>
<?php
if(!empty($_GET['edit'])) {

	$query = yasDB_select("select * from newsblog where id = '{$_GET['edit']}'",false);
	if($query->num_rows == 0) {
		echo '<center>You cannot edit a members comment that doesnt exist.</center>';
	} else {
		$row = $query->fetch_array(MYSQLI_ASSOC);
		?>
		<div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	    <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<form name="edit" method="post" action="index.php?act=newsblogcomments">
		<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
	    <th class="full" colspan="2">Edit - Comment</th>
	    </tr>
		<tr>
		<td class="first" width="172"><strong>Name</strong></td>
		<td class="last"><input type="text" name="username" value="<?php echo $row['username'];?>" /></td>
		</tr>
		<tr class="bg">
		<td class="first"><strong>Comment</strong></td>
		<td class="last"><textarea name="comment" /><?php echo $row['comment'];?></textarea></td>
		</tr>
		</tr>
		<tr>
		<td class="first"><strong>Submit</strong></td>
		<td class="last"><input type="hidden" name="id" value="<?php echo $row['id'];?>" /><input type="hidden" name="newsid" value="<?php echo $row['newsid'];?>" /><input type="submit" class="button" name="edit" value="Edit!" /><input type="reset" class="button" value="Reset"/></td>
		</tr>
		</table>
		</div>
		</form>		
	<?php
	}
	$query->close();
	
} elseif(isset($_POST['edit'])) {
	if(!isset($_POST['username']) || !isset($_POST['comment'])) {
		echo '<center>One or more fields was left empty.<br />';
		echo '<a href="index.php?act=newsblogcomments&edit=' . intval($_POST['id']) . '">Click here to go back</a><center>';
	} else {
		yasDB_update("update newsblog set username = '".yasDB_clean($_POST['username'])."', comment = '".yasDB_clean($_POST['comment'])."' WHERE id = ".intval($_POST['id']), false);
		echo '<center>Comment Successfully edited!<br />';
		echo '<a href="index.php?act=newsblogcomments">Click here to proceed</a></center>';
	}
} elseif(!empty($_GET['delete'])) {
	$query = yasDB_select("select id from newsblog where id = ".intval($_GET['delete']), false);
	if($query->num_rows == 0) {
		echo '<center>You cannot delete a members comment that does not exist!<br />';
		echo '<a href="index.php?act=newsblogcomments">Click here to go back</a></center>';
		$query->close();
		
	} else {
		$query->close();
		
		yasDB_delete("delete from newsblog where id = ".intval($_GET['delete']), false);
		echo '<center>Members comment successfully deleted.<br />';
		echo '<a href="index.php?act=newsblogcomments">Click here to proceed</a></center>';
	}
} elseif(isset($_POST['deletechecked'])) {
    $count = count($_POST['checkbox']);
    ?>
    <script type = "text/javascript">
        var response = confirm("Are you sure you want to delete <?php echo $count;?> these news comment(s)?");
        if (!response) {
            window.location.href = '<?php echo $setting['siteurl'];?>admin/index.php?act=newsblogcomments&remove=notta';
            
        }
    </script>
    <?php
    foreach ($_POST['checkbox'] as $box) {
        $query = yasDB_select("SELECT id FROM newsblog WHERE id = ".intval($box),false);
        if($query->num_rows == 0) {
            echo '<center>You cannot delete this news comment that does not exist!<br />';
            echo '<a href="index.php?act=newsblogcomments">Click here to go back</a></center>';
        } else {
            if ($_GET['remove'] != 'notta') {
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $query->close();            
                yasDB_delete("DELETE FROM newsblog WHERE id = ".intval($box),false);
                ?>
                <meta http-equiv="refresh" content="0;URL=<?php echo $setting['siteurl'];?>admin/index.php?act=newsblogcomments" />
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
	$result = yasDB_select("SELECT count(id) FROM newsblog",false);
	$query_data = $result->fetch_array(MYSQLI_NUM);
	$numrows = $query_data[0];
	$result->close();
	
	$rows_per_page = 10;
	$lastpage = ceil($numrows/$rows_per_page);
	$pageno = (int)$pageno;
	if ($pageno > $lastpage) {
	   $pageno = $lastpage;
	} 
	if ($pageno < 1) {
	   $pageno = 1;
	} 
	$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
	$query = yasDB_select("select * from newsblog $limit",false);
	if($query->num_rows == 0) {
		echo '<center>No members comments in the database!</center>';
	} else {
		?>
		<div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
						<th class="first" width="40px">ID</th>
						<th style="width:80px;">News ID</th>
						<th>Comment</th>
						<th>Name</th>
						<th>Date</th>
						<th>Ip Address</th>
						<th width="40px">Edit</th>
						<th class="last" >Delete</th>
					</tr>
		<?php
		while($row = $query->fetch_array(MYSQLI_ASSOC)) {
			?><form name="deleteform" method="post" action="">
			<tr>
			<td class="first style1"><?php echo $row['id'];?></td>
			<td class="first style1"><?php echo $row['newsid'];?></td>
			<td><?php echo $row['comment'];?></td>
			<td><?php echo $row['username'];?></td>
			<td><?php echo $row['timestamp'];?></td>
			<td><?php echo $row['ipaddress'];?></td>
			<td><a href="index.php?act=newsblogcomments&edit=<?php echo $row['id'];?>"><font color="#cc0000">Edit</font></a></td>
			<td class="last"><a href="index.php?act=newsblogcomments&delete=<?php echo $row['id'];?>" onclick="return confirm(\'Are you sure you want to delete this members comment?\')"><font color="#cc0000">Delete</font></a><br/><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $row['id'];?>"></td>
			</tr>
			<?php
		}
		$query->close();
		
		?>
		</table></div>
		<div style="text-align:right;padding-right:20px;padding-top:10px;">
        <input class="button" name="deletechecked" type="submit" id="deletechecked" value="Delete Checked">
        </form>
    </div>
    <br /><div style="text-align:center;padding-bottom:10px;">
		<?php
		if ($pageno == 1) {
			echo ' FIRST PREV ';
		} else {
			echo ' <a href="index.php?act=newsblogcomments&page=1">FIRST</a> ';
			$prevpage = $pageno-1;
			echo ' <a href="index.php?act=newsblogcomments&page=' . $prevpage . '">PREV</a> ';
		} 
		echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
		if ($pageno == $lastpage) {
			echo ' NEXT LAST ';
		} else {
			$nextpage = $pageno+1;
			echo ' <a href="index.php?act=newsblogcomments&page=' . $nextpage . '">NEXT</a> ';
			echo ' <a href="index.php?act=newsblogcomments&page=' . $lastpage . '">LAST</a> ';
		} ?> 
	</div>
	<?php
	}
}
?>
</div>