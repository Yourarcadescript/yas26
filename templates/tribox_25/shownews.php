<div id="center">
<div id="news_main_header">News Blog</div>
<div id="news_main_box">
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
$ipaddress = yasDB_clean($_SERVER['REMOTE_ADDR']);
yasDB_insert("INSERT INTO `newsblog` (userid, comment, ipaddress) values ('{$userid}', '{$comment}', '{$ipaddress}')",false);
echo '<script>alert("Comment added!");</script>';
} 
elseif (!$passes && !$missing) {
echo '<span style="color:red;">The security question was answered incorrectly. Please try again.</span><br/><br/>';
}
}
$query = yasDB_select("SELECT * FROM `news` WHERE id = '$id'",false);
$row = $query->fetch_array(MYSQLI_ASSOC);$query->close();
$news = $row['news_text'];
if ($setting['seo']=='yes') {	
echo '<div class="news_box">
<div class="news_box1">
<div class="news_link"><a href="'. $setting['siteurl'] .'news.html">Go Back</a></div>
<div class="news_image">
<img src="'.$setting['siteurl'].'/templates/'.$setting['theme'].'/skins/' . $setting['skin'] . '/images/news.png" height="60" width="60">
</div></div>
<div class="news_box2">
<div class="news_textbox">Topic</div>
<div class="news_textbox2">' . $row['news_text'] . '<p><p><strong>This news was brought to you by '.$setting['sitename'].'!</strong></div>
<div id="facebook_2">
<iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode($setting['siteurl']).'&amp;layout=standard&amp; 
show_faces=true&amp;width=500&amp;action=like&amp;font=lucida+grande&amp;colorscheme=light&amp;height=28" scrolling="no" frameborder="0" 
style="border:none; overflow:hidden; width:500px; height:28px;" allowTransparency="false"></iframe>
</div></div>';
} else {
echo '<div class="news_box">
<div class="news_box1">
<div class="news_link"><a href="'.$setting['siteurl'].'/index.php?act=news">Go Back</a></div>
<div class="news_image">
<img src="'.$setting['siteurl'].'/templates/'.$setting['theme'].'/skins/' . $setting['skin'] . '/images/news.png" height="60" width="60">
</div></div>
<div class="news_box2">	<div class="news_textbox">News</div>
<div class="news_textbox2">' . $row['news_text'] . '<p><p><strong>This news was brought to you by '.$setting['sitename'].'!</strong></div>
<div id="facebook_2">
<iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode($setting['siteurl']).'&amp;layout=standard&amp; 
show_faces=true&amp;width=500&amp;action=like&amp;font=lucida+grande&amp;colorscheme=light&amp;height=28" scrolling="no" frameborder="0" 
style="border:none; overflow:hidden; width:500px; height:28px;" allowTransparency="false"></iframe>
</div></div>';
}
?></div>
<div class="clear"></div>
<div id="blog_box_ad">
<div id="blog_ad">
<?php 	
if (ad("4") == 'Put AD code here') {
?><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/468x60.PNG';?>" width="468" height="60" />
<?php	
} else {
echo ad("4");
}
?>
</div>
</div>
<div class="clear"></div>
<div class="news_box">
<?php
$query = yasDB_select("SELECT * FROM newsblog");
$prefix = $setting['siteurl'] . 'templates/' . $setting['theme'] . '/skins/' . $setting['skin'] . '/images/smileys/';
if($query->num_rows == 0) {
echo '<div id="newsblog_text">This news blog has no comments, become a member and be the first to add one!</div>';
} else {
$query = yasDB_select("SELECT * FROM newsblog WHERE userid = '$id'");
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
echo '<div class="newsblog_box1">' . $row['name'] . '</div>	<div class="newsblog_box2">' . $text . '</div>';
      }
}
$query->close();
if (isset($_SESSION['user'])) {
?>
</div>
<div class="clear"></div>
<div class="news_box">
<div class="newsblog_box3"><center>
<form name="addcomment" method="post" action="">
<strong>Message:</strong><br />
<textarea name="comment" rows="3" cols="26" id="comment_message"></textarea><br />
<input name="userid" type="hidden" value="<?php echo $id;?>" />
<input type="hidden" name="timestamp" id="timestamp" value="<?php echo time();?>" />
</center>
</div>
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
<div class="newsblog_box4"><center><input name="name" type="hidden" value="<?php echo $_SESSION['user'];?>"/><br />
<?php
if ($setting['userecaptcha'] == "yes") {
@session_start();// securimage captcha
?>
<div style="width: 510px; float:left;height: 90px">
<img id="siimage" align="center" style="padding-right: 5px; border: 0" src="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_show.php?sid=<?php echo md5(time()) ?>" />
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="19" height="19" id="SecurImage_as3" align="middle">
<param name="allowScriptAccess" value="sameDomain" /><param name="allowFullScreen" value="false" /><param name="movie" value="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_play.swf?audio=securimage_play.php&bgColor1=#fff&bgColor2=#00132f&iconColor=#000&roundedCorner=5" />
<param name="quality" value="high" /><param name="bgcolor" value="#00132f" />
<embed src="<?php echo $setting['siteurl']; ?>includes/securimage/securimage_play.swf?audio=securimage_play.php&bgColor1=#fff&bgColor2=#00132f&iconColor=#000&roundedCorner=5" quality="high" bgcolor="#00132f" width="19" height="19" name="SecurImage_as3" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
<!-- pass a session id to the query string of the script to prevent ie caching -->
<a tabindex="-1" style="border-style: none" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = '<?php echo $setting['siteurl']; ?>includes/securimage/securimage_show.php?sid=' + Math.random(); return false"><img src="<?php echo $setting['siteurl']; ?>includes/securimage/images/refresh.gif" alt="Reload Image" border="0" onclick="this.blur()" align="middle" /></a>
<div style="clear: both"></div>
</div>Code:<br />
<input type="text" name="code" size="12" /><br /><br />
<input name="recaptcha" type="hidden" value="yes" />
<?php
// end securimage captcha
}
else {
?>Security Question: five + five = <br />
<input name="security" type="text" style="border: 1px solid #000;" /><br/>
<input name="recaptcha" type="hidden" value="no" />	
<?php } ?>
<input name="addcomment" type="submit" value="Add Comment" style="border: 1px solid #333; margin-top: 2px;" />
</form></center></div>
<?php	
}
?>
</div>
<div class="clear"></div>
</div>