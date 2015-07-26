<?php
include_once($setting['sitepath'].'/includes/pagination.class.php');
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$cache = RayCache::getInstance('newest'.$page, null, array('prefix' => $setting['theme'].'-'.$setting['skin'].'_', 'path' => 'cache/', 'expire' => $setting['cachelife']));
$data = $cache->read('home');
if ($data) {
	echo $data;
} else {
	$cache->start_caching();
$pic_settings = array('w'=>130,'h'=>100);
?>
<!-- begin center -->
<div id="center">
<div class="container_box1"><div id="headergames2">Newest Games</div>
<?php
	$query = yasDB_select("SELECT id FROM games LIMIT 1");
	if ($query->num_rows == 0) {
		echo '<center><h3>We have no Newest games yet!</h3></center>';
	} else {
		$result = yasDB_select("SELECT count(id) FROM `games` ");
		$query_data = $result->fetch_array(MYSQLI_NUM);
		$numrows = $query_data[0];
		$result->close();
		$pageurl = new pagination($numrows, $setting['seo'], 'mostplayed', 25, 3);
		$query = yasDB_select("SELECT id, title, thumbnail, description, plays FROM games ORDER BY `id` desc LIMIT " . $pageurl->start . ", " . $pageurl->limit);
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
		?>
		<div id="page_box">
		   <?php echo $pageurl->showPagination(); ?>
		</div>
		<?php
	}
	$query->close();
	?>
<div class="clear"></div></div>
<!-- end of newest -->
<?php
$cache->write('newest'.$page);
$cache->stop_caching();
echo $cache->read('newest'.$page);
}
?>