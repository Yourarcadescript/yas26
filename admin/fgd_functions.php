<?php
function get_content_of_url($url){
    $ohyeah = curl_init();
    curl_setopt($ohyeah, CURLOPT_RETURNTRANSFER, 1);
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
function wordsArray($string){
    $stopWords = array( 'the','of','and','a','to','in','is','you','that','it','he','was','for','on','are','as','with','his','they','I','at','be','this','have','from','or','one','had','by','word','but','not','what','all','were','we','when','your','can','said','there','use','an','each','which','she','do','how','their','if','will','up','other','about','out','many','then','them','these','so','some','her','would','like','him','into','has','two','more','write','go','see','number','no','way','could','people','my','than','first','water','been','call','who','oil','its','now','find','long','down','day','did','get','come','made','may','part','click');
	$punct = array('.',';','?','!',':',',');
	$string = str_replace($punct, ' ', $string);
	$string = trim($string); // trim the string
    $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string); // only take alphanumerical characters, but keep the spaces and dashes too�
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
function get_fgdfeed() {
	global $mysqli;
	//error_reporting(E_ALL ^ E_NOTICE);
	set_time_limit(0);
	@ini_set("max_execution_time", 600);
	@ini_set("default_socket_timeout", 240);
	$limit = 200;
	$start = 0;
	$totaltags = array();
	// create array of game tags for duplicate checking
	$query = yasDB_select("SELECT `uuid` FROM `fgdfeed`",false);
	$tags = array();
	while ($alltags = $query->fetch_array(MYSQLI_ASSOC)) {
		$totaltags[] = $alltags['uuid'];
	}
	unset($alltags);
	$query->close();
	if (count($totaltags) == 0) $totaltags[0] = '0';
	do {
		// This is the Flashgamedistribution.com feed url. For more info go here: http://flashgamedistribution.com/feeds.php
		$feedUrl = 'http://flashgamedistribution.com/feed.php?start='.$start.'&gpp='.$limit.'&feed=json';
		$data = get_content_of_url($feedUrl);	
		$json_data = json_decode($data, true);
		unset($data);
		$json_count = count($json_data);
		foreach ($json_data as $json) {
			if (!in_array($json['game_id'],$totaltags)) {
				$uuid = $json['game_id'];
				$title = yasDB_clean(stripslashes($json['title']));
				$gameurl = yasDB_clean($json['swf_filename']);
				$width = yasDB_clean($json['width']);
				$height = yasDB_clean($json['height']);
				$gamedesc = yasDB_clean(stripslashes($json['short_description']));
				$tags = yasDB_clean($json['tags']);
				$thumburl = yasDB_clean($json['thumb_filename']);
				$categories = $json['genres'];
				$highscores = yasDB_clean($json['highscores']);
				$mobile = yasDB_clean($json['is_mobileready']);
				$hasads = yasDB_clean($json['has_ads']);
				$featured = yasDB_clean($json['featured']);
				$multiplayer = yasDB_clean($json['multiplayer']);
				
				$sql = "INSERT INTO `fgdfeed` (`id`, `uuid`, `title`, `description`, `tags`, `gamefile`, `thumbfile`, `width`, `height`, `categories`, `hasads`, `multiplayer`, `featured`, `highscores`, `mobileready`, `installed`) 
				VALUES (NULL, '$uuid', '$title', '$gamedesc', '$tags', '$gameurl', '$thumburl', $width, $height, '$categories', $hasads, $multiplayer, $featured, $highscores, $mobile, 0)";
				
				$return = yasDB_insert($sql,false);
				if (!$return) return false;
			}
		}
		if ($start >= 15400) $limit = 20;
		$start = $start + $limit;
		unset($json);
		unset ($json_data);
	} while ($json_count > 0);	
	return true;
}
function install_fgdgame($gameid) {
	global $mysqli;
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
						'RPG' => 3,  //Adventure
						'Shooter' => 5,  //Shooter
						'Sports' => 4,  //Sports
						'Strategy' => 10,  //Strategy
						'Other' => 7);  //Other				
	// Download and save game file	
	if($result['gamefile']) {
		$g_url = str_replace("..", "", $result['gamefile']);
		$game_file = basename($g_url);
		$game_file = "fgd_" . $result['uuid'] . "." . GetFileExtension($result['gamefile']);
		$game_url = '../swf/' . $game_file;		
		download_file($g_url, $game_url);
	} else {
		return false;
	}
	// Download and save thumbnail pic
	if($result['thumbfile']) {
		$t_url = str_replace("..", "", $result['thumbfile']);
		$smallthumb = "fgd_" . $result['uuid'] . "." . GetFileExtension($result['thumbfile']);
		$thumb = '../img/' . $smallthumb;			
		download_file($t_url, $thumb);
	}
	$desc = yasDB_clean($result['description']);        // Prep for DB insert
	$gamename = yasDB_clean($result['title']);
	$gamefile = yasDB_clean(str_replace("../", "", $game_url));
	$gamethumb = yasDB_clean(str_replace("../", "", $thumb));
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
	$query = yasDB_insert("INSERT INTO `games` (`id`, `title`, `description`, `instructions`, `keywords`, `file`, `height`, `width`, `category`, `plays`, `code`, `type`, `source`, `sourceid`, `thumbnail`) VALUES (NULL, '$gamename', '$desc', '', '$tags', '$gamefile', $height, $width, $category, 0, '', 'SWF', 'FGD', $gameid, '$gamethumb')",false);
	if (!$query) { 
		echo 'Error updating Games database';
		return false;
	}
	$query = yasDB_update("UPDATE fgdfeed SET installed = 1 WHERE id = {$result['id']}", false);
	if (!query) {
		echo 'Error updating fgdfeed database';
		return false;
	}
	return true; 													
}
?>