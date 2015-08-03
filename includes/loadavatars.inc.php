<?php
session_start();
require_once("db_functions.inc.php");
require_once("config.inc.php");
require_once("filelist.inc.php");
$dir = $setting['sitepath'] . '/avatars';
$files = dir_list($dir);
$os = array("gif", "jpg", "jpeg", "png", "GIF", "JPG", "JPEG", "PNG");
$num = 0;
$count = count($files) + 1;
if ($count==0) {
	echo '<p>No free avatars available.</p>';
	exit();
}
$avquery = yasDB_select("SELECT avatar FROM avatars WHERE userid = '".$_SESSION['userid']."'");
if ($avquery->num_rows != 0) {
	while ($avfile = $avquery->fetch_array(MYSQL_ASSOC)) {
		echo '<li style="list-style:none;float:left;padding:5px 5px 5px 0px;">><input type="image" class="useravatars" src="' . $setting['siteurl'] . 'avatars/' . $avfile['avatar'] . '" onclick="switchAvatar(\''.$avfile['avatar'].'\');return false">';
		echo '<input type="image" src="'.$setting['siteurl'].'templates/'.$setting['theme'].'/images/close.png" height="15" width="15" name="avatar" value="Remove" onclick="deleteAvatar(\''.addslashes($avfile['avatar']).'\');return false"/></li>';
	}
}
$avquery->close();
while ($num < $count) {
	$file = $files[$num]['name'];
	$num++;
	$file = yasDB_clean($file);
	$ext = pathinfo($file, PATHINFO_EXTENSION);
	if (in_array($ext, $os)) {// && $files[$num]['type'] == 'file'  This kept on cutting off last pic??
		echo '<li style="list-style:none;float:left;padding:5px 5px 5px 0px;">><input type="image" src="' . $setting['siteurl'] . 'avatars/' . $file . '"  onclick="switchAvatar(\''.$file.'\');return false"></li>';
	}	
}
unset($files);
?>