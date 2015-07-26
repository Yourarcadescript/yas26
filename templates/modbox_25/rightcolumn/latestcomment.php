<div class="nav_box"><div class="nav">Latest Comment</div>
<div class="nav_box2">
<?php
$query = yasDB_select("SELECT * FROM newsblog");
if($query->num_rows == 0) {
echo '<div id="newsblog_text">This news blog has no comments, become a member and be the first to add one!</div>';
} else {	 
	$query = yasDB_select("SELECT *,DATE_FORMAT(`timestamp`,'%b %e, %y, %r') AS formatted_time FROM newsblog ORDER BY `timestamp` DESC LIMIT 1");
	$row = $query->fetch_array(MYSQLI_ASSOC);
	$query2 = yasDB_select("SELECT `topic` FROM `news` WHERE `id` = {$row['newsid']}");
	$row2 = $query2->fetch_array(MYSQLI_ASSOC);
	$query3 = yasDB_select("SELECT `id` FROM `user` WHERE `username` = '{$row['username']}'");
	$row3 = $query3->fetch_array(MYSQLI_ASSOC);
	$prefix = $setting['siteurl'] . 'templates/' . $setting['theme'] . '/skins/' . $setting['skin'] . '/images/smileys/';
	$id = $row3['id'];
	$text = $row['comment'];
	$text = str_replace(':D','<img src="' . $prefix . 'biggrin.gif" title="biggrin" alt="biggrin" />',$text);
	$text = str_replace(':?','<img src="' . $prefix . 'confused.gif" title="confused" alt="confused" />',$text);
	$text = str_replace('8)','<img src="' . $prefix . 'cool.gif" title="cool" alt="cool" />',$text);
	$text = str_replace(':cry:','<img src="' . $prefix . 'cry.gif" title="cry" alt="cry" />',$text);
	$text = str_replace(':shock:','<img src="' . $prefix . 'eek.gif" title="eek" alt="eek" />',$text);
	$text = str_replace(':evil:','<img src="' . $prefix . 'evil.gif" title="evil" alt="evil" />',$text);
	$text = str_replace(':lol:','<img src="' . $prefix . 'lol.gif" title="lol" alt="lol" />',$text);
	$text = str_replace(':x','<img src="' . $prefix . 'mad.gif" title="mad" alt="mad" />',$text);
	$text = str_replace(':P','<img src="' . $prefix . 'razz.gif" title="razz" alt="razz" />',$text);
	$text = str_replace(':oops:','<img src="' . $prefix . 'redface.gif" title="redface" alt="redface" />',$text);
	$text = str_replace(':roll:','<img src="' . $prefix . 'rolleyes.gif" title="rolleyes" alt="rolleyes" />',$text);
	$text = str_replace(':(','<img src="' . $prefix . 'sad.gif" title="sad" alt="sad" />',$text);					
	$text = str_replace(':)','<img src="' . $prefix . 'smile.gif" title="smile" alt="smile" />',$text);
	$text = str_replace(':o','<img src="' . $prefix . 'surprised.gif" title="surprised" alt="surprised" />',$text);
	$text = str_replace(':twisted:','<img src="' . $prefix . 'twisted.gif" title="twisted" alt="twisted" />',$text);
	$text = str_replace(':wink:','<img src="' . $prefix . 'wink.gif" title="wink" alt="wink" />',$text);
	if ($setting['seo']=='yes') {
		$memberlink = $setting['siteurl'] . 'showmember/'.$id.'.html';
	} else {
		$memberlink = $setting['siteurl'] . 'index.php?act=showmember&id='.$id;
	}
?>
	<center>
	<b>Topic</b> - <?php echo $row2['topic'];?><br/><hr />
	<?php echo $text;?><br/><hr />
	<b>Comment by </b><br/>
	<a href="<?php echo $memberlink;?>"><?php echo $row['username'];?></a><hr /><?php echo $row['formatted_time'];?>
	</center>

<?php	
	$query->close();
	$query2->close();
	$query3->close();
}		
?>
</div></div>