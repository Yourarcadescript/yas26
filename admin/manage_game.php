<script type="text/javascript">
tinyMCE.init({
    mode : "exact",
    elements : "review,edit_description,instructions",
	theme : "advanced",
    plugins : "spellchecker,pagebreak,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking",
    
    // Theme options
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,forecolor,backcolor",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,help,|,preview",
    theme_advanced_buttons3 : "charmap,emotions,iespell,media,advhr,ltr,rtl,|,spellchecker,|,visualchars,nonbreaking,|,fullscreen",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
	height : "300"

	    
});
</script>
<?php
if (!isset($_GET['title']) || empty($_GET['title'])) {
	$box_title = '---';
} else {
	$box_title = $_GET['title'];
}
if (!isset($_GET['category']) || empty($_GET['category'])) {
	$box_category = 'all';
} else {
	$box_category = $_GET['category'];
}
if (isset($_GET['leaderboard']) ) {
	$box_leaderboard = $_GET['leaderboard'] == 'on'?'yes':'no';
} else {
	$box_leaderboard === '---';
}
if (isset($_GET['featured']) ) {
	$box_featured = $_GET['featured'] == 'on'?'yes':'no';
} else {
	$box_featured = '---';
}
if (isset($_GET['active']) ) {
	$box_featured = $_GET['active'] == 'on'?'yes':'no';
} else {
	$box_featured = '---';
}
if (!isset($_GET['description']) || empty($_GET['description'])) {
	$box_description = '---';
} else {
	$box_description = $_GET['description'];
}
if (!isset($_GET['keywords']) || empty($_GET['keywords'])) {
	$box_keywords = '---';
} else {
	$box_keywords = $_GET['keywords'];
}
if (isset($_GET['gameplays'])) {
	$box_plays = $_GET['gameplays'];
} else {
	$box_plays = '---';
}
$query = yasDB_select("SELECT `id`, `title` FROM `games` ORDER BY `title` ASC");

$allgames = array();
while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
	$allgames[] = array('id'=>$row['id'], 'title'=>$row['title']);
}
$query->close;

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
	var response = confirm("Are you sure you want to delete " + a + " game(s)?");
	if (response) {
		$.post('deleteall.php', {'checkbox[]': boxArray }, function(data){ location.reload(true); });		
	}
}
</script>
<div id="center-column">
<div class="top-bar">
				<a href="index.php?act=uploadgames" title="Upload Games" class="button">ADD GAME</a>
				<h1>Cpanel - Games</h1>
				<div class="breadcrumbs"><a href="index.php?act=addmedia" title="Ad Media">Add Media</a> / <a href="index.php?act=addcode" title="Ad Code">Add Code</a> / <a href="index.php?act=uploadgames" title="Upload Games">Upload Games</a> / <a href="index.php?act=brokenfiles" title="Broken Files">Broken Files</a></div>
			</div><br />
		  <div class="select-bar">
		    <label>
		    <h3>Manage Games</h3>
		    </label>
		  </div>
<?php
$qry = yasDB_select("SELECT gameid from featuredgames");
$gameids = array();
while ($game = $qry->fetch_array(MYSQLI_NUM)) {
	$gameids[] = $game[0];
}

