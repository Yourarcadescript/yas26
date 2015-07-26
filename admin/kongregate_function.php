<?php
function getTime() {
	$a = explode (' ',microtime());
	return(double) $a[0] + $a[1];
}
function GetFileExtension($filepath) {
    preg_match('/[^?]*/', $filepath, $matches);
    $string = $matches[0];
    $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
    // check if there is any extension
    if(count($pattern) == 1) {
        echo 'No File Extension Present '.$filepath;
        exit;
    }
       
    if(count($pattern) > 1) {
        $filenamepart = $pattern[count($pattern)-1][0];
        preg_match('/[^?]*/', $filenamepart, $matches);
        return $matches[0];
    }
}
function GetFileName($filepath) {
    preg_match('/[^?]*/', $filepath, $matches);
    $string = $matches[0];
    //split the string by the literal dot in the filename
    $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
    //get the last dot position
    $lastdot = $pattern[count($pattern)-1][1];
    //now extract the filename using the basename function
    $filename = basename(substr($string, 0, $lastdot-1));
    //return the filename part
    return $filename;
} 
// cURL function to download and save a file
function download_file($url, $local_file) { // $url is the file we are getting, full address.....$local_file is the file to save to
	set_time_limit(0);
	//ini_set('display_errors',true);

	$fp = fopen ($local_file, 'wb+');//This is the file where we save the information
	$ch = curl_init($url);//Here is the file we are downloading
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
}
//  Send in Mochi category and output YAS category id
function get_content_of_url($url){
    $ohyeah = curl_init();
    curl_setopt($ohyeah, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ohyeah, CURLOPT_URL, $url);
    curl_setopt($ohyeah, CURLOPT_FOLLOWLOCATION, true);
	$data = curl_exec($ohyeah);
    curl_close($ohyeah);
    return $data;
 }
