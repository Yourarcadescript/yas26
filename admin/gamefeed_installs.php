<?php
if (!function_exists('GetFileExtension')) {
	function GetFileExtension($filepath) {
		preg_match('/[^?]*/', $filepath, $matches);
		$string = $matches[0];
		$pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
		if(count($pattern) == 1) {
			exit;
		}
		   
		if(count($pattern) > 1) {
			$filenamepart = $pattern[count($pattern)-1][0];
		    preg_match('/[^?]*/', $filenamepart, $matches);
			return $matches[0];
		}
	}
}
if (!function_exists('GetFileName')) {
	function GetFileName($filepath) {
		preg_match('/[^?]*/', $filepath, $matches);
		$string = $matches[0];
		$pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
		$lastdot = $pattern[count($pattern)-1][1];
		$filename = basename(substr($string, 0, $lastdot-1));
		return $filename;
	} 
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
	$fp = fopen ($local_file, 'wb+');//This is the file where we save the information
	$ch = curl_init($url);//Here is the file we are downloading
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
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
function wordsArray($string){
    $stopWords = array( 'the','of','and','a','to','in','is','you','that','it','he','was','for','on','are','as','with','his','they','I','at','be','this','have','from','or','one','had','by','word','but','not','what','all','were','we','when','your','can','said','there','use','an','each','which','she','do','how','their','if','will','up','other','about','out','many','then','them','these','so','some','her','would','make','like','him','into','has','two','more','write','go','see','number','no','way','could','people','my','than','first','water','been','call','who','oil','its','now','find','long','down','day','did','get','come','made','may','part','click');
	$punct = array('.',';','?','!',':');
	$string = str_replace($punct, ' ', $string);
	$string = preg_replace('/ss+/i', '', $string);
    $string = trim($string); // trim the string
    $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string); // only take alphanumerical characters, but keep the spaces and dashes too…
    $string = strtolower($string); // make it lowercase
    $matchWords = explode(" ", $string);
	foreach ( $matchWords as $key=>$item ) {
        if ( $item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 2 ) {
            unset($matchWords[$key]);
        }
    }
    $wordCountArr = array();
    if ( is_array($matchWords) ) {
        foreach ( $matchWords as $key => $val ) {
            $val = strtolower($val);
            if ( isset($wordCountArr[$val]) ) {
                $wordCountArr[$val]++;
            } else {
                $wordCountArr[$val] = 1;
            }
        }
    }
    arsort($wordCountArr);
   return $wordCountArr;		
}
function getCategory($words) {
	$allwords = array_keys($words);
	$keywords = array('puzzle,level,piece,block,ball,item,object,card,picture,tile,word,number,difference,skill,letter,color,pair,group,brick,contain,animal,find',
				   'action,level,blast,track,race,point,course,wheel,enemies,enemy,save,ball,bonus,weapon,weapons,block,obstacle,increase,defend,stunt,truck,car,tank,skill,star,upgrade,graphic,player,bomb,ship,wave,item,alien,steroid,bubble,score,feature,type,zombie,friend,high,fighting,fight',
				   'adventure,level,item,game,points,enemies,object,treasure,rescue,skill,kiss,obstacle,star,weapon,weapons,graphic,clue,creature,player,series,defend,need,feature,zombie,alien,character,stone,dangerous',
				   'sports,sport,level,point,ball,bat,bowling,gym,pool,cue,obstacle,player,skill,bonus,finish,track,high,friend,opponent,score,team,teams,time,race,racing,goal,tennis,golf,hockey,basketball,baseball,arrow,court,graphic,achievement,trick,shot,mode,character,stunt,jump,challenge,hole,card,level,piece,tile,pair,ball,block,letter,rule,mode,square,pile,opponent,board',
				   'shooter,shooting,shoot,shot,gun,weapon,weapons,aim,enemy,enemies,skill,bullet,point,alien,target,space,bomb,tank,invader,balloon,invade,dangerous,rocket,laser,target,targets',
				   'casino,card,cards,suit,deck,pile,number,slot,slots,bonus,graphic,rule,foundation,hand,texas,level,high,score,skill,sequence,ball,friend,spin,time,crap,combination,opponent,mode,credit,chip,chips,colour,column,color',
				   '',
				   'dressup,dress,dress-up,clothing,clothes,clothe,styling,style,styles,accessories,accessory,girl,couple,outfit,item,various,hair,look,shoes,color,love,delicious,nail,nails,manicure,polish,gorgeous,pretty,beautiful,necklace,earrings,fantasy,artist,flower,fabulous,glamorous,famous,costume,makeup,make-up',
				   'arcade,retro,classic,pac-man,pacman,pac,level,points,jump,platform,atari,skill,block,brick,metroid,worm,mega,extra,frogger,pong,paddle,mario,luigi,nintendo',
				   'strategy,level,point,defense,enemies,enemy,block,tower,item,ball,skill,object,wave,weapon,piece,card,upgrade,ship,type,finish,bomb,achievement,number,tile,find,hidden,challenge,challenges,challenging,brain,mind');
	$count = array(0,0,0,0,0,0,0,0,0,0);
	foreach ($allwords as $word) {
		for($i=0; $i<=9;$i++) {
			$keys = explode(",", $keywords[$i]);
			if(in_array($word, $keys)) { // trigger keywords, if found use this category, else continue with algorithm
				if($word == 'action') { return 2; } //action
				if($word == 'adventure') { return 3; } //adventure
				if($word == 'puzzle') { return 1; } //puzzle
				if($word == 'sports') { return 4; } //sports
				if($word == 'shooter') { return 5; } //shooter
				if($word == 'casino') { return 6; } //casino
				if($word == 'dressup') { return 8; } //dressup
				if($word == 'arcade') { return 9; } //arcade
				if($word == 'strategy') { return 10; } //strategy */
				$count[$i]++;
			}
		}
	}
	if (max($count) == 0) { return 7; } // no matches so return "other" category
	$max = array_keys($count, max($count)); // bring index with highest matches to the top and nab it
	unset($keywords);
	$best = $max[0];
	return ++$best;
}
function install_mochigame($gameid) { 
	$pre = 'mochi';
	global $mysqli;
	global $setting;
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
		$game_url = $setting['sitepath'].'/swf/' . $game_file;		
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
			$smallthumb = $pre."_" . $slug . "." . GetFileExtension($g_ext[0]);
		} else {
			$smallthumb = $pre."_" . $slug . "." . GetFileExtension($result['thumburl']);
		}		
		$sm_thumb = $setting['sitepath'].'/img/' . $smallthumb;			
		download_file($t_url, $sm_thumb);
		$gamethumb = 'img/' . $smallthumb;
	
	}
	// Download and save 200x200 thumbnail pic
	if(!empty($result['thumbnail_200x200'])) {
		$t_url = str_replace("..", "", $result['thumbnail_200x200']);
		$mediumthumb = $pre."_200_" . $slug . "." . GetFileExtension($result['thumbnail_200x200']);
		$med_thumb = $setting['sitepath'].'/img/' . $mediumthumb;			
		download_file($t_url, $med_thumb);
		$gamethumb200 = 'img/' . $mediumthumb; 
	} else {
		$gamethumb200 = '';
	}
				
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
		return false;
	}
	$query = yasDB_update("UPDATE `mochigames` SET isinstalled = 1 WHERE id = '{$result['id']}'",false);
	if (!query) {
		return false;
	}
	return true; 													
}
function install_playtomic($gameid) { 
	$table = '';
	$pre = 'ptomic';
	global $mysqli;
	global $setting;
	$categories = array('Action' => 2, //Action
						'Adventure' => 3, //Adventure
						'Strategy' => 10, //Strategy
						'Multiplayer' => 2,  //Action
						'Puzzle' => 1,  //Puzzle
						'Rhythm' => 7,  //Other
						'Shooter' => 5,  //Shooter
						'Sports' => 4,  //Sports
						'Other' => 7,
						'RPG' => 3,
						'Arcade' => 9,
						'Driving' => 4,
						'Defense' => 10,
						'Rhythm' => 7,
						'Education' => 7,
						'Gadgets' => 1,
						'Fighting' => 2,
						'Dress Up' => 8,
						'Board Game' => 7,
						'Pimp & Customize' => 8,
						'Pimp &amp; Customize' => 8
					);
	$query = yasDB_select("SELECT * FROM `playtomicfeed` WHERE `id` = '$gameid'",false);
	$result = $query->fetch_array(MYSQLI_ASSOC);
	
	$gamename = $result['name'];
	$thumburl = $result['thumburl'];
	$thumburl_200 = $result['thumbnail_200x200'];
	$screen1_thumb = '';
	$screen2_thumb = '';
	$screen3_thumb = '';
	$screen4_thumb = '';
	$gameurl = $result['gameurl'];
	$c = explode(",", $result['categories']);   	// Remove comma delimiter and seperate categories into array in order to insert single category.
	$category = $categories[$c[0]];
	$slug = str_replace(array('-','_'),'',$result['slug']);
	// Download and save game file	
	$game_file = $pre."_" . $slug . "." . GetFileExtension($result['gameurl']);
	$game_url = $setting['sitepath'].'/swf/' . $game_file;		
	download_file($gameurl, $game_url);
	$gamefile = 'swf/'.$game_file;
	// Download and save 100x100 thumbnail pic
	$smallthumb = $pre."_" . $slug . "." . GetFileExtension($result['thumburl']);
	$sm_thumb = $setting['sitepath'].'/img/' . $smallthumb;			
	download_file($thumburl, $sm_thumb);
	$gamethumb = 'img/' . $smallthumb;
		
	// Download and save 200x200 thumbnail pic
	$gamethumb200 = '';
	/*if(!empty($result['thumbnail_200x200']) || $type == 0) {
		$t_url = str_replace("..", "", $result['thumbnail_200x200']);
		$mediumthumb = $pre."_200_" . $slug . "." . GetFileExtension($result['thumbnail_200x200']);
		$med_thumb = '../img/' . $mediumthumb;			
		download_file($t_url, $med_thumb);
		$gamethumb200 = 'img/' . $mediumthumb; 
	} else {
		$gamethumb200 = '';
	}*/
				
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
	
	$query = yasDB_insert("INSERT INTO `games` (`id`, `title`, `description`, `instructions`, `keywords`, `file`, `height`, `width`, `category`, `plays`, `code`, `type`, `source`, `sourceid`, `thumbnail`, `thumbnail_200`, `screen1`, `screen2`, `screen3`, `screen4`) VALUES (NULL, '$gamename', '$desc', '$instructions', '$keywords', '$gamefile', '$height', '$width', $category, 0, '', 'SWF', 'PLAYTOMIC', $gameid, '$gamethumb', '$gamethumb200', '$screen1_thumb', '$screen2_thumb','$screen3_thumb','$screen4_thumb')",false);
	if (!$query) { 
		return false;
	}
	$query = yasDB_update("UPDATE `playtomicfeed` SET isinstalled = 1 WHERE id = '{$result['id']}'",false);
	if (!query) {
		return false;
	}
	return true; 													
}
function install_fgdgame($gameid) {
	global $mysqli;
	global $setting;
	$query = yasDB_select("SELECT * FROM `fgdfeed` WHERE `id` = '$gameid'", false);
	$result = $query->fetch_array(MYSQLI_ASSOC);
	$categories = array('Action' => 2, //Action
						'Adventure' => 3, //Adventure
						'Arcade' => 9,  //Arcade
						'Defense' => 10, //Strategy
						'Casino' => 6,  //Casino
						'Driving' => 2,  //Action
						'Fighting' => 2,  //Action
						'Gadgets' => 10,  //Strategy
						'Multiplayer' => 2,  //Action
						'Puzzle' => 1,  //Puzzle
						'Rhythm' => 7,  //Other
						'RPG' => 3,  //Adventure rhd         
						'Shooter' => 5,  //Shooter
						'Sports' => 4,  //Sports
						'Strategy' => 10,  //Strategy
						'Other' => 7);  //Other				
	// Download and save game file	
	if($result['gamefile']) {
		$g_url = str_replace("..", "", $result['gamefile']);
		$game_file = basename($g_url);
		$game_file = "fgd_" . $result['uuid'] . "." . GetFileExtension($result['gamefile']);
		$game_url = $setting['sitepath'].'/swf/' . $game_file;		
		download_file($g_url, $game_url);
	} else {
		return false;
	}
	// Download and save thumbnail pic
	if($result['thumbfile']) {
		$t_url = str_replace("..", "", $result['thumbfile']);
		$smallthumb = "fgd_" . $result['uuid'] . "." . GetFileExtension($result['thumbfile']);
		$thumb = $setting['sitepath'].'/img/' . $smallthumb;			
		download_file($t_url, $thumb);
	}
	$desc = yasDB_clean($result['description']);        // Prep for DB insert
	$gamename = yasDB_clean($result['title']);
	$gamefile = yasDB_clean('swf/'.$game_file);
	$gamethumb = yasDB_clean('img/'.$smallthumb);
	$tags = yasDB_clean($result['tags']);
	$height = $result['height'];
	$width = $result['width'];
	$c = explode(",",$result['categories']);
	if ($c[0] == "Other" || $c[0] == "Gadgets" || $c[0] == "Rhythm" || $c[0] == "Arcade") {
		$category = getCategory(wordsArray($result['title']. ' ' . $result['description'] . ' '. $result['tags']));
	} else {
		$category = $categories[$c[0]];
	}
	$query->close();
	$query = yasDB_insert("INSERT INTO `games` (`id`, `title`, `description`, `instructions`, `keywords`, `file`, `height`, `width`, `category`, `plays`, `code`, `type`, `source`, `sourceid`, `thumbnail`, `thumbnail_200`, `screen1`, `screen2`, `screen3`, `screen4`) VALUES (NULL, '$gamename', '$desc', '', '$tags', '$gamefile', $height, $width, $category, 0, '', 'SWF', 'FGD', $gameid, '$gamethumb', '', '', '','','')",false);
	if (!$query) { 
		return false;
	}
	$query = yasDB_update("UPDATE fgdfeed SET installed = 1 WHERE id = {$result['id']}", false);
	if (!query) {
		return false;
	}
	return true; 													
}
function install_foggame($gameid) {
	global $mysqli;
	global $setting;
	$query = yasDB_select("SELECT * FROM `fogfeed` WHERE `id` = '$gameid'", false);
	$result = $query->fetch_array(MYSQLI_ASSOC);
					
	// Download and save game file	
	if($result['swf_file']) {
		$g_url = str_replace("..", "", $result['swf_file']);
		$game_file = basename($g_url);
		$game_file = "fog_" . $result['uid'] . "." . GetFileExtension($result['swf_file']);
		$game_url = $setting['sitepath'].'/swf/' . $game_file;		
		download_file($g_url, $game_url);
	} else {
		return false;
	}
	// Download and save 100x100 thumbnail pic
	if($result['small_thumbnail_url']) {
		$t_url = str_replace("..", "", $result['small_thumbnail_url']);
		$smallthumb = "fog_small_" . $result['uid'] . "." . GetFileExtension($result['small_thumbnail_url']);
		$sm_thumb = $setting['sitepath'].'/img/' . $smallthumb;			
		download_file($t_url, $sm_thumb);
	}
	// Download and save 180x135 thumbnail pic
	if($result['med_thumbnail_url']) {
		$t_url = str_replace("..", "", $result['med_thumbnail_url']);
		$mediumthumb = "fog_med_" . $result['uid'] . "." . GetFileExtension($result['med_thumbnail_url']);
		$med_thumb = $setting['sitepath'].'/img/' . $mediumthumb;			
		download_file($t_url, $med_thumb); 
	}
	// Download and save 300x300 thumbnail pic
	if($result['large_thumbnail_url']) {
		$t_url = str_replace("..", "", $result['large_thumbnail_url']);
		$largethumb = "fog_large_" . $result['uid'] . "." . GetFileExtension($result['large_thumbnail_url']);
		$large_thumb = $setting['sitepath'].'/img/' . $largethumb;			
		download_file($t_url, $large_thumb);
	}	
	$desc = yasDB_clean($result['description']);        // Prep for DB insert
	$gamename = yasDB_clean($result['title']);
	$gamefile = yasDB_clean('swf/'.$game_file);
	$gamethumb = yasDB_clean('img/'.$smallthumb);
	$gamethumb200 = yasDB_clean('img/'.$largethumb);
	$height = $result['height'];
	$width = $result['width'];
	$controls = yasDB_clean($result['controls']);
	$category = $result['category'];
	$query->close();
	$query = yasDB_insert("INSERT INTO `games` (`id`, `title`, `description`, `instructions`, `keywords`, `file`, `height`, `width`, `category`, `plays`, `code`, `type`, `source`, `sourceid`, `thumbnail`, `thumbnail_200`, `screen1`, `screen2`, `screen3`, `screen4`) VALUES (NULL, '$gamename', '$desc', '$controls', '', '$gamefile', $height, $width, $category, 0, '', 'SWF', 'FOG', $gameid, '$gamethumb', '$gamethumb200', '', '','','')",false);
	if (!$query) { 
		return false;
	}
	$query = yasDB_update("UPDATE fogfeed SET installed = '1' WHERE id = {$result['id']}", false);
	if (!$query) {
		return false;
	}
	return true; 													
}
function install_konggame($gameid) {
	global $mysqli;
	global $setting;
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
		$game_url = $setting['sitepath'].'/swf/' . $game_file;		
		download_file($g_url, $game_url);
	} else {
		return false;
	}
	// Download and save thumbnail pic
	if($result['thumbnail']) {
		$t_url = str_replace("..", "", $result['thumbnail']);
		$smallthumb = "kong_" . preg_replace('#\W#', '', $result['title']) . "." . GetFileExtension($result['thumbnail']);
		$thumb = $setting['sitepath'].'/img/' . $smallthumb;			
		download_file($t_url, $thumb);
	}
	$desc = yasDB_clean($result['description']);        // Prep for DB insert
	$gamename = yasDB_clean($result['title']);
	$gamefile = yasDB_clean('swf/'.$game_file);
	$gamethumb = yasDB_clean('img/'.$smallthumb);
	$height = $result['height'];
	$width = $result['width'];
	$c = $result['category'];
	$category = $categories[$c];
	$query->close();
	$query = yasDB_insert("INSERT INTO `games` (`id`, `title`, `description`, `instructions`, `keywords`, `file`, `height`, `width`, `category`, `plays`, `code`, `type`, `source`, `sourceid`, `thumbnail`, `thumbnail_200`, `screen1`, `screen2`, `screen3`, `screen4`) VALUES (NULL, '$gamename', '$desc', '', '', '$gamefile', $height, $width, $category, 0, '', 'SWF', 'KONGREGATE', $gameid, '$gamethumb', '', '', '','','')",false);
	if (!$query) { 
		return false;
	}
	$query = yasDB_update("UPDATE kongregate SET installed = 1 WHERE id = {$result['id']}", false);
	if (!query) {
		return false;
	}
	return true; 													
}
function install_vascogame($gameid) {
	global $mysqli;
	global $setting;
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
						'skill games' => 10); //Strategy
	// Download and save game file	
	if($result['file']) {
		$g_url = str_replace("..", "", $result['file']);
		$game_file = basename($g_url);
		$game_file = "vasco_" . preg_replace('#\W#', '', $result['title']) . "." . GetFileExtension($result['file']);
		$game_url = $setting['sitepath'].'/swf/' . $game_file;		
		download_file($g_url, $game_url);
	} else {
		return false;
	}
	// Download and save thumbnail pic
	if($result['thumbnail']) {
		$t_url = str_replace("..", "", $result['thumbnail']);
		$smallthumb = "vasco_" . preg_replace('#\W#', '', $result['title']) . "." . GetFileExtension($result['thumbnail']);
		$thumb = $setting['sitepath'].'/img/' . $smallthumb;			
		download_file($t_url, $thumb);
	}
	$desc = yasDB_clean($result['description']);        // Prep for DB insert
	$gamename = yasDB_clean($result['title']);
	$gamefile = yasDB_clean('swf/'.$game_file);
	$gamethumb = yasDB_clean('img/'.$smallthumb);
	$height = $result['height'];
	$width = $result['width'];
	$c = $result['category'];
	$category = $categories[$c];
	$query->close();
	$query = yasDB_insert("INSERT INTO `games` (`id`, `title`, `description`, `instructions`, `keywords`, `file`, `height`, `width`, `category`, `plays`, `code`, `type`, `source`, `sourceid`, `thumbnail`, `thumbnail_200`, `screen1`, `screen2`, `screen3`, `screen4`) VALUES (NULL, '$gamename', '$desc', '', '', '$gamefile', $height, $width, $category, 0, '', 'SWF', 'VASCOGAMES', $gameid, '$gamethumb', '', '', '','','')",false);
	if (!$query) { 
		return false;
	}
	$query = yasDB_update("UPDATE `vascogames` SET `installed` = 1 WHERE `id` = {$result['id']}", false);
	if (!query) {
		return false;
	}
	return true; 													
}
?>