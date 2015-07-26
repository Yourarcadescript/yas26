<?php
include_once($setting['sitepath'].'/includes/pagination.class.php');
$pic_settings = array('w'=>130,'h'=>100);
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$cache = RayCache::getInstance($cat_data['name'].$page, null, array('prefix' => $setting['theme'].'-'.$setting['skin'].'_', 'path' => 'cache/', 'expire' => $setting['cachelife']));
$data = $cache->read('home');
if ($data) {	
	echo $data;
} else {	
	$cache->start_caching();
?>
<div id="center">
<div class="container_box1">
<!-- AJ-BreadCrumb Start -->
<ul id="breadcrumb">
<!-- home-nav -->
<li><a href="<?php echo $setting['siteurl'];?>" onMouseover="showhint('<b><?php echo $setting['sitename'];?></b>',this, event, 'auto')">Home</a></li>
<!-- home-nav-end -->
<!-- start: cat-nav -->
<li><?php echo $cat_data ['name'];?></li>
</ul>
<!-- AJ-BreadCrumb-End -->
<?php	
	$count = yasDB_select("SELECT count(id) AS count FROM `games` where category = " . $id);
	$total = $count->fetch_array(MYSQLI_ASSOC);
	if($total['count'] < 1) {
		echo '<br/><center><span style="font-size:200%;">There are no games in this category yet.</span></center>';
	}
	else {	
		$pageurl = new pagination($total['count'], $setting['seo'], '', $setting['gperpage'], 3);
		$query = yasDB_select("SELECT * FROM `games` where category = " . $id . " order by `id` desc LIMIT " . $pageurl->start . ", " . $pageurl->limit);
		while($games = $query->fetch_array(MYSQLI_ASSOC)) {
			$gameurl = prepgame($games['title']);	
			$games['description'] = stripslashes($games['description']);
			if (strlen($games['title']) > 19) {
				$games['title'] = substr($games['title'], 0, 16) . '...';
		    } else {
				$games['title'] = $games['title'];
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
			<div class="gameslinks">
			<ul>
			<li class="even">		
			<a href="<?php echo $gurl;?>">		
			<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?> Plays" width="130" height="100" /></a>
			</li>
			<li class="title">
			<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $games['title'];?></a>		
			<br>Plays: <?php echo $games['plays'];?>
			</li>
			</ul>
			</div>
		<?php }
		$query->close();
		?>
		<br style="clear: both"/>
		<div id="page_box"><?php echo $pageurl->showPagination();
		?></div><?php
	}
	?>
	<div class="clear"></div>
	</div>
	<?php
	$cache->write($cat_data['name'].$page);
	$cache->stop_caching();
	echo $cache->read($cat_data['name'].$page);
}
?>