<div class="gameslogin">
<ul>

<li class="even"><div id="headergames">Newest Games</div>
<?php
$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games ORDER BY id DESC LIMIT 6",false);
if ($query->num_rows == 0) {
	echo '<center><h3>We have no Top Rated games yet!</h3></center>';
}
if ($query->num_rows > 0) {	
	while($games = $query->fetch_array(MYSQLI_ASSOC)) {
		$pic_settings = array('w'=>90,'h'=>60);
		$gameurl = prepgame($games['title']);			
		$games['description'] = stripslashes($games['description']);
		if (file_exists($games['thumbnail'])) {
			$thumbnail = urldecode($games['thumbnail']);
		} else {
			$thumbnail = $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/nopic.jpg';
		}
		if ($setting['seo']=='yes') {
			$gurl = $setting['siteurl'] . 'game/' . $games['id'] . '/' . $gameurl . '.html';
		} else {
			$gurl = $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'];
		}
		$hinttext = '<h2 class=&quot;title&quot;>'.$games['title'].'</h2>'.$games['description'];
		$hinttext = str_replace(array("\r\n", "\r", "\n"), '<br/>', $hinttext);
		$hinttext = str_replace(array('"',"'"),array('&quot;','&#146;'),$hinttext);
	?>
		<div class="inner_box3"><a href="<?php echo $gurl;?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?>  Plays">		
		<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" width="90" height="60" /></a><br/>
		<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $games['title'];?></a>		
		</div>
	<?php	
	}
}	
$query->close();
?>               
</li>
<li class="even"><div id="headergames">Mostplayed Games</div>
<?php
$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games ORDER BY plays DESC LIMIT 6");
if ($query->num_rows == 0) {
	echo '<center><h3>We have no Most Played games yet!</h3></center>';
} else {	
	while($games = $query->fetch_array(MYSQLI_ASSOC)) {
		$pic_settings = array('w'=>90,'h'=>60);
		$gameurl = prepgame($games['title']);
		$games['description'] = stripslashes($games['description']);
		if (file_exists($games['thumbnail'])) {
			$thumbnail = urldecode($games['thumbnail']);
		} else {
			$thumbnail = $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/nopic.jpg';
	}
		if ($setting['seo']=='yes') {
			$gurl = $setting['siteurl'] . 'game/' . $games['id'] . '/' . $gameurl . '.html';
		} else {
			$gurl = $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'];
		}
		$hinttext = '<h2 class=&quot;title&quot;>'.$games['title'].'</h2>'.$games['description'];
		$hinttext = str_replace(array("\r\n", "\r", "\n"), '<br/>', $hinttext);
		$hinttext = str_replace(array('"',"'"),array('&quot;','&#146;'),$hinttext);
		?>
		<div class="inner_box3"><a href="<?php echo $gurl;?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?>  Plays">		
		<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" width="90" height="60" /></a><br/>
		<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $games['title'];?></a>		
		</div>		
	<?php
	}
}
$query->close();
?>
</li>
<li class="even"><div id="headergames">Random Games</div>
<?php           
	$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games ORDER BY rand() LIMIT 6");
	if ($query->num_rows == 0) {
		echo '<center><h3>We have no Random games yet!</h3></center>';
	} else {		
		while($games = $query->fetch_array(MYSQLI_ASSOC)) {		
		$pic_settings = array('w'=>90,'h'=>60);
		$gameurl = prepgame($games['title']);				
		$games['description'] = stripslashes($games['description']);
		if (file_exists($games['thumbnail'])) {
			$thumbnail = urldecode($games['thumbnail']);
		} else {
			$thumbnail = $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/nopic.jpg';
		}		
		if ($setting['seo']=='yes') {
			$gurl = $setting['siteurl'] . 'game/' . $games['id'] . '/' . $gameurl . '.html';
		} else {
			$gurl = $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'];
		}
		$hinttext = '<h2 class=&quot;title&quot;>'.$games['title'].'</h2>'.$games['description'];
		$hinttext = str_replace(array("\r\n", "\r", "\n"), '<br/>', $hinttext);
		$hinttext = str_replace(array('"',"'"),array('&quot;','&#146;'),$hinttext);
		?>
		<div class="inner_box3"><a href="<?php echo $gurl;?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?>  Plays">		
		<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" width="90" height="60" /></a><br/>
		<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $games['title'];?></a>		
		</div>	
<?php 
		}
	}
$query->close();?>	
</li>
</ul>
<div class="clear"></div>
</div>