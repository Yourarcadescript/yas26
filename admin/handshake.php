<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['or']) && isset($_POST['fo'])) {
	$mysqli = new mysqli("localhost", "jasminro_hshake", "eQ+c-fqMZKTK", "jasminro_store") or die();
	$url = $mysqli->real_escape_string($_POST['or']);
	$version = $mysqli->real_escape_string($_POST['ver']);
	if ($_POST['fo'] == 'install') {
		$type = 'install';
	} else {
		$type = 'admin';
	}
	if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
	if(isset($_SERVER['HTTP_REFERER'])){
		$referral = $_SERVER['HTTP_REFERER'];
	} else {
		$referral = "NA";
	}
	$date = time();
	$result = $mysqli->query("SELECT `id` FROM `script_users` WHERE `domain` = '$url' AND `type` = '$type' LIMIT 1") or die();
	if ($result->num_rows == 0) {
		$insert = $mysqli->query("INSERT INTO `script_users` (`domain`, `referral`, `firstip`, `lastip`, `firstdate`, `lastdate`, `count`, `type`, 'version') VALUES ('$url', '$referral', '$ip', '$ip', $date, $date, 1, '$type', '$version')") or die();
	} else {
		$id = $result->fetch_array();
		$update = $mysqli->query("UPDATE `script_users` SET `lastip` = '$ip', `lastdate` = $date, `count` = `count` + 1 WHERE `id` = {$id[0]}");
	}
}
?>