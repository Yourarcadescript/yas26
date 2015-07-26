<?php
$cache = RayCache::getInstance('home', null, array('prefix' => $setting['theme'], 'path' => 'cache/', 'expire' => $setting['cachelife']));
$data = $cache->read('home');
if ($data) {
	echo $data;
} else {
	$cache->start_caching();

$pic_settings = array('w'=>80,'h'=>80);
?>
<!-- begin center -->
<div id="center"> 
<div id="home_container">

<!-- start of news box -->
<div id="news"><div id="nav1">News</div>
<div align="left" style="padding:6px;height:90px;overflow:hidden;">
<?php
        $sql = yasDB_select("SELECT * FROM `news` ORDER BY `id` DESC LIMIT 1",false);		
        $news = $sql->fetch_array(MYSQLI_ASSOC);		
        $text = closeTags(strlen($news['news_text'])>230?substr($news['news_text'],0,230).'...':$news['news_text']);				
        if ($setting['seo'] == 'yes') {
			$news_url = $setting['siteurl'] . 'news.html';
		} else {
			$news_url = $setting['siteurl'] . 'index.php?act=news';
		}
		echo '<center><b>'. $news['date'] . '</center></b>			
              <left>'. $text. '</left>				  
              <div style="position:absolute;bottom:0px;right:70px;"><a href="' . $news_url . '">Read All News</a></div>';        	
        $sql->close();		
?>
</div>
</div>
<!-- end of news box -->
<!-- start of banner box -->
<div id="banner2"><div id="nav2">Banner</div>
<div align="center" style="width:100px;height:100px;margin-left:4px;padding:0;background-color:#fff;">
<?php
echo ad("8");	
?>
</div></div>
<!-- end of banner box -->
<!-- start of mostplayed box -->
<div id="mostplayed"><div id="nav5">Most Played</div>
<?php
$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games ORDER BY plays DESC LIMIT 9");
if ($query->num_rows == 0) {
	echo '<center><h3>We have no Most Played games yet!</h3></center>';
} else {
	$pic_settings = array('w'=>80,'h'=>80);
	while($games = $query->fetch_array(MYSQLI_ASSOC)) {
		$gameurl = prepgame($games['title']);
		$title = $games['title'];
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
		$hinttext = '<h2 class=&quot;title&quot;>'.$games['title'].'</h2>';
		$hinttext = str_replace(array('"',"'"),array('&quot;','&#146;'),$hinttext);
		?>
		<div class="inner_box3"><a href="<?php echo $gurl;?>"  class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, 'auto')">		
		<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" width="80" height="80" alt="<?php echo $games['title'];?> - <?php echo $games['plays'];?>  Plays"/></a>		
		</div>		
	<?php
	}
}
$query->close();
?>
</div>
<!-- end of mostplayed box -->
<!-- start of random box -->
<div id="random"><div id="nav6">Random Games</div>
<?php           
	$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games ORDER BY rand() LIMIT 4");
	if ($query->num_rows == 0) {
		echo '<center><h3>We have no Random games yet!</h3></center>';
	} else {
		$pic_settings = array('w'=>100,'h'=>100);
		while($games = $query->fetch_array(MYSQLI_ASSOC)) {		
			$gameurl = prepgame($games['title']);				
			$games['description'] = stripslashes($games['description']);			
			if (strlen($games['title']) > 34) {
				$title = substr($games['title'], 0, 31) . '...';
			} else {
				$title = $games['title'];
			}
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
			<div class="inner_box2">	
			<a href="<?php echo $gurl;?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?> Plays">		
			<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" width="100" height="100" /></a>		
			<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $title;?></a>		
			<div class="clear"></div></div>
<?php 
		}
	}
$query->close();?>
</div>
<!-- end of random box -->
<br style="clear: both"/>
<!-- start of featured box -->
<div class="container_box1"><div class="header">Featured Games</div>
<?php
	$query = yasDB_select("SELECT `id` , `title` , `thumbnail` , `description` , `plays` FROM `games` INNER JOIN `featuredgames` ON `games`.`id` = `featuredgames`.`gameid` ORDER BY rand()");
	$pic_settings = array('w'=>130,'h'=>100);
	if ($query->num_rows == 0) {
		$query->close();
		$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games ORDER BY rand() LIMIT 5");
		if ($query->num_rows == 0) {
			echo '<center><h3>We have no Featured games yet!</h3></center>';
		}
	}
	if ($query->num_rows > 0) {
		while($games = $query->fetch_array(MYSQLI_ASSOC)){	
			$gameurl = prepgame($games['title']);			
			$games['description'] = stripslashes($games['description']);
			if (strlen($games['title']) > 19) {
				$title = substr($games['title'], 0, 16) . '...';
			} else {
				$title = $games['title'];
			}
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
			<div class="inner_box"><div class="images">		
			<a href="<?php echo $gurl;?>">		
			<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?> Plays" width="130" height="100" /></a>		
			<div class="text2">
			<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $title;?></a></div>
			</div>
			<div class="clear"></div><br style="clear: both"/></div>	
			<?php 
			}
		}
