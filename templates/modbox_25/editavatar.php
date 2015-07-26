<div id="center">
<?php
include($setting['sitepath'] . '/includes/filelist.inc.php');
if(isset($_SESSION["user"])) {
	$user=$_SESSION["user"];
	$id=$_SESSION["userid"];
	?>	
	<div class="container_box1">
	<div class="header">Edit Avatar:</div>
	<div class="text">
	<form enctype="multipart/form-data" name="settings" method="post" action="<?php echo $setting['siteurl'];?>includes/avatarupload.php">
	<input type="hidden" name="MAX_FILE_SIZE" value="102400" />
	<input type="hidden" name="userid" value="<?php echo $_SESSION['userid'];?>" />
	<input type="file" name="file" class="fileUpload" multiple><button id="px-submit" type="submit">Upload</button>
	<button id="px-clear" type="reset">Clear</button>
	</form>
	</div>
	<div class="editavatar_header">Free Avatars</div>
	<div class="avatarBox">
	<?php
	$dir = $setting['sitepath'] . '/avatars';
	$files = dir_list($dir);
	$os = array("gif", "jpg", "jpeg", "png", "GIF", "JPG", "JPEG", "PNG");
	$n = 0;
	$num = 0;
	$count = count($files);
	if ($count==0) {
		echo '<p>No free avatars available.</p>';
		exit();
	}
	$avquery = yasDB_select("SELECT avatar FROM avatars WHERE userid = '$id'");
	if ($avquery->num_rows != 0) {
		while ($avfile = $avquery->fetch_array(MYSQL_ASSOC)) {
			echo '<div class="avatar_images">
			<input type="image" class="useravatars" src="' . $setting['siteurl'] . 'avatars/' . $avfile['avatar'] . '" onclick="switchAvatar(\''.$avfile['avatar'].'\');return false">';
			echo '<center><input type="image" src="'.$setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/close.png" height="15" width="15" name="avatar" value="Remove" onclick="deleteAvatar(\''.addslashes($avfile['avatar']).'\');return false"/></center></div>';
		}
	}
	$avquery->close();
	while ($num < $count) {
		$file = $files[$num]['name'];
		$num++;
		$n++;
		$file = yasDB_clean($file);
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if (in_array($ext, $os)) {
			echo '<div class="avatar_images">
			<input type="image" src="' . $setting['siteurl'] . 'avatars/' . $file . '"  onclick="switchAvatar(\''.$file.'\');return false" width="100px" height="100px"></div>';
		}
	}
	unset($files);
} else {
	echo "<p><h3>Not logged in!</h3><p></div>";
}
?>
<div class="clear"></div>
</div>
<div class="editavatar_header">Disclaimer:</div>
<div class="editavatar_disclaimer">
<ul style="list-style-type:none;">
<li>
All avatars are free to use. Many avatars curtesy of <a href="http://www.koolavatar.com/" target="_blank">koolavatar.com</a>.
Do not upload illegal pictures. 
Uploaded pictures should be considered public domain. Illegal uploaded pictures will result in a IMMEDIATE BAN.
</ul>
</li>
</div>
</div>