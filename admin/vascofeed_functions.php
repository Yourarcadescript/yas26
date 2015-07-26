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
function get_content_of_url($url){
    $ohyeah = curl_init();
    curl_setopt($ohyeah, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ohyeah, CURLOPT_URL, $url);
    curl_setopt($ohyeah, CURLOPT_FOLLOWLOCATION, true);
	$data = curl_exec($ohyeah);
    curl_close($ohyeah);
    return $data;
 }
function download_file($url, $local_file) { // $url is the file we are getting, full address.....$local_file is the file to save to
	set_time_limit(0);
	ini_set('display_errors',true);

	$fp = fopen ($local_file, 'wb+');//This is the file where we save the information
	$ch = curl_init($url);//Here is the file we are downloading
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
}
function get_vascogames_feed() {
	$Start = getTime();
	$mem_limit = substr(ini_get("memory_limit"), 0, -1);	
	global $mysqli;
	global $setting;
	set_time_limit(0);
	@ini_set("max_execution_time", 1000);
	@ini_set("default_socket_timeout", 240);	   
	$query = yasDB_select("SELECT `vasco_id` FROM vascogames",false);
	$tags = array();
	$i=0;
	$no_errors = 0;
	$new_games = 0;
	// create array of game tags for duplicate checking
	while ($alltags = $query->fetch_array(MYSQLI_ASSOC)) {
		$tags[$i] = $alltags['vasco_id'];
		$i++;
	}
	unset($alltags);
	$query->close();	
	$feedUrl = 'http://4v4.com/xml.php?format=xml';
	$xmlfile = get_content_of_url($feedUrl);
	$xml = new SimpleXMLElement($xmlfile);		
	foreach ($xml->entry as $game) {
		$gameid = intval(basename((string)$game->player->url, '.swf'));
		if (!in_array($gameid,$tags)) {
			$gamename = yasDB_clean((string)$game->title);
			$gamedesc = yasDB_clean((string)$game->children('http://search.yahoo.com/mrss/')->description);
			$gamecat = yasDB_clean((string)$game->children('http://search.yahoo.com/mrss/')->category);
			$gameurl = yasDB_clean((string)$game->player->url);
			$gamewidth = intval($game->player->width);
			$gameheight = intval($game->player->height);
			$thumburl1 = yasDB_clean((string)$game->thumbnail[0]->url);
			$thumburl2 = yasDB_clean((string)$game->thumbnail[1]->url);
			$sql = "INSERT INTO vascogames (`id`, `vasco_id`, `title`, `file`, `thumbnail`, `width`, `height`, `category`, `description`, `installed`, `hidden`) 
			VALUES (NULL, $gameid, '$gamename', '$gameurl', '$thumburl1', $gamewidth, $gameheight, '$gamecat', '$gamedesc', 0, 0)";
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
function install_vascogame($gameid) {
	global $mysqli;
	$query = yasDB_select("SELECT * FROM `vascogames` WHERE `id` = '$gameid'", false);
	$result = $query->fetch_array(MYSQLI_ASSOC);
	$categories = array('action games' => 2, //Action
						'adventure games' => 3, //Adventure
						'strategy games' => 10, //Strategy
						'skill games' => 10,  //Strategy
						'puzzle games' => 1,  //Puzzle
						'arcade games' => 9,  //Arcade
						'shooting games' => 5,  //Shooter
						'sports games' => 4,  //Sports
						'misc games' => 7,	//Other
						'car games' => 4); //Strategy
	// Download and save game file	
	if($result['file']) {
		$g_url = str_replace("..", "", $result['file']);
		$game_file = basename($g_url);
		$game_file = "vasco_" . preg_replace('#\W#', '', $result['title']) . "." . GetFileExtension($result['file']);
		$game_url = '../swf/' . $game_file;		
		download_file($g_url, $game_url);
	} else {
		return false;
	}
	// Download and save thumbnail pic
	if($result['thumbnail']) {
		$t_url = str_replace("..", "", $result['thumbnail']);
		$smallthumb = "vasco_" . preg_replace('#\W#', '', $result['title']) . "." . GetFileExtension($result['thumbnail']);
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
	if ($category == null || $category == '' || $category == 0) {
		$category == 7;
	}
	$query = yasDB_insert("INSERT INTO `games` (`id`, `title`, `description`, `instructions`, `keywords`, `file`, `height`, `width`, `category`, `plays`, `code`, `type`, `source`, `sourceid`, `thumbnail`, `thumbnail_200`, `screen1`, `screen2`, `screen3`, `screen4`) VALUES (NULL, '$gamename', '$desc', '', '', '$gamefile', $height, $width, $category, 0, '', 'SWF', 'VASCOGAMES', $gameid, '$gamethumb', '', '', '','','')",false);
	if (!$query) { 
		echo 'Error updating Games database';
		return false;
	}
	$query = yasDB_update("UPDATE `vascogames` SET `installed` = 1 WHERE `id` = {$result['id']}", false);
	if (!query) {
		echo 'Error updating vascogames database';
		return false;
	}
	return true; 													
}