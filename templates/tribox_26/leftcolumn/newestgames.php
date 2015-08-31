<?php
$cache = RayCache::getInstance('newest', null, array('prefix' => 'tribox_', 'path' => 'cache/', 'expire' => $setting['cachelife']));
$data = $cache->read('newest');
if ($data) {
	echo $data;
} else {
	$cache->start_caching();
?>
<div class="navh3">Newest Games</div>
<div style="float:left;width:174px;height:auto;margin:0 0; padding:0 0;">
<?php
$query = yasDB_select("SELECT id, title, thumbnail FROM games ORDER BY id DESC LIMIT 8"); 
if ($query->num_rows == 0) {
    echo '<center><h4>We have no rated games yet!</h4></center>';
} else {
    $pic_settings = array('w'=>35,'h'=>35);
    while($games = $query->fetch_array(MYSQLI_ASSOC)) {
        $gameurl = prepgame($games['title']);            
        $games['description'] = stripslashes($games['description']);
        if (strlen($games['title']) > 25) {
            $title = substr($games['title'], 0, 22) . '...';
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
        ?>
        <div style="float:left;margin:0 0;">
            <a href="<?php echo $gurl;?>"><img src="<?php echo resize($thumbnail, $pic_settings);?>" style="margin:4px;" alt="" width="35" height="35" border="0" align="left" class="hintanchor" onMouseover="showhint('<b><?php echo addslashes($games['title']); ?></b>', this, event, 'auto')"></a>
        </div> <?php
    }
}
?>
</div>
<div style="clear:both;"></div>
<?php
$cache->write('newest');
$cache->stop_caching();
echo $cache->read('newest');
}
?>