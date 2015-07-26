<?php
include_once($setting['sitepath'].'/includes/pagination.class.php');
$pic_settings = array('w'=>125,'h'=>80);
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$cache = RayCache::getInstance($cat_data['name'].$page, null, array('prefix' => $setting['theme'].'-'.$setting['skin'].'_', 'path' => 'cache/', 'expire' => $setting['cachelife']));
$data = $cache->read('home');
if ($data) {	
	echo $data;
} else {	
	$cache->start_caching();?><div id="center">
	<?php	
	$runOnce = false;
	function echoOnce() {  
		global $runOnce; 
		if(!$runOnce) { 
			$runOnce = true; return "<b>Sub categories:</b><br><div class='catpages'>";
		}
	}
	?>
	<!-- AJ-BreadCrumb Start -->
	<ul id="breadcrumb">
	<!-- home-nav -->
	<li><a href="<?php echo $setting['siteurl'];?>" onMouseover="showhint('<b><?php echo $setting['sitename'];?></b>',this, event, 'auto')">Home</a></li>
	<!-- home-nav-end -->
	<!-- start: cat-nav -->
	<li><?php echo $cat_data ['name'];?></li>
	</ul>
	<!-- AJ-BreadCrumb-End -->
	<div id="cat_main_box">
	<?php	
	$query = yasDB_select("SELECT * FROM categories WHERE active='yes' AND parent='no' ORDER BY `order` desc");
	while($row = $query->fetch_array(MYSQLI_ASSOC)) {
		if ($id = $row['pid']) {
			echo echoOnce();
			if ($setting['seo']=='yes') {
				echo '<a href="' . $setting['siteurl'] . 'category/' . $row['id'] .'/1.html">' . $row['name'] . '</a>&nbsp;|&nbsp;';
			}
			else {	
				echo '<a href="' . $setting['siteurl'] . 'index.php?act=cat&id=' . $row['id'] .'">' . $row['name'] . '</a>&nbsp;|&nbsp;';
			}	
		}  	
	}	
	$count = yasDB_select("SELECT count(id) AS count FROM `games` where category = " . yasDB_clean($_GET['id']));
	$total = $count->fetch_array(MYSQLI_ASSOC);
	if($total['count'] < 1) {
		echo '<br/><center><span style="font-size:200%;">There are no games in this category yet.</span></center>';
	}
	else {	
		$pageurl = new pagination($total['count'], $setting['seo'], '', $setting['gperpage'], 3);
		$query = yasDB_select("SELECT * FROM `games` where category = " . yasDB_clean($_GET['id']) . " order by `id` desc LIMIT " . $pageurl->start . ", " . $pageurl->limit);
		while($games = $query->fetch_array(MYSQLI_ASSOC)) {
			$gameurl = prepgame($games['title']);	
			$games['description'] = substr($games['description'],0,140).'...';
			$games['description'] = stripslashes($games['description']);
			$description = str_replace(array("\r\n", "\r", "\n", "'", '"'), ' ', $games['description']);
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
			<div class="cat_box"><div class="cat_gamepic">
			<a href="<?php echo $gurl;?>" title="<?php echo $games['title'];?> - <?php echo $games['plays'];?> Plays">
			<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" width="80" height="80" /></a></div>
			<div class="cat_game_text">
			<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<b>Description</b><br><?php echo $description;?>', this, event, '200px')">
			<strong><?php echo $games['title'];?></strong></a></div>
			<div class="game_stats"><strong>Plays: </strong><?php echo $games['plays'];?></div>
			<br style="clear: both"/></div> 
		<?php }
		$query->close();
		?>
		<br style="clear: both"/>
		<div id="cat_page_box"><?php echo $pageurl->showPagination();
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