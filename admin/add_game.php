<script type="text/javascript">
tinyMCE.init({
    mode : "exact",
    elements : "description,instructions",
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
<h1>Cpanel - Upload Games</h1>
<div class="breadcrumbs"><a href="index.php?act=addmedia" title="Ad Media">Add Media</a> / <a href="index.php?act=addcode" title="Ad Code">Add Code</a> / <a href="index.php?act=managegames" title="Manage Games">Manage Games</a> / <a href="index.php?act=brokenfiles" title="Broken Files">Broken Files</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Add Game</h3>
</label>
</div>
<?php

function get_file_extension($filepath) {
    preg_match('/[^?]*/', $filepath, $matches);
    $string = $matches[0];
    $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
    // check if there is any extension
    if(count($pattern) == 1) {
        return false;
    }
       
    if(count($pattern) > 1) {
        $filenamepart = $pattern[count($pattern)-1][0];
        preg_match('/[^?]*/', $filenamepart, $matches);
        return strtolower($matches[0]);
    }
}
$num = rand(0, pow(10, 5)) . '-'; // 5 digit random number to prefix game name
$thumb_types=array(
    'gif',
    'jpeg',
    'jpg',
	'png'
);
$file_types=array(
	'swf',
	'unity3d',
	'dcr',
	'mov',
	'mpg',
	'avi',
	'flv',
	'wmv'
);
if (isset($_POST['add'])) {
	include_once ("../includes/db_functions.inc.php");
	if (!isset($_FILES['file']['name']) || !isset($_FILES['thumbnail']['name'])) {
		echo '<div style="text-align: center;">No file or thumbnail detected</div>';
		exit();
	}
	$thumb_ext = get_file_extension($_FILES["thumbnail"]["name"]) or die("<div style=\"text-align: center;\">You must include a game pic.</div>");
	$file_ext = get_file_extension($_FILES["file"]["name"]) or die("<div style=\"text-align: center;\">You must include a game file.</div>");
	if (in_array($thumb_ext, $thumb_types) || in_array($file_ext, $file_types)) {
		if ($_FILES["file"]["error"] > 0) {
			echo "<center>Return Code: " . $_FILES["thumbnail"]["error"] . "</center>";
		} else {
			move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $setting['sitepath']."/img/" . $num . preg_replace('/[^a-zA-Z0-9.-_]/', '', $_FILES["thumbnail"]["name"]));
			move_uploaded_file($_FILES["file"]["tmp_name"], $setting['sitepath']."/swf/" . $num . preg_replace('/[^a-zA-Z0-9.-_]/', '', $_FILES["file"]["name"]));
			$img = yasDB_clean("img/" .  $num . preg_replace('/[^a-zA-Z0-9.-_]/', '', $_FILES["thumbnail"]["name"]));
			$file = yasDB_clean("swf/" .  $num . preg_replace('/[^a-zA-Z0-9.-_]/', '', $_FILES["file"]["name"]));
			$desc = yasDB_clean(trim($_POST['description']));
			$title = yasDB_clean($_POST['title']);
			$height = intval($_POST['height']);
			$width = intval($_POST['width']);
			$instr = yasDB_clean(trim($_POST['instructions']));
			$keywords = yasDB_clean($_POST['keywords']);
			if($file_ext == 'swf'){
				if ($_POST['height'] OR $_POST['width'] <= 0 ) {
					list($width, $height, $type, $attributes) = getimagesize($setting['sitepath'].'/'.$file);
				}
			}	
			$query = yasDB_insert("INSERT INTO games (title, description, instructions, keywords, category, thumbnail, file, height, width, type) VALUES ('$title', '$desc', '$instr', '$keywords', ".intval($_POST['category']).", '$img', '$file', $height, $width, '".yasDB_clean($_POST['type'])."')",false);
			if ($query) { 
				echo '<center><p align="center"><b>Game successfully added!</b></p></center>';
			} else {
				echo '<center><p align="center"><b>Error updating game database!</b></p></center>';
			}
		}
	} else {
		echo '<center>File or thumbnail type not supported</center>';
		exit();
	}
}
 ?>
 <div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	    <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
        <form enctype="multipart/form-data" action="index.php?act=uploadgames" method="post">
		<input type="hidden" name="act" value="uploadgames"/>
		<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
	    <th class="full" colspan="2">Upload A Game</th>
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
		<td class="first"><strong>Instructions</strong></td><td class="last"></td>
		<tr>
		<td style="background-color:#fff;width:100%;"><textarea name="instructions" id="instructions" style="width:100%;"></textarea></td>
		</tr>
		<tr class="bg">
		<td class="first"><strong>Keywords</strong></td>
		<td class="last"><input type="text" name="keywords" style="width:275px;"/></td>
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
		<td class="first"><strong>Game File Type</strong></td>
		<td class="last"><select name="type">
		<option value="SWF">swf</option>
		<option value="DCR">dcr</option>
	    </select></td>
		</tr>
		<tr>
		<td class="first" width="172"><strong>Game File</strong></td>
		<td class="last"><input type="file" name="file" /> (swf/dcr)</td>
		</tr>
		<tr class="bg">
		<td class="first"><strong>Thumbnail</strong></td>
		<td class="last"><input type="file" name="thumbnail"> (gif/jpg/png)</td>
		</tr>
		<tr>
		<td class="first" width="172"><strong>Height:*</strong></td>
		<td class="last"><input type="text" name="height" /></td>
		</tr>
		<tr class="bg">
		<td class="first"><strong>Width:*</strong></td>
		<td class="last"><input type="text" name="width" /></td>
		</tr>
		<tr>
		<td class="first" width="172"></td>
		<td class="last"><input type="submit" class="button" name="add" value="Add Game!" /><!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Use ftp?&nbsp;&nbsp;<input type="checkbox" name="ftp" id="ftp" /></td>-->
		</tr>
		</table>
		</div>
		</form>
</div>