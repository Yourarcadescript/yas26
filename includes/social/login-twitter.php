<?php
require("twitter/twitteroauth.php");
$twitteroauth = new TwitterOAuth($setting['tw_app_id'], $setting['tw_app_secret']);
$request_token = $twitteroauth->getRequestToken($setting['siteurl'] . 'includes/social/getTwitterData.php');
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
if ($twitteroauth->http_code == 200) {
    $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
    header('Location: ' . $url);
	exit;
} else {
    die('Something wrong happened.');
}
?>