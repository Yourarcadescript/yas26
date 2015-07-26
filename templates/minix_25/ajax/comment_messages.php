<?php
include("../../../includes/db_functions.inc.php");
include("../../../includes/config.inc.php");
if (isset($_GET['gameid'])) {
	$id = $_GET['gameid'];
	$query = yasDB_select("SELECT * FROM comments WHERE gameid = $id");
	$emptymessage = '<div id="comment_text">This game has no comments, be the first to add one!</div>';
} elseif (isset($_GET['userid'])){
	$id = $_GET['userid'];
	$query = yasDB_select("SELECT * FROM memberscomments WHERE userid = $id");
	$emptymessage = '<div id="comment_text">This member has no comments, be the first to add one!</div>';
} else {
	$id = $_GET['newsid'];
	$query = yasDB_select("SELECT * FROM newsblog WHERE newsid = $id");
	$emptymessage =  '<div id="newsblog_text">This news blog has no comments, become a member and be the first to add one!</div>';
}
$total = $query->num_rows;
if($total == 0) {
	echo $emptymessage;
} else {
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		$text = $row['comment'];
		$text = str_replace(':D','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/biggrin.gif" title="biggrin" alt="biggrin" />',$text);
		$text = str_replace(':?','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/confused.gif" title="confused" alt="confused" />',$text);
		$text = str_replace('8)','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/cool.gif" title="cool" alt="cool" />',$text);
		$text = str_replace(':cry:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/cry.gif" title="cry" alt="cry" />',$text);
		$text = str_replace(':shock:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/eek.gif" title="eek" alt="eek" />',$text);
		$text = str_replace(':evil:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/evil.gif" title="evil" alt="evil" />',$text);
		$text = str_replace(':lol:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/lol.gif" title="lol" alt="lol" />',$text);
		$text = str_replace(':x','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/mad.gif" title="mad" alt="mad" />',$text);
		$text = str_replace(':P','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/razz.gif" title="razz" alt="razz" />',$text);
		$text = str_replace(':oops:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/redface.gif" title="redface" alt="redface" />',$text);
		$text = str_replace(':roll:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/rolleyes.gif" title="rolleyes" alt="rolleyes" />',$text);
		$text = str_replace(':(','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/sad.gif" title="sad" alt="sad" />',$text);					
		$text = str_replace(':)','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/smile.gif" title="smile" alt="smile" />',$text);
		$text = str_replace(':o','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/surprised.gif" title="surprised" alt="surprised" />',$text);
		$text = str_replace(':twisted:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/twisted.gif" title="twisted" alt="twisted" />',$text);
		$text = str_replace(':wink:','<img src="' . $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/wink.gif" title="wink" alt="wink" />',$text);
		if (isset($_GET['gameid'])) {
			echo '<div class="container_box5"><div class="comment_box1">Post by - ' . $row['name'] . '</div><div class="comment_box2">' . $text . '</div></div>';
		} elseif (isset($_GET['userid'])){
			echo '<div class="container_box5"><div class="comment_box1">Post by - ' . $row['name'] . '</div><div class="comment_box2">' . $text . '</div></div>';
		} elseif (isset($_GET['newsid'])){
			echo '<div class="container_box5"><div class="comment_box1">Post by - ' . $row['username'] . '</div><div class="comment_box2">' . $text . '</div></div>';
		}
	}
}
$query->close();
?>
<div class="clear"></div>