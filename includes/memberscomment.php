<?php
include("db_functions.inc.php");
include("inc.php");
$result = yasDB_select("SELECT * FROM settings WHERE id = 1") or die("Unexpected error retrieving settings");
$settings = $result->fetch_array(MYSQLI_ASSOC);
$result->close();
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
elseif ($settings['userecaptcha'] == "no" && $_POST['security'] != 'ten') {
    die('You did not pass the security check.  Go back and try again.');
}
$comment_timestamp = trim($_POST['timestamp']);
$submitted_timestamp  = time();
if(isset($_POST['addcomment'])) {
    if(empty($_POST['userid'])) {
        echo 'Sorry, the member you were commenting seems to be invalid.';
    }
    elseif(empty($_POST['comment']) || empty($_POST['name'])) {
        echo 'Please go back and try again, it seems the comment or name was left empty.';
    }
    else {
        $userid = yasDB_clean($_POST['userid']);
        $comment = yasDB_clean($_POST['comment']);
        $name = yasDB_clean($_POST['name']);
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        yasDB_insert("INSERT INTO `memberscomments` (userid, comment, ipaddress, name) values ('{$userid}', '{$comment}', '{$ipaddress}', '{$name}')",false);
        echo '<script>alert("Comment added!");</script>';
        
        
    }
}
else {
    echo 'Unexpected error!';
}
if(empty($_POST['userid'])) {
    echo '<META http-equiv="refresh" content="2; URL=' . $setting['siteurl'] . '">';
}
else {
    if ($setting['seo']=='yes') {
        echo '<META http-equiv="refresh" content="2; URL=' . $setting['siteurl'] . 'showmember/' . $_POST['userid'] . '/.html">';
    }
    elseif ($setting['seo']=='no') {
        echo '<META http-equiv="refresh" content="2; URL=' . $setting['siteurl'] . 'index.php?act=showmember&id=' . $_POST['userid'] . '">';    
    }
}
?>