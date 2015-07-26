<div id="center">
<div class="container_box1">
<div id="headergames2">Search Results for <?php echo $q?></div>   
<?php
if(!ctype_digit($q)) {
	$query = yasDB_select("SELECT * FROM games WHERE title LIKE '%$q%' OR description LIKE '%$q%' OR keywords LIKE '%$q%' ORDER BY (CASE WHEN title LIKE '%$q%' THEN 1 ELSE 0 END) DESC limit 20",false);
}
if($query->num_rows =='0') {
	echo '<center><h3>No results!</h3></center>';
	echo "";
}
while($games = $query->fetch_array(MYSQLI_ASSOC)) {
	$gameurl = prepgame($games['title']);	
	$games['description'] = stripslashes($games['description']);
	if (strlen($games['title']) > 19) {
		$title = substr($games['title'], 0, 16) . '...';
	} else {
		$title = $games['title'];
	}
	if (file_exists($games['thumbnail'])) {
		$thumbnail = $games['thumbnail'];
		$thumbnail = urldecode($thumbnail);
	} else {
		$thumbnail = $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/nopic.jpg';
	}
	$pic_settings = array('w'=>130,'h'=>100);
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
			<li class="even"><div class="images">		
			<a href="<?php echo $gurl;?>">		
			<img align="absmiddle" src="<?php echo resize($thumbnail, $pic_settings);?>" title="<?php echo $title;?> - <?php echo $games['plays'];?> Plays" width="130" height="100" /></a>
			</div></li>
			<li class="title">
			<a href="<?php echo $gurl;?>" class="hintanchor" onMouseover="showhint('<?php echo $hinttext; ?>', this, event, '200px')"><?php echo $title;?></a>		
			</li>
			</ul>
			</div>
<?php		
}
$query->close();
?>
<div class="clear"></div>
</div>
<?php
/* The following code saves a visitors game search to be displayed with the search box or elsewhere */
$filename = $setting['sitepath']. "/searches.txt";
if(file_exists($filename)) {
    if (filesize($filename) > 0) { 
        $fh = fopen($filename, "r+");
        $searchArray = array();
        $searches = fread($fh, filesize($filename));
        if ($searches) { 
            $searchArray = unserialize($searches);
            if (!in_array($q, $searchArray)) {
                ftruncate($fh, rand(1, filesize($filename)));
                rewind($fh);
            }
        }
    } else {
        $fh = fopen($filename, "w");
    }
} else {
    $fh = fopen($filename, "w+");
}
if (!in_array($q, $searchArray)) { 
    $searchArray[] = $q;
    if (count($searchArray) > 100) { $first = array_shift($searchArray); } // change 5 to the max number of game searches to track
    $newSearch = serialize($searchArray);
    fwrite($fh,$newSearch);
}
fclose($fh);
/* End save visitor game search */
?>