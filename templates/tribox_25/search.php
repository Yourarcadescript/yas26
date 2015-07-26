<div id="center">
<div id="search_main_header">Search Results for <?php echo $q?></div>   
<div id="search_main_box">
<?php
if(!ctype_digit($q)) {
    $query = yasDB_select("SELECT * FROM games WHERE title LIKE '%$q%' OR description LIKE '%$q%' ORDER BY (CASE WHEN title LIKE '%$q%' THEN 1 ELSE 0 END) DESC limit 20",false);
}
if($query->num_rows =='0') {
    echo '<div id="text_box">No results!</div>';
    echo "";
}
while($games = $query->fetch_array(MYSQLI_ASSOC)) {
    $gameurl = prepgame($games['title']);    
    $games['description'] = substr($games['description'],0,140).'...';
    $games['description'] = stripslashes($games['description']);
    $description = str_replace(array("\r\n", "\r", "\n", "'", '"'), ' ', $games['description']);
    if (file_exists($games['thumbnail'])) {
        $thumbnail = $games['thumbnail'];
        $thumbnail = urldecode($thumbnail);
    } else {
        $thumbnail = $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/nopic.jpg';
    }
    $pic_settings = array('w'=>80,'h'=>80);
    if ($setting['seo']=='yes') {
    $gurl = $setting['siteurl'] . 'game/' . $games['id'] . '/' . $gameurl . '.html';
    } else {
    $gurl = $setting['siteurl'] . 'index.php?act=game&id=' . $games['id'];
    }
        echo '<div class="search_box"><div class="search_gamepic">
        <a href="' . $gurl . '"><img align="absmiddle" src="' . resize($thumbnail, $pic_settings) . '" alt="' . $games['title'] . '" width="80" height="80" /></div>
        <div class="search_title"><strong>
        <a href="' . $gurl . '" class="hintanchor" onMouseover="showhint(\'<b>Description</b><br>'.$description.'\', this, event, \'200px\')"> ' . $games['title'] . '</a></strong></a></div>
        <div class="search_game_stats"><strong>Plays: </strong>' . $games['plays'] . '</div><br style="clear: both"/></div>'; 
}
$query->close();
?>
<div class="clear"></div></div>
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
    if (count($searchArray) > 10) { $first = array_shift($searchArray); } // change 10 to the max number of game searches to track
    $newSearch = serialize($searchArray);
    fwrite($fh,$newSearch);
}
fclose($fh);
/* End save visitor game search */
?>