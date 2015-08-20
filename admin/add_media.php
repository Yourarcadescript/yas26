<?php
function get_youtube_data($yt_url, $idOnly = false) {
	$api_key = 'AIzaSyDX_tS5U_dTvn9AufXdKrCh9TeLrArPPOU';
	;
	$parts = explode("=",$yt_url);
	$video_id = end($parts);
	if(!is_numeric($video_id)) {
		preg_match('![?&]{1}v=([^&]+)!', $yt_url, $match); // give it a second go
		$video_id = $match[1];
    }
	if($idOnly) return $video_id;
	$v3_api_url = 'https://www.googleapis.com/youtube/v3/videos?id='. $video_id . '&key='. $api_key . '&part=snippet';
	//Using cURL php extension to make the request to youtube API
    $ch = curl_init();
    //curl_setopt($ch, CURLOPT_URL, 'http://gdata.youtube.com/feeds/api/videos?q=' . $video_id);
	curl_setopt($ch, CURLOPT_URL, $v3_api_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //$feed holds a rss feed xml returned by youtube API
    $feed = curl_exec($ch);
    print_r($feed);
	exit;
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

<div id="center-column">
<div class="top-bar">
<h1>Cpanel - Media</h1>
<div class="breadcrumbs"><a href="index.php?act=addcode" title="Ad Code">Add Code</a> / <a href="index.php?act=managegames" title="Manage Games">Manage Games</a> / <a href="index.php?act=uploadgames" title="Upload Games">Upload Games</a> / <a href="index.php?act=brokenfiles" title="Broken Files">Broken Files</a></div>
</div><br />
<div class="select-bar">
<label>
<h3>Add Media</h3>
</label>
</div>
<?php
if(isset($_POST['add'])) {
		$title = yasDB_clean($_POST['title']);
		$desc = yasDB_clean($_POST['description']);
		$thumb = $_POST['thumbnail'];
		$file = 'http://www.youtube.com/watch?v=' . get_youtube_data($_POST['file'], true);
		$height = yasDB_clean($_POST['height']);
		$width = yasDB_clean($_POST['width']);
		$keywords = yasDB_clean($_POST['keywords']);
		if ($_POST['type'] == 'YOUTUBE') {
			$data = get_youtube_data($_POST['file']);
			print_r($data);
			exit;
			$tn = $data['thumbnail'];
			$title = yasDB_clean($data['title']);
			$desc = yasDB_clean($data['description']);
			$keywords = yasDB_clean($data['keywords']);
			if (remoteFileExists($tn) === true) {
				$thumb = 'img/' . preg_replace('#\W#', '', $title) . rand(0, pow(10, 5)) . '.' . get_file_extension($tn);
				download_file($tn, $setting['sitepath'] . '/' . $thumb);
			} else {
				$thumb = '';
			}
		}
		yasDB_insert("INSERT INTO games (title, description, category, thumbnail, keywords, file, height, width, type) values ('$title', '$desc', ".intval($_POST['category']).", '$thumb', '$keywords', '$file', '$height', '$width', '{$_POST['type']}')",false);
		echo '<div style="text-align: center;">Media successfully added!<br/>';
		echo '<a href="index.php?act=addmedia">Click here to proceed</a></div>';
	
} else {
?><div class="table">
		<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
	    <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
		<form enctype="multipart/form-data" action="" method="post">
		<table class="listing form" cellpadding="0" cellspacing="0">
		<tr>
	    <th class="full" colspan="2">ADD MEDIA TO YOUR SITE</th>
	    </tr>
		<tr>
		<td class="first" width="172"><strong>Title</strong></td>
		<td class="last"><input type="text" name="title" /></td>
		</tr>
		<tr class="bg">
		<td class="first"><strong>Description</strong></td>
		<td class="last"><textarea name="description"></textarea></td>
		</tr>
		<tr>
		<td class="first" width="172"><strong>Category</strong></td>
		<td class="last"><select name="category">
	    <?php
	    $query = yasDB_select("SELECT * FROM categories");
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
	    <option value="WMV">wmv</option>
	    <option value="AVI">avi</option>
	    <option value="MPG">mpg</option>
	    <option value="MOV">mov</option>
	    <option value="IMAGE">image</option>
	    <option value="YOUTUBE">youtube</option>
	    <option value="FLV">flv</option>
	    <option value="SWF">swf</option>
	    <option value="DCR">dcr</option>
	</select></td>
		</tr>
		<tr>
		<td class="first" width="172"><strong>File path</strong></td>
		<td class="last"><input type="text" name="file" value="media/files/" /></td>
		<tr class="bg">
		<td class="first" width="172"><strong>Height:*</strong></td>
		<td class="last"><input type="text" name="height" /></td>
		</tr>
		<tr>
		<td class="first" width="172"><strong>Thumbnail path</strong></td>
		<td class="last"><input type="text" name="thumbnail" value="img/" /></td>
		<tr class="bg">
		<td class="first" width="172"><strong>Height:*</strong></td>
		<td class="last"><input type="text" name="height" /></td>
		</tr>
		<tr>
		<td class="first" width="172"><strong>Keywords</strong></td>
		<td class="last"><input type="text" name="keywords" value="" /></td>
		<tr class="bg">
		<td class="first" width="172"><strong>Height:*</strong></td>
		<td class="last"><input type="text" name="height" /></td>
		</tr>
		<tr>
		<td class="first"><strong>Width:*</strong></td>
		<td class="last"><input type="text" name="width" /></td>
		</tr>
		<tr class="bg">
		<td class="first" width="172"><strong>Submit</strong></td>
		<td class="last"><input type="submit" class="button" name="add" value="Add Media!" /></td>
		</tr>
		</table>
		</div>
		</form>
		<br/><br/>
		*Youtube videos automatically get title, description, keywords and thumbnail from Youtube API. Just put the video url in the 'File path' box, choose category and 'File' drop menu 'youtube' and click 'Add Media'.
<?php
}
?>
</div>