<?php
function prepGame($title) {
  $title = str_replace(" ", "-", $title);
  $title = str_replace("'", "_", $title);
  $title = str_replace('"', "_", $title);
  $title = str_replace('/', "_", $title);
  $title = str_replace("\\", "_", $title);
  return rawurlencode($title);
}
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
function get_mgffeed() {
  global $mysqli;
  error_reporting(E_ALL ^ E_NOTICE);
  @ini_set("max_execution_time", 600);
  @ini_set("default_socket_timeout", 240);

  // create array of game tags for duplicate checking
  $query = yasDB_select("SELECT `uid` FROM `mgffeed`",false);
  $tags = array();
  $i=0;
  while ($alltags = $query->fetch_array(MYSQLI_ASSOC)) {
    $tags[$i] = $alltags['uid'];
    $i++;
  }
  unset($alltags);
  $query->close();

  // This is the MGF feed url. For more info go here: http://www.mochigamefeed.com/feed.php
  $feedUrl = 'http://mochigamefeed.com/updatedfeed.php';

  $data = get_content_of_url($feedUrl);
  $json_data = json_decode($data, true);
  unset($data);
  $json_count = count($json_data);
  //$cat_array = array("puzzle", "action", "adventure", "sports", "shooter", "casino", "other", "girls", "education", "strategy", "jigsaw", "coloring", "driving", "fighting", "rhythm", "board game", "customize");
  $cat_array = array("puzzle", "action", "adventure", "sports", "shooter", "casino", "other", "dressup", "arcade", "strategy", "cartoon", "coloring");
  foreach ($json_data as $json) {
    if ($json['title'] == NULL) break;
    if (!in_array($json['id'],$tags)) {
      $cat = strtolower(yasDB_clean($json['category']));
      if (in_array($cat, $cat_array)) {
        $category = array_search($cat, $cat_array) + 1;
      } else {
        $category = 7; // if not found set category to other
      }
      $title = yasDB_clean($json['title']);
      $uid = intval($json['id']);
      $game_file = yasDB_clean($json['file']);
      $game_url = "http://www.mochigamefeed.com/view/".intval($json['id'])."/".prepGame($title).".html";
      $width = intval($json['width']);
      $height = intval($json['height']);
      $description = yasDB_clean($json['description']);
      $instructions = yasDB_clean($json['instructions']);
      $small_thumburl = yasDB_clean($json['thumbnail']);
      $medium_thumburl = yasDB_clean($json['medthumb']);
      $large_thumburl = yasDB_clean($json['lgthumb']);
      $screen1 = yasDB_clean($json['screen1']);
      $screen1 = yasDB_clean($json['screen2']);
      $zip = yasDB_clean($json['zip']);
      $controls = yasDB_clean(stripslashes($json['controls']));
      $created = yasDB_clean($json['installdate']);
      $review = yasDB_clean($json['review']);
      $rating = yasDB_clean($json['rating']);
      $ads = yasDB_clean($json['ads']);
      $hsapi = yasDB_clean($json['hsapi']);
      $keywords = yasDB_clean($json['keywords']);

      $sql = "INSERT INTO mgffeed (`id`, `uid`, `title`, `controls`, `installdate`, `game_url`, `description`, `instructions`, `category`, `thumbnail`, `medthumb`, `lgthumb`, `file`, `zip`, `screen1`, `screen2`, `width`, `height`, `review`, `rating`, `ads`, `hsapi`, `keywords`,`installed`, `hidden`)
      VALUES (NULL, $uid, '$title', '$controls', '$created', '$game_url', '$description', '$instructions', $category, '$small_thumburl', '$medium_thumburl', '$large_thumburl', '$game_file', '$zip', '$screen1', '$screen2', $width, $height, '$review', '$rating', '$ads', '$hsapi', '$keywords', '0', '0')";

      $return = yasDB_insert($sql,false);
      if (!$return) break; // if there is a db insert error just keep going.
    }
  }
  unset($json);
  unset($json_data);
  return true;
}
function install_mgfgame($gameid) {
  global $mysqli;
  $query = yasDB_select("SELECT * FROM `mgffeed` WHERE `id` = '$gameid'", false);
  $result = $query->fetch_array(MYSQLI_ASSOC);

  // Download and save game file
  if($result['file']) {
    $g_url = str_replace("..", "", $result['file']);
    $game_file = basename($g_url);
    $game_file = "mgf_" . $result['title'] . "." . GetFileExtension($result['file']);
    $game_url = '../swf/' . $game_file;
    download_file($g_url, $game_url);
  } else {
    return false;
  }
  // Download and save thumbnail pic
  if($result['thumbnail']) {
    $t_url = str_replace("..", "", $result['thumbnail']);
    $smallthumb = "mgf_" . $result['title'] . "." . GetFileExtension($result['thumbnail']);
    $sm_thumb = '../img/' . $smallthumb;
    download_file($t_url, $sm_thumb);
  }
  $desc = yasDB_clean($result['description']);        // Prep for DB insert
  $gamename = yasDB_clean($result['title']);
  $keywords = yasDB_clean($result['keywords']);
  $gamefile = yasDB_clean(str_replace("../", "", $game_url));
  $gamethumb = yasDB_clean(str_replace("../", "", $sm_thumb));
  $gamethumb200 = yasDB_clean(str_replace("../", "", $med_thumb));
  $height = intval($result['height']);
  $width = intval($result['width']);
  $instructions = yasDB_clean($result['instructions']);
  $keywords = yasDB_clean($result['keywords']);
  $category = $result['category'];
  $review = yasDB_clean($result['review']);
  $query->close();
  $query = yasDB_insert("INSERT INTO `games` (`id`, `title`, `description`, `instructions`, `keywords`, `file`, `height`, `width`, `category`, `plays`, `code`, `type`, `source`, `sourceid`, `thumbnail`, `ismochi`, `thumbnail_200`, `screen1`, `screen2`, `screen3`, `screen4`, `review`, `active`) VALUES (NULL, '$gamename', '$desc', '$instructions', '$keywords', '$gamefile', $height, $width, $category, 0, '', 'SWF', 'OTHER', $gameid, '$gamethumb', 0, '$gamethumb200', '', '','','', '$review', 1)",false);
  if (!$query) {
    echo 'Error updating Games database';
    return false;
  }
  $query = yasDB_update("UPDATE mgffeed SET installed = '1' WHERE id = {$result['id']}", false);
  if (!$query) {
    echo 'Error updating mgffeed database';
    return false;
  }
  return true;
}
?>