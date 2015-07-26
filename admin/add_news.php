<script type="text/javascript">
tinyMCE.init({
    mode : "textareas",
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
<div id="center-column">
<div class="top-bar">
				<h1>Cpanel - News</h1>
				<div class="breadcrumbs"><a href="index.php?act=newsblogcomments" title="News Blog Comments">News Blog Comments</a></div>
			</div><br />
		  <div class="select-bar">
		    <label>
		    <h3>Manage News</h3>
		    </label>
		  </div>
<?php
if(!empty($_GET['edit'])) {

	$query = yasDB_select("SELECT * FROM news WHERE id = '{$_GET['edit']}'",false);
	if($query->num_rows == 0) {
		echo '<center>You cannot edit news that doesnt exist.</center>';
	} else {
		$news = $query->fetch_array(MYSQLI_ASSOC);
		?><div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<form name="edit" method="post" action="index.php?act=news">
		<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
	        <th class="full" colspan="2">Edit - News</th>
			</tr>
			<tr>
			<td class="first" width="172"><strong>Date</strong></td>
			<td class="last"><input type="text" name="date" value="<?php echo $news['date'];?>"  /></td>
			</tr>
			<tr>
			<td class="first" width="172"><strong>News</strong></td><td class="last"></td>
			</tr>
			<tr>
			<td style="background-color:#fff;width:100%;"><textarea name="news_text" cols="40" rows="5"><?php echo $news['news_text'];?></textarea></td>
			</tr>
			<tr>
			<td class="first" width="172"><strong>Topic</strong></td>
			<td class="last"><input type="text" name="topic" value="<?php echo $news['topic'];?>"  /></td>
			</tr>
			<tr>
			<td class="first" width="172"></td>
			<td class="last"><input type="hidden" name="id" value="<?php echo $news['id'];?>" /><input type="submit" class="button" name="edit" value="Edit!" /></td>
			</tr>
        </form>
		</table>
	    </div>
	<?php
	}
	$query->close();
	
} elseif(isset($_POST['edit'])) {
	if(empty($_POST['date']) || empty($_POST['news_text'])) {
		echo '<center>One or more fields was left empty.<br />';
		echo '<a href="index.php?act=news?edit=' . $_POST['id'] . '">Click here to go back</a></center>';
	} else {
		yasDB_update("UPDATE news SET date = '{$_POST['date']}', news_text = '{$_POST['news_text']}', topic = '{$_POST['topic']}' WHERE id = '{$_POST['id']}'",false);
		echo '<center>News Successfully edited!<br />';
		echo '<a href="index.php?act=news">Click here to proceed</a></center>';
	}
} elseif(!empty($_GET['delete'])) {
	$query = yasDB_select("SELECT id FROM news WHERE id = '{$_GET['delete']}'");
	if($query->num_rows == 0) {
		echo '<center>You cannot delete news that does not exist!<br />';
		echo '<a href="index.php?act=news">Click here to go back</a></center>';
		$query->close();
		
	} else {
		$query->close();
		
		yasDB_delete("DELETE FROM news WHERE id = '{$_GET['delete']}'");
		echo '<center>News successfully deleted.<br />';
		echo '<a href="index.php?act=news">Click here to proceed</a></center>';
	}
} elseif(isset($_POST['deletechecked'])) {
    $count = count($_POST['checkbox']);
    ?>
    <script type = "text/javascript">
        var response = confirm("Are you sure you want to delete <?php echo $count;?> new(s)?");
        if (!response) {
            window.location.href = '<?php echo $setting['siteurl'];?>admin/index.php?act=news&remove=notta';
            
        }
    </script>
    <?php
    foreach ($_POST['checkbox'] as $box) {
        $query = yasDB_select("SELECT id, date, news_text, topic FROM news WHERE id = '$box'",false);
        if($query->num_rows == 0) {
            echo '<center>You cannot delete this news that does not exist!<br />';
            echo '<a href="index.php?act=news">Click here to go back</a></center>';
        } else {
            if ($_GET['remove'] != 'notta') {
                $row = $query->fetch_array(MYSQLI_ASSOC);
                $query->close();            
                yasDB_delete("DELETE FROM news WHERE id = '$box'",false);
                ?>
                <meta http-equiv="refresh" content="0;URL=<?php echo $setting['siteurl'];?>admin/index.php?act=news" />
                <?php
            }
        } 
	}
} elseif(isset($_POST['add'])) {
      $news = yasDB_clean($_POST['news_text']);
	  $topic = yasDB_clean($_POST['topic']);
	  yasDB_insert("INSERT INTO news (date, news_text, topic) values ('{$_POST['date']}', '$news', '{$_POST['topic']}')");
      echo 'News entry Successfully added!<br />';
      echo '<a href="index.php?act=news">Click here to proceed!</a>';
} else {

	if (isset($_GET['page'])) {
		$pageno = $_GET['page'];
	} else {
		$pageno = 1;
	} 
	$result = yasDB_select("SELECT count(id) FROM news",false);
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
	
	echo '<center>Add News Entry<br/><br/>';?>	
   <form name="add" method="post" action="index.php?act=news">
   Date: <br /><input type="text" name="date" size="35" value="<?php echo date("D, j F Y"); ?>"><br />   
   Topic: <br /><input type="text" name="topic" size="35" /><br />
   News item: <br /><center><textarea name="news_text" rows="4" cols="40"></textarea></center><br />
   
   <input type="submit" class="button" value="Add News" name="add">
   </form><br /></center>
<?php   
	$query = yasDB_select("SELECT * FROM news order by id DESC $limit",false);
	if($query->num_rows == 0) {
		echo 'No news in the database!';
	} else {
		?>
		<div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
		<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<table class="listing" cellpadding="0" cellspacing="0">
		<tr>
						<th class="first" style="width:40px">ID</th>
						<th style="width:100px;">Topic</th>
						<th style="width:100px;">Date</th>
						<th >News</th>
						<th style="width:40px;">Edit</th>
						<th class="last" style="width:40px;">Delete</th>
					</tr>
		<?php
		while($news = $query->fetch_array(MYSQLI_ASSOC)) {
			?><form name="deleteform" method="post" action="">
            <tr>		
			<td class="first style1"><?php echo $news['id'];?></td>
			<td style="overflow:hidden;"><?php echo $news['topic'];?></td>
			<td><?php echo $news['date'];?></td>
			<td style="overflow:hidden;"><?php echo $news['news_text'];?></td>
			<td><a href="index.php?act=news&edit=<?php echo $news['id'];?>"><font color="#cc0000">Edit</font></a></td>
		    <td class="last"><a href="index.php?act=news&delete=<?php echo $news['id'];?>" onclick="return confirm(\'Are you sure you want to delete this news?\')"><font color="#cc0000">Delete</font></a><br/><input name="checkbox[]" type="checkbox" id="checkbox[]" value="<?php echo $news['id'];?>"></td>
			</tr>
			<?php
		}
		$query->close();
		
		?>
		</table>
		</div>
		<div style="text-align:right;padding-right:20px;padding-top:10px;">
        <input name="deletechecked" class="button" type="submit" id="deletechecked" value="Delete Checked">
        </form>
    </div>
    <br /><div style="text-align:center;padding-bottom:10px;">
		<?php
		if ($pageno == 1) {
			echo ' FIRST PREV ';
		} else {
			echo ' <a href="index.php?act=news&page=1">FIRST</a> ';
			$prevpage = $pageno-1;
			echo ' <a href="index.php?act=news&page=' . $prevpage . '">PREV</a> ';
		} 
		echo ' ( Page ' . $pageno . ' of ' . $lastpage . ' ) ';
		if ($pageno == $lastpage) {
			echo ' NEXT LAST ';
		} else {
			$nextpage = $pageno+1;
			echo ' <a href="index.php?act=news&page=' . $nextpage . '">NEXT</a> ';
			echo ' <a href="index.php?act=news&page=' . $lastpage . '">LAST</a> ';
		} ?> 
	</div>
	<?php
	}
}
?>
</div>