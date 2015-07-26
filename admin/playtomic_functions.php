<?php
/*function get_content_of_url($url){
    $ohyeah = curl_init();
    curl_setopt($ohyeah, CURLOPT_URL, $url);
	if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')){
		curl_setopt($ohyeah, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ohyeah, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ohyeah,CURLOPT_SSL_VERIFYPEER,true);
		$data = curl_exec($ohyeah);
    } else {
		$data = curl_redir_exec($ohyeah);
	}
	curl_close($ohyeah);
	return $data;
 }*/
function get_content_of_url($url){
    $headers = array(
    "Accept-Language: en-us",
    "User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
    "Connection: Keep-Alive",
    "Cache-Control: no-cache"
    );
	$ohyeah = curl_init();
    curl_setopt($ohyeah, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ohyeah, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ohyeah, CURLOPT_URL, $url);
    curl_setopt($ohyeah, CURLOPT_FOLLOWLOCATION, true);
	$data = curl_exec($ohyeah);
    curl_close($ohyeah);
    return $data;
 }
 function curl_redir_exec($ch)
{
static $curl_loops = 0;
static $curl_max_loops = 20;
if ($curl_loops++>= $curl_max_loops)
{
$curl_loops = 0;
return FALSE;
}
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
list($header, $data) = explode("\n\r", $data, 2);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($http_code == 301 || $http_code == 302)
{
$matches = array();
preg_match('/Location:(.*?)\n/', $header, $matches);
$url = @parse_url(trim(array_pop($matches)));
if (!$url)
{
//couldn't process the url to redirect to
$curl_loops = 0;
return $data;
}
$last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
if (!$url['scheme'])
$url['scheme'] = $last_url['scheme'];
if (!$url['host'])
$url['host'] = $last_url['host'];
if (!$url['path'])
$url['path'] = $last_url['path'];
$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:'');
curl_setopt($ch, CURLOPT_URL, $new_url);
return curl_redir_exec($ch);
} else {
$curl_loops=0;
return $data;
}
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
function get_playtomicfeed() {
	global $mysqli;
	set_time_limit(0);
	@ini_set("max_execution_time", 600);
	@ini_set("default_socket_timeout", 240);
	
	// create array of game tags for duplicate checking
	$query = yasDB_select("SELECT `gametagid` FROM `playtomicfeed`",false);
	$tags = array();
	$i=0;
	while ($alltags = $query->fetch_array(MYSQLI_ASSOC)) {
		$tags[$i] = $alltags['gametagid'];
		$i++;
	}
	unset($alltags);
	$query->close();
							
	// This is the Playtomic feed url. For more info go here: http://playtomic.com/games/feed 671
	$feedUrl = 'https://playtomic.com/games/feed/mochi?format=json&category=1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19&language=1&audience=0,1,2';
	$data = get_content_of_url($feedUrl);	
	//$data = file_get_contents($feedUrl);
	$json_data = json_decode($data, true);
	unset($data);
	$json_count = count($json_data['games']);
	foreach ($json_data['games'] as $json) {
		if ($json['name'] == NULL) break;
		if (!in_array($json['game_tag'],$tags)) {
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
			if (empty($gameurl)) {
				$gameurl = str_replace('.'.GetFileExtension($thumburl), '.swf', $thumburl);
			}
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
			$sql = "INSERT INTO `playtomicfeed` (`id`, `name`, `gameurl`, `width`, `height`, `description`, `keywords`, `thumburl`, `categories`, `gametagid`, `screenthumburl1`, `instructions`, `rating`, `leaderboard`, `coinsenabled`, `revshareenabled`, `recommended`, `isinstalled`, `slug`, `thumbnail_200x200`, `screenthumburl2`, `screenthumburl3`, `screenthumburl4`, `uuid`, `author`) 
			VALUES (NULL, '$gamename', '$gameurl', '$gamewidth', '$gameheight', '$gamedesc', '$gamekeywords', '$thumburl', '$gamecats', '$gametagkey', '$screen_thumb_url', '$instructions', '$rating', '$leaderboard', '$coinsenabled', '$coins_rev_enabled', '$recommended', 0, '$slug', '$thumburl_200x200', '$screen_thumb_url2', '$screen_thumb_url3', '$screen_thumb_url4', '$uuid', '$author')";
				
			$return = yasDB_insert($sql,false);
			if (!$return) break; // if there is a db insert error just keep going. A duplicate may have snuck in as the limit/offset of the feed is not 100% accurate
		}
	}
	unset($json);
	unset ($json_data);
	return true;
}
function install_playtomic($gameid) { 
	$table = '';
	$pre = 'ptomic';
	global $mysqli;
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
	$game_url = '../swf/' . $game_file;		
	download_file($gameurl, $game_url);
	$gamefile = 'swf/'.$game_file;
	// Download and save 100x100 thumbnail pic
	$smallthumb = $pre."_" . $slug . "." . GetFileExtension($result['thumburl']);
	$sm_thumb = '../img/' . $smallthumb;			
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
	
	$query = yasDB_insert("INSERT INTO `games` (`id`, `title`, `description`, `instructions`, `keywords`, `file`, `height`, `width`, `category`, `plays`, `code`, `type`, `source`, `sourceid`, `thumbnail`, `thumbnail_200`, `screen1`, `screen2`, `screen3`, `screen4`) VALUES (NULL, '$gamename', '$desc', '$instructions', '$keywords', '$gamefile', '$height', '$width', $category, 0, '', 'SWF', 'PLAYTOMIC', $gameid, '$gamethumb', '$gamethumb200', '$screen1_thumb', '$screen2_thumb','$screen3_thumb','$screen4_thumb')",false);
	if (!$query) { 
		echo 'Error updating Games database';
		return false;
	}
	$query = yasDB_update("UPDATE `playtomicfeed` SET isinstalled = 1 WHERE id = '{$result['id']}'",false);
	if (!query) {
		echo 'Error updating $table database';
		return false;
	}
	return true; 													
}
?>