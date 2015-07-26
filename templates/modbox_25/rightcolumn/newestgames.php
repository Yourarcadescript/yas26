<?php
$cache = RayCache::getInstance('newest', null, array('prefix' => $setting['theme'].'_', 'path' => 'cache/', 'expire' => $setting['cachelife']));
$data = $cache->read('newest');
if ($data) {
	echo $data;
} else {
	$cache->start_caching();
?>
<div class="nav_box"><div class="nav">Newest Games</div>
<div class="nav_box2">
<div class="links1">
<?php
$query = yasDB_select("SELECT id, title FROM games ORDER BY id DESC LIMIT 5");
		while($games = $query->fetch_array(MYSQLI_ASSOC)) {		
			$gameurl = prepgame($games['title']);				
			if (strlen($games['title']) > 19) {
			$games['title'] = substr($games['title'], 0, 16) . '...';
			} else {
			$games['title'] = $games['title'];
			}		
			if ($setting['seo']=='yes') {
			$gurl = $setting['siteurl'] . 'game/' . $games['id'] . '/' . $gameurl . '.html';
			} else {
			$gurl = $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'];
			}
			?>
			<a href="<?php echo $gurl;?>"><?php echo $games['title'];?></a>
<?php }
$query->close();
?>
</div></div></div>
<?php
$cache->write('newest');
$cache->stop_caching();
echo $cache->read('newest');
}
?>