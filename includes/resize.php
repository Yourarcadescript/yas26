<?php
function GetFileExtension($filepath) {
    preg_match('/[^?]*/', $filepath, $matches);
    $string = $matches[0];
    $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
    // check if there is any extension
    if(count($pattern) == 1) {
        //echo 'No File Extension Present';
        return '';
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
function resize($thumb,$size=null, $source="game"){
	$imageMethod = "GD";
	global $setting;
	switch ($source) {
		case 'download':
			$sourcePath = $setting['sitepath'] . '/ourgames/img/';
			break;
		case 'avatar':
			if (strstr($thumb, 'useruploads')) {
				$sourcePath = $setting['sitepath'] . '/avatars/useruploads/';
			} else {
				$sourcePath = $setting['sitepath'] . '/avatars/';
			}
			break;
		default:
			$sourcePath = $setting['sitepath'] . '/img/';
			break;
	}
	$cachePath = $setting['sitepath'] . '/cache/img/';
	$ext = GetFileExtension($thumb);
	$filename = GetFileName($thumb) . '.' . $ext;
	$thumbPath = $sourcePath . $filename;
	$imagickPath = '/convert/convert';
	if (!file_exists($thumbPath)) return $setting['siteurl'].'templates/'.$setting['theme'].'/skins/'.$setting['skin'].'/images/nopic150.jpg';
	$newthumbname = md5_file($thumbPath);
	$ext = GetFileExtension($thumbPath);
	if(isset($size['w'])) $w = $size['w'];
	if(isset($size['h'])) $h = $size['h'];
	if(!empty($w) and !empty($h)) {
		$newthumbpath = $cachePath.$newthumbname.'_w'.$w.'_h'.$h.'.'.$ext;
	} elseif(!empty($w)) {
		$newthumbpath = $cachePath.$newthumbname.'_w'.$w.'.'.$ext;	
	} elseif(!empty($h)) {
		$newthumbpath = $cachePath.$newthumbname.'_h'.$h.'.'.$ext;
	} else {
		return false;
	}
	if (file_exists($newthumbpath)) return str_replace($setting['sitepath'].'/',$setting['siteurl'], $newthumbpath);
	switch ($imageMethod) {
		case 'IM':
			list($width,$height) = getimagesize($thumbPath);
			$rw = $width  / $w;
			$rh = $height / $h;
			if ( $rw > $rh ) {
				$nw = round( ( $width / $rh ) );
				$resize = $nw;
			}
			if ( $rh > $rw ) {
				$nh = round( ( $height / $rw ) );
				$resize = "x".$nh;
			} else {
				$resize = $w."x".$h;
			}
			$cmd = $setting['sitepath'].$imagickPath." ".$thumbPath." -resize ".$resize." -size ".$w."x".$h." xc:transparent +swap -gravity center -composite -quality 90 ".$newthumbpath;
			$c = exec($cmd, $data, $ret);
			if($ret != 0) {
				return $thumb;
			} else {
				return str_replace($setting['sitepath'].'/',$setting['siteurl'], $newthumbpath);
			}
			break;
		case 'GD':
			include_once ("resizer.php");
			$image = new SimpleImage();
			if (!$image) return $thumbPath;
			$image->load($thumbPath);
			$image->resize($w, $h);
			$image->save($newthumbpath);
			return str_replace($setting['sitepath'].'/',$setting['siteurl'], $newthumbpath); 
			break;
		default:
			return $thumbPath;
			break;
	}
}
?>