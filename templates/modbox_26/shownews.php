<div id="center">
<div class="container_box1">
<div class="header">News Blog</div>
<?php
if (isset($_POST['addcomment'])) {	
	if(empty($_POST['comment']) || empty($_POST['name'])) { 
		echo '<span style="color:red;">Please go back and try again, it seems the comment or name was left empty.</span><br/><br/>';
		$missing = true;
	} else {
		$missing = false;
	}	
if ($_POST['recaptcha'] == 'yes') {	
    include($setting['sitepath']."/includes/securimage/securimage.php");
        $img = new Securimage();
        $valid = $img->check($_POST['code']);
    if (!$valid) {
        $passed = false;
    } else {
        $passed = true;	
    }	
}	
elseif ($_POST['recaptcha'] == 'no') {
        $answer = array('10', 'ten');
if(!in_array(strtolower($_POST['security']),$answer)) {	
        $passed = false;
    } else {
        $passed = true;
    }
}
if ($passed && !$missing) {
$userid = yasDB_clean($_POST['userid']);
$comment = yasDB_clean($_POST['comment']);
$name = yasDB_clean($_POST['name']);
$ipaddress = yasDB_clean($_SERVER['REMOTE_ADDR']);
yasDB_insert("INSERT INTO `newsblog` (username, newsid, comment, ipaddress) values ('{$_SESSION['user']}', $id, '$comment', '$ipaddress')",false);
echo '<script>alert("Comment added!");</script>';
    } 
elseif (!$passes && !$missing) {
echo '<span style="color:red;">The security question was answered incorrectly. Please try again.</span><br/><br/>';
    }
}
$query = yasDB_select("SELECT * FROM `news` WHERE id = '$id'",false);
$row = $query->fetch_array(MYSQLI_ASSOC);$query->close();
$news = $row['news_text'];
if ( $setting['seo']=='yes' ) {
$newslink = $setting['siteurl'].'news.html';
} else {
$newslink = $setting['siteurl'] . 'index.php?act=news';
}
echo '
<div class="container_box3">
<div id="newsbox" style="text-align:center;">
<a href="'. $newslink .'"><img src="'.$setting['siteurl'].'/templates/'.$setting['theme'].'/skins/' . $setting['skin'] . '/images/news.png" height="100" width="100"><br/>More News<br/></a>
</div>
<div id="textbox"><div class="topicbox"><h2>'.$row['topic'].'</h2></div><div id="topic">' . $row['news_text'] . '</div>
<div class="topicbox">
<iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode($setting['siteurl']).'&amp;layout=standard&amp; 
show_faces=true&amp;width=500&amp;action=like&amp;font=lucida+grande&amp;colorscheme=light&amp;height=28" scrolling="no" frameborder="0" 
style="border:none; overflow:hidden; width:500px; height:28px;" allowTransparency="false"></iframe>
</div>
</div>
</div>';
?>
<div class="clear"></div>
</div>
<div class="container_box1" style="min-height:90px;">
<div class="header">Advertisement</div>
<div style="width:728px;height:90px;background-color:#fff;color:#000;margin-left:5px;">
<?php 	
echo ad("12");
?>
</div>
<div class="clear"></div>
</div>
<div class="container_box1">
<div class="header">Comments:</div>
<div class="container_box3">
<div id="messages">
<?php
$query = yasDB_select("SELECT * FROM newsblog");
$prefix = $setting['siteurl'] . 'templates/' . $setting['theme'] . '/skins/' . $setting['skin'] . '/images/smileys/';
if($query->num_rows == 0) {
	echo '<div id="newsblog_text">This news blog has no comments, become a member and be the first to add one!</div>';
} else {
	$query = yasDB_select("SELECT comment, username FROM newsblog WHERE newsid = '$id'");
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
		echo '<div class="container_box5"><div class="comment_box1">Post by - ' . $row['username'] . '</div><div class="comment_box2">' . $text . '</div></div>';
	}
}
?>
</div></div>
<div class="clear"></div>
</div>
<?php
if (isset($_SESSION['user'])) {
	?>
	<div class="container_box1">
	<div class="header">Leave A Comment</div>
	<div class="container_box3">
	<center><div id="preview"></div></center>
	<div id="commentBox">
	<div class="container_box4">
	<center>
	<form name="addcomment" id="addcomment" method="post" action="">
	<strong>Message:</strong><br />
	<textarea name="comment" rows="3" cols="26" id="comment_message"></textarea><br />
	<input name="newsid" id="newsid" type="hidden" value="<?php echo $id;?>" />
	<input type="hidden" name="timestamp" id="timestamp" value="<?php echo time();?>" />
	</center><br/>
	</div>
	<div class="container_box4">
	<div id="smiles"><center>
	<a href="javascript:addsmilie(' :D ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/biggrin.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :? ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/confused.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' 8) ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/cool.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :cry: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/cry.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :shock: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/eek.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :evil: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/evil.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :lol: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/lol.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :x ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/mad.gif';?>" border="0"  /></a><br />
	<a href="javascript:addsmilie(' :P ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/razz.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :oops: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/redface.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :roll: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/rolleyes.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :( ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/sad.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :) ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/smile.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :o ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/surprised.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :twisted: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/twisted.gif';?>" border="0"  /></a>
	<a href="javascript:addsmilie(' :wink: ')"><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/smileys/wink.gif';?>" border="0"  /></a>
	</center>
	</div>
	</div>
	<div class="container_box4"><center><input name="name" type="hidden" value="<?php echo $_SESSION['user'];?>"/><br />
		<input type="hidden" name="recaptcha" id="recaptcha" value="no">
		<input type="hidden" name="security" id="security" value="10">
		<input name="addcomment" type="submit" value="Add Comment" style="border: 1px solid #333; margin-top: 2px;" /><br/><br/>
	</form></center></div>
	</div>
</div>
<div class="clear"></div>
</div><?php } ?>