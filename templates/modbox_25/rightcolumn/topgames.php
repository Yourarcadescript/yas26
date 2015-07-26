<?php
$cache = RayCache::getInstance('top', null, array('prefix' => 'tribox_', 'path' => 'cache/', 'expire' => 60));
$data = $cache->read('top');
if ($data) {
	echo $data;
} else {
	$cache->start_caching();
?>
	<div class="navh3">Top Games</div>
<div class="links1">
<?php
$query = yasDB_select("select * from games order by plays desc limit 5");
while($row = $query->fetch_array(MYSQLI_ASSOC)) {
	$gameurl = $row['title'];
	$gameurl = str_replace(" ", "-", $gameurl);
	$gameurl = str_replace("'", "_", $gameurl);
	$gameurl = str_replace('"', "_", $gameurl);
	$gameurl = str_replace('/', "_", $gameurl);
	$gameurl = str_replace("\\", "_", $gameurl);
	$gameurl = rawurlencode($gameurl);
	if ($setting['seo']=='yes') {
		echo'<a href="' . $setting['siteurl'] . 'game/' . $row['id'] . '/' . $gameurl . '.html' . '">' . $row['title'] . '</a>';
	} else {
		echo'<a href="' . $setting['siteurl'] . 'index.php?act=game&id=' . $row['id'] . '">' . $row['title'] . '</a>';
	}
}
$query->close();
?>
</div>
<?php
$cache->write('top');
$cache->stop_caching();
echo $cache->read('top');
}
?>