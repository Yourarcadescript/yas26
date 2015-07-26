<?php
function get_youtube_data($yt_url, $idOnly = false) {
    $parts = explode("=",$yt_url);
	$video_id = end($parts);
	if(!is_numeric($video_id)) {
		preg_match('![?&]{1}v=([^&]+)!', $yt_url, $match); // give it a second go
		$video_id = $match[1];
    }
	if($idOnly) return $video_id;
	define('YT_API_URL', 'http://gdata.youtube.com/feeds/api/videos?q=');
    //Using cURL php extension to make the request to youtube API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, YT_API_URL . $video_id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //$feed holds a rss feed xml returned by youtube API
    $feed = curl_exec($ch);
    if (!$feed) { return false; }
    curl_close($ch);
    //Using SimpleXML to parse youtube's feed
    $xml = simplexml_load_string($feed);
    $entry = $xml->entry[0];
    $media = $entry->children('media', true);
    $data = array();
    $group = $media[0];
    $data['title'] = (string)$group->title;
    $data['description'] = (string)$group->description;
    $data['keywords'] = (string)$group->keywords;
    $thumb = $group->thumbnail[0];
    list($thumb_url, $thumb_width, $thumb_height, $thumb_time) = $thumb->attributes();
    $data['thumbnail'] = (string)$thumb_url;
    return $data;
}
function remoteFileExists($path){
    return (@fopen($path,"r")==true);
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
function get_file_extension($filepath) {
    preg_match('/[^?]*/', $filepath, $matches);
    $string = $matches[0];
    $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
    // check if there is any extension
    if(count($pattern) == 1) {
        return false;
    }

    if(count($pattern) > 1) {
        $filenamepart = $pattern[count($pattern)-1][0];
        preg_match('/[^?]*/', $filenamepart, $matches);
        return strtolower($matches[0]);
    }
}
?>
<?php
if (isset($_POST['name'])) {
    $name = $_POST['name'];
} else if (isset($_SESSION['user'])) {
    $name = $_SESSION['user'];
} else {
    $name = '';
}
?>
<div id="center">
<div id="home_container">
<div class="container_box1"><div class="header">Submit Videos</div>
<?php
if(isset($_POST['add'])) {
        $title = yasDB_clean($_POST['title']);
        $desc = yasDB_clean($_POST['description']);
        $thumb = $_POST['thumbnail'];
        $file = 'http://www.youtube.com/watch?v=' . get_youtube_data($_POST['file'], true);
        $height = intval($_POST['height']);
        $width = intval($_POST['width']);
        $keywords = yasDB_clean($_POST['keywords']);
        if ($_POST['type'] == 'YOUTUBE') {
            $data = get_youtube_data($_POST['file']);
            $tn = $data['thumbnail'];
            $title = yasDB_clean($data['title']);
            $desc = yasDB_clean($data['description']);
            $keywords = yasDB_clean($data['keywords']);

        if (isset($_POST['name'])) {
            $name = yasDB_clean($_POST['name']);
        } else if (isset($_SESSION['user'])) {
            $name = $_SESSION['user'];
        } else {
            $name = '';
        }

            if (remoteFileExists($tn) === true) {
                $thumb = 'img/' . preg_replace('#\W#', '', $title) . rand(0, pow(10, 5)) . '.' . get_file_extension($tn);
                download_file($tn, $setting['sitepath'] . '/' . $thumb);
            } else {
                $thumb = '';
            }
        }
        yasDB_insert("INSERT INTO games (title, description, category, thumbnail, keywords, file, height, width, type, active) values ('$title', '$desc', ".intval($_POST['category']).", '$thumb', '$keywords', '$file', $height, $width, '".yasDB_clean($_POST['type'])."', 1)",false);
        
        if (isset($_SESSION['user'])) {
        $user = yasDB_clean($_SESSION['user']);
        //yasDB_update("UPDATE `user` set videos = videos +1 WHERE username = '$user'"); // add a video to users profile
        }

        echo '<center>Media successfully added!</font><br/>';
        echo '<a href="index.php?">Click here to proceed</a></center>';

} else {

?><div class="table">
        <form enctype="multipart/form-data" action="" method="post">
        <table class="listing form" cellpadding="0" cellspacing="0">
        <tr>
        <th class="full" colspan="2">ADD YOUTUBE VIDEOS TO ALL PET HUMOR</th>
        </tr>
        <tr>
        <td class="first" width="172"><strong>Category</strong></td>
        <td class="last"><select name="category">
        <?php
        $query = yasDB_select("SELECT * FROM categories WHERE active='Yes'");
        while($row = $query->fetch_array(MYSQLI_ASSOC)) {
        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
        $query->close();
        ?>
        </select></td>
        </tr>
        <tr class="bg">
        <td class="first"><strong>File</strong></td>
        <td class="last"><select name="type">
        <option value="YOUTUBE">youtube</option>
    </select></td>
        </tr>
        <tr>
        <td class="first" width="172"><strong>File path</strong></td>
        <td class="last"><input type="text" name="file" value="youtube url" /></td>
        </tr>
        <tr class="bg">
        <td class="first" width="172"><strong>Submit</strong></td>
        <td class="last"><input type="hidden" name="date" /><input type="submit" class="button" name="add" value="Add Media!" /></td>
        </tr>
        </table>
        </div>
        </form>
        <br/><br/>
        <center>*Youtube videos will automatically get title, description and image from the Youtube API. <br/>Just put the video url in the 'File path' box, choose category and click 'Add Media'.</center><br/>
<?php
}
?>
</div>
<div class="clear"></div></div>

<div class="container_box1" style="min-height:0px"><div class="header">Advertisement</div><div align="center" style="padding:6px">
<?php
if (ad("6") == 'Put AD code here') {
    ?><img src="<?php echo $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/468x60.PNG';?>" width="468" height="60" />
    <?php
} else {
    echo ad("6");
}
?>
</div>
<div class="clear"></div></div> 
