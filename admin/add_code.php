<script type="text/javascript">
tinyMCE.init({
    mode : "exact",
    elements : "description",
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
	theme_advanced_height : "550"
	    
});
</script>
<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Insert Code</h1>
<div class="breadcrumbs"><a href="index.php?act=addmedia" title="Ad Media">Add Media</a> / <a href="index.php?act=managegames" title="Manage Games">Manage Games</a> / <a href="index.php?act=uploadgames" title="Upload Games">Upload Games</a> / <a href="index.php?act=brokenfiles" title="Broken Files">Broken Files</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Add Embed Code</h3>
</label>
</div>
<?php
if(isset($_POST['add'])) {
	$title = yasDB_clean($_POST['title']);
	$desc = yasDB_clean($_POST['description']);
	$thumb = yasDB_clean($_POST['thumbnail']);
	$code = yasDB_clean($_POST['code'],false,false);
	yasDB_insert("INSERT INTO games (title, description, category, thumbnail, code, height, width, type) values ('$title', '$desc', '{$_POST['category']}', '$thumb', '$code', '', '', 'CustomCode')",false);
	echo '<center>Embed Code successfully added!<br/><br/>';
	echo '<a href="index.php?act=addmedia">Click here to proceed</a><center>';
} else {
?>
<div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	    <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<form enctype="multipart/form-data" action="" method="post">
		<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
	    <th class="full" colspan="2">Add Code</th>
	    </tr>
		<tr>
		<td class="first" width="172"><strong>Title</strong></td>
		<td class="last"><input type="text" name="title" style="width:275px;"/></td>
		</tr>
		<tr class="bg">
		<td class="first"><strong>Description</strong></td><td class="last"></td>
		<tr>
		<td style="background-color:#fff;width:100%;"><textarea name="description" id="description" style="width:100%;"></textarea></td>
		</tr>
		<tr>
		<td class="first" width="172"><strong>Category</strong></td>
		<td class="last"><select name="category">
	    <?php
	    $query = yasDB_select("SELECT * FROM categories",false);
	    while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
	    }
	    $query->close();
		?>
	    </select></td>
		</tr>
		<tr class="bg">
		<td class="first"><strong>Code</strong></td>
		<td class="last"><textarea name="code" style="width:275px;height:175px;"></textarea></td>
		</tr>
		<tr>
		<td class="first" width="172"><strong>Thumbnail</strong></td>
		<td class="last"><input type="text" name="thumbnail" value="img/" style="width:275px;"/></td>
		</tr>
		<tr class="bg">
		<td class="first"><strong>Submit</strong></td>
		<td class="last"><input type="submit" class="button" name="add" value="Add Code!" /></td>
		</tr>
		</table>
		</div>
		</form>
	<?php
}
?>
</div>