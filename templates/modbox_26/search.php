<div id="center">
<div class="container_box1">
<div class="header">Search Results for <?php echo $q?></div>   
<?php
if(!ctype_digit($q)) {
	$query = yasDB_select("SELECT * FROM games WHERE title LIKE '%$q%' OR description LIKE '%$q%' ORDER BY (CASE WHEN title LIKE '%$q%' THEN 1 ELSE 0 END) DESC limit 20",false);
}
if($query->num_rows =='0') {
	echo '<div id="text_box">No results!</div>';
	echo "";
}
while($games = $query->fetch_array(MYSQLI_ASSOC)) {
	$gameurl = prepGame($games['title']);	
	$games['description'] = substr($games['description'],0,140).'...';
	$games['description'] = stripslashes($games['description']);
	$description = str_replace(array("\r\n", "\r", "\n", "'", '"'), ' ', $games['description']);
	if (strlen($games['title']) > 19) {
	$games['title'] = substr($games['title'], 0, 16) . '...';
	} else {
	$games['title'] = $games['title'];
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
		echo '<div class="inner_box"><div class="images">
		<a href="' . $gurl . '"><img align="absmiddle" src="' . resize($thumbnail, $pic_settings) . '" alt="' . $games['title'] . '" width="130" height="100" /></a></div>
		<div class="text2"><strong>
		<a href="' . $gurl . '" class="hintanchor" onMouseover="showhint(\'<b>Description</b><br>'.$description.'\', this, event, \'200px\')"> ' . $games['title'] . '</a></strong></a>
		<br>Plays: </strong>' . $games['plays'] . '</div>
		<div class="clear"></div><br style="clear: both"/></div>'; 
}
$query->close();
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
    if (count($searchArray) > 5) { $first = array_shift($searchArray); } // change 5 to the max number of game searches to track
    $newSearch = serialize($searchArray);
    fwrite($fh,$newSearch);
}
fclose($fh);
/* End save visitor game search */
?>
<div class="clear"></div>
</div>