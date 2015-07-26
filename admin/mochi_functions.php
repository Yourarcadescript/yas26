<?php
function getTime() {
	$a = explode (' ',microtime());
	return(double) $a[0] + $a[1];
}

function flush2(){
    echo(str_repeat(' ',256));
    // check that buffer is actually set before flushing
    if (ob_get_length()){           
        @ob_flush();
        @flush();
        @ob_end_flush();
    }   
    @ob_start();
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
// cURL function to get file contents
function get_content_of_url($url){
    $ohyeah = curl_init();
    curl_setopt($ohyeah, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ohyeah, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ohyeah, CURLOPT_URL, $url);
    $data = curl_exec($ohyeah);
    curl_close($ohyeah);
    return $data;
 }
// cURL function to download and save a file
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
// Original PHP code by Chirp Internet: www.chirp.com.au 
function myTruncate($string, $limit, $break=" ", $pad="...") {          
    if(strlen($string) <= $limit) return $string;                     // return with no change if string is shorter than $limit   
    $string = substr($string, 0, $limit);                              // is $break present between $limit and the end of the string?
    if(false !== ($breakpoint = strrpos($string, $break))) {          // $break char is the indicator of a new part, ie a word-
        $string = substr($string, 0, $breakpoint);                     // display a maximum of $limit parts
    } 
    return $string . $pad;  
}

//  Send in Mochi category and output YAS category id
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

// For this to work, you need cURL and JSON enabled on the server--- use phpinfo() in a file and run it from the server to check your settings.					
function install_mochigame($gameid) { 
	$pre = 'mochi';
	global $mysqli;
	$query = yasDB_select("SELECT * FROM `mochigames` WHERE `id` = '$gameid'",false);
	$result = $query->fetch_array(MYSQLI_ASSOC);
	$gamename = $result['name'];
	$thumburl = $result['thumburl'];
	$thumburl_200 = $result['thumbnail_200x200'];
	$gameurl = $result['gameurl'];
	$category = explode(",", $result['categories']);   	// Remove comma delimiter and seperate categories into array in order to insert single category.
	$category = convert_category($category[0]);         // Function to convert from Mochi category to YAS category ID integer	
	$slug = str_replace(array('-','_'),'',$result['slug']);
	// Download and save game file	
	if($result['gameurl']) {
		$g_url = str_replace("..", "", $result['gameurl']);
		$game_file = $pre."_" . $slug . "." . GetFileExtension($result['gameurl']);
		$game_url = '../swf/' . $game_file;		
		download_file($g_url, $game_url);
		$gamefile = 'swf/'.$game_file;
	} else {
		return false;
	}
	// Download and save 100x100 thumbnail pic
	if($result['thumburl']) {
		$t_url = str_replace("..", "", $result['thumburl']);
		if ($type==1) {
			$g_ext = explode("?", $result['thumburl']);
			echo $g_ext[0];
			$smallthumb = $pre."_" . $slug . "." . GetFileExtension($g_ext[0]);
		} else {
			$smallthumb = $pre."_" . $slug . "." . GetFileExtension($result['thumburl']);
		}		
		$sm_thumb = '../img/' . $smallthumb;			
		download_file($t_url, $sm_thumb);
		$gamethumb = 'img/' . $smallthumb;
	
	}
	// Download and save 200x200 thumbnail pic
	if(!empty($result['thumbnail_200x200'])) {
		$t_url = str_replace("..", "", $result['thumbnail_200x200']);
		$mediumthumb = $pre."_200_" . $slug . "." . GetFileExtension($result['thumbnail_200x200']);
		$med_thumb = '../img/' . $mediumthumb;			
		download_file($t_url, $med_thumb);
		$gamethumb200 = 'img/' . $mediumthumb; 
	} else {
		$gamethumb200 = '';
	}
		
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// Remove code comment to download the game screen images - depends on availabilty in the feed //
	/////////////////////////////////////////////////////////////////////////////////////////////////
	/*
	// Download and save screen 1 pic
	if($result['screenthumburl1']) {
		$t_url = str_replace("..", "", $result['screenthumburl1']);
		$largethumb = $pre."_screen1_" . $slug . "." . GetFileExtension($result['screenthumburl1']);
		$screen1_thumb = '../img/' . $largethumb;			
		download_file($t_url, $screen1_thumb);
	}	
	// Download and save screen 2 pic
	if($result['screenthumburl2']) {
		$t_url = str_replace("..", "", $result['screenthumburl2']);
		$largethumb = $pre."_screen2_" . $slug . "." . GetFileExtension($result['screenthumburl2']);
		$screen2_thumb = '../img/' . $largethumb;			
		download_file($t_url, $screen2_thumb);
	}	
	// Download and save screen 3 pic
	if($result['screenthumburl3']) {
		$t_url = str_replace("..", "", $result['screenthumburl3']);
		$largethumb = $pre."_screen3_" . $slug . "." . GetFileExtension($result['screenthumburl3']);
		$screen3_thumb = '../img/' . $largethumb;			
		download_file($t_url, $screen3_thumb);
	}	
	// Download and save screen 4 pic
	if($result['screenthumburl2']) {
		$t_url = str_replace("..", "", $result['screenthumburl4']);
		$largethumb = $pre."_screen4_" . $slug . "." . GetFileExtension($result['screenthumburl4']);
		$screen4_thumb = '../img/' . $largethumb;			
		download_file($t_url, $screen4_thumb);
	}	
	*/	
			
	$desc = yasDB_clean($result['description']);        // Prep for DB insert
	$instructions = yasDB_clean($result['instructions']);
	$keywords = $result['keywords'];
	$keywords = yasDB_clean($keywords);
	$gamename = yasDB_clean($gamename);
	$gamefile = yasDB_clean($gamefile);
	$gamethumb = yasDB_clean($gamethumb);
	$height = $result['height'];
	$width = $result['width'];
	$query->close();
	
	$query = yasDB_insert("INSERT INTO `games` (`id`, `title`, `description`, `instructions`, `keywords`, `file`, `height`, `width`, `category`, `plays`, `code`, `type`, `source`, `sourceid`, `thumbnail`, `thumbnail_200`, `screen1`, `screen2`, `screen3`, `screen4`) VALUES (NULL, '$gamename', '$desc', '$instructions', '$keywords', '$gamefile', '$height', '$width', '{$category['id']}', 0, '', 'SWF', 'MOCHI', $gameid, '$gamethumb', '$gamethumb200', '$screen1_thumb', '$screen2_thumb','$screen3_thumb','$screen4_thumb')",false);
	if (!$query) { 
		echo 'Error updating Games database';
		return false;
	}
	$query = yasDB_update("UPDATE `mochigames` SET isinstalled = 1 WHERE id = '{$result['id']}'",false);
	if (!query) {
		echo 'Error updating mochigames database';
		return false;
	}
	return true; 													
}
			
//####################################################################################################################      
function get_mochifeed() {
	$Start = getTime();
	$mem_limit = substr(ini_get("memory_limit"), 0, -1);	
	global $mysqli;
	global $setting;
	error_reporting(E_ALL ^ E_NOTICE);
	set_time_limit(0);
	@ini_set("max_execution_time", 1000);
	@ini_set("default_socket_timeout", 240);
	$mochipubid = $setting['mochi_pub_key'];				// Retrieve our Mochi key
	if (!$mochipubid) {
		echo 'You need a Mochimedia Publisher ID to continue.';
		return false;
	}
       
    $limit = '1500';
	$offset = $setting['mochi_offset'] >= 0 ? $setting['mochi_offset'] : 0;
	$no_errors = 0;
	$new_games = 0;
	
	
	//$feedUrl = 'http://www.mochimedia.com/feeds/games/'.$mochipubid.'/all/all?limit='.$limit.'&offset='.$offset.'&format=json';    
	$query = yasDB_select("SELECT `gametagid`, `uuid` FROM mochigames",false);
	$tags = array();
	//$uid_array = array();
	$i=0;
	// create array of game tags for duplicate checking
	while ($alltags = $query->fetch_array(MYSQLI_ASSOC)) {
		$tags[$i] = $alltags['gametagid'];
		//$uid_array[$i] = $alltags['uuid'];
		$i++;
	}
	unset($alltags);
	$query->close();	
	
	do {	
		// This is the Mochiads feed url. For more info go here: https://www.mochimedia.com/support/pub_docs#feed_3 ...when signed in
		$feedUrl = 'http://catalog.mochimedia.com/feeds/query/?partner_id='.$mochipubid.'&limit='.$limit.'&offset='.$offset;
		$data = get_content_of_url($feedUrl);	
		$json_data = json_decode($data, true);
		unset($data);
		$json_count = count($json_data["games"]);
		if ($json_count < 1) {
			break;
		}
		foreach ($json_data["games"] as $json) {
			if ($json['name'] == NULL) break;
			if (!in_array($json['game_tag'],$tags)) {// && !in_array($json['uuid'],$uid_array)) {
				$gamename = yasDB_clean($json['name']);
				$gametagkey = yasDB_clean($json['game_tag']);
				$gameurl = yasDB_clean($json['swf_url']);
				$gamewidth = yasDB_clean($json['width']);
				$gameheight = yasDB_clean($json['height']);
				$gamedesc = yasDB_clean($json['description']);
				$gamekeywords_array = $json['tags'];
				$gamekeywords = yasDB_clean(implode(", ", $gamekeywords_array));      //  Game keywords comma delimited i.e. "dressup,makeover,barbie"         
				unset($gamekeywords_array);
				$thumburl = yasDB_clean($json['thumbnail_url']);
				$gamecat_array = $json['categories'];
				$gamecats = yasDB_clean(implode(", ", $gamecat_array));    // All categories comma delimited
				unset($gamecat_array);
				
				$instructions = yasDB_clean($json['instructions']);
				$rating = yasDB_clean($json['rating']);
				$leaderboard = yasDB_clean($json['leaderboard_enabled']);
				$coinsenabled = yasDB_clean($json['coins_enabled']);
				$coins_rev_enabled = yasDB_clean($json['coins_revshare_enabled']);
				$recommended = yasDB_clean($json['recommended']);
				$controls = yasDB_clean($json['control_scheme']);
				$slug = yasDB_clean($json['slug']); 
				$thumburl_200x200 = yasDB_clean($json['thumbnail_large_url']);
				$screen_thumb_url = yasDB_clean($json['screen1_thumb']);
				$screen_thumb_url2 = yasDB_clean($json['screen2_thumb']);
				$screen_thumb_url3 = yasDB_clean($json['screen3_thumb']);
				$screen_thumb_url4= yasDB_clean($json['screen4_thumb']);
				$uuid = yasDB_clean($json['uuid']);
				$author = yasDB_clean($json['author']);
				// As of now we do not need all these fields.  But this will allow for future script growth, ie : Building a leaderboard mod.
				$sql = "INSERT INTO mochigames (`id`, `name`, `gameurl`, `width`, `height`, `description`, `keywords`, `thumburl`, `categories`, `gametagid`, `screenthumburl1`, `instructions`, `rating`, `leaderboard`, `coinsenabled`, `revshareenabled`, `recommended`, `isinstalled`, `slug`, `thumbnail_200x200`, `screenthumburl2`, `screenthumburl3`, `screenthumburl4`, `uuid`, `author`) 
				VALUES (NULL, '$gamename', '$gameurl', '$gamewidth', '$gameheight', '$gamedesc', '$gamekeywords', '$thumburl', '$gamecats', '$gametagkey', '$screen_thumb_url', '$instructions', '$rating', '$leaderboard', '$coinsenabled', '$coins_rev_enabled', '$recommended', 0, '$slug', '$thumburl_200x200', '$screen_thumb_url2', '$screen_thumb_url3', '$screen_thumb_url4', '$uuid', '$author')";
				
				$return = yasDB_insert($sql,false);
				if ($return === false) {
					$no_errors++; // if there is a db insert error just keep going. A duplicate may have snuck in as the limit/offset of the feed is not 100% accurate
				} else {
					$new_games++;
				}
			}
		}
		unset($json);
		unset ($json_data);
		$offset = $offset + $limit;		
	} while ($json_count > 0);
		
	echo 'Available script memory: '. $mem_limit. ' MB<br/>';
	echo 'Peak memory usage: '. (memory_get_peak_usage(true)/1024)/1024 . ' MB<br/>';
	$End = getTime();
	echo "Time taken = ".number_format(($End - $Start),2)." secs<br/>";
	echo "Errors encountered: ".$no_errors."<br/>";
	echo "New games added: ".$new_games."<br/>Finished!";
	$offset = intval($offset - 2*$limit);
	$offset = $offset > 0 ? $offset : 0;
	if ($offset != $setting['mochi_offset']) {
		yasDB_update("UPDATE `settings` SET `mochi_offset` = $offset WHERE `id` = 1");
		include("../includes/settings_function.inc.php");
		createConfigFile();
	}
	return true;
}
?>