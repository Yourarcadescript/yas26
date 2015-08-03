<?php
session_start();
ob_start();
include_once("../../includes/db_functions.inc.php");
include_once("../../includes/config.inc.php");
require("twitter/twitteroauth.php");
require ("config/functions.php");
if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {
    $twitteroauth = new TwitterOAuth($setting['tw_app_id'], $setting['tw_app_secret'], $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
    $_SESSION['access_token'] = $access_token;
    $user_info = $twitteroauth->get('account/verify_credentials');
    
	if (isset($user_info->error)) {
     	header('Location: '.$setting['siteurl']);
    } else {
		$twitter_otoken = $_SESSION['oauth_token'];
		$twitter_otoken_secret = $_SESSION['oauth_token_secret'];
		$email = '';
        $uid = $user_info->id;
        $username = $user_info->name;
        $user = new User();
        $userdata = $user->checkUser($uid, 'twitter', $username, $email, $twitter_otoken, $twitter_otoken_secret);
        
		if(!empty($userdata)){
            $_SESSION['userid'] = $userdata['id'];
			$_SESSION['user'] = $userdata['username'];
			$_SESSION['oauth_id'] = $uid;
            $_SESSION['oauth_provider'] = $userdata['oauth_provider'];
            $now = time(); 
			$query = yasDB_select("SELECT `id` FROM `membersonline` WHERE `memberid` = '{$userdata['id']}'");
			if ($query->num_rows==0) {
				yasDB_insert("INSERT INTO `membersonline` (id, memberid, timeactive) VALUES ('', '{$userdata['id']}', '$now')",false);
			}
			else {
				yasDB_update("UPDATE `membersonline` SET timeactive='$now' WHERE `memberid`='{$userdata['id']}'");
			}
			header("Location: ".$setting['siteurl']);
        }
    }
} else {
    header('Location: '.$setting['siteurl']);
}
ob_end_flush();
?>