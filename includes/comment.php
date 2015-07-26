<?php
include("db_functions.inc.php");
include("config.inc.php");
if ($setting['seo'] == 'yes') {
	$return = $setting['siteurl'] . 'game/' . $_POST['gameid'] . '/'.$_POST['title'].'.html';
} else {
	$return = $setting['siteurl'] . 'index.php?act=game&id=' . $_POST['gameid'];
}
if ($_POST['recaptcha'] == 'yes') {	
	include("securimage/securimage.php");
	$img = new Securimage();
	$valid = $img->check($_POST['code']);

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
if ($passed) {
	$comment_timestamp = trim($_POST['timestamp']);
	$submitted_timestamp  = time();
	if(isset($_POST['addcomment'])) {
		if(empty($_POST['gameid'])) {
			echo '<script>alert("Sorry, the game you were commenting seems to be invalid.");</script>';
			echo '<META http-equiv="refresh" content="2; URL=' . $setting['siteurl'] . '">';
			exit;
		}
		elseif(empty($_POST['comment']) || empty($_POST['name'])) {
			echo '<script>alert("Please go back and try again, it seems the comment or name was left empty.");</script>';
			echo '<META http-equiv="refresh" content="2; URL=' . $return . '">';
			exit;
		}
		else {
			$gameid = yasDB_clean($_POST['gameid']);
			$comment = yasDB_clean($_POST['comment'],true);
			$name = yasDB_clean($_POST['name']);
			$ipaddress = yasDB_clean($_SERVER['REMOTE_ADDR']);
			yasDB_insert("INSERT INTO `comments` (gameid, comment, ipaddress, name) VALUES ('{$gameid}', '{$comment}', '{$ipaddress}', '{$name}')",false);
			echo '<script>alert("Comment added!");</script>';
		}
	}
	else {
		echo '<META http-equiv="refresh" content="2; URL=' . $setting['siteurl'] . '">';
	}
	echo '<META http-equiv="refresh" content="2; URL=' . $return . '">';    
} else {
	?>
	<br/>
    <div style="margin:0 auto; text-align:center;width:350px;background:#99A4B5;">
	<form name="addcomment2" method="post" action="<?php echo $return; ?>"> 
    <br/>The security question wasn't answered correctly. <br/>Go back and try it again.
	<input type="hidden" name="name" value="<?php echo $_POST['name']; ?>"/>
    <input type="hidden" name="comment" value="<?php echo $_POST['comment']; ?>"/>
    <br/><br/><input type="submit" name="contactsubmit2" value="Go Back" />
    </form><br/></div>
	<?php 
}
?>