if(!empty($_GET['edit'])) {
	$query = yasDB_select("SELECT * FROM games WHERE id = " . intval($_GET['edit']),false);
	if($query->num_rows == 0) {
		echo 'You cannot edit a file that doesnt exist.';
		$query->close();
		
	} else {
		$_SESSION['editurl'] = $_SERVER['HTTP_REFERER'];
		$row = $query->fetch_array(MYSQLI_ASSOC);
		$query->close();
		$catName = array();
		$catId = array();
		$i = 0;
		$query = yasDB_select("SELECT id, name FROM categories",false);
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
			<form name="edit" method="post" action="index.php?act=managegames">
			<table class="listing form" cellpadding="0" cellspacing="0">
	        <tr>
	        <th class="full" colspan="2">Edit - <?php echo $row['title'];?></th>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Title</strong></td>
			<td class="last"><input type="text" name="title" value="<?php echo $row['title'];?>" style="width:275px;"  /></td>
			</tr>
			<tr>
			<td class="first"><strong>Game Height</strong></td>
			<td class="last"><input type="text" name="gameheight" value="<?php echo $row['height'];?>" /></td>
		    </tr class="bg">
			<tr class="bg">
			<td class="first"><strong>Game Width</strong></td>
			<td class="last"><input type="text" name="gamewidth" value="<?php echo $row['width'];?>" /></td>
			</tr>
			<tr>
			<td class="first"><strong>code</strong></td>
			<td class="last"><textarea name="gamecode" cols="35" rows="5" style="width:275px;"/><?php echo htmlentities($row['code']);?></textarea></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Description</strong></td><td class="last"></td>
			</tr>
			<tr>
			<td style="background-color:#fff;width:100%;"><textarea name="description" id="edit_description" style="width:275px;"/><?php echo $row['description'];?></textarea></td>
			</tr>
            <tr>
			<td class="first"><strong>Instructions</strong></td><td class="last"></td>
			</tr>
			<tr>
			<td style="background-color:#fff;width:100%;"><textarea name="instructions" id="instructions "style="width:275px;"/><?php echo $row['instructions'];?></textarea></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Keywords</strong></td>
			<td class="last"><textarea name="keywords" style="width:275px;"/><?php echo $row['keywords'];?></textarea></td>
			</tr>
			<tr>
			<td class="first"><strong>Category</strong></td>
			<td class="last"><select name="category">
		<option value="<?php echo $row['category'];?>"><?php echo $catName[$row['category']-1];?></option>
		<?php
		$len = count($catName);
		for($i=0;$i<$len;$i++) {
		?><option value="<?php echo $catId[$i];?>"><?php echo $catName[$i];?></option>
		<?php } ?></select></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Thumbnail</strong></td>
			<td class="last"><input type="text" name="thumbnail" value="<?php echo $row['thumbnail'];?>"  style="width:275px;"/></td>
			</tr>
			<tr>
			<td class="first"><strong>File</strong></td>
			<td class="last"><select name="type">
				<option value="<?php echo $row['type'];?>"><?php echo $row['type'];?></option>
				<option value="SWF">swf</option>
				<option value="DCR">dcr</option>
				<option value="FLV">flv</option>
				<option value="WMV">wmv</option>
				<option value="AVI">avi</option>
				<option value="MPG">mpg</option>
				<option value="MOV">mov</option>
				<option value="IMAGE">image</option>
				<option value="YOUTUBE">youtube</option>
				</select> <input type="text" name="file" value="<?php echo $row['file'];?>" style="width:202px;" /></td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Featured</strong></td>
			<td class="last">
				<select name="featured">
				<option value="yes" <?php if (in_array($row['id'], $gameids)) { echo 'selected="selected"'; }?>>Yes</option>
				<option value="no" <?php if (!in_array($row['id'], $gameids)) { echo 'selected="selected"'; }?>>No</option>
				</select>
			</td>
			</tr>
			<tr>
			<td class="first"><strong>Active</strong></td>
			<td class="last">
				<select name="active">
				<option value="1" <?php if ($row['active']==1) { echo 'selected="selected"';}?>>Yes</option>
				<option value="0" <?php if ($row['active']==0) { echo 'selected="selected"';}?>>No</option>
				</select>
			</td>
			</tr>
			<tr class="bg">
			<td class="first"><strong>Game Review</strong></td><td class="last"></td>
			</tr>
			<tr>
			<td style="background-color:#fff;width:100%;"><textarea name="review" id="review" cols="35" rows="5" style="width:100%;"/><?php echo $row['review'];?></textarea></td>
			</tr>
			<tr>
			<td class="first"></td>
			<td class="last"><input type="hidden" name="id" value="<?php echo $row['id'];?>"/>
		<input type="hidden" name="m" value="<?php echo $_GET['m'];?>"/>
		<input type="submit" class="button" name="edit" value="Edit!"/>&nbsp;
		<input type="reset" class="button" name="reset" value="Reset"/>&nbsp;
		<input name="cancel" class="button" type="button" value="Cancel" onclick="history.go(-1);" />

		</td>
			</tr>
			</table>
			</div>
			</form>
	<?php			
	}
	
} elseif(isset($_POST['edit'])) {
	if(empty($_POST['title']) || empty($_POST['thumbnail'])) {
		echo '<center>One or more fields was left empty.<br />';
		echo '<a href="index.php?act=managegames&edit=' . intval($_POST['id']) . '">Click here to go back</a></center>';
	} else {
		$pid = intval($_POST['id']);
		yasDB_update("UPDATE games SET title = '". yasDB_clean($_POST['title'])."', description = '".yasDB_clean($_POST['description'])."', instructions = '".yasDB_clean($_POST['instructions'])."', code = '".yasDB_clean($_POST['gamecode'])."',keywords = '".yasDB_clean($_POST['keywords'])."', category = ".intval($_POST['category']).", height = ".intval($_POST['gameheight']).", width = ".intval($_POST['gamewidth']).", type = '".yasDB_clean($_POST['type'])."', thumbnail = '".yasDB_clean($_POST['thumbnail'])."', file = '".yasDB_clean($_POST['file'])."', active=".intval($_POST['active']).", review='".yasDB_clean($_POST['review'])."' where id = $pid",false);
		$result = yasDB_select("SELECT gameid FROM featuredgames WHERE gameid = $pid");
		if ($_POST['featured'] == 'yes') {
			if ($result->num_rows == 0) {
				yasDB_insert("INSERT INTO featuredgames (gameid) VALUES($pid)");
			}
		}
		elseif ($_POST['featured'] == 'no') {
			yasDB_delete("DELETE FROM featuredgames WHERE gameid = $pid");
		}
		echo '<p align="center">File Successfully edited!<br />';
		if (!empty($_POST['m'])) {
			if (isset($_SESSION['editurl']) && $_SESSION['editurl'] != '') {
				$eurl = $_SESSION['editurl'];
			} else {
				$eurl = 'index.php?act=managegames';
			}
			echo '<center><a href="'.$eurl.'">Click here to proceed</a></p></center>';
			$_SESSION['editurl'] = '';	
		} else {
			echo '<center><a href="index.php?act=brokenfiles">Click here to proceed</a></p></center>';
		}
	}
} elseif(!empty($_GET['delete'])) {
	$query = yasDB_select("SELECT id, file, thumbnail, source, sourceid FROM games WHERE id = ".intval($_GET['delete']),false);
	if($query->num_rows == 0) {
		echo '<center>You cannot delete a game that does not exist!<br />';
		echo '<a href="index.php?act=managegames">Click here to go back</a></center>';
	} else {
		$table = array (
					'FGD'=>'fgdfeed',
					'FOG'=>'fogfeed',
					'KONGREGATE'=>'kongregate',
				);
		$row = $query->fetch_array(MYSQLI_ASSOC);
		@unlink('../' . $row['file']);
		@unlink('../' . $row['thumbnail']);
		$query->close();
		
		yasDB_delete("delete from games where id = ".intval($_GET['delete']),false);
		if ($row['source'] != 'OTHER') {
		yasDB_update("UPDATE `".$table[$row['source']]."` SET `installed` = '0' WHERE `id` = ".$row['sourceid']);

		}
		echo '<center><p style="text-align:center;">Game successfully deleted.<br />';
		echo '<a href="index.php?act=managegames">Click here to proceed</a></p></center>';
	}
} else {
	$catName = array();
	$catId = array();
	$i = 0;
	$query = yasDB_select("SELECT id, name FROM categories",false);
	while($names = $query->fetch_array(MYSQLI_BOTH)) {
		$catName[$i] = $names['name'];
		$catId[$i] = $names['id'];
		$i++;
	}
	$query->close();
	?>

	<p align="center">  
    <form enctype="multipart/form-data" action="index.php" method="get" style="padding:5px;">
   		<fieldset style="border:2px solid #9097A9;background-color:#EBEBEB">
		<legend style="color:#EF5F00;font-weight:bold;border:1px solid #9097A9;background-color:#FFFFFF;">Game Filter Search</legend>
		<input type="hidden" name="act" value="managegames">
		Title:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="title" id="title"/>&nbsp;&nbsp;
		<label for="category_filter">Category:</label>
   		<select name="category" id="category">
   		<option value="all" selected>All Categories</option>
   		<optgroup label="category">
		<?php
		for ($c=0;$c<$i;$c++) {
			?><option value="<?php echo $catId[$c]; ?>"><?php echo $catName[$c]; ?></option><?php
		}
		?>
		</optgroup>
   		</select>&nbsp;&nbsp;&nbsp;
   		<label for="plays_filter">Game Plays</label>
		<select name="gameplays" id="gameplays">
		<option value="all" selected>No Filter</option>
		<option value="high">High to Low</option>
		<option value="low">Low to High</option>
		</select>
		<!--Leaderboard: <input type="checkbox" name="leaderboard" id="leaderboard"> --><br/><br/>                       		
        Description: <input type="text" name="description"/>&nbsp;
        Keywords: <input type="text" name="keywords"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Featured: <input type="checkbox" name="featured" id="featured">
        </p><br/>
        <center><input type="submit" class="button" name="filter" value="Filter Games" /></center>
		</fieldset>
	</form><br/>	
			
<?php
	$query_cats = yasDB_select("SELECT name FROM categories");	
	$cats = array();
	$i = 0;
	while ($catRow = $query_cats->fetch_array(MYSQLI_ASSOC)) {;
		$cats[$i] = $catRow['name'];
		$i++;
	}
	$query_cats->close();
	if (isset($_GET['page'])) {
	   $pageno = $_GET['page'];
	} else {
	   $pageno = 1;
	} 
	$category = isset($_GET['category'])?$_GET['category']:'all';
	$title = isset($_GET['title'])?yasDB_clean($_GET['title']):'';
	$description = isset($_GET['description'])?yasDB_clean($_GET['description']):'';
	$keywords = isset($_GET['keywords'])?yasDB_clean($_GET['keywords']):'';
	$featured = isset($_GET['featured'])?yasDB_clean($_GET['featured']):'';
	if (isset($_GET['filter'])) {
		if(isset($_GET['exact'])) {
			$sql = "SELECT * FROM `games` WHERE `id` = ".intval($_GET['exact']);
		} else {
			$flag = false;
			$flag2 = false;
			if ($title == '') {
				$sql_title = '';
			} else {
				$flag = true;
				$flag2 = true;
				$sql_title = " title LIKE '%$title%'";
			}
			if ($category == 'all') {
				$sql_category = '';
				$join1 = '';
			} else {
				$join1 = ($flag2 === true)?' AND':'';
				$flag = true;
				$flag2 = true;
				$sql_category = $join1." category = $category";
			}
			if ($keywords == '') {
				$sql_keywords = '';
				$join2 = '';
			} else {
				$join2 = ($flag2 === false)?'':' AND';
				$flag = true;
				$flag2 = true;
				$sql_keywords = $join2." keywords LIKE '%$keywords%'";
			}
			if ($description == '') {
				$join3 = '';
				$sql_description = '';
			} else {
				$join3 = ($flag2 === false)?'':' AND';
				$flag = true;
				$flag2 = true;
				$sql_description = $join3." description LIKE '%$description%'";
			}
			if ($featured == 'on') {
				$join4 = ($flag2 === false)?'':' AND';
				$flag = true;
				$flag2 = true;
				$sql_featured = $join4.' id IN (SELECT gameid from featuredgames)';
			} else {
				//$flag2 = false;
				$sql_featured = '';
			}
			$where = $flag === true ? ' WHERE' : '';
			$sql = 'SELECT * FROM games' . $where . $sql_title . $sql_category . $sql_keywords . $sql_description . $sql_featured;
		}
	} else {
		$sql = "SELECT * FROM games";		
	}
	$query = yasDB_select($sql);
	$numrows = $query->num_rows;
	$query->close;
	// Number of games per page **************************	
	$rows_per_page = 20;
	//****************************************************
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
	if ($box_plays == 'high') {
		$sql .=  ' ORDER BY `plays` DESC '.$limit;
	} else if ($box_plays == 'low') {
		$sql .=  ' ORDER BY `plays` ASC '.$limit;
	} else {
		$sql .=  ' ORDER BY `id` DESC '.$limit;
	}
	$query = yasDB_select($sql);
	if (isset($_GET['exact'])) {
		$q2 = yasDB_select("SELECT `title` FROM `games` WHERE `id` = ".intval($_GET['exact']));
		$q2result = $q2->fetch_array(MYSQLI_ASSOC);
		$box_title = $q2result['title'];
		$q2->close;
	}
	?>
	<div style="float:left;width:230px;margin:0 0 5px 0;padding:5px;text-align:left;">
	<form>
	<fieldset style="border:2px solid #9097A9;background-color:#EBEBEB">
	<legend style="color:#EF5F00;font-weight:bold;border:1px solid #9097A9;background-color:#FFFFFF;">Search Results</legend>
	Title: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $box_title; ?><br/>
	Game plays: &nbsp;&nbsp;<?php echo $box_plays; ?><br/>
	Category: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($box_category != 'all') {echo $catName[$box_category-1];} else { echo $box_category; } ?><br/>
	Description: &nbsp;&nbsp;<?php echo $box_description; ?><br/>
	Keywords: &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $box_keywords; ?><br/>
	Featured: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $box_featured; ?><br/>
	Games: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $numrows ?><br/>
	</fieldset>
	</form>
	</div>
	<div style="float:right;margin:40px 40px 0 0;width:305px;">
	<fieldset style="border:2px solid #9097A9;background-color:#EBEBEB">
	<legend style="color:#EF5F00;font-weight:bold;border:1px solid #9097A9;background-color:#FFFFFF;">Exact Search</legend>
	<select name="titlesearch" onchange="location.href = this.value;" style="width:174px;">
		<option value="">Search by title</option>
			<?php
			foreach ($allgames as $game) {
				?><option value="index.php?act=managegames&filter=filter&exact=<?php echo $game['id'];?>&m=1"><?php echo $game['title'];?></option><?php
			}
			?>
	</select>&nbsp;&nbsp;
	<select name="idsearch" onchange="location.href = this.value;">
		<option value="">Search by id</option>
			<?php
			$tmp = Array();
			foreach($allgames as &$ma) {
				$tmp[] = &$ma["id"];
			}
			array_multisort($tmp, $allgames); 
			unset($tmp);
			foreach ($allgames as &$game) {
				?><option value="index.php?act=managegames&filter=filter&exact=<?php echo $game['id'];?>&m=1"><?php echo $game['id'];?></option><?php
			}
			?>
	</select>
	</fieldset>
	</form>
	</div>
	
	<div style="clear:both;"></div>
	<form name="deleteform" method="post" action="">
	<input name="deletechecked" class="button" type="button" id="deletechecked" value="Delete Checked" style="float:right;margin-left:5px;" onClick="deleteChecked(document.deleteform.checkbox)"/>
	<input type="button" class="button" name="Check_All" value="Check All" onClick="Check(document.deleteform.checkbox)" style="float:right;"/>
	<br/><br/>
    <div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing" cellpadding="0" cellspacing="0">
			<tr>
			<th class="first" width="86px">Game</th>
			<th>Plays</th>
			<th width="204px">Description</th>
			<th>Category</th>
			<th>Featured</th>
			<th class="last"></th>
			</tr>
	<?php
	if($query->num_rows==0) {
    } else { 
		while($row = $query->fetch_array(MYSQLI_ASSOC)) {
			$row['description'] = stripslashes($row['description']);
			$des = str_replace(array("\r\n", "\r", "\n", "'", '"'), ' ', $row['description']);
			?><tr>
			<td class="first style1" width="80px" style="height:125px;" ><img src="<?php echo $setting['siteurl'] . $row['thumbnail'];?>" style="width: 60px; height: 50px;" /><br/><?php echo $row['title'];?></td>
			<td><?php echo $row['plays']; ?></td>
			<td style="position:relative;height:125px;"><?php echo substr($row['description'],0, 200).'...';?><div style="position:relative; right:0; bottom:0; color:red;"><span class="hintanchor" style="font-weight:bold;cursor:pointer;" onMouseover="showhint('<?php echo addslashes($des);?>', this, event, '300px')">More</span></div></td>
			<td style="height:125px;"><?php echo $cats[$row['category']-1];?></td>
			<td style="height:125px;"><?php if (in_array($row['id'], $gameids)) echo '<img src="img/checkmark.png" width="16" height="16" alt="Yes" />';?></td>
			<td class="last" style="height:125px;"><a href="index.php?act=managegames&edit=<?php echo $row['id'];?>&m=1"><img src="img/edit.png" height="25" width="25" alt="edit game" title="edit" style="text-decoration:none;"</a>
			<a href="index.php?act=managegames&delete=<?php echo $row['id'];?>" onclick="return confirm(\'Are you sure you want to delete this game?\')"><img src="img/delete.png" height="25" width="25" alt="delete game" title="delete" /></a><br/><br/><input name="checkbox" type="checkbox" id="checkbox[]" value="<?php echo $row['id'];?>"></td>
			</tr>
<?php			
		}
	}
	unset ($cats);
	$query->close();
	?>
</table>
</div> </form>

<div style="text-align:center;padding-bottom:10px;">	
<?php
     if ($pageno == 1) {
	   echo ' FIRST PREV ';
	} else {
	   echo ' <a href="index.php?act=managegames&page=1&title='.$title.'&category='.$category.'&description='.$description.'&keywords='.$keywords.'&featured='.$featured.'&filter=filter">FIRST</a> ';
	   $prevpage = $pageno-1;
	   echo ' <a href="index.php?act=managegames&page=' . $prevpage . '&title='.$title.'&category='.$category.'&description='.$description.'&keywords='.$keywords.'&featured='.$featured.'&filter=filter">PREV</a> ';
	} 
	echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
	if ($pageno == $lastpage) {
	   echo ' NEXT LAST ';
	} else {
	   $nextpage = $pageno+1;
	   echo ' <a href="index.php?act=managegames&page=' . $nextpage . '&title='.$title.'&category='.$category.'&description='.$description.'&keywords='.$keywords.'&featured='.$featured.'&filter=filter">NEXT</a> ';
	   echo ' <a href="index.php?act=managegames&page=' . $lastpage . '&title='.$title.'&category='.$category.'&description='.$description.'&keywords='.$keywords.'&featured='.$featured.'&filter=filter">LAST</a> ';
	}
?>
</div>
<?php
}
?>
</div>