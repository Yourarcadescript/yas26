<?php include_once("config.inc.php");
require_once('recaptchalib.php');
$result = yasDB_select("SELECT * FROM settings WHERE id = 1") or die("Unexpected error retrieving settings");
$settings = $result->fetch_array(MYSQLI_ASSOC);
if ($settings['userecaptcha'] == "yes" && $settings['rprivate'] != '' && $settings['rpublic'] != '') {	
	require_once('recaptchalib.php');
	$resp = recaptcha_check_answer ($settings['rprivate'],
                                	$_SERVER["REMOTE_ADDR"],
                               		$_POST["recaptcha_challenge_field"],
                                	$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
  		die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
       	"(reCAPTCHA said: " . $resp->error . ")");
	}
}
elseif ($settings['userecaptcha'] == "no") {
	if ($_POST['security'] != '10' || strtolower($_POST['security']) != 'ten') {
		die('You did not pass the security check.  Go back and try again.');
	}
}

$username=yasDB_clean($_POST["username"]);
$password=md5(yasDB_clean($_POST["password"]));
$repeatpassword=md5(yasDB_clean($_POST["repeatpassword"]));
$name=yasDB_clean($_POST["name"]);
$email=yasDB_clean($_POST["email"],true);
$website=yasDB_clean($_POST["website"],true);
$date = time() + (0 * 24 * 60 * 60);  

$res=yasDB_select("select * from user where username like '$username'"); //or die(mysql_error());
if($res->num_rows == 0){
	$flag=mysql_query("insert into user SET username='$username', password='$password', repeatpassword='$repeatpassword', name='$name', email='$email', website='$website', plays='0', points='0', date='$date' ");
	if($flag==1){ ?>
	<script>alert("Registered: You can now log in");</script>
	<? } else {?><script>alert("Action Failed");</script><? } ?>
	<META HTTP-EQUIV="Refresh" CONTENT="0; URL=<?=$setting['siteurl'];?>">

<? } else { ?> <script>alert("Sorry username exists try again!!");</script>
<? include ($setting['sitepath']."index.php?act=register"); }?>
