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
	var response = confirm("Are you sure you want to delete " + a + " comments(s)?");
	if (response) {
		$.post('delete_comments.php', {'checkbox[]': boxArray }, function(data){ location.reload(true); });		
	}
}
</script>
<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Game Comments</h1>
<div class="breadcrumbs"><a href="index.php?act=memberscomment" title="Members Comments">Members Comments</a> / <a href="index.php?act=newsblogcomments" title="News Blog Comments">News Blog Comments</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Manage Comments</h3>
</label>
</div>
<?php
if(!empty($_GET['edit'])) {
	if(!is_numeric($_GET['edit'])) {
		echo 'Invalid GET data';
		exit;
	}
	$query = yasDB_select("select * from comments where id = ".intval($_GET['edit']),false);
	if($query->num_rows == 0) {
		echo '<center>You cannot edit a comment that doesnt exist.</center>';
	} else {
		$row = $query->fetch_array(MYSQLI_ASSOC);
		?>
		<div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	    <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<form name="edit" method="post" action="index.php?act=comments">
			<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
	    <th class="full" colspan="2">Edit - Comment</th>
	    </tr>
		<tr>
		<td class="first" width="172"><strong>Name</strong></td>
		<td class="last"><input type="text" name="name" value="<?php echo $row['name'];?>" /></td>
		</tr>
		<tr class="bg">
		<td class="first"><strong>Comment</strong></td>
		<td class="last"><textarea name="comment" /><?php echo $row['comment'];?></textarea></td>
		</tr>
		</tr>
		<tr>
		<td class="first"><strong>Submit</strong></td>
		<td class="last"><input type="hidden" name="id" value="<?php echo $row['id'];?>" /><input type="submit" class="button" name="edit" value="Edit!" /><input type="reset" class="button" value="Reset"/></td>
		</tr>
		</table>
		</div>
		</form>		
	<?php
	}
	$query->close();
	
} elseif(isset($_POST['edit'])) {
	if(!is_numeric($_POST['id'])) {
		echo 'Invalid POST data';
		exit;
	}
	if(empty($_POST['name']) || empty($_POST['comment'])) {
		echo '<center>One or more fields was left empty.<br />';
		echo '<a href="index.php?act=comments?edit=' . intval($_POST['id']) . '">Click here to go back</a></center>';
	} else {
		yasDB_update("update comments set name = '".yasDB_clean($_POST['name'])."', comment = '".yasDB_clean($_POST['comment'])."' where id = ".intval($_POST['id']),false);
		echo '<center>Comment Successfully edited!<br />';
		echo '<a href="index.php?act=comments">Click here to proceed</a></center>';
	}
} elseif(!empty($_GET['delete'])) {
	$query = yasDB_select("select id from comments where id = ". intval($_GET['delete']),false);
	if($query->num_rows == 0) {
		echo '<center>You cannot delete a comment that does not exist!<br />';
		echo '<a href="index.php?act=comments">Click here to go back</a></center>';
		$query->close();
		
	} else {
		$query->close();
		
		yasDB_delete("delete from comments where id = ". intval($_GET['delete']),false);
		echo '<center>Comment successfully deleted.<br />';
		echo '<a href="index.php?act=comments">Click here to proceed</a></center>';
	}
} elseif(isset($_POST['deletechecked'])) {
    $count = count($_POST['checkbox']);
    ?>
    <script type = "text/javascript">
        var response = confirm("Are you sure you want to delete <?php echo $count;?> comment(s)?");
        if (!response) {
            window.location.href = '<?php echo $setting['siteurl'];?>admin/index.php?act=comments&remove=notta';
            
        }
    </script>
    <?php
    foreach ($_POST['checkbox'] as $box) {
        if(!is_numeric($box)) {
			echo 'Invalid POST data';
			exit;
		}
		$box = intval($box);
		$query = yasDB_select("SELECT id FROM comments WHERE id = ".$box,false);
        if($query->num_rows == 0) {
            echo '<center>You cannot delete this comment that does not exist!<br />';
            echo '<a href="index.php?act=comments">Click here to go back</a></center>';
        } else {
            if ($_GET['remove'] != 'notta') {
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $query->close();            
                yasDB_delete("DELETE FROM comments WHERE id = ".$box,false);
                ?>
                <meta http-equiv="refresh" content="0;URL=<?php echo $setting['siteurl'];?>admin/index.php?act=comments" />
                <?php
            }
        } 
	}
} else {

	if (isset($_GET['page'])) {
		$pageno = intval($_GET['page']);
	} else {
		$pageno = 1;
	} 
	$result = yasDB_select("SELECT count(id) FROM comments",false);
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
	$query = yasDB_select("select * from comments $limit",false);
	if($query->num_rows == 0) {
		echo '<center>No comments in the database!</center>';
	} else {
		?>
		<div style="text-align:right;padding-right:20px;padding-top:10px;margin-bottom:5px;">
			<form name="deleteform" method="post" action="">
				<input name="deletechecked" class="button" type="submit" id="deletechecked" value="Delete Checked" onClick="deleteChecked(document.deleteform.checkbox)"/>
				<input type="button" class="button" name="Check_All" value="Check All" onClick="Check(document.deleteform.checkbox)" style="float:right;"/>
			</form>
		</div>
		<div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		
		<table class="listing" cellpadding="0" cellspacing="0">
		<tr>
			<th class="first" width="40px">ID</th>
			<th style="width:50px" >Game ID</th>
			<th style="width:80px;">Name</th>
			<th>Comment</th>
			<th style="width:100px;">Ip Address</th>
			<th width="30px">Edit</th>
			<th class="last" style="width:50px">Delete</th>
		</tr>
		<?php
		while($row = $query->fetch_array(MYSQLI_ASSOC)) {
			?>		
	        <tr>
			<td class="first style1"><?php echo $row['id'];?></td>
			<td><?php echo $row['gameid'];?></td>
			<td style="overflow:hidden;"><?php echo $row['name'];?></td>
			<td style="overflow:hidden;"><?php echo $row['comment'];?></td>
			<td><?php echo $row['ipaddress'];?></td>
			<td><a href="index.php?act=comments&edit=<?php echo $row['id'];?>"><font color="#cc0000">Edit</font></a></td>
			<td class="last"><a href="index.php?act=comments&delete=<?php echo $row['id'];?>" onclick="return confirm(\'Are you sure you want to delete this comment?\')"><font color="#cc0000">Delete</font></a><br/><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $row['id'];?>"></td>
			</tr>
			<?php
		}
		$query->close();		
		?></table>
		</div>		
		<br /><div style="text-align:center;padding-bottom:10px;">
		<?php
		if ($pageno == 1) {
			echo ' FIRST PREV ';
		} else {
			echo ' <a href="index.php?act=comments&page=1">FIRST</a> ';
			$prevpage = $pageno-1;
			echo ' <a href="index.php?act=comments&page=' . $prevpage . '">PREV</a> ';
		} 
		echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
		if ($pageno == $lastpage) {
			echo ' NEXT LAST ';
		} else {
			$nextpage = $pageno+1;
			echo ' <a href="index.php?act=comments&page=' . $nextpage . '">NEXT</a> ';
			echo ' <a href="index.php?act=comments&page=' . $lastpage . '">LAST</a> ';
		} ?> 
	</div>
	<?php
	}
}
?>
</div>