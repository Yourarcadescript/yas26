<?php
$cache = RayCache::getInstance('home', null, array('prefix' => $setting['theme'], 'path' => 'cache/', 'expire' => $setting['cachelife']));
$data = $cache->read('home');
if ($data) {
	echo $data;
} else {
	$cache->start_caching();
	$pic_settings = array('w'=>130,'h'=>100);
	?>
	<!-- begin center -->
	<div id="center">
	 <div id="home_container">
	<!-- start of ads_box box -->
	<div class="container_box1" style="height:100px;width:100px;float:left;margin-left:12px;padding:4px;"><?php echo ad("8"); ?></div>
	<div class="container_box1" style="height:100px;width:468px;float:left;margin-left:10px;padding:2px 5px 4px 5px;">
	<div id="headergames2" style="width:468px;margin:0 0 10px 0;padding:0;"><span style="padding-left:40px;">Advertisement</span></div>
		<?php echo ad("4"); ?>
	</div>
	<div class="container_box1" style="height:100px;width:100px;float:left;margin-left:12px;padding:4px"><?php echo ad("9"); ?></div><div style="clear:both"></div>
	<!-- end of ads box -->
	 <!-- start of featured box -->
	<div class="container_box1"><div id="headergames2">Featured Games</div>
	<?php
		$query = yasDB_select("SELECT `id` , `title` , `thumbnail` , `description` , `plays` FROM `games` INNER JOIN `featuredgames` ON `games`.`id` = `featuredgames`.`gameid` ORDER BY rand()");
		$pic_settings = array('w'=>130,'h'=>100);
		if ($query->num_rows == 0) {
			$query->close();
			$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games ORDER BY rand() LIMIT 5");
			if ($query->num_rows == 0) {
				echo '<center><h3>We have no Featured Games games yet!</h3></center>';
			}
		}
		if ($query->num_rows > 0) {
			while($games = $query->fetch_array(MYSQLI_ASSOC)){	
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
				<div class="gameslinks">
				<ul>
				<li class="even">		
				<a href="<?php echo $gurl;?>">		
				<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?> Plays" width="130" height="100" /></a>
				</li>
				<li class="title">
				<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $games['title'];?></a>		
				</li>
				</ul>
				</div>
	<?php 
				}
			}
	$query->close();
	?>

	<div class="clear"></div></div>
	<!-- end of featured box -->

	<!-- start of toprated -->

	<div class="container_box1"><div id="headergames2">Top Rated</div>
	<?php
	$query = yasDB_select("SELECT `games`.`id`, `title`, `thumbnail`, `description`, `plays` FROM `games` INNER JOIN `ratingsbar` ON `games`.`id` = `ratingsbar`.`id` WHERE `ratingsbar`.`total_votes` > 0 ORDER BY `ratingsbar`.`total_value`/`ratingsbar`.`total_votes` DESC, `ratingsbar`.`total_votes` DESC LIMIT 10",false); 
	if ($query->num_rows == 0) {
		$query->close();
		$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games ORDER BY rand() LIMIT 10");
		if ($query->num_rows == 0) {
			echo '<center><h3>We have no Top Rated games yet!</h3></center>';
		}
	}
	if ($query->num_rows > 0) {
		$pic_settings = array('w'=>130,'h'=>100);
		while($games = $query->fetch_array(MYSQLI_ASSOC)) {
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
				<div class="gameslinks">
				<ul>
				<li class="even">		
				<a href="<?php echo $gurl;?>">		
				<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?> Plays" width="130" height="100" /></a>
				</li>
				<li class="title">
				<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $games['title'];?></a>			
				</li>
				</ul>
				</div>
		<?php	
		}
	}
	
	?>
	<div class="clear"></div></div>
	<!-- end of toprated -->
	<!-- start of Category Games -->
	<div class="container_box1"><div id="headergames2">Category Games</div>
	<?php
		$query1 = yasDB_select("SELECT id FROM categories WHERE active='yes' ORDER BY id LIMIT 10");
		while($category = $query1->fetch_array(MYSQLI_ASSOC)) {
			if ($query1->num_rows == 0) {
				echo '<center><h3>We have no Select Category games yet!</h3></center>';
				break;
			}
			$query->close();
			$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games WHERE category={$category['id']} ORDER BY rand() LIMIT 1",true);
			
			$pic_settings = array('w'=>130,'h'=>100);
			while($games = $query->fetch_array(MYSQLI_ASSOC)) {
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
				<div class="gameslinks">
				<ul>
				<li class="even">		
				<a href="<?php echo $gurl;?>">		
				<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?> Plays" width="130" height="100" /></a>
				</li>
				<li class="title">
				<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $games['title'];?></a>			
				</li>
				</ul>
				</div>
				<?php 
			}
		}
	?>
	<div class="clear"></div></div>
	<!-- end of Category Games -->
	</div>
	<?php
	$cache->write('home');
	$cache->stop_caching();
	echo $cache->read('home');
}
?>