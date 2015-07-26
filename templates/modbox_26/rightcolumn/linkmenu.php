<?php
$cache = RayCache::getInstance('linkmenu', null, array('prefix' => $setting['theme'].'_', 'path' => 'cache/', 'expire' => $setting['cachelife']));
$data = $cache->read('linkmenu');
if ($data) {
	echo $data;
} else {
	$cache->start_caching();
?>
<div class="nav_box"><div class="nav">Links</div>
<div class="nav_box2">
<div class="links1">
<?php
$select_links = yasDB_select("SELECT * FROM links WHERE approved='yes' ORDER BY `in` DESC LIMIT $setting[numblinks]");
while($links = $select_links->fetch_array(MYSQLI_ASSOC)){
	echo '<a href="' . $links['url'] . '" target="_blank" title="In: ' . $links['in'] . ' Out: ' . $links['out']. '" onClick="addHit(\''.$links['id'].'\');return true;">' . $links['text'] . '</a><br>';
}
?>
<br/><a href="<?php echo $setting['siteurl'];?>index.php?act=links">More links</a>
<br><br><a href="<?php echo $setting['siteurl'];?>index.php?act=addlink">Add your link</a>
</div></div></div>
<?php
$cache->write('linkmenu');
$cache->stop_caching();
echo $cache->read('linkmenu');
}
?>