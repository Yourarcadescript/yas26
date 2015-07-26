<?php
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
header('(anti-spam-(anti-spam-content-type:)) Image/PNG');
$url = $_GET['url'];
switch(strtolower(GetFileExtension($url))) {
	case 'gif':
		$image = imagecreatefromgif($url);
		break;
	case 'jpg':
	case 'jpeg':
		$image = imagecreatefromjpeg($url);
		break;
	case 'png':
		imagecreatefrompng($url);
		break;
}
imagePNG($image);
?>