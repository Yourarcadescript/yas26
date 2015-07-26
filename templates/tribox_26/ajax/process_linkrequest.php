<?php
include("../../../includes/db_functions.inc.php");
include("../../../includes/config.inc.php");
include("../../../includes/checklinks.class.php");

$post_text = yasDB_clean($_POST['text'],true);
$post_desc = yasDB_clean($_POST['description'],true);
$post_url = yasDB_clean($_POST['url']);
$post_reciprocal = yasDB_clean($_POST['reciprocal']);
$post_linkemail = yasDB_clean($_POST['linkemail']);

if(empty($post_text) OR empty($post_url) OR empty($post_reciprocal) OR empty($post_linkemail) OR empty($post_desc)){
	echo "<h3>You didn't fill everything out for your link!</h3>";
	exit; 
}
if ($_POST['recaptcha'] == 'yes') {	
	include("../../../includes/securimage/securimage.php");
	$img = new Securimage();
	$valid = $img->check($_POST['security']);
	if (!$valid) {
		$passed = false;
	} else {
		$passed = true;
	}
}
elseif ($_POST['recaptcha'] == 'no') {
	$answer = array('10', 'ten');
	if(!in_array(strtolower($_POST['security']),$answer)) {
		$passed = false;
	} else {
		$passed = true;
	}
}
if(!$passed) {
	$errormsg =  "You have failed the security code.  Please try again.";  
}
elseif(strlen($post_text) > 30) {
	$errormsg =  'Your text cannot be greater then 30 characters.';
}
elseif(strlen($post_url) > 65) {
	$errormsg =  'Your url cannot be greater then 65 characters.';
}
elseif(strlen($post_reciprocal) > 65) {
	$errormsg =  'Your Reciprocal link cannot be greater then 65 characters.';
}
elseif($passed) {
    $checklink = new checkLink;
	$response = $checklink->validateLink($post_reciprocal,$setting['siteurl']);
	switch ($response) {
		case LINKFOUND:		
			if ($setting['approvelinks']=='no') {
				yasDB_insert("INSERT INTO links(`url`, `text`, `description`, `reciprocal`, `approved`, `email`)
							VALUES('$post_url', '$post_text', '$post_desc', '$post_reciprocal', 'no', '$post_linkemail')");
				$successmsg = "Your link has been added. It will not show up until an admin approves the link. </br>An email will be sent on approval.";
			} else {
				yasDB_insert("INSERT INTO links(`url`, `text`, `description`, `reciprocal`, `approved`, `email`)
							VALUES('$post_url', '$post_text', '$post_desc', '$post_reciprocal', 'yes', '$post_linkemail')");
				$successmsg =  'Your link has been added and approved pending a manual review by an admin.';
			}
			break;
		case LINKNOTFOUND:
			$errormsg = "We did not find our link on your web page " . $post_reciprocal . ". </br>Please make sure you have added it and it is a hard link and then resubmit the request.";
			break;
		case LINKFOUNDNOFOLLOW:
			$errormsg = 'Our link was found but you have added a rel="nofollow" attribute. Please remove this and resubmit the request. </br>We use a scheduled back link checker. Please respect our link exchange.';
			break;
		case LINKDATAERROR:
			$errormsg = 'We received an error while checking for our link on your web page ' . $post_reciprocal . '. </br>Please check that you typed the reciprocal link correctly and resubmit the request';
			break;
	}
	// send email to admin notifying of the link request
	$message = 'Link exchange request through '.$setting['siteurl']."\r\n";
	$message .= 'Url: '.$post_url."\r\n";
	$message .= 'Reciprocal link: '.$post_reciprocal."\r\n";
	$message .= 'Text: '.$post_text."\r\n";
	$message .= 'Description: '.$post_desc."\r\n";
	$message .= 'Email: '.$post_linkemail."\r\n";
	if ($errormsg) $message .= 'Error message: ' . str_replace("</br>", "\r\n", $errormsg); 
	$headers = 'From: '.$setting['sitename'].' <'.$setting['sitename'].'>';
	$subject = 'Link exchange request from '.$post_url.' through '.$setting['sitename'];
	@mail($setting['email'], $subject, $message, $headers); 
}
if ($errormsg) {
	echo "<h3>".$errormsg."</h3>";
}
elseif ($successmsg) {
	echo "<h3>".$successmsg."</h3>";
}
?>