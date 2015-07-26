<?php
require 'facebook/facebook.php';
require 'config/functions.php';
$facebook = new Facebook(array(
            'appId' => $setting['fb_app_id'],
            'secret' => $setting['fb_app_secret'],
            ));

$user = $facebook->getUser();

if ($user) {
  try {
    $user_profile = $facebook->api('/me');
		
  } catch (FacebookApiException $e) {
    error_lxog($e);
    $user = null;
  }
	
    if (!empty($user_profile )) {
        $username = $user_profile['name'];
		$uid = $user_profile['id'];
		$email = $user_profile['email'];
        $user = new User();
        $userdata = $user->checkUser($uid, 'facebook', $username, $email);
        
		if(!empty($userdata)){
            $_SESSION['userid'] = $userdata['id'];
			$_SESSION['oauth_id'] = $uid;
            $_SESSION['user'] = $userdata['username'];
			$_SESSION['email'] = $email;
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
			exit;
        }
    } else {
        die("There was an error.");
    }
} else {
    $login_url = $facebook->getLoginUrl(array( 'scope' => 'email'));
    header("Location: " . $login_url);	
}
?>