$query->close();
?>
<div class="clear"></div></div>
<!-- end of featured box -->
<!-- start of ads_box box -->
<div class="container_ad1">
<div class="header_ad1">Advertisement</div>
<div class="ad_300x250">
<?php 	
echo ad("3");
?>
</div>

</div>
<div class="container_ad2">
<div class="header_ad2">Newest Games</div>
<?php
	$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games ORDER BY id DESC LIMIT 6");
	if ($query->num_rows == 0) {
		echo '<center><h3>We have no Featured games yet!</h3></center>';
	}
	if ($query->num_rows > 0) {
		$pic_settings = array('w'=>120,'h'=>100);
		while($games = $query->fetch_array(MYSQLI_ASSOC)){	
			$gameurl = prepgame($games['title']);			
			$games['description'] = stripslashes($games['description']);
			if (strlen($games['title']) > 17) {
				$title = substr($games['title'], 0, 14) . '...';
			} else {
				$title = $games['title'];
			}
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
			<div class="inner_box_ad2"><div class="images_ad2">		
			<a href="<?php echo $gurl;?>">		
			<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?> Plays" width="120" height="100" /></a>		
			<div class="text3">
			<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $title;?></a></div>
			</div>
			<div class="clear"></div><br style="clear: both"/></div>	
			<?php 
			}
		}
$query->close();
?>
</div>
<!-- end of ads box -->
<!-- start of toprated -->
<div class="clear"></div>
<div class="container_box1"><div class="header">Top Rated</div>
<?php
$query = yasDB_select("SELECT `games`.`id`, `title`, `thumbnail`, `description`, `plays` FROM `games` INNER JOIN `ratingsbar` ON `games`.`id` = `ratingsbar`.`id` WHERE `ratingsbar`.`total_votes` > 0 ORDER BY `ratingsbar`.`total_value`/`ratingsbar`.`total_votes` DESC, `ratingsbar`.`total_votes` DESC LIMIT 5",false);
if ($query->num_rows == 0) {
	$query->close();
	$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games ORDER BY rand() LIMIT 5");
	if ($query->num_rows == 0) {
		echo '<center><h3>We have no Top Rated games yet!</h3></center>';
	}
}
if ($query->num_rows > 0) {
	$pic_settings = array('w'=>130,'h'=>100);
	while($games = $query->fetch_array(MYSQLI_ASSOC)) {
		$gameurl = prepgame($games['title']);				
		$games['description'] = stripslashes($games['description']);		
		if (strlen($games['title']) > 19) {
			$title = substr($games['title'], 0, 16) . '...';
		} else {
			$title = $games['title'];
		}
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
		<div class="inner_box"><div class="images">		
		<a href="<?php echo $gurl;?>">		
		<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?> Plays" width="130" height="100" /></a>
		</div>		
		<div class="text2">
		<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $title;?></a>		
		</div>
		<div class="clear"></div></div>
	<?php	
	}
}
$query->close();
?>
<div class="clear"></div></div>
<!-- end of toprated -->
<!-- start of newest games box -->

<div class="container_box1"><div class="header">More Random Games</div>
<?php
$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games ORDER BY rand() LIMIT 5",false);
if ($query->num_rows == 0) {
	echo '<center><h3>We have no Newest games yet!</h3></center>';
}
if ($query->num_rows > 0) {
	$pic_settings = array('w'=>130,'h'=>100);
	while($games = $query->fetch_array(MYSQLI_ASSOC)) {
		$gameurl = prepgame($games['title']);			
		$games['description'] = stripslashes($games['description']);
		if (strlen($games['title']) > 19) {
			$title = substr($games['title'], 0, 16) . '...';
		} else {
			$title = $games['title'];
		}
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
		<div class="inner_box"><div class="images">		
		<a href="<?php echo $gurl;?>">		
		<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?> Plays" width="130" height="100" /></a>
		</div>
		<div class="text2">
		<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $title;?></a>		
		</div><div class="clear"></div></div>
	<?php	
	}
}	
$query->close();
?>               
<div class="clear"></div></div>
<!-- end of newest games box -->

</div>
<?php
$cache->write('home');
$cache->stop_caching();
echo $cache->read('home');
}
?>