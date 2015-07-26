<div id="center-column">
<div class="top-bar">
<a href="index.php?act=addlink" title="Add Link" class="button">ADD LINK</a>
<h1>Cpanel - links</h1>
<div class="breadcrumbs"><a href="index.php?act=general" title="Settings">Settings</a> / <a href="index.php?act=categories">Categories</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Manage links</h3>
</label>
</div>
<?php
if(isset($_POST['reset'])) {
		yasDB_update("UPDATE links SET `in` = '0', `out` = '0'",false);
		echo '<center>In/out successfully reset.<br />';
		echo '<a href="index.php?act=links">Click here to proceed</a></center>';
	
} else {
	if(!empty($_GET['edit'])) {
		$query = yasDB_select("SELECT * FROM links WHERE id = '{$_GET['edit']}'",false);
		if($query->num_rows == 0) {
			echo '<center>You cannot edit a link that doesnt exist.</center>';
		} else {
			$row = $query->fetch_array(MYSQLI_ASSOC);
			?><div class="table">
		    <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
			<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
			<form name="edit" method="post" action="index.php?act=links">
			<table class="listing form" cellpadding="0" cellspacing="0">
			<tr>
	        <th class="full" colspan="2">Edit - Link</th>
			</tr>
			<tr>
			<td class="first" width="172"><strong>Site Title</strong></td>
			<td class="last"><input type="text" name="text" size="40" value="<?php echo $row['text'];?>"  /></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Url</strong></td>
			<td class="last"><input type="text" name="url" size="40" value="<?php echo $row['url'];?>"  /></td>
		    </tr>
			<tr>
			<td class="first"><strong>Reciprocal</strong></td>
			<td class="last"><input type="text" name="reciprocal" size="40" value="<?php echo $row['reciprocal'];?>"  /></td>
		    </tr>
			<tr class="bg">
			<td class="first" width="172"><strong>Site description</strong></td>
			<td class="last"><input type="text" name="description" size="40" value="<?php echo $row['description'];?>"  /></td>
			</tr>
			<tr>
			<td class="first"><strong>Email</strong></td>
			<td class="last"><input type="text" name="email" size="40" value="<?php echo $row['email'];?>"  /></td>
		    </tr>
			<tr class="bg">
			<td class="first"><strong>In</strong></td>
			<td class="last"><input type="text" size=5 name="in" value="<?php echo $row['in'];?>"  /></td>
		    </tr>
			<tr>
			<td class="first" width="172"><strong>Out</strong></td>
			<td class="last"><input type="text" size=5 name="out" value="<?php echo $row['out'];?>"  /></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Approved</strong></td>
			<td class="last"><select name="approved">
			<option value ="<?php echo $row['approved'];?>" /><?php echo $row['approved'];?></option>
			<option value ="yes">yes</option>
			<option value ="no">no</option>
			</select> <input type="hidden" name="id" value="<?php echo $row['id'];?>" /></td>
		    </tr>
			<tr>
			<td class="first" width="172"><strong>Submit</strong></td>
			<td class="last"><input type="submit" name="check" class="button" value="Edit!" />&nbsp;
			<input type="reset" class="button" value="Reset"/></td>
			</tr>
			</table>
			</div>
			</form>
			<?php
		}
		$query->close();
		
	} elseif(isset($_POST['check'])) {
		if(empty($_POST['text']) || empty($_POST['url']) || empty($_POST['approved'])) {
			echo '<center>One or more fields was left empty.<br />';
			echo '<a href="index.php?act=links&edit=' . $_POST['id'] . '">Click here to go back</a></center>';
		} else {
			yasDB_select("UPDATE links SET text = '{$_POST['text']}', url = '{$_POST['url']}', description = '{$_POST['description']}', `in` = '{$_POST['in']}', `out` = '{$_POST['out']}', `approved` = '{$_POST['approved']}', `reciprocal` = '{$_POST['reciprocal']}', `email` = '{$_POST['email']}' where id = '{$_POST['id']}'",false);
			echo '<center><br/>Link Successfully edited!<br/><br/>';
			echo '<a href="index.php?act=links"><font color="green">Click here to proceed</font></a><br/><br/></center>';
		}
	} elseif(!empty($_GET['delete'])) {
		$query = yasDB_select("SELECT id FROM links WHERE id = '{$_GET['delete']}'",false);
		if($query->num_rows == 0) {
			echo '<center>You cannot delete a link that does not exist!<br />';
			echo '<a href="index.php?act=links">Click here to go back</a></center>';
			$query->close();
			
		} else {
		$query->close();
			yasDB_delete("DELETE FROM links WHERE id = '{$_GET['delete']}'",false);
			echo '<center>Link successfully deleted.<br />';
			echo '<a href="index.php?act=links">Click here to proceed</a></center>';
		   }
		} elseif(isset($_POST['deletechecked'])) {
			$count = count($_POST['checkbox']);
			/*?>
			<script type = "text/javascript">
				var response = confirm("Are you sure you want to delete <?php echo $count;?> link(s)?");
				if (!response) {
					window.location.href = '<?php echo $setting['siteurl'];?>admin/index.php?act=links';
					
				}
			</script>
			<?php*/
			foreach ($_POST['checkbox'] as $box) {
				$query = yasDB_select("SELECT id FROM links WHERE id = '$box'",false);
				if($query->num_rows == 0) {
					echo '<center>You cannot delete a link that does not exist!<br />';
					echo '<a href="index.php?act=links">Click here to go back</a></center>';
				} else {
					if ($_GET['remove'] != 'notta') {
						$row = $query->fetch_array(MYSQLI_ASSOC);				
						$row['id'];				
						$query->close();            
						yasDB_delete("delete from links where id = '$box'",false);
						?>
						<meta http-equiv="refresh" content="0;URL=<?php echo $setting['siteurl'];?>admin/index.php?act=links" />
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
	$result = yasDB_select("SELECT count(id) FROM links",false);
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
		$query = yasDB_select("SELECT * FROM links ORDER BY `id` DESC $limit",false);
		if($query->num_rows == 0) {
			echo '<center>There are no links in the database!</center>';
		} else {
			?>
<form name="reset" method="post" action="">
			<input type="submit" class="button" name="reset" value="Reset in/out" /></form><br />
			<div class="table">
				<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
				<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
				<table class="listing" cellpadding="0" cellspacing="0">
				<tr>
						<th class="first" width="40px">ID</th>
						<th style="width:75px;">Site Title</th>
						<th style="width:175px;">Site URL</th>
						<th>In</th>
						<th>Out</th>
						<th style="width:60px;">Approved</th>
						<th width="40px">Edit</th>
						<th class="last" >Delete</th>
					</tr>
			<?php
			while($row = $query->fetch_array(MYSQLI_ASSOC)) {
				if($row['approved'] == 'no') {
					$row['approved'] = '<strong style="color: #cc0000;">no</strong>';
				}
				else if($row['approved'] == 'yes') {
					$row['approved'] = '<strong style="color: green;">yes</strong>';
				}
				
				?><form name="deleteform" method="post" action="">
				<tr>
			<td class="first style1"><?php echo $row['id'];?></td>
			<td style="overflow:hidden;"><?php echo $row['text'];?></td>
			<td style="overflow:hidden;"><?php echo $row['url'];?></td>
			<td><?php echo $row['in'];?></td>
			<td><?php echo $row['out'];?></td>
			<td><?php echo $row['approved'];?></td>
			<td><a href="index.php?act=links&edit=<?php echo $row['id'];?>"><font color="#cc0000">Edit</font></a></td>
		    <td class="last"><a href="index.php?act=links&delete=<?php echo $row['id'];?>" onclick="return confirm(\'Are you sure you want to delete this link?\')"><font color="#cc0000">Delete</font></a><br/><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $row['id'];?>"></td>
			</tr>
			<?php
		}
		$query->close();
		}
		?>
			</table>
			<div style="text-align:right;padding-right:20px;padding-top:10px;">
        <input name="deletechecked" class="button" type="submit" id="deletechecked" value="Delete Checked" onclick="return confirm(\'Are you sure you want to delete this link?\')">
        </form>
    </div></div>
    <br /><div style="text-align:center;padding-bottom:10px;">
		<?php
		if ($pageno == 1) {
			echo ' FIRST PREV ';
		} else {
			echo ' <a href="index.php?act=links&page=1">FIRST</a> ';
			$prevpage = $pageno-1;
			echo ' <a href="index.php?act=links&page=' . $prevpage . '">PREV</a> ';
		} 
		echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
		if ($pageno == $lastpage) {
			echo ' NEXT LAST ';
		} else {
			$nextpage = $pageno+1;
			echo ' <a href="index.php?act=links&page=' . $nextpage . '">NEXT</a> ';
			echo ' <a href="index.php?act=links&page=' . $lastpage . '">LAST</a> ';
		} ?> 
	</div>
		<?php				
	}
}
?>
</div>