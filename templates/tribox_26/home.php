<?php
$cache = RayCache::getInstance('home', null, array('prefix' => 'tribox_', 'path' => 'cache/', 'expire' => $setting['cachelife']));
$data = $cache->read('home');
if ($data) {
	echo $data;
} else {
	$cache->start_caching();

$pic_settings = array('w'=>80,'h'=>80);
?>
<!-- begin center -->
<div id="center">
<div id="home_main_header"><a href="<?php echo $setting['siteurl'];?>"><?php echo $setting['sitename'];?>    Play free online games to the max!</a></div>   
<div id="home_main_box">
<?php
$select_cats = yasDB_select("SELECT * FROM categories WHERE active='yes'AND home='yes' ORDER BY `order` DESC",false);
$gamesort = $setting['gamesort'];   
$numbgames = 3;
while($cats = $select_cats->fetch_array(MYSQLI_ASSOC)){	
	$catid = $cats['id'];
	if ($gamesort=='popular') {
		$query = yasDB_select("SELECT * FROM games WHERE category=".$catid." ORDER BY plays DESC LIMIT ".$numbgames."",false);
	}
	elseif ($gamesort=='newest') {
		$query = yasDB_select("SELECT * FROM games WHERE category=".$catid." ORDER BY id DESC LIMIT ".$numbgames."",false);
	} 
	elseif ($gamesort=='random') {
      $query = yasDB_select("SELECT * FROM games WHERE category=".$catid." ORDER BY md5(rand()) LIMIT ".$numbgames."",false);
	}
	elseif ($gamesort=='rated') {
      $query = yasDB_select("SELECT * FROM games WHERE category=".$catid." ORDER BY id DESC LIMIT ".$numbgames."",false);
	}
	if ($query->num_rows == 0) continue;
	// begin home_main_wrap
	echo '<div class="home_main_wrap">';
		// begin home_list_column
		echo '<div class="home_list_column">';
			// begin home_nav_image
			echo '<div class="home_nav_image">';			
				echo '<h2>';
				if ($setting['seo'] == 'yes') {
					echo '<a href="' . $setting['siteurl'] . 'category/' . $cats['id'] .'/1.html">' . $cats['name'] . '</a></h2>';
				}
				elseif ($setting['seo'] == 'no') {
					echo '<a href="' . $setting['siteurl'] . 'index.php?act=cat&id=' . $cats['id'] .'">' . $cats['name'] . '</a></h2>';
				}				
			echo '</div>';
			// end home_nav_image
			$num = 0;
			while($games = $query->fetch_array(MYSQLI_ASSOC)){	
				$gameurl = prepgame($games['title']);
				if($num == 4){
					echo '';
					$num = 0;
				}
				$hinttext = '<h2 class=&quot;title&quot;>'.$games['title'].'</h2>'.$games['description'];
				$hinttext = str_replace(array("\r\n", "\r", "\n"), '<br/>', $hinttext);
				$hinttext = str_replace(array('"',"'"),array('&quot;','&#146;'),$hinttext);
				if (file_exists($games['thumbnail'])) {
					$thumbnail = $games['thumbnail'];
					$thumbnail = urldecode($thumbnail);
				} else {
					$thumbnail = $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/nopic.jpg';
				}
				if ($setting['seo']=='yes') {
				    // begin main_game_holder
                    echo '<div class="main_game_holder">';
					echo '<a href="' . $setting['siteurl'] . 'game/' . $games['id'] . '/' . $gameurl . '.html' . '">';
					echo '<img align="absmiddle" src="' . resize($thumbnail, $pic_settings) . '" width="80" height="80" /></a>';
					// begin main_hame_holder_text
					echo '<div class="main_game_holder_text">';
				    echo '<a href="' . $setting['siteurl'] . 'game/' . $games['id'] . '/' . $gameurl . '.html' . '" class="hintanchor" onMouseover="showhint(\''.$hinttext.'\', this, event, \'200px\')">' . $games['title'] . '</a>';
					echo '</div>';
					// end main_gain_holder_text
					// begin play button
					echo '<div class="play"><a href="' . $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'] . '"><img src="' . $setting['siteurl'] . 'templates/' . $setting['theme'] . '/skins/'.$setting['skin'].'/images/buttons/play.png" width="40" height="26" alt="Playing:'.$row['file'].'" /></a></div>';
					// end play button
					echo '</div>';
					// end main_game_holder
				} elseif ($setting['seo']=='no') {
					// begin main_game_holder
					echo '<div class="main_game_holder">';					
					echo '<a href="' . $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'] . '">';
					echo '<img align="absmiddle" src="' . resize($thumbnail, $pic_settings) . '" width="80" height="80" /></a>';
					// begin main_hame_holder_text
					echo '<div class="main_game_holder_text">';					
                    echo '<a href="' . $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'] . '" class="hintanchor" onMouseover="showhint('.$hinttext.', this, event, \'200px\')"> ' . $games['title'] . '</a>';
					echo '</div>';			   
					// end main_gain_holder_text
					// begin play button
					echo '<div class="play"><a href="' . $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'] . '"><img src="' . $setting['siteurl'] . 'templates/' . $setting['theme'] . '/skins/'.$setting['skin'].'/images/buttons/play.png" width="40" height="26" alt="Playing:'.$row['file'].'" /></a></div>';
					// end play button
					echo '</div>';
					// end main_game_holder    
				}
				$num++;
			}	
	echo '</div>';
	// end home_list_column	
	echo '</div>';
	// end home_main_wrap
}
$select_cats->close();
?><div class="clear"></div></div>
<?php
$cache->write('home');
$cache->stop_caching();
echo $cache->read('home');
}
?>