function convert_category($category) {
	global $mysqli;
	if (strtolower($category) == "puzzle") $category = "Puzzles";
	$sql = 'SELECT `'.$category.'` FROM mochicatlookup WHERE id = 1';
	$query = yasDB_select($sql,false);  // Get corresponding category from lookup table
	$newcat = $query->fetch_array(MYSQLI_NUM);
	$query->close();
		// Put all current YAS categories into an array
    $allcats = array();
	$query = yasDB_select("SELECT `name` FROM `categories`",false);
	while ($cats = $query->fetch_array(MYSQLI_ASSOC)) {
		$allcats[] = $cats['name'];
	}
	$query->close();
	if (!in_array($newcat[0], $allcats)) {			// Check to see if category from lookup table still exits in YAS category table 
		$cat = 'Other';							// If it doesn't, set the new category to 'Other' so games are accessable to users
	}
	else {
		$cat = $newcat[0];
	}
	unset($allcats);
	$query = yasDB_select("SELECT id, name FROM categories WHERE name = '$cat'",false);
	$catid = $query->fetch_array(MYSQLI_ASSOC);
	$query->close();
	$query = yasDB_select("SELECT active FROM categories WHERE name = '$cat'",false);	 
	$active = $query->fetch_array(MYSQLI_NUM);
	$query->close();
	if ($active[0] == 'no') {
		yasDB_update("UPDATE categories SET active = 'yes' WHERE id = '{$catid[0]}'",false);	//If the category is not active set it to active
	}
	return $catid;
}
function get_kongregate_feed() {
	$Start = getTime();
	$mem_limit = substr(ini_get("memory_limit"), 0, -1);	
	global $mysqli;
	global $setting;
	set_time_limit(0);
	@ini_set("max_execution_time", 1000);
	@ini_set("default_socket_timeout", 240);	   
	$query = yasDB_select("SELECT `kong_id` FROM kongregate",false);
	$tags = array();
	$i=0;
	$no_errors = 0;
	$new_games = 0;
	// create array of game tags for duplicate checking
	while ($alltags = $query->fetch_array(MYSQLI_ASSOC)) {
		$tags[$i] = $alltags['kong_id'];
		$i++;
	}
	unset($alltags);
	$query->close();	
	$feedUrl = 'http://www.kongregate.com/games_for_your_site.xml';
	$xmlfile = get_content_of_url($feedUrl);
	$data = new SimpleXMLElement($xmlfile);	
	foreach ($data->game as $game) {
		if ($game->id == NULL) break;
		if (!in_array($game->id,$tags)) {
			$gamename = yasDB_clean($game->title);
			$gameid = intval($game->id);
			$gameurl = yasDB_clean($game->flash_file);
			$gamewidth = intval($game->width);
			$gameheight = intval($game->height);
			$gamedesc = yasDB_clean($game->description);
			$thumburl = yasDB_clean($game->thumbnail);
			$gamecat = yasDB_clean($game->category);
			$rating = $game->rating;
			settype($rating, "float");
			$author = yasDB_clean($game->developer_name);
			$sql = "INSERT INTO kongregate (`id`, `kong_id`, `title`, `file`, `thumbnail`, `width`, `height`, `category`, `description`, `rating`, `developer`, `installed`, `hidden`) 
			VALUES (NULL, $gameid, '$gamename', '$gameurl', '$thumburl', $gamewidth, $gameheight, '$gamecat', '$gamedesc', '$rating', '$author', 0, 0)";
			$return = yasDB_insert($sql,false);
			if ($return === false) {
				$no_errors++;
			} else {
				$new_games++;
			}
		}
	}
	unset($game);
	echo 'Available script memory: '. $mem_limit. ' MB<br/>';
	echo 'Peak memory usage: '. (memory_get_peak_usage(true)/1024)/1024 . ' MB<br/>';
	$End = getTime();
	echo "Time taken = ".number_format(($End - $Start),2)." secs<br/>";
	echo "Errors encountered: ".$no_errors."<br/>";
	echo "New games added: ".$new_games."<br/>Finished!";
	return true;
}
function install_konggame($gameid) {
	global $mysqli;
	$query = yasDB_select("SELECT * FROM `kongregate` WHERE `id` = '$gameid'", false);
	$result = $query->fetch_array(MYSQLI_ASSOC);
	$categories = array('Action' => 2, //Action
						'Adventure & RPG' => 3, //Adventure
						'Strategy & Defense' => 10, //Strategy
						'Multiplayer' => 2,  //Action
						'Puzzle' => 1,  //Puzzle
						'Rhythm' => 7,  //Other
						'Shooter' => 5,  //Shooter
						'Sports & Racing' => 4,  //Sports
						'Music & More' => 7);  //Other				
	// Download and save game file	
	if($result['file']) {
		$g_url = str_replace("..", "", $result['file']);
		$game_file = basename($g_url);
		$game_file = "kong_" . preg_replace('#\W#', '', $result['title']) . "." . GetFileExtension($result['file']);
		$game_url = '../swf/' . $game_file;		
		download_file($g_url, $game_url);
	} else {
		return false;
	}
	// Download and save thumbnail pic
	if($result['thumbnail']) {
		$t_url = str_replace("..", "", $result['thumbnail']);
		$smallthumb = "kong_" . preg_replace('#\W#', '', $result['title']) . "." . GetFileExtension($result['thumbnail']);
		$thumb = '../img/' . $smallthumb;			
		download_file($t_url, $thumb);
	}
	$desc = yasDB_clean($result['description']);        // Prep for DB insert
	$gamename = yasDB_clean($result['title']);
	$gamefile = yasDB_clean(str_replace("../", "", $game_url));
	$gamethumb = yasDB_clean(str_replace("../", "", $thumb));
	$height = $result['height'];
	$width = $result['width'];
	$c = $result['category'];
	$category = $categories[$c];
	$query->close();
	$query = yasDB_insert("INSERT INTO `games` (`id`, `title`, `description`, `instructions`, `keywords`, `file`, `height`, `width`, `category`, `plays`, `code`, `type`, `source`, `sourceid`, `thumbnail`, `thumbnail_200`, `screen1`, `screen2`, `screen3`, `screen4`) VALUES (NULL, '$gamename', '$desc', '', '', '$gamefile', $height, $width, $category, 0, '', 'SWF', 'KONGREGATE', $gameid, '$gamethumb', '', '', '','','')",false);
	if (!$query) { 
		echo 'Error updating Games database';
		return false;
	}
	$query = yasDB_update("UPDATE kongregate SET installed = 1 WHERE id = {$result['id']}", false);
	if (!query) {
		echo 'Error updating kongergate database';
		return false;
	}
	return true; 													
}