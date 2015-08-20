<?php
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
function get_content_of_url($url){
    $ohyeah = curl_init();
    curl_setopt($ohyeah, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ohyeah, CURLOPT_URL, $url);
    curl_setopt($ohyeah, CURLOPT_FOLLOWLOCATION, true);
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
    $stopWords = array( 'the','of','and','a','to','in','is','you','that','it','he','was','for','on','are','as','with','his','they','I','at','be','this','have','from','or','one','had','by','word','but','not','what','all','were','we','when','your','can','said','there','use','an','each','which','she','do','how','their','if','will','up','other','about','out','many','then','them','these','so','some','her','would','make','like','him','into','has','two','more','write','go','see','number','no','way','could','people','my','than','first','water','been','call','who','oil','its','now','find','long','down','day','did','get','come','made','may','part','click');
	$punct = array('.',';','?','!',':');
	$string = str_replace($punct, ' ', $string);
	$string = preg_replace('/ss+/i', '', $string);
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
function get_fogfeed() {
	global $mysqli;
	error_reporting(E_ALL ^ E_NOTICE);
	@ini_set("max_execution_time", 600);
	@ini_set("default_socket_timeout", 240);
	
	// create array of game tags for duplicate checking
	$query = yasDB_select("SELECT `uid` FROM `fogfeed`",false);
	$tags = array();
	$i=0;
	while ($alltags = $query->fetch_array(MYSQLI_ASSOC)) {
		$tags[$i] = $alltags['uid'];
		$i++;
	}
	unset($alltags);
	$query->close();
	
	// This is the FOG feed url. For more info go here: http://www.freegamesforyourwebsite.com/feeds.php
	$feedUrl = 'http://freegamesforyourwebsite.com/feeds/games/?format=json&category=all&limit=all';    
	$data = get_content_of_url($feedUrl);	
	$json_data = json_decode($data, true);
	unset($data);
	$json_count = count($json_data);
	foreach ($json_data as $json) {
		if ($json['title'] == NULL) break;
		if (!in_array($json['id'],$tags)) {
			// little voodo to extract a category from game text as the feed has no game categories
			$data = $json['title'] . ' ' . $json['description']. ' '. $json['tags'];
			$category = getCategory(wordsArray($data));
			// end category voodoo
			$title = yasDB_clean($json['title']);
			$uid = yasDB_clean($json['id']);
			$game_file = yasDB_clean($json['swf_file']);
			$game_url = yasDB_clean($json['game_url']);
			$resolution = explode("x",$json['resolution']);
			$width = $resolution[0];
			$height = $resolution[1];
			$description = yasDB_clean($json['description']);
			$small_thumburl = yasDB_clean($json['small_thumbnail_url']);
			$medium_thumburl = yasDB_clean($json['med_thumbnail_url']);
			$large_thumburl = yasDB_clean($json['large_thumbnail_url']);
			
			$controls = yasDB_clean(stripslashes($json['controls']));
			$created = yasDB_clean($json['created']);
			$updated = yasDB_clean($json['updated']);
			
			$sql = "INSERT INTO fogfeed (`id`, `uid`, `title`, `controls`, `updated`, `game_url`, `description`, `category`, `small_thumbnail_url`, `med_thumbnail_url`, `large_thumbnail_url`, `created`, `swf_file`, `width`, `height`, `installed`) 
			VALUES (NULL, $uid, '$title', '$controls', '$updated', '$game_url', '$description', $category, '$small_thumburl', '$medium_thumburl', '$large_thumburl', '$created', '$game_file', $width, $height, '0')";
			
			$return = yasDB_insert($sql,false);
			if (!$return) break; // if there is a db insert error just keep going.
		}
	}
	unset($json);
	unset($json_data);
	return true;
}
function install_foggame($gameid) {
	global $mysqli;
	$query = yasDB_select("SELECT * FROM `fogfeed` WHERE `id` = '$gameid'", false);
	$result = $query->fetch_array(MYSQLI_ASSOC);
					
	// Download and save game file	
	if($result['swf_file']) {
		$g_url = str_replace("..", "", $result['swf_file']);
		$game_file = basename($g_url);
		$game_file = "fog_" . $result['uid'] . "." . GetFileExtension($result['swf_file']);
		$game_url = '../swf/' . $game_file;		
		download_file($g_url, $game_url);
	} else {
		return false;
	}
	// Download and save 100x100 thumbnail pic
	if($result['small_thumbnail_url']) {
		$t_url = str_replace("..", "", $result['small_thumbnail_url']);
		$smallthumb = "fog_small_" . $result['uid'] . "." . GetFileExtension($result['small_thumbnail_url']);
		$sm_thumb = '../img/' . $smallthumb;			
		download_file($t_url, $sm_thumb);
	}
	// Download and save 180x135 thumbnail pic
	if($result['med_thumbnail_url']) {
		$t_url = str_replace("..", "", $result['med_thumbnail_url']);
		$mediumthumb = "fog_med_" . $result['uid'] . "." . GetFileExtension($result['med_thumbnail_url']);
		$med_thumb = '../img/' . $mediumthumb;			
		download_file($t_url, $med_thumb); 
	}
	// Download and save 300x300 thumbnail pic
	if($result['large_thumbnail_url']) {
		$t_url = str_replace("..", "", $result['large_thumbnail_url']);
		$largethumb = "fog_large_" . $result['uid'] . "." . GetFileExtension($result['large_thumbnail_url']);
		$large_thumb = '../img/' . $largethumb;			
		download_file($t_url, $large_thumb);
	}	
	$desc = yasDB_clean($result['description']);        // Prep for DB insert
	$gamename = yasDB_clean($result['title']);
	$gamefile = yasDB_clean(str_replace("../", "", $game_url));
	$gamethumb = yasDB_clean(str_replace("../", "", $sm_thumb));
	$height = $result['height'];
	$width = $result['width'];
	$controls = yasDB_clean($result['controls']);
	$category = $result['category'];
	$query->close();
	$query = yasDB_insert("INSERT INTO `games` (`id`, `title`, `description`, `instructions`, `keywords`, `file`, `height`, `width`, `category`, `plays`, `code`, `type`, `source`, `sourceid`, `thumbnail`) VALUES (NULL, '$gamename', '$desc', '$controls', '', '$gamefile', $height, $width, $category, 0, '', 'SWF', 'FOG', $gameid, '$gamethumb')",false);
	if (!$query) { 
		echo 'Error updating Games database';
		return false;
	}
	$query = yasDB_update("UPDATE fogfeed SET installed = '1' WHERE id = {$result['id']}", false);
	if (!$query) {
		echo 'Error updating fog game database';
		return false;
	}
	return true; 													
}
?>