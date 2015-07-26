<div class="nav_box"><div class="nav">Random Games</div>
<div class="nav_box2">
<div class="links1">	
<?php
$query = yasDB_select("SELECT id, title FROM games ORDER BY RAND() DESC LIMIT 5",false);
		while($games = $query->fetch_array(MYSQLI_ASSOC)) {		
			$gameurl = prepgame($games['title']);						
			if ($setting['seo']=='yes') {
			$gurl = $setting['siteurl'] . 'game/' . $games['id'] . '/' . $gameurl . '.html';
			} else {
			$gurl = $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'];
			}
			?>
			<a href="<?php echo $gurl;?>"><?php echo $games['title'];?></a>
<?php			
}	
$query->close();
?>
</div></div></div>